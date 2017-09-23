@extends('SMS.Coordinator.CoordinatorMain')
@section('content')
<div class="content-wrapper">
	<section class="content-header">
		<h1>
			Scholar List
		</h1>
		<ol class="breadcrumb">
			<li><a href="{{ url('coordinator/dashboard') }}"><i class="fa fa-dashboard"></i> Dashboard</a></li>
			<li><i class="fa fa-graduation-cap"></i> Scholar</li>
			<li class="active"><i class="fa fa-list-ul"></i> List</li>
		</ol>
	</section>
	<section class="content">
		<div class="row">
			<div class="col-xs-12">
				<div class="box box-danger">
					<div class="box-body table-responsive">
						<div class="row col-sm-12 form-group">
							<div class="row col-sm-3">
								<select id="status" name="status" class="form-control">
									<option value="Continuing" selected="selected">Continuing</option>
									<option value="Graduated">Graduated</option>
									<option value="Forfeit">Forfeit</option>
								</select>
							</div>
						</div>
						<table id="student-table" class="table table-bordered table-striped table-hover" cellspacing="0" width="100%">
							<thead>
								<th>ID</th>
								<th>Student</th>
								<th>Requirements</th>
								<th>Claiming</th>
								<th>Status</th>
								<th >Action</th>
							</thead>
							<tbody id="student-list">
							</tbody>
						</table>
					</div>
				</div>
			</div>
		</div>
	</section>
</div>
@endsection
@section('script')
{!! Html::script("custom/ListAjax.min.js") !!}
<script type="text/javascript">
	var dataurl = "{!! route('scholars.store') !!}";
</script>
@endsection
