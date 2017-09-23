@extends('SMS.Admin.AdminMain')
@section('content')
<div class="content-wrapper">
	<section class="content-header">
		<h1>
			Utilities
		</h1>
		<ol class="breadcrumb">
			<li><a href="{{ url('admin/dashboard') }}"><i class="fa fa-dashboard"></i> Dashboard</a></li>
			<li class="active"><i class="fa fa-fw fa-gear"></i> Utilities</li>
		</ol>
	</section>
	<section class="content">
		<div class="nav-tabs-custom">
			<ul class="nav nav-tabs">
				<li class="active"><a href="#tab_1" data-toggle="tab">Settings</a></li>
				<li><a href="#tab_2" data-toggle="tab">Backup</a></li>
			</ul>
			<div class="tab-content">
				<div class="tab-pane active row" id="tab_1">
					<div class="col-xs-12">
						{{ Form::open([
							'id' => 'frm', 'data-parsley-validate' => '',
							'route' => 'adminutilities.store'
						])
					}}
					<div class="form-group col-xs-6">
						<label for="title" class="control-label">Title:</label>
						<input type="text" name="title" value="{{ $setting->title }}" class="form-control">
					</div>
					<div class="form-group col-xs-6">
						{{ Form::label('image', 'Logo:') }}<br>
						<div class="btn btn-default btn-file">
							<i class="fa fa-image"></i> Choose Image..
							<input type="file" id="image" name="image">
						</div>
					</div>
					<div class="form-group col-xs-6">
						<label for="year_count" class="control-label">No. year level:</label>
						{{ Form::selectRange('year', 1, $setting->year_count, $setting->year_count, [
							'id' => 'year',
							'class' => 'form-control',
						])
					}}
				</div>
				<div class="form-group col-xs-6">
					<label for="semester_count" class="control-label">No. semesters:</label>
					{{ Form::selectRange('semester', 1, $setting->semester_count, $setting->semester_count, [
						'id' => 'semester',
						'class' => 'form-control',
					])
				}}
			</div>
			<div class="form-group">
				{{ Form::button("<i class='fa fa-save'></i> Save", [
					'id' => 'btn-save',
					'class' => 'btn btn-success pull-right',
					'type' => ''
				]) 
			}}
		</div>
		{{ Form::close() }}
	</div>
</div>
<div class="tab-pane row" id="tab_2">
</div>
</div>
</div>
</section>
</div>
@endsection