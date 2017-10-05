@extends('SMS.Coordinator.CoordinatorMain')
@section('override')
@endsection
@section('content')
<div class="content-wrapper">
	<section class="content-header">
		<h1>
			Checklist
		</h1>
		<ol class="breadcrumb">
			<li><a href="{{ url('coordinator/dashboard') }}"><i class="fa fa-dashboard"></i> Dashboard</a></li>
			<li><i class="fa fa-fw fa-gears"></i> Settings</li>
			<li class="active"><i class="fa fa-fw fa-tasks"></i> Checklist</li>
		</ol>
	</section>
	<section class="content">
		<div class="nav-tabs-custom">
			<ul class="nav nav-tabs">
				<li class="active"><a href="#tab_1" data-toggle="tab">Claiming</a></li>
				<li><a href="#tab_2" data-toggle="tab">School</a></li>
				<li><a href="#tab_3" data-toggle="tab">Course</a></li>
			</ul>
			<div class="tab-content">
				<div class="tab-pane active" id="tab_1">
					<div class="box-body table-responsive">
						<table id="table-claiming" class="table table-bordered table-striped table-hover" cellspacing="0" width="100%">
							<thead>
								<th>Budget Type</th>
								<th>Status</th>
							</thead>
							<tbody id="list-claiming">
							</tbody>
						</table>
					</div>
				</div>
				<div class="tab-pane" id="tab_2">
					<div class="box-body table-responsive">
						<table id="table-school" class="table table-bordered table-striped table-hover" cellspacing="0" width="100%">
							<thead>
								<th>Abbreviation</th>
								<th>School Name</th>
								<th>Status</th>
							</thead>
							<tbody id="list-school">
							</tbody>
						</table>
					</div>
				</div>
				<div class="tab-pane" id="tab_3">
					<div class="box-body table-responsive">
						<table id="table-course" class="table table-bordered table-striped table-hover" cellspacing="0" width="100%">
							<thead>
								<th>Abbreviation</th>
								<th>Course Name</th>
								<th>Status</th>
							</thead>
							<tbody id="list-course">
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
{!! Html::script("custom/CoordinatorChecklistAjax.min.js") !!}
<script type="text/javascript">
	var dataurlclaiming = "{!! route('coordinatorclaiming.data') !!}";
	var dataurlschool = "{!! route('coordinatorschool.data') !!}";
	var dataurlcourse = "{!! route('coordinatorcourse.data') !!}";
</script>
@endsection