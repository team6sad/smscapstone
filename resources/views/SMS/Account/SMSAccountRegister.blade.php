@extends('SMS.SMSMain')
@section('title')
<title>ScholarMS|Login</title>
@endsection
@section('override')
{!! Html::style("css/parsley.css") !!}
@endsection
@section('login')
<div class="navbar-custom-menu">
  <ul class="nav navbar-nav">
    <li class="{{Request::path() == 'login' ? 'active' : ''}}"><a href="{{ url('/login') }}">Login</a></li>
    <li class="{{Request::path() == 'register' ? 'active' : ''}}"><a href="{{ url('/register') }}">Register</a></li>
  </ul>
</div>
@endsection
@section('middlecontent')
<div class="container" style="margin-top: 20px;">
  <div class="row">
    <div class="col-md-8 col-md-offset-2">
      <div class="panel panel-default">
        <div class="panel-heading">Register</div>
        <div class="panel-body">
          <form class="form-horizontal" role="form" method="POST" action="{{ route('register') }}">
            {{ csrf_field() }}
            <div class="form-group{{ $errors->has('first_name') ? ' has-error' : '' }}">
              <label for="first_name" class="col-md-4 control-label">First Name</label>
              <div class="col-md-6">
                <input id="first_name" type="text" class="form-control" name="first_name" value="{{ old('first_name') }}" required autofocus>
                @if ($errors->has('first_name'))
                <span class="help-block">
                  <strong>{{ $errors->first('first_name') }}</strong>
                </span>
                @endif
              </div>
            </div>
            <div class="form-group{{ $errors->has('middle_name') ? ' has-error' : '' }}">
              <label for="middle_name" class="col-md-4 control-label">Middle Name</label>
              <div class="col-md-6">
                <input id="middle_name" type="text" class="form-control" name="middle_name" value="{{ old('middle_name') }}" required>
                @if ($errors->has('middle_name'))
                <span class="help-block">
                  <strong>{{ $errors->first('middle_name') }}</strong>
                </span>
                @endif
              </div>
            </div>
            <div class="form-group{{ $errors->has('last_name') ? ' has-error' : '' }}">
              <label for="last_name" class="col-md-4 control-label">Last Name</label>
              <div class="col-md-6">
                <input id="last_name" type="text" class="form-control" name="last_name" value="{{ old('last_name') }}" required>
                @if ($errors->has('last_name'))
                <span class="help-block">
                  <strong>{{ $errors->first('last_name') }}</strong>
                </span>
                @endif
              </div>
            </div>
            <div class="form-group{{ $errors->has('cell_no') ? ' has-error' : '' }}">
              <label for="cell_no" class="col-md-4 control-label">Cell no.</label>
              <div class="col-md-6">
                <input id="cell_no" type="text" class="form-control" name="cell_no" value="{{ old('cell_no') }}" required>
                @if ($errors->has('cell_no'))
                <span class="help-block">
                  <strong>{{ $errors->first('cell_no') }}</strong>
                </span>
                @endif
              </div>
            </div>
            <div class="form-group{{ $errors->has('email') ? ' has-error' : '' }}">
              <label for="email" class="col-md-4 control-label">E-Mail Address</label>
              <div class="col-md-6">
                <input id="email" type="email" class="form-control" name="email" value="{{ old('email') }}" required>
                @if ($errors->has('email'))
                <span class="help-block">
                  <strong>{{ $errors->first('email') }}</strong>
                </span>
                @endif
              </div>
            </div>
            <div class="form-group{{ $errors->has('password') ? ' has-error' : '' }}">
              <label for="password" class="col-md-4 control-label">Password</label>
              <div class="col-md-6">
                <input id="password" type="password" class="form-control" name="password" required>
                @if ($errors->has('password'))
                <span class="help-block">
                  <strong>{{ $errors->first('password') }}</strong>
                </span>
                @endif
              </div>
            </div>
            <div class="form-group">
              <label for="password-confirm" class="col-md-4 control-label">Confirm Password</label>
              <div class="col-md-6">
                <input id="password-confirm" type="password" class="form-control" name="password_confirmation" required>
              </div>
            </div>
            <div class="form-group">
              <div class="col-md-6 col-md-offset-4">
                <button type="submit" class="btn btn-primary">
                  Register
                </button>
              </div>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection
@section('endscript')
{!! Html::script("js/jquery.backstretch.min.js") !!}
{!! Html::script("js/scriptslogin.js") !!}
{!! Html::script("js/parsley.min.js") !!} 
@endsection