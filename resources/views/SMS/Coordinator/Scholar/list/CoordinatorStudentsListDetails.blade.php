@extends('SMS.Coordinator.CoordinatorMain')
@section('content')
<div class="content-wrapper">
	<section class="content-header">
		<h1>
			Scholar Details
		</h1>
		<ol class="breadcrumb">
			<li><a href="{{ url('coordinator/dashboard') }}"><i class="fa fa-dashboard"></i> Dashboard</a></li>
			<li><i class="fa fa-graduation-cap"></i> Scholar</li>
			<li class="active"><i class="fa fa-list-ul"></i> Details</li>
		</ol>
	</section>
	<section class="content">
		<div class="row">
			<div class="col-md-3">
				<div class="box box-primary">
					<div class="box-body box-profile">
						<img class="profile-user-img img-responsive img-circle" src="{{ asset('images/'.$application->picture) }}" alt="User profile picture">
						<h3 class="profile-username text-center">{{ $application->strStudName }}</h3>
						<ul class="list-group list-group-unbordered">
							<li class="list-group-item">
								<b>Email</b> <a class="pull-right">{{ $application->email }}</a>
							</li>
							<li class="list-group-item">
								<b>Contact No.</b> <a class="pull-right">{{ $application->cell_no }}</a>
							</li>
							<li class="list-group-item">
								@if ($application->gender)
								<b>Gender</b> <a class="pull-right">Female</a>
								@else
								<b>Gender</b> <a class="pull-right">Male</a>
								@endif
							</li>
							<li class="list-group-item">
								<b>Birthday</b> <a class="pull-right">{{ $application->date }}</a>
							</li>
							<li class="list-group-item">
								<b>Age</b> <a class="pull-right">{{ $application->birthday->diffInYears() }}</a>
							</li>
							<li class="list-group-item">
								<b>Status</b> <a class="pull-right student_status">{{ $application->student_status }}</a>
							</li>
							@if ($application->student_status != 'Continuing')
							<li class="list-group-item">
								<b>Year</b> <a class="pull-right">....</a>
							</li>
							<li class="list-group-item">
								<b>Semester</b> <a class="pull-right">....</a>
							</li>
							@else
							<li class="list-group-item">
								<b>Year</b> <a class="pull-right">{{ $grade->year }}</a>
							</li>
							<li class="list-group-item">
								<b>Semester</b> <a class="pull-right">{{ $grade->semester }}</a>
							</li>
							@endif
						</ul>
					</div>
				</div>
				<div class="box box-primary">
					<div class="box-header with-border">
						<h3 class="box-title">About Scholar</h3>
					</div>
					<div class="box-body">
						<strong><i class="fa fa-graduation-cap margin-r-5"></i> School</strong>
						<p class="text-muted">
							{{ $application->school }}
						</p>
						<hr>
						<strong><i class="fa fa-book margin-r-5"></i> Course</strong>
						<p class="text-muted">
							{{ $application->course }}
						</p>
						<hr>
						<strong><i class="fa fa-map-marker margin-r-5"></i> Address</strong>
						<p class="text-muted">{{ $application->house_no }} {{ $application->street }} {{ $application->barangay }} {{ $application->district }}</p>
						<hr>
						<strong><i class="fa fa-plus margin-r-5"></i> Religion</strong>
						<p class="text-muted">{{ $application->religion }}</p>
						<hr>
					</div>
				</div>
			</div>
			<div class="col-md-9">
				@if (count($errors) > 0)
				<div class="alert alert-warning alert-dismissible" role="alert">
					<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
					<strong>Errors:</strong>
					<ul>
						@foreach ($errors->all() as $error)
						<li>{{$error}}</li>
						@endforeach
					</ul>
				</div>
				@endif
				<div class="nav-tabs-custom">
					<ul class="nav nav-tabs">
						<li id="available" class="active"><a href="#tab_1" data-toggle="tab">Details</a></li>
						<li><a href="#tab_2" data-toggle="tab">Requirements</a></li>
						<li><a href="#tab_3" data-toggle="tab">Claiming</a></li>
						<li><a href="#tab_4" data-toggle="tab">Receipts</a></li>
						<li class="pull-right header"><span class="mailbox-read-time"><a href="{{ route('details.form',$application->user_id) }}" target="_blank" class="btn btn-default btn-sm text-muted"><i class="fa fa-print"></i></a></span></li>
					</ul>
					<div class="tab-content">
						<div class="tab-pane active" id="tab_1">
							<div class="row">
								<div class="form-group col-sm-6">
									<label>Scholarship Status</label>
									{{ Form::select('student_status', [
										'Continuing' => 'Continuing',
										'Graduated' => 'Graduated',
										'Forfeit' => 'Forfeit'
									], $application->student_status, [
										'id' => 'student_status',
										'class' => 'form-control'])
									}}
								</div>
								<div class="form-group col-sm-6">
									<label>Account Status</label><br>
									@if ($application->is_active)
									<input type='checkbox' id='isActive' name='isActive' value='{{ $application->id }}' data-toggle='toggle' data-style='android' data-onstyle='success' data-offstyle='danger' data-on="<i class='fa fa-check-circle'></i> Active" data-off="<i class='fa fa-times-circle'></i> Inactive" data-size='small' checked="checked">
									@else
									<input type='checkbox' id='isActive' name='isActive' value='{{ $application->id }}' data-toggle='toggle' data-style='android' data-onstyle='success' data-offstyle='danger' data-on="<i class='fa fa-check-circle'></i> Active" data-off="<i class='fa fa-times-circle'></i> Inactive" data-size='small'>
									@endif
								</div>
							</div>
							<hr>
							<div class="form-group">
								<label>Grades PDF</label>
								<div class="table-responsive">
									<table class="table table-striped">
										<thead>
											<tr>
												<th>Year</th>
												<th>Semester</th>
												<th>Action</th>
											</tr>
										</thead>
										<tbody>
											@foreach ($pdf as $pdfs)
											<tr>
												<td>{{ $pdfs->year }}</td>
												<td>{{ $pdfs->semester }}</td>
												<td><a href="{{ asset('docs/'.$pdfs->pdf) }}" target="_blank"><button type="button" class="btn btn-info btn-xs"><i class='fa fa-eye'></i> View</button></a></td>
											</tr>
											@endforeach
										</tbody>
									</table>
								</div>
							</div>
							<hr>
							<div class="form-group">
								<label>Tranfer History</label>
								<div class="table-responsive">
									<table class="table table-striped">
										<thead>
											<tr>
												<th>Old School</th>
												<th>Old Course</th>
												<th>Year</th>
												<th>Semester</th>
												<th>Date</th>
											</tr>
										</thead>
										<tbody>
											@if ($shift->isEmpty())
											<tr>
												<td>N/A</td>
												<td>N/A</td>
												<td>N/A</td>
												<td>N/A</td>
												<td>N/A</td>
											</tr>
											@else
											@foreach ($shift as $shifts)
											<tr>
												<td>{{ $shifts->school }}</td>
												<td>{{ $shifts->course }}</td>
												<td>{{ $shifts->year }}</td>
												<td>{{ $shifts->semester }}</td>
												<td>{{ $shifts->shift_date->format('F d, Y') }}</td>
											</tr>
											@endforeach
											@endif
										</tbody>
									</table>
								</div>
							</div>
						</div>
						<div class="tab-pane" id="tab_2">
							@foreach ($allgrade as $allgrades)
							<div class="panel box box-success">
								<div class="box-header with-border">
									<h4 class="box-title">
										<a data-toggle="collapse" data-parent="#accordion" href="#req{{ $allgrades->id }}">
											{{ $allgrades->year }} Year {{ $allgrades->semester }} Semester
										</a>
									</h4>
								</div>
								<div id="req{{ $allgrades->id }}" class="panel-collapse collapse">
									<div class="box-body">
										<ul>
											@foreach ($oldgrade as $oldgrades)
											@if ($allgrades->id == $oldgrades->grade_id)
											<li>{{ $oldgrades->description }} - {{ $oldgrades->date_passed }}</li>
											@endif
											@endforeach
										</ul>
									</div>
								</div>
							</div>
							@endforeach
							@if ($application->student_status=='Continuing')
							{{ Form::open([
								'id' => 'frmRequirement',
								'route' => ['scholars.requirements',$application->id]
							])
						}}
						<div class="panel box box-success">
							<div class="box-header with-border">
								<h4 class="box-title">
									<a data-toggle="collapse" data-parent="#accordion" href="#req">
										Latest
									</a>
								</h4>
							</div>
							<div id="req" class="panel-collapse collapse in">
								<div class="box-body">
									<div class="col-xs-12">
										<?php $ctr = 0 ?>
										@if ($application->is_renewal == 0 && $getsem != 0)
										@foreach ($requirement as $requirements)
										<?php $ctr++; ?>
										<div class="col-sm-6">
											<div class="checkbox">
												<label><input type="checkbox" value="{{ $requirements->id }}" name="steps[]">{{ $requirements->description }}</label>
											</div>
										</div>
										@endforeach
										@if ($ctr==0)
										<center>Requirements Completed</center>
										@endif
										@else
										<center>Not Current Accepted in Renewal</center>
										@endif
									</div>
								</div>
							</div>
						</div>
						@if ($ctr!=0)
						<div class="row">
							<div class="col-xs-12">
								<div class="form-group">
									{{ Form::button("<i class='fa fa-paper-plane'></i> Submit", [
										'class' => 'btn btn-success btn-requirement pull-right',
										'type' => ''
									]) 
								}}
							</div>
						</div>
					</div>
					@endif
					{{ Form::close() }}
					@endif
				</div>
				<div class="tab-pane" id="tab_3">
					@foreach ($allgrade as $allgrades)
					<div class="panel box box-success">
						<div class="box-header with-border">
							<h4 class="box-title">
								<a data-toggle="collapse" data-parent="#accordion" href="#{{ $allgrades->id }}">
									{{ $allgrades->year }} Year {{ $allgrades->semester }} Semester
								</a>
							</h4>
						</div>
						<div id="{{ $allgrades->id }}" class="panel-collapse collapse">
							<div class="box-body">
								<ul>
									@foreach ($oldallocation as $oldallocations)
									@if ($allgrades->id == $oldallocations->grade_id)
									<li>{{ $oldallocations->description }} - {{ $oldallocations->date_claimed }} // Amount: {{ $oldallocations->amount }} // OR: {{ $oldallocations->receipt_id }}</li>
									@endif
									@endforeach
								</ul>
							</div>
						</div>
					</div>
					@endforeach
					@if ($application->student_status=='Continuing')
					{{ Form::open([
						'id' => 'frmClaiming',
						'route' => ['scholars.stipend',$application->id]
					])
				}}
				<div class="panel box box-success">
					<div class="box-header with-border">
						<h4 class="box-title">
							<a data-toggle="collapse" data-parent="#accordion" href="#collapseThree">
								Latest
							</a>
						</h4>
					</div>
					<div id="collapseThree" class="panel-collapse collapse in">
						<div class="box-body">
							<div class="col-xs-12">
								@if ($count == $studentstep)
								<?php $ctr2 = 0 ?>
								@foreach ($allocation as $allocations)
								<?php $ctr2++; ?>
								<div class="col-sm-6">
									<div class="checkbox">
										<label><input type="checkbox" value="{{ $allocations->id }}" name="claim[]">{{ $allocations->description }}</label>
									</div>
									<input type="text" name="amount{{ $allocations->id }}" value="{{ $allocations->amount }}" class="form-control">
								</div>
								@endforeach
								@if ($ctr2==0)
								<center>Claiming Completed</center>
								@endif
								@else
								<center>Complete Requirements First</center>
								@endif
							</div>
						</div>
					</div>
				</div>
				@if ($count == $studentstep)
				@if ($ctr2!=0)
				<div class="row">
					<div class="col-xs-12">
						<div class="form-group">
							{{ Form::button("<i class='fa fa-paper-plane'></i> Submit", [
								'class' => 'btn btn-success btn-claiming pull-right',
								'type' => ''
							]) 
						}}
					</div>
				</div>
			</div>
			@endif
			@endif
			{{ Form::close() }}
			@endif
		</div>
		<div class="tab-pane" id="tab_4">
			<div class="box-body table-responsive">
				<table id="table" class="table table-bordered table-striped table-hover" cellspacing="0" width="100%">
					<thead>
						<th>OR No.</th>
						<th>Total</th>
						<th>Date</th>
						<th>Action</th>
					</thead>
					<tbody id="list">
					</tbody>
				</table>
			</div>
		</div>
	</div>
</div>
</div>
</div>
</section>
</div>
@endsection
@section('script')
{!! Html::script("custom/CoordinatorScholarAjax.min.js") !!}
<script type="text/javascript">
	var dataurl = "{{ route('coordinatorreceipt.data', $application->user_id) }}";
	var url = "{{ route('scholars.status', $application->user_id) }}";
	@if (Session::has('success'))
	window.open("{{ route('scholars.receipt',Session::get('success')) }}", '_blank');
	swal({
		title: "Success!",
		text: "<center>Data Stored</center>",
		type: "success",
		showConfirmButton: true,
		confirmButtonClass: "btn-success",
		html: true
	});
	@endif
	@if (Session::has('confirm'))
	swal({
		title: "Success!",
		text: "<center>Data Stored</center>",
		type: "success",
		showConfirmButton: true,
		confirmButtonClass: "btn-success",
		html: true
	});
	@endif
</script>
@endsection