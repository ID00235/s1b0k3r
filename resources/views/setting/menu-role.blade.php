@extends('layout')
@section("pagetitle")
{!! $pagetitle !!}
@endsection

@section('content')
<?php
$main_path = Request::segment(1);
loadHelper('akses'); 
$list_menu = DB::select("select a.id_menu as value, concat(b.nama_menu, ' : ', a.nama_menu) as text 
from menu as a , menu as b 
where a.id_menu_induk > 0 and b.id_menu_induk = 0 
and a.id_menu_induk = b.id_menu 
order by b.id_menu , a.id_menu
");

$list_option_yes_no = json_decode(json_encode([
		['value'=>'1', 'text'=>'Yes'],
		['value'=>'0', 'text'=>'No']
	]));
?>
<!-- Default box -->
<div class="box">
<div class="box-header with-border">
  <h3 class="box-title">{{$pagetitle}}</h3>
  <div class="box-tools pull-right">
     
  </div>
</div>
<div class="box-body" style="padding: 20px;">
	<div class="row">
	   	<div class="col-md-12">
	   		<a href="{{url('setting-role')}}" class="btn btn-default btn-sm"><i class="la la-arrow-left"></i> Kembali</a>
	   		@if(ucc())
	   		{{Html::btnModal('<i class="la la-plus-circle"></i> Tambah Menu','modal-tambah-menu-role','primary')}}
	   		<hr>
	   		@endif
	   		<table id="tabel" width="100%" 
	   			 class="table table-head table-bordered table-condensed table-hover table-striped">
	   			<thead>
	   				<tr>
	   					<th width="5%">No.</th>
	   					<th width="55%">Nama Menu</th>
	   					<th width="10%">Create</th>
	   					<th width="10%">Update</th>
	   					<th width="10%">Delete</th>
	   					<th width="10%">Aksi</th>
	   				</tr>
	   			</thead>
	   			<tbody>

	   			</tbody>
	   		</table>
	   	</div>
	</div>
</div>
<!-- /.box-body -->
<div class="box-footer">
  &nbsp;
</div>
<!-- /.box-footer-->
</div>
<!-- /.box -->
@endsection

@section("modal")
 
{{ Form::bsOpen('form-tambah-menu-role',url($main_path."/insert-menu")) }}
	{{Html::mOpen('modal-tambah-menu-role','Tambah Menu Role')}}
		{{ Form::bsHidden('id_role', $role->id_role)}}
		{{ Form::bsSelect2('Menu','id_menu',$list_menu,'',true,'md-8')}}
		{{ Form::bsRadionInline('ucc','Create',$list_option_yes_no,'',true,'md-8')}}
		{{ Form::bsRadionInline('ucu','Update',$list_option_yes_no,'',true,'md-8')}}
		{{ Form::bsRadionInline('ucd','Delete',$list_option_yes_no,'',true,'md-8')}}
	{{Html::mCloseSubmit('<i class="la la-save"></i> Simpan')}}
{{ Form::bsClose()}}

{{ Form::bsOpen('form-edit-menu-role',url($main_path."/update-menu")) }}
	{{Html::mOpen('modal-edit-menu-role','Update Menu Role')}}
		{{ Form::bsHidden('uuid','') }}
		{{ Form::bsReadOnly('Menu','nama_menu','',true,'md-8')}}
		{{ Form::bsRadionInline('ucc','Create',$list_option_yes_no,'',true,'md-8')}}
		{{ Form::bsRadionInline('ucu','Update',$list_option_yes_no,'',true,'md-8')}}
		{{ Form::bsRadionInline('ucd','Delete',$list_option_yes_no,'',true,'md-8')}}
	{{Html::mCloseSubmit('<i class="la la-save"></i> Simpan')}}
{{ Form::bsClose()}}


{{ Form::bsOpen('form-hapus-menu-role',url($main_path."/delete-menu")) }}
	{{Html::mOpen('modal-hapus-menu-role','Hapus Menu Role')}}
		{{ Form::bsHidden('uuid','') }}
		<p>Anda Yakin Ingin Menghapus Menu dari Role Ini?</p>
		<div id="info">
			
		</div>
	{{Html::mCloseSubmit('<i class="la la-trash"></i> Hapus')}}
{{ Form::bsClose()}}


@endsection

@section("js")
<script type="text/javascript">
	$(function(){
		
 	    var $tabel1 = $('#tabel').DataTable({
		    processing: true,
		    serverSide: true,
		    ajax: "{{url('setting-role/dt-menu/'.$uuid_role)}}",
		    "iDisplayLength": 10,
		    columns: [
		    	 {data:'DT_Row_Index' , orderable:false, searchable: false,sClass:""},
		         {data:'nama_menu' , name:"nama_menu" , orderable:true, searchable: true,sClass:""},
		         {data:'ucc' , name:"ucc" , orderable:true, searchable: false,sClass:"text-center"},
		         {data:'ucu' , name:"ucu" , orderable:false, searchable: true,sClass:"text-center"},
		         {data:'ucd' , name:"ucd" , orderable:false, searchable: true,sClass:"text-center"},
		         {data:'action' , orderable:false, searchable: false,sClass:"text-center"},
		        ],
		        "fnRowCallback": function( nRow, aData, iDisplayIndex, iDisplayIndexFull ) {
	        	   	$('td:eq(2)', nRow).html( aData['ucc'] == 1 ? '<i class="blue la la-check"></i>': '<i class="red la la-times"></i>' );
	        	   	$('td:eq(3)', nRow).html( aData['ucu'] == 1 ? '<i class="blue la la-check"></i>': '<i class="red la la-times"></i>' );
	        	   	$('td:eq(4)', nRow).html( aData['ucd'] == 1 ? '<i class="blue la la-check"></i>': '<i class="red la la-times"></i>' );
		        	$(nRow).addClass( aData["rowClass"] );
		        	return nRow;
		    },
		});


		$validator_form_tambah = $("#form-tambah-menu-role").validate();
		$("#modal-tambah-menu-role").on('show.bs.modal', function(e){
			$validator_form_tambah.resetForm();
			$("#form-tambah-menu-role .select2").val('');
			$("#form-tambah-menu-role .select2").trigger('change');
			$("#form-tambah-menu-role").clearForm();
		});

		$('#form-tambah-menu-role').ajaxForm({
			beforeSubmit:function(){$("#form-tambah-menu-role button[type=submit]").button('loading');},
			success:function($respon){
				$("#form-tambah-menu-role button[type=submit]").button('reset');
				$("#modal-tambah-menu-role").modal('hide'); 
				if ($respon.status==true){
					 showNotify('Berhasil',$respon.message);
					 $tabel1.ajax.reload(null, true);
				}else{
					showAlert('Gagal',$respon.message);
				}
			},
			error:function(){
				$("#form-tambah-menu-role button[type=submit]").button('reset');
				$("#modal-tambah-menu-role").modal('hide'); 
				showAlert('Gagal','Terjadi Kesalahan, Ulangi Input Data!');
			}
		}); 


		$validator_form_edit = $("#form-edit-menu-role").validate();
		$("#modal-edit-menu-role").on('show.bs.modal', function(e){
			$uuid  = $(e.relatedTarget).data('uuid');
			$validator_form_edit.resetForm();
			$("#form-edit-menu-role .select2").val('');
			$("#form-edit-menu-role .select2").trigger('change');
			$("#form-edit-menu-role").clearForm();
			$("#form-edit-menu-role button[type=submit]").button('loading');

			$.get("{{url('setting-role/get-role-menu')}}/"+$uuid, function(respon){
				if(respon.status){
					$("#form-edit-menu-role button[type=submit]").button('reset');
					$("#form-edit-menu-role #nama_menu").val(respon.data.nama_menu);
					$("#form-edit-menu-role #uuid").val(respon.data.uuid);
					 
					$("#form-edit-menu-role input[name=ucc][value=" + respon.data.ucc +"]").prop('checked',true);
					 
					$("#form-edit-menu-role input[name=ucu][value=" + respon.data.ucu +"]").prop('checked',true);
					 
					$("#form-edit-menu-role input[name=ucd][value=" + respon.data.ucd +"]").prop('checked',true);
				}else{
					showAlert('Gagal',respon.message);
				}
			})
		});

		$('#form-edit-menu-role').ajaxForm({
			beforeSubmit:function(){$("#form-edit-menu-role button[type=submit]").button('loading');},
			success:function($respon){
				$("#form-edit-menu-role button[type=submit]").button('reset');
				$("#modal-edit-menu-role").modal('hide'); 
				if ($respon.status==true){
					 showNotify('Berhasil',$respon.message);
					 $tabel1.ajax.reload(null, true);
				}else{
					showAlert('Gagal',$respon.message);
				}
			},
			error:function(){
				$("#form-edit-menu-role button[type=submit]").button('reset');
				$("#modal-edit-menu-role").modal('hide'); 
				showAlert('Gagal','Terjadi Kesalahan, Ulangi Input Data!');
			}
		}); 

		$validator_form_hapus = $("#form-hapus-menu-role").validate();
		$("#modal-hapus-menu-role").on('show.bs.modal', function(e){
			$uuid  = $(e.relatedTarget).data('uuid');
			$validator_form_hapus.resetForm();
			$("#form-hapus-menu-role button[type=submit]").button('loading');
			$.get("{{url('setting-role/get-role-menu')}}/"+$uuid, function(respon){
				if(respon.status){
					$("#form-hapus-menu-role #uuid").val(respon.data.uuid);
					$("#form-hapus-menu-role #info").html(respon.informasi);
					$("#form-hapus-menu-role button[type=submit]").button('reset');
				}else{
					showAlert('Gagal',respon.message);
				}
			})
		});

		$('#form-hapus-menu-role').ajaxForm({
			beforeSubmit:function(){$("#form-hapus-menu-role button[type=submit]").button('loading');},
			success:function($respon){
				$("#form-hapus-menu-role button[type=submit]").button('reset');
				$("#modal-hapus-menu-role").modal('hide'); 
				if ($respon.status==true){
					 showNotify('Berhasil',$respon.message);
					 $tabel1.ajax.reload(null, true);
				}else{
					showAlert('Gagal',$respon.message);
				}
			},
			error:function(){
				$("#form-hapus-menu-role button[type=submit]").button('reset');
				$("#modal-hapus-menu-role").modal('hide'); 
				showAlert('Gagal','Terjadi Kesalahan, Ulangi Input Data!');
			}
		}); 
	})
</script>
@endsection

