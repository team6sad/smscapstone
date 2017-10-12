@extends('SMS.Coordinator.CoordinatorMain')
@section('content')
<div class="content-wrapper">
	<section class="content-header">
		<h1>
			Renewal
		</h1>
		<ol class="breadcrumb">
			<li><a href={{ url('coordinatr/dashboard') }}> <i class="fa fa-dashboard"></i> Dashboard</a></li>
			<li class="active"><i class="fa fa-refresh"></i> Renewal</a></li>
		</ol>
	</section>
	<section class="content">
		<div class="box box-danger">
			<div class="modal fade" id="message">
				<div class="modal-dialog">
					<div class="modal-content">
						<div class="modal-header">
							{{ Form::button('&times;', [
								'class' => 'close',
								'type' => '',
								'data-dismiss' => 'modal'
							]) 
						}}
						<h4>Message</h4>
					</div>
					<div class="modal-body">
						{{ Form::open([
							'id' => 'frm',
							'data-parsley-whitespace' => 'squish'])
						}}
						<div class="form-group">
							{{ Form::label('name', 'Subject') }}
							<input class="form-control" id="title" type="text" name="title" placeholder="Subject:" required="required">
						</div>
						<div class="form-group">
							{{ Form::label('name', 'Message') }}
							<textarea id="description" name="description" class="form-control" style="resize: none; height: 250px" required="required"></textarea>
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
					'id' => 'frm', 'data-parsley-whitespace' => 'squish',
					'route' => 'coordinatorrenewal.postCriteria'
				])
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
					@if ($utility->renewal_auto_accept)
					<label><input type="checkbox" name="renewal_auto_accept" checked="checked">Automatically accept scholars with only "Passed" grades</label>
					@else
					<label><input type="checkbox" name="renewal_auto_accept">Automatically accept scholars with only "Passed" grades</label>
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
			<th>Student Name</th>
			<th>No of Withdraw</th>
			<th>No of Drop</th>
			<th>Action</th>
		</thead>
		<tbody id="list">
		</tbody>
	</table>
</div>
</div>
</section>
</div>
@endsection
@section('script')
{!! Html::script("custom/CoordinatorRenewalAjax.min.js") !!}
<script type="text/javascript">
	var dataurl = "{!! route('coordinatorrenewal.data') !!}";
	@if (Session::has('success'))
	swal({
		title: "Success!",
		text: "<center>{{Session::get('success')}}</center>",
		type: "success",
		showConfirmButton: true,
		confirmButtonClass: "btn-success",
		html: true
	});
	@endif
</script>
@endsection