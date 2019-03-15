@extends('layout')
@section("pagetitle")
{!! $pagetitle !!}
@endsection

@section('content')
<?php
$main_path = Request::segment(1);
loadHelper('akses'); 
$list_user_menu = DB::table('users')->select('id as value','nama_pengguna as text')
					->where('nama_pengguna','0')
					->orderby('id','asc')
					->get();
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
	   		<!-- {{ Form::bsSelect2('User Menu','id',$list_user_menu,'',true,'md-8')}} -->
	   		@if(ucc())
	   		{{Html::btnModal('<i class="la la-plus-circle"></i> Tambah User','modal-tambah-user','primary')}}
	   		<hr>
	   		@endif
	   		<table id="tabel" width="100%" 
	   			 class="table table-head table-bordered table-condensed table-hover table-striped">
	   			<thead>
	   				<tr>
	   					<th width="5%">No.</th>
	   					<th width="25%">Nama Pengguna</th>
	   					<th width="15%">Nama User</th>
	   					<th width="25%">Email</th>
	   					<th width="15%">Akses Pengguna</th>
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

{{ Form::bsOpen('form-tambah-user',url($main_path."/insert-user")) }}
	{{Html::mOpen('modal-tambah-user','Tambah User')}}
		{{ Form::bsTextField('Nama Pengguna','nama_pengguna','',true,'md-8') }}
		{{ Form::bsTextField('User Id','username','',true,'md-8')}}
		{{ Form::bsTextField('Email','email','',true,'md-8') }}
		{{ Form::bsPassword('Password','password','',true,'md-8') }}
	{{Html::mCloseSubmit('<i class="la la-save"></i> Simpan')}}
{{ Form::bsClose()}}


{{ Form::bsOpen('form-edit-user',url($main_path."/update-user")) }}
	{{Html::mOpen('modal-edit-user','Edit User')}}
		{{ Form::bsTextField('Nama Pengguna','nama_pengguna','',true,'md-8')}}
		{{ Form::bsTextField('User Id','username','',true,'md-8') }}
		{{ Form::bsTextField('Email','email','',true,'md-8') }}
		{{ Form::bsPassword('Password','password','',true,'md-8') }}
		{{ Form::bsHidden('uuid','') }}
	{{Html::mCloseSubmit('<i class="la la-save"></i> Simpan')}}
{{ Form::bsClose()}}

