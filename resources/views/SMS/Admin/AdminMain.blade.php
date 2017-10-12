<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
  <title>Scholarship MS</title>
  {!! Html::style("plugins/pace/pace.min.css") !!}
  {!! Html::style("css/bootstrap.min.css") !!}
  {!! Html::style("css/font-awesome.css") !!}
  {!! Html::style("css/bootstrap-toggle.min.css") !!}
  {!! Html::style("css/stylesheet.css") !!}
  {!! Html::style("css/parsley.css") !!}
  {!! Html::style("plugins/datatables/dataTables.bootstrap.min.css") !!}
  {!! Html::style("plugins/sweetalert/sweetalert.min.css") !!}
  <link rel="icon" href="{{ asset('img/logo.ico') }}">
  @yield('head')
  {!! Html::style("css/AdminLTE.min.css") !!}
  {!! Html::style("css/_all-skins.min.css") !!}
  {!! Html::script("plugins/jQuery/jquery-3.2.1.min.js") !!}
  {!! Html::script("js/bootstrap.min.js") !!} 
  {!! Html::script("plugins/pace/pace.min.js") !!}
  <style type="text/css">
  [data-notify="container"] {
    width: 25%;
  }
</style>
</head>
<body class="hold-transition skin-red sidebar-mini">
  <div class="wrapper">
    <header class="main-header">
      <a href="{{ url('admin/dashboard') }}" class="logo">
        <span class="logo-mini"><img src="{{ asset('img/logo.png') }}" width="35" height="35"></span>
        <span class="logo-lg"><b>Scholar</b>MS</span>
      </a>
      <nav class="navbar navbar-static-top" role="navigation">
        <a href="#" class="sidebar-toggle" data-toggle="push-menu" role="button">
          <span class="sr-only">Toggle navigation</span>
        </a>
        <div class="navbar-custom-menu">
          <ul class="nav navbar-nav">
            <li class="dropdown user user-menu">
              <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                <img src="{{ asset('images/'.Auth::user()->picture) }}" class="user-image" alt="User Image">
                <span class="hidden-xs">{{ Auth::user()->first_name }} {{ Auth::user()->last_name }}</span>
              </a>
              <ul class="dropdown-menu">
                <li class="user-header">
                  <img src="{{ asset('images/'.Auth::user()->picture) }}" class="img-circle" alt="User Image">
                  <p style="text-align: center;">
                    {{ Auth::user()->first_name }} {{ Auth::user()->last_name }}
                    <small>ID: {{ Auth::id() }}</small>
                  </p>
                </li>
                <li class="user-footer">
                  <div class="pull-left">
                    <a href="{{ url('admin/profile') }}" class="btn btn-default btn-flat"><i class="fa fa-user"></i> Profile</a>
                  </div>
                  <div class="pull-right">
                    <a href="{{ route('logout') }}" class="btn btn-default btn-flat"
                    onclick="event.preventDefault();
                    document.getElementById('logout-form').submit();"><i class="fa fa-sign-out"></i> 
                    Sign out
                  </a>
                  <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                    {{ csrf_field() }}
                  </form>
                </div>
              </li>
            </ul>
          </li>
        </ul>
      </div>
    </nav>
  </header>
  <aside class="main-sidebar">
    <section class="sidebar">
      <div class="user-panel">
        <div class="pull-left image">
          <img src="{{ asset('images/'.Auth::user()->picture) }}" class="img-circle" alt="User Image">
        </div>
        <div class="pull-left info">
          <p>{{ Auth::user()->first_name }} {{ Auth::user()->last_name }}</p>
        </div>
      </div>
      <ul class="sidebar-menu" data-widget="tree">
        <li class="header">NAVIGATION</li>
        <li class="{{Request::path() == 'admin/dashboard' ? 'active' : ''}}"><a href="{{ url('admin/dashboard') }}"><i class="fa fa-dashboard"></i><span>Dashboard</span></a></li>
        {{-- <li class="header">MAINTENANCE</li> --}}
        <!-- Optionally, you can add icons to the links -->
        <li class="treeview {{Request::path() == 'admin/district' ? 'active' : ''}} {{Request::path() == 'admin/barangay' ? 'active' : ''}}">
          <a href="#"><i class="fa fa-sitemap"></i><span>Municipality</span>
            <span class="pull-right-container">
              <i class="fa fa-angle-left pull-right"></i>
            </span>
          </a>
          <ul class="treeview-menu">
            <li class="{{Request::path() == 'admin/district' ? 'active' : ''}}"><a href="{{ url('admin/district') }}"><i class="fa fa-bank"></i><span>District</span></a></li>
            <li class="{{Request::path() == 'admin/barangay' ? 'active' : ''}}"><a href="{{ url('admin/barangay') }}"><i class="fa fa-map-o"></i><span>Barangay</span></a></li>
          </ul>
        </li>
        <li class="{{Request::path() == 'admin/councilor' ? 'active' : ''}}"><a href="{{ url('admin/councilor') }}"><i class="fa fa-gavel"></i><span>Councilor</span></a></li>
        <li class="treeview {{Request::segment(2) == 'grade' ? 'active' : ''}}{{Request::path() == 'admin/school' ? 'active' : ''}} {{Request::path() == 'admin/course' ? 'active' : ''}} {{Request::path() == 'admin/credit' ? 'active' : ''}}">
          <a href="#"><i class="fa fa-pencil-square-o"></i><span>Education</span>
            <span class="pull-right-container">
              <i class="fa fa-angle-left pull-right"></i>
            </span>
          </a>
          <ul class="treeview-menu">
            <li class="{{Request::segment(2) == 'grade' ? 'active' : ''}}"><a href="{{ url('admin/grade') }}"><i class="fa fa-level-up"></i><span>Academic Grading</span></a></li>
            <li class="{{Request::path() == 'admin/school' ? 'active' : ''}}"><a href="{{ url('admin/school') }}"><i class="fa fa-graduation-cap"></i><span>School</span></a></li>
            <li class="{{Request::path() == 'admin/course' ? 'active' : ''}}"><a href="{{ url('admin/course') }}"><i class="fa fa-book"></i><span>Course</span></a></li>
            <li class="{{Request::path() == 'admin/credit' ? 'active' : ''}}"><a href="{{ url('admin/credit') }}"><i class="fa fa-hourglass-o"></i><span>Credit</span></a></li>
          </ul>
        </li>
        <li class="treeview {{Request::path() == 'admin/batch' ? 'active' : ''}}{{Request::segment(2) == 'requirements' ? 'active' : ''}}">
          <a href="#"><i class="fa fa-sticky-note-o"></i><span>Scholarship</span>
            <span class="pull-right-container">
              <i class="fa fa-angle-left pull-right"></i>
            </span>
          </a>
          <ul class="treeview-menu">
            <li class="{{Request::path() == 'admin/batch' ? 'active' : ''}}"><a href="{{ url('admin/batch') }}"><i class="fa fa-stack-overflow"></i><span>Batch</span></a></li>
            <li class="{{Request::segment(2) == 'requirements' ? 'active' : ''}}"><a href="{{ url('admin/requirements') }}"><i class="fa  fa-files-o"></i><span>Requirements</span></a></li>
          </ul>
        </li>
        <li class="{{Request::path() == 'admin/budget-type' ? 'active' : ''}}"><a href="{{ url('admin/budget-type') }}"><i class="fa fa-money"></i><span>Budget Type</span></a></li>
        <li class="{{Request::path() == 'admin/users' ? 'active' : ''}}"><a href="{{ url('admin/users') }}"><i class="fa fa-users"></i><span>User Accounts</span></a></li>
        <li class="{{Request::path() == 'admin/utilities' ? 'active' : ''}}"><a href="{{ url('admin/utilities') }}"><i class="fa fa-gear"></i><span>Utilities</span></a></li>
      </ul>
    </section>
  </aside>
  @yield('content')
</div>
{!! Html::script("js/bootstrap-toggle.min.js") !!} 
{!! Html::script("js/bootstrap-notify.min.js") !!} 
{!! Html::script("js/script.js") !!}
{!! Html::script("js/parsley.min.js") !!}
{!! Html::script("plugins/fastclick/fastclick.min.js") !!}
{!! Html::script("plugins/datatables/jquery.dataTables.min.js") !!}
{!! Html::script("plugins/datatables/dataTables.bootstrap.min.js") !!}
{!! Html::script("plugins/sweetalert/sweetalert.min.js") !!}
@yield('script')
{!! Html::script("js/adminlte.min.js") !!}
{!! Html::script("js/ajax-expired-session.js") !!} 
</body>
</html>