@extends('SMS.Coordinator.CoordinatorMain')
@section('override')
{!! Html::style("plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.min.css") !!}
@endsection
@section('content')
<div class="content-wrapper">
	<section class="content-header">
		<h1>
			Utilities
		</h1>
		<ol class="breadcrumb">
			<li><a href="{{ url('coordinator/dashboard') }}"><i class="fa fa-dashboard"></i> Dashboard</a></li>
			<li class="active"><i class="fa fa-fw fa-gear"></i> Utilities</li>
		</ol>
	</section>
	<section class="content">
		<div class="nav-tabs-custom">
			<ul class="nav nav-tabs">
				<li class="active"><a href="#tab_1" data-toggle="tab">Misc.</a></li>
				<li><a href="#tab_2" data-toggle="tab">Backup</a></li>
			</ul>
			<div class="tab-content">
				<div class="tab-pane active" id="tab_1">
					<div class="box-body pad row">
						{{ Form::open(['route' => 'coordinatorutilities.utility'])}}
						<div class="col-md-6">
							<div class="form-group">
								<label>Essay Question</label>
								<textarea class="textarea" name="essay" placeholder="Place some text here"
								style="width: 100%; height: 400px; font-size: 14px; line-height: 18px; border: 1px solid #dddddd; padding: 10px;" required="required">{{ $utility->essay }}</textarea>
							</div>
						</div>
						<div class="col-md-6">
							<label>Criteria</label>
							<p>Parent Monthly Income Cap</p>
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
								<div class="checkbox">
									@if ($utility->renewal_auto_accept)
									<label><input type="checkbox" name="renewal_auto_accept" checked="checked">Automatically accept scholars with only "Passed" grades</label>
									@else
									<label><input type="checkbox" name="renewal_auto_accept">Automatically accept scholars with only "Passed" grades</label>
									@endif
								</div>
							</div>
						</div>
						<div class="col-md-12">
							<div class="form-group">
								<button type="submit" class="btn btn-success pull-right"><i class='fa fa-paper-plane'></i> Submit</button>
							</div>
						</div>
						{{ Form::close() }}
					</div>
				</div>
				<div class="tab-pane" id="tab_2">
				</div>
			</div>
		</div>
	</section>
</div>
@endsection
@section('script')
{!! Html::script("plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.all.min.js") !!}
<script type="text/javascript">
	$('.textarea').wysihtml5();
	$('.wysihtml5-toolbar').remove();
</script>
@endsection