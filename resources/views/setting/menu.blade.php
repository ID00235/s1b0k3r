@extends('layout')
@section("pagetitle")
{!! $pagetitle !!}
@endsection

@section('content')
<?php
$main_path = Request::segment(1);
loadHelper('akses'); 
$list_group_menu = DB::table('menu')->select('id_menu as value','nama_menu as text')
					->where('id_menu_induk','0')->orderby('urutan','asc')->get();
?>
<!-- Default box -->
<div class="box">
<div class="box-header with-border">
  <h3 class="box-title">{{$pagetitle}}</h3>
  <div class="box-tools pull-right">
     
  </div>
</div>
<div class="box-body">
	<div class="row">
	   	<div class="col-md-12">
	   		{{ Form::bsSelect2('Group Menu','id_menu_induk',$list_group_menu,'',true,'md-8')}}
	   		@if(ucc())
	   		{{Html::btnModal('<i class="la la-plus-circle"></i> Tambah Menu','modal-tambah-menu','primary')}}
	   		<hr>
	   		@endif
	   		<table id="tabel" width="100%" 
	   			 class="table table-head table-bordered table-condensed table-hover table-striped">
	   			<thead>
	   				<tr>
	   					<th width="5%">No.</th>
	   					<th width="20%">Group Menu</th>
	   					<th width="10%">No.Urut</th>
	   					<th width="30%">Nama Menu</th>
	   					<th width="25%">URL</th>
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
  Pengaturan Menu
</div>
<!-- /.box-footer-->
</div>
<!-- /.box -->
@endsection

@section("modal")

{{ Form::bsOpen('form-tambah-menu',url($main_path."/insert-menu")) }}
	{{Html::mOpen('modal-tambah-menu','Tambah Menu')}}
		{{ Form::bsSelect2('Group Menu','id_menu_induk',$list_group_menu,'',true,'md-8')}}
		{{ Form::bsTextField('Nama Menu','nama_menu','',true,'md-8') }}
		{{ Form::bsTextField('Path URL Menu','url','',true,'md-8') }}
		{{ Form::bsNumeric('Urutan','urutan','',true,'md-4') }}
	{{Html::mCloseSubmit('<i class="la la-save"></i> Simpan')}}
{{ Form::bsClose()}}


{{ Form::bsOpen('form-edit-menu',url($main_path."/update-menu")) }}
	{{Html::mOpen('modal-edit-menu','Edit Menu')}}
		{{ Form::bsSelect2('Group Menu','id_menu_induk',$list_group_menu,'',true,'md-8')}}
		{{ Form::bsTextField('Nama Menu','nama_menu','',true,'md-8') }}
		{{ Form::bsTextField('Path URL Menu','url','',true,'md-8') }}
		{{ Form::bsNumeric('Urutan','urutan','',true,'md-4') }}
		{{ Form::bsHidden('uuid','') }}
	{{Html::mCloseSubmit('<i class="la la-save"></i> Simpan')}}
{{ Form::bsClose()}}

@endsection

@section("js")
<script type="text/javascript">
	$(function(){
		

		var $tabel1 = $('#tabel').DataTable({
		    processing: true,
		    serverSide: true,
		    ajax: "{{url('setting-menu/dt')}}",
		    "iDisplayLength": 10,
		    columns: [
		    	 {data:'DT_Row_Index' , orderable:false, searchable: false,sClass:""},
		         {data:'group_menu' , name:"group_menu" , orderable:true, searchable: true,sClass:""},
		         {data:'urutan' , name:"urutan" , orderable:true, searchable: false,sClass:"text-center"},
		         {data:'nama_menu' , name:"nama_menu" , orderable:false, searchable: true,sClass:""},
		         {data:'url' , name:"url" , orderable:false, searchable: false,sClass:""},
		         {data:'action' , orderable:false, searchable: false,sClass:"text-center"},
		        ],
		        "fnRowCallback": function( nRow, aData, iDisplayIndex, iDisplayIndexFull ) {
		        $(nRow).addClass( aData["rowClass"] );
		        return nRow;
		    },
		});

		$validator_form_tambah = $("#form-tambah-menu").validate();
		$("#modal-tambah-menu").on('show.bs.modal', function(e){
			$validator_form_tambah.resetForm();
			$("#form-tambah-menu .select2").val('');
			$("#form-tambah-menu .select2").trigger('change');
			$("#form-tambah-menu").clearForm();
		});

		$('#form-tambah-menu').ajaxForm({
			beforeSubmit:function(){$("#form-tambah-menu button[type=submit]").button('loading');},
			success:function($respon){
				$("#form-tambah-menu button[type=submit]").button('reset');
				$("#modal-tambah-menu").modal('hide'); 
				if ($respon.status==true){
					 showNotify('Berhasil',$respon.message);
					 $tabel1.ajax.reload(null, true);
				}else{
					showAlert('Gagal',$respon.message);
				}
			},
			error:function(){
				$("#form-tambah-menu button[type=submit]").button('reset');
				$("#modal-tambah-menu").modal('hide'); 
				showAlert('Gagal','Terjadi Kesalahan, Ulangi Input Data!');
			}
		}); 


		$validator_form_edit = $("#form-edit-menu").validate();
		$("#modal-edit-menu").on('show.bs.modal', function(e){
			$uuid  = $(e.relatedTarget).data('uuid');
			$validator_form_edit.resetForm();
			$("#form-edit-menu .select2").val('');
			$("#form-edit-menu .select2").trigger('change');
			$("#form-edit-menu").clearForm();
			$("#form-edit-menu button[type=submit]").button('loading');
			$.get("{{url('setting-menu/get-data')}}/"+$uuid, function(respon){
				if(respon.status){
					$("#form-edit-menu button[type=submit]").button('reset');
					$("#form-edit-menu #id_menu_induk").val(respon.data.id_menu_induk);
					$("#form-edit-menu #nama_menu").val(respon.data.nama_menu);
					$("#form-edit-menu #url").val(respon.data.url);
					$("#form-edit-menu #urutan").val(respon.data.urutan);
					$("#form-edit-menu #uuid").val(respon.data.uuid);
					$("#form-edit-menu .select2").trigger('change');
				}else{
					showAlert('Gagal',respon.message);
				}
			})
		});

		$('#form-edit-menu').ajaxForm({
			beforeSubmit:function(){$("#form-edit-menu button[type=submit]").button('loading');},
			success:function($respon){
				$("#form-edit-menu button[type=submit]").button('reset');
				$("#modal-edit-menu").modal('hide'); 
				if ($respon.status==true){
					 showNotify('Berhasil',$respon.message);
					 $tabel1.ajax.reload(null, true);
				}else{
					showAlert('Gagal',$respon.message);
				}
			},
			error:function(){
				$("#form-edit-menu button[type=submit]").button('reset');
				$("#modal-edit-menu").modal('hide'); 
				showAlert('Gagal','Terjadi Kesalahan, Ulangi Input Data!');
			}
		}); 
	})
</script>
@endsection

