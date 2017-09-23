  @extends('SMS.Coordinator.CoordinatorMain')
  @section('content')
  <div class="content-wrapper">
    <section class="content-header">
      <h1>
        Applicants
      </h1>
      <ol class="breadcrumb">
        <li><a href="{{ url('coordinator/dashboard') }}"><i class="fa fa-dashboard"></i> Dashboard</a></li>
        <li class="active"><i class="fa fa-users"></i> Applicants</li>
      </ol>
    </section>
    <section class="content">
      <div class="row">
        <div class="container col-sm-12">
          <div class="box box-danger">
            <div class="box-body table-responsive">
              <div class="row col-sm-12 form-group">
                <div class="row col-sm-3">
                  <select id="status" name="status" class="form-control">
                    <option value="Pending" selected="selected">Pending</option>
                    <option value="Accepted">Accepted</option>
                    <option value="Declined">Declined</option>
                  </select>
                </div>
              </div>
              <table id="table" class="table table-bordered table-striped table-hover" cellspacing="0" width="100%">
                <thead>
                  <th>ID</th>
                  <th>Student</th>
                  <th>School</th>
                  <th>Course</th>
                  <th>Date Applied</th>
                  <th>Action</th>
                </thead>
                <tbody id="list">
                </tbody>
              </table>
            </div>
          </div>
        </div>
      </section>
    </div>
    @endsection
    @section('script')
    {!! Html::script("custom/ApplicantsAjax.min.js") !!}
    <script type="text/javascript">
      var dataurl = "{!! route('applicants.data') !!}";
      @if (Session::has('success'))
      swal({
        title: "Success!",
        text: "<center>{{Session::get('success')}}</center>",
        type: "success",
        timer: 1000,
        showConfirmButton: false,
        html: true
      });
      @elseif (Session::has('fail'))
      swal({
        title: "Failed!",
        text: "<center>{{Session::get('fail')}}</center>",
        type: "error",
        confirmButtonClass: "btn-success",
        showConfirmButton: true,
        html: true
      });
      @endif
    </script>
    @endsection