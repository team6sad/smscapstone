  @extends('SMS.Coordinator.CoordinatorMain')
  @section('override')
  {!! Html::style("plugins/datepicker/datepicker3.css") !!}
  @endsection
  @section('content')
  <div class="content-wrapper">
    <section class="content-header">
      <h1>
        Events
      </h1>
      <ol class="breadcrumb">
        <li><a href="{{ url('coordinator/dashboard') }}"><i class="fa fa-dashboard"></i> Dashboard</a></li>
        <li class="active"><i class="fa fa-list"></i> Queries</li>
        <li class="active"><i class="fa fa-users"></i> Events</li>
      </ol>
    </section>
    <section class="content">
      <div class="row">
        <div class="col-sm-12">
          <div class="box box-danger container">
            <div class="row">
              <br>
              {{ Form::open(['data-parsley-whitespace' => 'squish', 'target' => '_blank', 'route' => 'queries.postEvents']) }}
              <div class="form-group col-md-6">
                <label class="control-label">From:</label>
                <div class="input-group date">
                  <div class="input-group-addon">
                    <i class="fa fa-calendar"></i>
                  </div>
                  <input type="text" name="date_from" class="form-control datepicker" required="required">
                </div>
              </div>
              <div class="form-group col-md-6">
                <label class="control-label">To:</label>
                <div class="input-group date">
                  <div class="input-group-addon">
                    <i class="fa fa-calendar"></i>
                  </div>
                  <input type="text" name="date_to" class="form-control datepicker" required="required">
                </div>
              </div>
              <div class="col-md-12">
                <label class="control-label">Status:</label>
                <div class="checkbox">
                  <label><input type="checkbox" value="Done" name="status[]">Done</label>
                </div>
                <div class="checkbox">
                  <label><input type="checkbox" value="Ongoing" name="status[]">Ongoing</label>
                </div>
                <div class="checkbox">
                  <label><input type="checkbox" value="Cancelled" name="status[]">Cancelled</label>
                </div>
              </div>
              <div class="col-md-12 form-group">
                <button type="submit" class="btn btn-primary pull-right">Generate</button>
              </div>
              {{ Form::close() }}
            </div>
          </div>
        </div>
      </section>
    </div>
    @endsection
    @section('script')
    {!! Html::script("plugins/datepicker/bootstrap-datepicker.js") !!}
    <script type="text/javascript">
      $('.datepicker').datepicker({
        viewMode: "years",
        autoclose: true,
        format: 'yyyy-mm-dd'
      });
    </script>
    @endsection