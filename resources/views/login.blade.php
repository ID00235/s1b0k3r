<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>D-Stock | Log in</title>
  <!-- Tell the browser to be responsive to screen width -->
  <meta content="width=device-width, initial-scale=1, maximum-scale=1, 
  user-scalable=no" name="viewport">
  <!-- Bootstrap 3.3.7 -->
  <link rel="stylesheet" href="{{asset('css/bootstrap.min.css')}}">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="{{asset('css/font-awesome.min.css')}}">
  <link rel="stylesheet" href="{{asset('css/line-awesome.min.css')}}">
  <!-- Theme style -->
  <link rel="stylesheet" href="{{asset('css/AdminLTE.min.css')}}">
  <style type="text/css">
    .login-page, .register-page {
      background:  #85929e ;
    }
  </style>
</head>
<body class="hold-transition login-page">
<div class="login-box">
    <!-- /.login-logo -->
  <div class="login-box-body">
    <p style="background:  #000   !important; color: #fff !important; padding: 20px !important;">
       <img height="70px" class="pull-right" src="{{asset('img/batanghari.png')}}">
      <big>Sistem Informasi Buku Kerja Harian</big><br>
        <b>Dinas Komunikasi dan Informatika</b>
    </p>
     
    <hr>
    <form action="{{url('submit-login')}}" method="post">
      {{csrf_field()}}
      <p class="text-center">Masukan Username dan Password !</p>
        <div class="form-group has-feedback">
          <input type="text" class="form-control" placeholder="Username" name="username">
          <span class="fa fa-user form-control-feedback"></span>
        </div>
        <div class="form-group has-feedback">
          <input type="password" class="form-control" placeholder="Password" name="password">
          <span class="glyphicon glyphicon-lock form-control-feedback"></span>
        </div>
        <div class="row">
          <div class="col-xs-8">
          </div>
          <!-- /.col -->
          <div class="col-xs-4">
            <button type="submit" class="btn btn-primary btn-block btn-flat">Login In</button>
          </div>
          <!-- /.col -->
        </div>
    </form>
   
   <hr>
   <center>
    <small class="text-center">Copyright &copy; 2019 Diskominfo Kab. Batang Hari</small>
   </center>
  </div>
</div>
<script src="{{url('js/jquery.min.js')}}"></script>
<script src="{{url('js/bootstrap.min.js')}}"></script>
<script src="{{asset('vendor/jquery.form.min.js')}}"></script>
<script src="{{asset('vendor/jquery.validate.min.js')}}"></script>
<script src="{{asset('vendor/sweetalert.min.js')}}"></script>
<script type="text/javascript">
  $(function(){
    
      @if(Session::has('error'))
          $message = "{{Session::get('error')}}";
          swal("Peringatan", $message, "error");
      @endif

      @if(Session::has('success'))
          $message = "{{Session::get('success')}}";
          swal("Berhasil", $message, "success");
      @endif
  })
</script>
</body>
</html>
