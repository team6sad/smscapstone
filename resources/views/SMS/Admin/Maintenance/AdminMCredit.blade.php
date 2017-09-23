@extends('SMS.Admin.AdminMain')
@section('content')
<div class="content-wrapper">
	<section class="content-header">
		<h1>
			Credit
		</h1>
		<ol class="breadcrumb">
			<li><a href="{{ url('admin/dashboard') }}"><i class="fa fa-dashboard"></i> Dashboard</a></li>
			<li><i class="fa fa-pencil-square-o"></i> Education</li>
			<li class="active"><i class="fa fa-fw fa-hourglass-o"></i> Credit</li>
		</ol>
	</section>
	<section class="content">
		<div class="row">
			<div class="container col-sm-12">
				<div class="box box-danger">
					<div class="modal fade" id="add_course">
						<div class="modal-dialog">
							<div class="modal-content">
								<div class="modal-header">
									{{ Form::button('&times;', [
										'class' => 'close',
										'type' => '',
										'data-dismiss' => 'modal' 
									]) 
								}}
								<h4>Add Credit</h4>
							</div>
							<div class="modal-body">
								{{ Form::open([
									'id' => 'frm', 'data-parsley-whitespace' => 'squish'
								])
							}}
							<div class="form-group">
								{{ Form::label('name', 'Select School') }}
								{{ Form::select('school_id', $school, null, [
									'id' => 'school_id',
									'class' => 'form-control'])
								}}
							</div>
							<div class="form-group">
								{{ Form::label('name', 'Select Course') }}
								{{ Form::select('course_id', $course, null, [
									'id' => 'course_id',
									'class' => 'form-control'])
								}}
							</div>
							<div class="form-group">
								{{ Form::label('name', "Year", [
									'class' => 'control-label'
								]) 
							}}
							{{ Form::selectRange('year', 1, $setting->year_count, $setting->year_count, [
								'id' => 'year',
								'class' => 'form-control',
							])
						}}
					</div>
					<div class="form-group">
						{{ Form::label('name', "Semester", [
							'class' => 'control-label'
						]) 
					}}
					{{ Form::selectRange('semester', 1, $setting->semester_count, $setting->semester_count, [
						'id' => 'semester',
						'class' => 'form-control',
					])
				}}
			</div>
			<div class="form-group">
				{{ Form::button('Submit', [
					'id' => 'btn-save',
					'class' => 'btn btn-success btn-block',
					'value' => 'add',
					'type' => ''
				]) 
			}}
		</div>
		{{ Form::close() }}
	</div>
</div>
</div>
</div>
<div class="box-body table-responsive">
	{{ Form::button("<i class='fa fa-plus'></i> Add Credit", [
		'id' => 'btn-add',
		'class' => 'btn btn-primary btn-sm',
		'value' => 'add',
		'type' => '',
		'style' => 'margin-bottom: 10px;'
	]) 
}}
<table id="table" class="table table-bordered table-striped table-hover" cellspacing="0" width="100%">
	<thead>
		<th>School</th>
		<th>Course</th>
		<th>Year</th>
		<th>Semester</th>
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
{!! Html::script("custom/CreditAjax.min.js") !!}
<script type="text/javascript">
	var dataurl = "{!! route('credit.data') !!}";
</script>
@endsection