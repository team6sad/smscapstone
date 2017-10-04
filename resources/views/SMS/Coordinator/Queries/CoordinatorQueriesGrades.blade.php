  @extends('SMS.Coordinator.CoordinatorMain')
  @section('override')
  {!! Html::style("plugins/select2/select2.min.css") !!}
  @endsection
  @section('content')
  <div class="content-wrapper">
    <section class="content-header">
      <h1>
        Grades
      </h1>
      <ol class="breadcrumb">
        <li><a href="{{ url('coordinator/dashboard') }}"><i class="fa fa-dashboard"></i> Dashboard</a></li>
        <li class="active"><i class="fa fa-list"></i> Queries</li>
        <li class="active"><i class="fa fa-users"></i> Grades</li>
      </ol>
    </section>
    <section class="content">
      <div class="row">
        <div class="col-sm-12">
          <div class="box box-danger container">
            <div class="row">
              <br>
              {{ Form::open(['data-parsley-whitespace' => 'squish', 'target' => '_blank', 'route' => 'queries.postGrades']) }}
              <div class="col-md-12 form-group row">
                <div class="col-md-6">
                  <label class="control-label">Student:</label>
                  <select class="form-control dropdownbox" name="name">
                    @foreach ($application as $applications)
                    <option value="{{ $applications->id }}">{{ $applications->strStudName }}</option>
                    @endforeach
                  </select>
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
    {!! Html::script("plugins/select2/select2.min.js") !!}
    <script type="text/javascript">
      $('.dropdownbox').select2();
    </script>
    @endsection