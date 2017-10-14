  @extends('SMS.Coordinator.CoordinatorMain')
  @section('override')
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
        <li class="active"><i class="fa fa-level-up"></i> Grades</li>
      </ol>
    </section>
    <section class="content">
      <div class="row">
        <div class="col-sm-12">
          <div class="box box-danger container">
            {{ Form::open(['data-parsley-whitespace' => 'squish', 'target' => '_blank', 'route' => 'queries.postGrades']) }}
            <br>
            <div class="form-group">
              <label>Type</label><br>
              <label class="radio-inline"><input type="radio" name="status" value="P" checked="checked">Passed</label>
              <label class="radio-inline"><input type="radio" name="status" value="F">Failed</label>
            </div>
            <table id="table" class="table table-bordered table-striped table-hover" cellspacing="0" width="100%">
              <thead>
                <th><input type="checkbox" name="checkbox" id="checkbox"></th>
                <th>No.</th>
                <th>Semester Date</th>
              </thead>
              <tbody id="list">
                @foreach ($budget as $key => $budgets)
                <tr>
                  <td><input type="checkbox" name="checked[]" class="checkbox" value="{{ $budgets->id }}"></td>
                  <td>{{ $key + 1 }}</td>
                  <td>{{ $budgets->budget_date->format('M d, Y') }}</td>
                </tr>
                @endforeach
              </tbody>
            </table>
            <br>
            <div class="row">
              <div class="col-md-12 form-group">
                <button type="submit" class="btn btn-primary pull-right">Generate</button>
              </div>
            </div>
            {{ Form::close() }}
          </div>
        </div>
      </div>
    </section>
  </div>
  @endsection
  @section('script')
  <script type="text/javascript">
    $('#table').DataTable({
      "aaSorting": [],
      "columnDefs": [
      { "width": "30px", "targets": 0 },
      { "width": "40px", "targets": 1 },
      { orderable: false, targets: 0 }
      ]
    });
    $("#checkbox").click(function(){
      $('input:checkbox').not(this).prop('checked', this.checked);
    });
  </script>
  @endsection