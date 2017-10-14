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
            <div class="modal fade" id="criteria">
              <div class="modal-dialog">
                <div class="modal-content">
                  <div class="modal-header">
                    {{ Form::button('&times;', [
                      'class' => 'close',
                      'type' => '',
                      'data-dismiss' => 'modal'
                    ]) 
                  }}
                  <h4>Criteria</h4>
                </div>
                <div class="modal-body">
                  {{ Form::open([
                    'route' => 'applicants.postCriteria'
                  ])
                }}
                Parent Monthly Income Cap
                {{ Form::select('income_cap', [
                  '35,000 and Above' => '35,000 and Above',
                  '30,000 - 35,000' => '30,000',
                  '25,000 - 30,000' => '25,000',
                  '20,000 - 25,000' => '20,000',
                  '15,000 - 20,000' => '15,000',
                  '10,000 - 15,000' => '10,000',
                  '10,000 and Below' => '10,000 and Below'
                ], $utility->income_cap, [
                  'id' => 'income_cap',
                  'class' => 'form-control'])
                }}
                <div class="form-group">
                  <div class="checkbox">
                    @if ($utility->passing_grades)
                    <label><input type="checkbox" name="passing_grades" checked="checked">Automatically decline "Failed" grades (for applicants and renewal)</label>
                    @else
                    <label><input type="checkbox" name="passing_grades">Automatically decline "Failed" grades (for applicants and renewal)</label>
                    @endif
                  </div>
                  <div class="checkbox">
                    @if ($utility->no_siblings)
                    <label><input type="checkbox" name="no_siblings" checked="checked">Does not have sibling affiliated</label>
                    @else
                    <label><input type="checkbox" name="no_siblings">Does not have sibling affiliated</label>
                    @endif
                  </div>
                </div>
                <div class="modal-footer">
                  <button type="submit" class="btn btn-success">Apply</button>
                </div>
                {{ Form::close() }}
              </div>
            </div>
          </div>
        </div>
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
          <div class="pull-right">
            <a id="cri" style="color: black; cursor: pointer;"><strong><u>Criteria</u></strong></a>
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
    showConfirmButton: true,
    confirmButtonClass: "btn-success",
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