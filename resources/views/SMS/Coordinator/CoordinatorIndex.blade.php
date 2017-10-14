@extends('SMS.Coordinator.CoordinatorMain')
@section('content')
<div class="content-wrapper">
	<section class="content-header">
		<h1>
			Dashboard
		</h1>
	</section>
	<section class="content">		
		<div class="row">
			@if ($utility->phase_status)
			<div class="col-md-4">
				<div class="callout callout-success">
					<h4><i class="icon fa fa-info"></i> Renewal Status</h4>
					<p>Renewal Phase Ongoing</p>
				</div>
			</div>
			@else
			<div class="col-md-4">
				<div class="callout callout-danger">
					<h4><i class="icon fa fa-info"></i> Renewal Status</h4>
					<p>Renewal Phase Closed</p>
				</div>
			</div>
			@endif
			<div class="col-md-4">
				<div class="info-box bg-aqua">
					<span class="info-box-icon"><i class="fa fa-money"></i></span>
					<div class="info-box-content">
						<span class="info-box-text">Remaining Budget</span>
						<span class="info-box-number remaining-budget">0</span>
						<div class="progress">
							<div class="progress-bar remaining-budget-bar" style="width: 50%"></div>
						</div>
						<span class="progress-description remaining-budget-progress">
						</span>
					</div>
				</div>
			</div>
			<div class="col-md-4">
				<div class="info-box bg-yellow">
					<span class="info-box-icon"><i class="fa fa-stack-overflow"></i></span>
					<div class="info-box-content">
						<span class="info-box-text">Remaining Slot</span>
						<span class="info-box-number remaining-slot">0</span>
						<div class="progress">
							<div class="progress-bar remaining-slot-bar" style="width: 50%"></div>
						</div>
						<span class="progress-description remaining-slot-progress">
						</span>
					</div>
				</div>
			</div>
			<div class="col-md-4 col-xs-6">
				<div class="small-box bg-yellow">
					<div class="inner">
						<h3>{{ $applicants }}</h3>
						<p>Applicants</p>
					</div>
					<div class="icon">
						<i class="fa fa-users"></i>
					</div>
					<a href="{{ route('applicants.index') }}" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
				</div>
			</div>
			<div class="col-md-4 col-xs-6">
				<div class="small-box bg-green">
					<div class="inner">
						<h3>{{ $renewal }}</h3>
						<p>Renewal</p>
					</div>
					<div class="icon">
						<i class="fa fa-refresh"></i>
					</div>
					<a href="{{ route('coordinatorrenewal.index') }}" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
				</div>
			</div>
			<div class="col-md-4 col-xs-6">
				<div class="small-box bg-red">
					<div class="inner">
						<h3>{{ $scholar }}</h3>
						<p>Current Scholar</p>
					</div>
					<div class="icon">
						<i class="fa fa-graduation-cap"></i>
					</div>
					<a href="{{ route('scholars.index') }}" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
				</div>
			</div>
		</div>
	</section>
</div>
@endsection
@section('script')
<script type="text/javascript">
	$('.remaining-budget').text($('.budget').text());
	$('.remaining-slot').text($('.slot').text());
	var budget = ($('.remaining-budget').text()/{{ $latest->amount }})*100 + '%';
	var slot = ($('.remaining-slot').text()/{{ $latest->slot_count }})*100 + '%';
	$('.remaining-budget-bar').css('width', budget);
	$('.remaining-slot-bar').css('width', slot);
	$('.remaining-budget-progress').text(budget);
	$('.remaining-slot-progress').text(slot);
</script>
@endsection