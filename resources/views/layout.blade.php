<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>Buker | @section("pagetitle") @show</title>
  <!-- Tell the browser to be responsive to screen width -->
  <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
  <!-- Bootstrap 3.3.7 -->
  <link rel="stylesheet" href="{{asset('vendor/bootstrap/css/bootstrap.min.css')}}">
  <link rel="stylesheet" href="{{asset('css/app.css')}}">
  <link rel="stylesheet" href="{{asset('css/animate.css')}}">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="{{asset('css/font-awesome.min.css')}}">
  <link rel="stylesheet" href="{{asset('css/line-awesome.min.css')}}">
  <link rel="stylesheet" href="{{asset('css/AdminLTE.min.css')}}">
  <link rel="stylesheet" href="{{asset('css/skins/_all-skins.min.css')}}">
  <link rel="stylesheet" href="{{asset('css/skins/skin-green-light.min.css')}}">
  <link href="{{asset('vendor/datepicker/css/bootstrap-datepicker.css')}}" rel="stylesheet">
  <link href="{{asset('vendor/notify.css')}}" rel="stylesheet">
  <link href="{{asset('vendor/select2/select2.css')}}" rel="stylesheet">
  <link href="{{asset('vendor/select2/select2-bootstrap.css')}}" rel="stylesheet">
  <link href="{{asset('vendor/datatable/dataTables.bootstrap.min.css')}}" rel="stylesheet">
  <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
  <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
  <!--[if lt IE 9]>
  <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
  <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
  <![endif]-->
</head>
<body class="hold-transition skin-yellow sidebar-mini">
<!-- Site wrapper -->
<div class="wrapper">

  <header class="main-header">
    <!-- Logo -->
    <a href="{{url('/')}}" class="logo">
      <!-- mini logo for sidebar mini 50x50 pixels -->
      <span class="logo-mini"><small>BUK3R</small></span>
      <!-- logo for regular state and mobile devices -->
      <span class="logo-lg"><b>Buker</b><small> Dinas Komunikasi dan Informatika</small></span>
    </a>
    <!-- Header Navbar: style can be found in header.less -->
    <nav class="navbar navbar-static-top">
      <!-- Sidebar toggle button-->
      <ul class="nav navbar-nav pull-left">
          <li class="hidden-xs">
              <a>Sistem Informasi Buku Kerja Harian</a>
          </li>
      </ul>
      <div class="navbar-custom-menu">
        <ul class="nav navbar-nav">
          <!-- User Account: style can be found in dropdown.less -->
          <li class="dropdown user user-menu">
            <a href="#" class="dropdown-toggle" data-toggle="dropdown">
              <img src="{{url("img/batanghari.png")}}" class="user-image" alt="User Image">
              <span class="hidden-xs">{{Auth::user()->username}}</span>
            </a>
            <ul class="dropdown-menu">
              <!-- User image -->
              <li class="user-header">
                <img src="{{asset("img/batanghari.png")}}" class="img-circle">
                <p>
                  {{Auth::user()->username}} - {{Auth::user()->nama_lengkap}}
                  <small>{{Auth::user()->email}}</small>
                </p>
              </li>
              <!-- Menu Body -->
              <li class="user-body">
                <!-- /.row -->
              </li>
              <!-- Menu Footer-->
              <li class="user-footer">
                <div class="pull-left">
                  <a href="{!! url('')!!} " class="btn btn-default btn-flat">Akun Saya</a>
                </div>
                <div class="pull-right">
                  <a href="{{url('logout')}}" class="btn btn-default btn-flat">Log out</a>
                </div>
              </li>
            </ul>
          </li>
        </ul>
      </div>
    </nav>
  </header>
  <!-- =============================================== -->

  <!-- Left side column. contains the sidebar -->
  <aside class="main-sidebar">
    <!-- sidebar: style can be found in sidebar.less -->
    <section class="sidebar">
      <!-- Sidebar user panel -->
      <div class="user-panel">
        <div class="pull-left image">
          <img src="{{asset('img/batanghari.png')}}" class="img-circle" alt="User Image">
        </div>
        <div class="pull-left info">
          <p>{{Auth::user()->nama_lenglap}}</p>
          <a href="#"><i class="fa fa-circle text-success"></i> {{Auth::user()->username}}</a>
        </div>
      </div>
      @include("sidebar")
    </section>
    <!-- /.sidebar -->
  </aside>

  <!-- =============================================== -->

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        @if(isset($pagetitle)){{$pagetitle}}@endif
        <small>@if(isset($smalltitle)){{$smalltitle}}@endif</small>
      </h1>
    </section>

    <!-- Main content -->
    <section class="content">
       @section("content") @show
    </section>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->

  <footer class="main-footer">
    <div class="pull-right hidden-xs">
      <small>Dikembangkan Oleh: Diskominfo - 2019 </small>
    </div>
    <strong>Copyright &copy; 2019 Aplikasi LPPK (Laporan Perkembangan Pelaksanaan Kegiatan)</strong>
  </footer>
</div>
<!-- ./wrapper -->
@section("modal")
@show
<script src="{{asset('js/jquery.min.js')}}"></script>
<script src="{{asset('vendor/select2/select2.js')}}"></script>
<script src="{{asset('vendor/bootstrap/js/bootstrap.min.js')}}"></script>
<script src="{{asset('/js/jquery.slimscroll.min.js')}}"></script>
<script src="{{asset('js/fastclick.js')}}"></script>
<script src="{{asset('js/adminlte.min.js')}}"></script>
<script src="{{asset('vendor/datepicker/js/bootstrap-datepicker.min.js')}}"></script>
<script src="{{asset('vendor/datatable/jquery.dataTables.min.js')}}"></script>
<script src="{{asset('vendor/datatable/dataTables.bootstrap.min.js')}}"></script>
<script src="{{asset('vendor/bootstrap-notify.js')}}"></script>
<script src="{{asset('vendor/jquery.form.min.js')}}"></script>
<script src="{{asset('vendor/jquery.validate.min.js')}}"></script>
<script src="{{asset('vendor/sweetalert.min.js')}}"></script>
<script src="{{asset('vendor/jquery.mask.min.js')}}"></script>
 
<script src="{{asset('js/init.js')}}"></script>
<script src="{{asset('js/demo.js')}}"></script>
<script>
  var $base_url = '{{url("/")}}';
  $(document).ready(function () {
    $('.sidebar-menu').tree();
    $('.datemask').datepicker({
        uiLibrary: 'bootstrap',
        format: 'dd/mm/yyyy'
    });
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
@section("js")
@show
</body>
</html>
