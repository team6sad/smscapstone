<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <title>Scholarship MS</title>
  <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
  {!! Html::style("plugins/pace/pace.min.css") !!} 
  {!! Html::style("css/bootstrap.min.css") !!}
  {!! Html::style("css/font-awesome.css") !!}
  {!! Html::style("css/bootstrap-toggle.min.css") !!}
  {!! Html::style("css/stylesheet.css") !!}
  {!! Html::style("css/parsley.css") !!}
  {!! Html::style("plugins/datatables/dataTables.bootstrap.min.css") !!}
  {!! Html::style("plugins/sweetalert/sweetalert.min.css") !!}
  @yield('override')
  {!! Html::style("css/AdminLTE.min.css") !!}
  {!! Html::style("css/_all-skins.min.css") !!}
  {!! Html::script("plugins/jQuery/jquery-3.2.1.min.js") !!}
  {!! Html::script("js/bootstrap.min.js") !!}
  {!! Html::script("plugins/pace/pace.min.js") !!}
  <style type="text/css">
  [data-notify="container"] {
    width: 25%;
  }
  .counter {
    overflow: hidden;
    text-overflow: ellipsis;
    padding: 10px
  }
</style>
<link rel="icon" href="{{ asset('img/logo.ico') }}">
</head>
<body class="hold-transition skin-red sidebar-mini">
  <div class="wrapper">
    <header class="main-header">
      <a href="{{ url('coordinator/dashboard') }}" class="logo">
        <span class="logo-mini"><img src="{{ asset('img/logo.png') }}" width="35" height="35"></span>
        <span class="logo-lg"><b>Scholar</b>MS</span>
      </a>
      <nav class="navbar navbar-static-top" role="navigation">
        <a href="#" class="sidebar-toggle" data-toggle="push-menu" role="button">
          <span class="sr-only">Toggle navigation</span>
        </a>
        <div class="navbar-custom-menu">
          <ul class="nav navbar-nav">
            <li class="dropdown tasks-menu">
              <a>
                <span class="hidden-xs">Budget: <small class="label bg-yellow budget">{{ $budget->amount }}</small></span>
              </a> 
            </li>
            <li class="dropdown tasks-menu">
              <a>
                <span class="hidden-xs">Slot: <small class="label bg-green slot">{{ $budget->slot_count }}</small></span>
              </a>
            </li>
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
                    <a href="{{ url('coordinator/profile') }}" class="btn btn-default btn-flat"><i class="fa fa-user"></i> Profile</a>
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
          <small>Coun. {{ $councilor->first_name }} {{ $councilor->last_name }}</small>
        </div>
      </div>
      <ul class="sidebar-menu" data-widget="tree">
        <li class="header">NAVIGATION</li>
        <li class="{{Request::path() == 'coordinator/dashboard' ? 'active' : ''}}"><a href="{{ url('coordinator/dashboard') }}"><i class="fa fa-dashboard"></i><span>Dashboard</span></a></li>
        <li class="treeview {{Request::path() == 'coordinator/renewal' ? 'active' : ''}} {{Request::path() == 'coordinator/applicants' ? 'active' : ''}}">
          <a href="#"><i class="fa fa-wpforms"></i><span>Applications</span>
            <span class="pull-right-container">
              <i class="fa fa-angle-left pull-right"></i>
            </span>
          </a>
          <ul class="treeview-menu">
            <li class="{{Request::path() == 'coordinator/applicants' ? 'active' : ''}}"><a href="{{ url('coordinator/applicants') }}"><i class="fa fa-users"></i><span>Applicants</span></a></li>
            <li class="{{Request::path() == 'coordinator/renewal' ? 'active' : ''}}"><a href="{{ url('coordinator/renewal') }}"><i class="fa fa-refresh"></i><span>Renewal</span></a></li>
          </ul>
        </li>
        <li class="{{Request::path() == 'coordinator/scholars' ? 'active' : ''}}"><a href="{{ url('coordinator/scholars') }}"><i class="fa fa-graduation-cap"></i><span>Scholars</span></a></li>
        <li class="treeview {{Request::path() == 'coordinator/messages' ? 'active' : ''}} {{Request::path() == 'coordinator/messages/create' ? 'active' : ''}} {{Request::path() == 'coordinator/messages/sent' ? 'active' : ''}} {{Request::path() == 'coordinator/announcements' ? 'active' : ''}} {{Request::path() == 'coordinator/events' ? 'active' : ''}}">
          <a href="#"><i class="fa fa-phone"></i><span>Communication&emsp;&emsp;&emsp;<small class="label bg-green panelnotif"></small></span>
            <span class="pull-right-container">
              <i class="fa fa-angle-left pull-right"></i>
            </span>
          </a>
          <ul class="treeview-menu">
            <li class="{{Request::path() == 'coordinator/messages' ? 'active' : ''}}{{Request::path() == 'coordinator/messages/create' ? 'active' : ''}} {{Request::path() == 'coordinator/messages/sent' ? 'active' : ''}}"><a href="{{ url('coordinator/messages') }}"><i class="fa fa-envelope"></i><span>Messages<small class="label pull-right bg-green panelnotif"></small></span></a></li>
            <li class="{{Request::path() == 'coordinator/announcements' ? 'active' : ''}}"><a href="{{ url('coordinator/announcements') }}"><i class="fa fa-bullhorn"></i><span>Announcements</span></a></li>
            <li class="{{Request::path() == 'coordinator/events' ? 'active' : ''}}"><a href="{{ url('coordinator/events') }}"><i class="fa fa-flag"></i><span>Events</span></a></li>
          </ul>
        </li>
        <li class="treeview {{Request::path() == 'coordinator/queries/events' ? 'active' : ''}}{{Request::path() == 'coordinator/queries/students' ? 'active' : ''}}{{Request::path() == 'coordinator/queries/grades' ? 'active' : ''}}">
          <a href="#"><i class="fa fa-list"></i><span>Queries</span>
            <span class="pull-right-container">
              <i class="fa fa-angle-left pull-right"></i>
            </span>
          </a>
          <ul class="treeview-menu">
            <li class="{{Request::path() == 'coordinator/queries/events' ? 'active' : ''}}"><a href="{{ url('coordinator/queries/events') }}"><i class="fa fa-flag"></i><span>Events</span></a></li>
            <li class="{{Request::path() == 'coordinator/queries/students' ? 'active' : ''}}"><a href="{{ url('coordinator/queries/students') }}"><i class="fa fa-graduation-cap"></i><span>Students</span></a></li>
          </ul>
        </li>
        <li class="treeview {{Request::path() == 'coordinator/reports' ? 'active' : ''}}">
          <a href="#"><i class="fa fa-trophy"></i><span>Reports</span>
            <span class="pull-right-container">
              <i class="fa fa-angle-left pull-right"></i>
            </span>
          </a>
          <ul class="treeview-menu">
            <li class="{{Request::path() == 'coordinator/reports/grades' ? 'active' : ''}}"><a href="{{ url('coordinator/reports/grades') }}"><i class="fa fa-level-up"></i><span>Grades</span></a></li>
            <li class="{{Request::path() == 'coordinator/reports' ? 'active' : ''}}"><a href="{{ url('coordinator/reports') }}"><i class="fa fa-money"></i><span>Budgets</span></a></li>
          </ul>
        </li>
        <li class="{{Request::path() == 'coordinator/utilities' ? 'active' : ''}}"><a href="{{ url('coordinator/utilities') }}"><i class="fa fa-gear"></i><span>Utilities</span></a></li>
        <li class="treeview {{Request::path() == 'coordinator/requirements' ? 'active' : ''}} {{Request::path() == 'coordinator/checklist' ? 'active' : ''}} {{Request::path() == 'coordinator/budget' ? 'active' : ''}}">
          <a href="#"><i class="fa fa-gears"></i><span>Settings</span>
            <span class="pull-right-container">
              <i class="fa fa-angle-left pull-right"></i>
            </span>
          </a>
          <ul class="treeview-menu">
            <li class="{{Request::path() == 'coordinator/budget' ? 'active' : ''}}"><a href="{{ url('coordinator/budget') }}"><i class="fa fa-money"></i><span>Budget</span></a></li>
            <li class="{{Request::path() == 'coordinator/requirements' ? 'active' : ''}}"><a href="{{ url('coordinator/requirements') }}"><i class="fa fa-files-o"></i><span>Requirements</span></a></li>
            <li class="{{Request::path() == 'coordinator/checklist' ? 'active' : ''}}"><a href="{{ url('coordinator/checklist') }}"><i class="fa fa-tasks"></i><span>Checklist</span></a></li>
          </ul>
        </li>
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
<script type="text/javascript">
  var notif = "{!! route('coordinatormessage.unreadmessage') !!}";
</script>
{!! Html::script("custom/NotificationAjax.min.js") !!}
{!! Html::script("js/ajax-expired-session.js") !!}
</body>
</html>