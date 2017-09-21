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
			<div class="box-body">
				<table id="table" class="table table-bordered table-striped table-hover" cellspacing="0" width="100%">
					<thead>
						<th>Student Name</th>
						<th>No of Failed</th>
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
</script>
@endsection