<?php
$path = Request::segment(1);
$sesion_menu = session('menusesuax');
$menu_session  = json_decode(($sesion_menu));
?>
<!-- sidebar menu: : style can be found in sidebar.less -->
<ul class="sidebar-menu" data-widget="tree">
  <li class="header">MENU</li>
  <li class="@if($path=='') active @endif">
    <a href="{{ url('/') }}">
      <i class="la la-home"></i> <span>Beranda</span>
    </a>
  </li>
  
  @foreach($menu_session as $mnu_induk)
      <?php
      $active = false;
      $expand = false;
      foreach($mnu_induk->child as $mc){
          if($path==$mc->url){
              $active = true;
              $expand = true;
          }
      } 
      ?>
      <li class="treeview @if($expand) active @endif" id="mnu_induk_{!!$mnu_induk->id_menu!!}">
        <a href="#">
          <i class="{!! $mnu_induk->icon !!}"></i> <span>{!! $mnu_induk->nama_menu !!}</span>
          <span class="pull-right-container">
            <i class="fa fa-angle-left pull-right"></i>
          </span>
        </a>
        <ul class="treeview-menu">
          @foreach($mnu_induk->child as $mnu_child)
          <li class="@if($path==$mnu_child->url) active @endif">
            <a href="{!! url($mnu_child->url) !!}">
              {!! $mnu_child->nama_menu !!}
            </a>
          </li>
          @endforeach
        </ul>
      </li>
  @endforeach
</ul>