@extends('SMS.Student.StudentMain')
@section('content')
<div class="content-wrapper">
	<section class="content-header">
		<h1>
			Dashboard
		</h1>
	</section>
	<section class="content">
		@if ($utility->phase_status && !$application->is_new)
		@if (!$application->is_renewal && !$userbudget)
		<div class="callout callout-success">
			<h4><i class="icon fa fa-info"></i> Renewal Status</h4>
			Renewal Phase Ongoing
		</div>
		@else
		<div class="callout callout-success">
			<h4><i class="icon fa fa-info"></i> Renewal Status</h4>
			Renewal request has been submitted
		</div>
		@endif
		@else
		<div class="callout callout-danger">
			<h4><i class="icon fa fa-info"></i> Renewal Status</h4>
			Renewal Phase Closed
		</div>
		@endif
		<div class="row">
			<div class="col-md-6">
				<div class="box box-danger">
					<div class="box-header with-border">
						<h3 class="box-title">Requirements</h3>
						<div class="box-tools pull-right">
							<button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
							</button>
						</div>
					</div>
					<div class="box-body">
						<div class="table-responsive">
							<table class="table no-margin">
								<thead>
									<tr>
										<th>Description</th>
										<th>Status</th>
									</tr>
								</thead>
								<tbody>
									@foreach ($list as $lists)
									<tr>
										<td>{{ $lists->description }}</td>
										@if ($list->count() == $requirement->count())
										<td>Not Passed</td>
										@elseif ($requirement->isEmpty())
										<td>Passed</td>
										@else
										@if ($requirement->where('id',$lists->id)->count() < 1)
										<td>Passed</td>
										@else
										<td>Not Passed</td>
										@endif
										@endif
									</tr>
									@endforeach
								</tbody>
							</table>
						</div>
					</div>
				</div>
			</div>
			<div class="col-md-6">
				<div class="box box-danger">
					<div class="box-header with-border">
						<h3 class="box-title">Claiming</h3>
						<div class="box-tools pull-right">
							<button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
							</button>
						</div>
					</div>
					<div class="box-body">
						<div class="table-responsive">
							<table class="table no-margin">
								<thead>
									<tr>
										<th>Description</th>
										<th>Amount</th>
										<th>Status</th>
									</tr>
								</thead>
								<tbody>
									@foreach ($allocate as $allocates)
									<tr>
										<td>{{ $allocates->description }}</td>
										@if ($allocate->count() == $claiming->count())
										<td>{{ $allocates->amount }}</td>
										<td>Not Claimed</td>
										@elseif ($claiming->isEmpty())
										<td>{{ $allocates->amount }}</td>
										<td>Claimed</td>
										@else
										@if ($claiming->where('id',$allocates->id)->count() < 1)
										<td>{{ $allocates->amount }}</td>
										<td>Claimed</td>
										@else
										<td>{{ $allocates->amount }}</td>
										<td>Not Claimed</td>
										@endif
										@endif
									</tr>
									@endforeach
								</tbody>
							</table>
						</div>
					</div>
				</div>
			</div>
		</div>
	</section>
</div>
@endsection