{{ Form::bsOpen('form-hapus-user',url($main_path."/delete-user")) }}
	{{Html::mOpen('modal-hapus-user','Konfirmasi Hapus user')}}
		{{ Form::bsHidden('uuid','') }}
		<p>Anda Yakin Ingin Menghapus user Ini?</p>
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
		    ajax: "{{url('setting-user/dt')}}",
		    "iDisplayLength": 10,
		    columns: [
		    	 {data:'DT_Row_Index' , orderable:false, searchable: false,sClass:""},
		         {data:'nama_pengguna' , name:"nama_pengguna" , orderable:true, searchable: false,sClass:""},
		         {data:'username' , name:"username" , orderable:false, searchable: true,sClass:""},
		         {data:'email' , name:"email" , orderable:false, searchable: false,sClass:""},
		         {data:'nama_role' , name:"nama_role" , orderable:true, searchable: true,sClass:""},
		         {data:'action' , orderable:false, searchable: false,sClass:"text-center"},
		        ],
		        "fnRowCallback": function( nRow, aData, iDisplayIndex, iDisplayIndexFull ) {
		        $(nRow).addClass( aData["rowClass"] );
		        return nRow;
		    },
		});

		$validator_form_tambah = $("#form-tambah-user").validate();
		$("#modal-tambah-user").on('show.bs.modal', function(e){
			$validator_form_tambah.resetForm();
			$("#form-tambah-user").clearForm();
		});

		$('#form-tambah-user').ajaxForm({
			beforeSubmit:function(){$("#form-tambah-user button[type=submit]").button('loading');},
			success:function($respon){
				$("#form-tambah-user button[type=submit]").button('reset');
				$("#modal-tambah-user").modal('hide'); 
				if ($respon.status==true){
					 showNotify('Berhasil',$respon.message);
					 $tabel1.ajax.reload(null, true);
				}else{
					showAlert('Gagal',$respon.message);
				}
			},
			error:function(){
				$("#form-tambah-user button[type=submit]").button('reset');
				$("#modal-tambah-user").modal('hide'); 
				showAlert('Gagal','Terjadi Kesalahan, Ulangi Input Data!');
			}
		}); 


		$validator_form_edit = $("#form-edit-user").validate();
		$("#modal-edit-user").on('show.bs.modal', function(e){
			$uuid  = $(e.relatedTarget).data('uuid');
			$validator_form_edit.resetForm();
			$("#form-edit-user .select2").val('');
			$("#form-edit-user .select2").trigger('change');
			$("#form-edit-user").clearForm();
			$("#form-edit-user button[type=submit]").button('loading');
			$.get("{{url('setting-user/get-data')}}/"+$uuid, function(respon){
				if(respon.status){
					$("#form-edit-user button[type=submit]").button('reset');
					$("#form-edit-user #nama_pengguna").val(respon.data.nama_pengguna);
					$("#form-edit-user #username").val(respon.data.username);
					$("#form-edit-user #email").val(respon.data.email);
					$("#form-edit-user #password").val(respon.data.password);
					$("#form-edit-user #uuid").val(respon.data.uuid);
					$("#form-edit-user .select2").trigger('change');
				}else{
					showAlert('Gagal',respon.message);
				}
			})
		});

		$('#form-edit-user').ajaxForm({
			beforeSubmit:function(){$("#form-edit-user button[type=submit]").button('loading');},
			success:function($respon){
				$("#form-edit-user button[type=submit]").button('reset');
				$("#modal-edit-user").modal('hide'); 
				if ($respon.status==true){
					 showNotify('Berhasil',$respon.message);
					 $tabel1.ajax.reload(null, true);
				}else{
					showAlert('Gagal',$respon.message);
				}
			},
			error:function(){
				$("#form-edit-user button[type=submit]").button('reset');
				$("#modal-edit-user").modal('hide'); 
				showAlert('Gagal','Terjadi Kesalahan, Ulangi Input Data!');
			}
		});

		$validator_form_hapus = $("#form-hapus-user").validate();
		$("#modal-hapus-user").on('show.bs.modal', function(e){
			$uuid  = $(e.relatedTarget).data('uuid');
			$validator_form_hapus.resetForm();
			$("#form-hapus-user button[type=submit]").button('loading');
			$.get("{{url('setting-user/get-data')}}/"+$uuid, function(respon){
				if(respon.status){
					$("#form-hapus-user #uuid").val(respon.data.uuid);
					$("#form-hapus-user #info").html(respon.informasi);
					$("#form-hapus-user button[type=submit]").button('reset');
				}else{
					showAlert('Gagal',respon.message);
				}
			})
		});

		$('#form-hapus-user').ajaxForm({
			beforeSubmit:function(){$("#form-hapus-user button[type=submit]").button('loading');},
			success:function($respon){
				$("#form-hapus-user button[type=submit]").button('reset');
				$("#modal-hapus-user").modal('hide'); 
				if ($respon.status==true){
					 showNotify('Berhasil',$respon.message);
					 $tabel1.ajax.reload(null, true);
				}else{
					showAlert('Gagal',$respon.message);
				}
			},
			error:function(){
				$("#form-hapus-user button[type=submit]").button('reset');
				$("#modal-hapus-user").modal('hide'); 
				showAlert('Gagal','Terjadi Kesalahan, Ulangi Hapus Data!');
			}
		}); 
	})
</script>
@endsection

