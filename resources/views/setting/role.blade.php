@extends('layout')
@section("pagetitle")
{!! $pagetitle !!}
@endsection

@section('content')
<?php
$main_path = Request::segment(1);
loadHelper('akses'); 
 
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
	   		@if(ucc())
	   		{{Html::btnModal('<i class="la la-plus-circle"></i> Tambah Role','modal-tambah-role','primary')}}
	   		<hr>
	   		@endif
	   		<table id="tabel" width="100%" 
	   			 class="table table-head table-bordered table-condensed table-hover table-striped">
	   			<thead>
	   				<tr>
	   					<th width="5%">No.</th>
	   					<th width="65%">Nama Role</th>
	   					<th width="20%">Menu</th>
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

{{ Form::bsOpen('form-tambah-role',url($main_path."/insert-role")) }}
	{{Html::mOpen('modal-tambah-role','Tambah Role')}}
		{{ Form::bsTextField('Nama Role','nama_role','',true,'md-8') }}
	{{Html::mCloseSubmit('<i class="la la-save"></i> Simpan')}}
{{ Form::bsClose()}}


{{ Form::bsOpen('form-edit-role',url($main_path."/update-role")) }}
	{{Html::mOpen('modal-edit-role','Edit Role')}}
		{{ Form::bsTextField('Nama Role','nama_role','',true,'md-8') }}
		{{ Form::bsHidden('uuid','') }}
	{{Html::mCloseSubmit('<i class="la la-save"></i> Simpan')}}
{{ Form::bsClose()}}



{{ Form::bsOpen('form-hapus-role',url($main_path."/delete-role")) }}
	{{Html::mOpen('modal-hapus-role','Konfirmasi Hapus Role')}}
		{{ Form::bsHidden('uuid','') }}
		<p>Anda Yakin Ingin Menghapus Role Ini?</p>
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
		    ajax: "{{url('setting-role/dt')}}",
		    "iDisplayLength": 10,
		    columns: [
		    	 {data:'DT_Row_Index' , orderable:false, searchable: false,sClass:"text-center"},
		         {data:'nama_role' , name:"nama_role" , orderable:true, searchable: 
		         true,sClass:""},
		         {data:'menu_role' , name:"menu_role" , orderable:false, searchable: 
		         false,sClass:"text-center"},
		         {data:'action' , orderable:false, searchable: false,sClass:"text-center"},
		        ],
		        "fnRowCallback": function( nRow, aData, iDisplayIndex, iDisplayIndexFull ) {
		        $(nRow).addClass( aData["rowClass"] );
		        return nRow;
		    },
		});

		$validator_form_tambah = $("#form-tambah-role").validate();
		$("#modal-tambah-role").on('show.bs.modal', function(e){
			$validator_form_tambah.resetForm();
			$("#form-tambah-role").clearForm();
		});

		$('#form-tambah-role').ajaxForm({
			beforeSubmit:function(){$("#form-tambah-role button[type=submit]").button('loading');},
			success:function($respon){
				$("#form-tambah-role button[type=submit]").button('reset');
				$("#modal-tambah-role").modal('hide'); 
				if ($respon.status==true){
					 showNotify('Berhasil',$respon.message);
					 $tabel1.ajax.reload(null, true);
				}else{
					showAlert('Gagal',$respon.message);
				}
			},
			error:function(){
				$("#form-tambah-role button[type=submit]").button('reset');
				$("#modal-tambah-role").modal('hide'); 
				showAlert('Gagal','Terjadi Kesalahan, Ulangi Input Data!');
			}
		}); 


		$validator_form_edit = $("#form-edit-role").validate();
		$("#modal-edit-role").on('show.bs.modal', function(e){
			$uuid  = $(e.relatedTarget).data('uuid');
			$validator_form_edit.resetForm();
			 
			$("#form-edit-role button[type=submit]").button('loading');
			$.get("{{url('setting-role/get-data')}}/"+$uuid, function(respon){
				if(respon.status){
					$("#form-edit-role button[type=submit]").button('reset');
					$("#form-edit-role #nama_role").val(respon.data.nama_role);
					$("#form-edit-role #uuid").val(respon.data.uuid);
					$("#form-edit-role .select2").trigger('change');
				}else{
					showAlert('Gagal',respon.message);
				}
			})
		});

		$('#form-edit-role').ajaxForm({
			beforeSubmit:function(){$("#form-edit-role button[type=submit]").button('loading');},
			success:function($respon){
				$("#form-edit-role button[type=submit]").button('reset');
				$("#modal-edit-role").modal('hide'); 
				if ($respon.status==true){
					 showNotify('Berhasil',$respon.message);
					 $tabel1.ajax.reload(null, true);
				}else{
					showAlert('Gagal',$respon.message);
				}
			},
			error:function(){
				$("#form-edit-role button[type=submit]").button('reset');
				$("#modal-edit-role").modal('hide'); 
				showAlert('Gagal','Terjadi Kesalahan, Ulangi Proses Update Data!');
			}
		}); 

		$validator_form_hapus = $("#form-hapus-role").validate();
		$("#modal-hapus-role").on('show.bs.modal', function(e){
			$uuid  = $(e.relatedTarget).data('uuid');
			$validator_form_hapus.resetForm();
			$("#form-hapus-role button[type=submit]").button('loading');
			$.get("{{url('setting-role/get-data')}}/"+$uuid, function(respon){
				if(respon.status){
					$("#form-hapus-role #uuid").val(respon.data.uuid);
					$("#form-hapus-role #info").html(respon.informasi);
					$("#form-hapus-role button[type=submit]").button('reset');
				}else{
					showAlert('Gagal',respon.message);
				}
			})
		});

		$('#form-hapus-role').ajaxForm({
			beforeSubmit:function(){$("#form-hapus-role button[type=submit]").button('loading');},
			success:function($respon){
				$("#form-hapus-role button[type=submit]").button('reset');
				$("#modal-hapus-role").modal('hide'); 
				if ($respon.status==true){
					 showNotify('Berhasil',$respon.message);
					 $tabel1.ajax.reload(null, true);
				}else{
					showAlert('Gagal',$respon.message);
				}
			},
			error:function(){
				$("#form-hapus-role button[type=submit]").button('reset');
				$("#modal-hapus-role").modal('hide'); 
				showAlert('Gagal','Terjadi Kesalahan, Ulangi Hapus Data!');
			}
		}); 
	})
</script>
@endsection

