  @extends('SMS.Coordinator.CoordinatorMain')
  @section('override')
  {!! Html::style("plugins/datepicker/datepicker3.css") !!}
  @endsection
  @section('content')
  <div class="content-wrapper">
    <section class="content-header">
      <h1>
        Students
      </h1>
      <ol class="breadcrumb">
        <li><a href="{{ url('coordinator/dashboard') }}"><i class="fa fa-dashboard"></i> Dashboard</a></li>
        <li class="active"><i class="fa fa-list"></i> Queries</li>
        <li class="active"><i class="fa fa-graduation-cap"></i> Students</li>
      </ol>
    </section>
    <section class="content">
      <div class="row">
        <div class="col-sm-12">
          <div class="box box-danger container">
            <div class="row">
              <br>
              {{ Form::open(['data-parsley-whitespace' => 'squish', 'target' => '_blank', 'route' => 'queries.postStudents']) }}
              <div class="col-md-6 form-group">
                <label class="control-label">Batch:</label>
                <select class="form-control" name="batch">
                  @foreach ($batch as $batches)
                  <option value="{{ $batches->id }}">{{ $batches->description }}</option>
                  @endforeach
                </select>
              </div>
              <div class="col-md-6 form-group">
                <label class="control-label">Type:</label>
                <select class="form-control" name="status">
                  <option value="Accepted">Accepted (Application)</option>
                  <option value="Pending">Pending (Application)</option>
                  <option value="Declined">Declined (Application)</option>
                  <option value="Continuing">Continuing (Scholarship)</option>
                  <option value="Graduated">Graduated (Scholarship)</option>
                  <option value="Forfeit">Forfeited (Scholarship)</option>
                </select>
              </div>
              <div class="col-md-12 form-group">
                <div class="col-md-12 row">
                  <label class="control-label">School:</label>
                </div>
                @foreach ($school as $schools)
                <div class="col-md-4">
                  <div class="checkbox">
                    <label><input type="checkbox" value="{{ $schools->id }}" name="school[]">{{ $schools->description }}</label>
                  </div>
                </div>
                @endforeach
              </div>
              <div class="col-md-12 form-group">
                <div class="col-md-12 row">
                  <label class="control-label">Course:</label>
                </div>
                @foreach ($course as $courses)
                <div class="col-md-4">
                  <div class="checkbox">
                    <label><input type="checkbox" value="{{ $courses->id }}" name="course[]">{{ $courses->description }}</label>
                  </div>
                </div>
                @endforeach
              </div>
              <div class="col-md-12 form-group">
                <div class="col-md-12 row">
                  <label class="control-label">Barangay:</label>
                </div>
                @foreach ($barangay as $barangays)
                <div class="col-md-4">
                  <div class="checkbox">
                    <label><input type="checkbox" value="{{ $barangays->id }}" name="barangay[]">{{ $barangays->description }}</label>
                  </div>
                </div>
                @endforeach
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