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
        <li class="active"><i class="fa fa-trophy"></i> Reports</li>
        <li class="active"><i class="fa fa-users"></i> Grades</li>
      </ol>
    </section>
    <section class="content">
      <div class="row">
        <div class="col-sm-12">
          <div class="box box-danger container">
            {{ Form::open(['data-parsley-whitespace' => 'squish', 'target' => '_blank', 'route' => 'reports.postGrades']) }}
            <br>
            <table id="table" class="table table-bordered table-striped table-hover" cellspacing="0" width="100%">
              <thead>
                <th><input type="checkbox" name="checkbox" id="checkbox"></th>
                <th>ID</th>
                <th >Name</th>
              </thead>
              <tbody id="list">
                @foreach ($application as $applications)
                <tr>
                  <td><input type="checkbox" name="name[]" class="checkbox" value="{{ $applications->id }}"></td>
                  <td>{{ $applications->id }}</td>
                  <td><table><tr><td><div class='col-md-2'><img src='{{ asset('images/'.$applications->picture) }}' class='img-circle' alt='data Image' height='40'></div></td><td>{{ $applications->strStudName }}</td></tr></table></td>
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
      { orderable: false, targets: 0 }
      ]
    });
    $("#checkbox").click(function(){
      $('input:checkbox').not(this).prop('checked', this.checked);
    });
  </script>
  @endsection