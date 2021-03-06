@extends('SMS.Admin.AdminMain')
@section('content')
<div class="content-wrapper">
	<section class="content-header">
		<h1>
			Barangay
		</h1>
		<ol class="breadcrumb">
			<li><a href="{{ url('admin/dashboard') }}"><i class="fa fa-dashboard"></i> Dashboard</a></li>
			<li><i class="fa fa-sitemap"></i> Municipality</li>
			<li class="active"><i class="fa fa-fw fa-map-o"></i> Barangay</li>
		</ol>
	</section>
	<section class="content">
		<div class="row">
			<div class="container col-sm-12">
				<div class="box box-danger">
					<div class="modal fade" id="add_barangay">
						<div class="modal-dialog">
							<div class="modal-content">
								<div class="modal-header">
									{{ Form::button('&times;', [
										'class' => 'close',
										'type' => '',
										'data-dismiss' => 'modal'
										]) 
									}}
									<h4>Add Barangay</h4>
								</div>
								<div class="modal-body">
									{{ Form::open([
										'id' => 'frmBarangay', 'data-parsley-whitespace' => 'squish'
										])
									}}
									<div class="form-group">
										{{ Form::label('name', 'Select District') }}
										{{ Form::select('intDistID', $district, null, [
											'id' => 'intDistID',
											'class' => 'form-control'])
										}}
									</div>
									<div class="form-group">
										{{ Form::label('name', 'Barangay Name') }}
										{{ Form::text('strBaraDesc', null, [
											'id' => 'strBaraDesc',
											'class' => 'form-control',
											'maxlength' => '25',
											'required' => 'required',
											'data-parsley-pattern' => '^[a-zA-Z0-9.ñ -]+$',
											'autocomplete' => 'off'
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
						{{ Form::button("<i class='fa fa-plus'></i> Add Barangay", [
							'id' => 'btn-add',
							'class' => 'btn btn-primary btn-sm',
							'value' => 'add',
							'type' => '',
							'style' => 'margin-bottom: 10px;'
							]) 
						}}
						<table id="barangay-table" class="table table-bordered table-striped table-hover" cellspacing="0" width="100%">
							<thead>
								<th>District Name</th>
								<th>Barangay Name</th>
								<th>Status</th>
								<th>Action</th>
							</thead>
							<tbody id="barangay-list">
							</tbody>
						</table>
					</div>
				</div>
			</div>
		</section>
	</div>
	@endsection
	@section('script')
	{!! Html::script("custom/BarangayAjax.min.js") !!}
	<script type="text/javascript">
		var dataurl = "{!! route('barangay.data') !!}";
	</script>
	@endsection