<!DOCTYPE html>
<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
	<title>Budgets Report</title>
	<style type="text/css">
	table {
		border-collapse: collapse;
	}
	table, th, td {
		border: 1px solid black;
		padding: 5px;
	}
	tr:nth-child(even) {background-color: #f2f2f2}
	th {
		background-color: #DD4B39;
		color: white;
	}
	tfoot {
		background-color: #FAFFBD;
		color: black;
	}
	img {
		width: 100px; 
		height: 100px;
		border: 1px solid;
	}
	.img-right {
		float: right;
		clear: left;
	}
	.img-left {
		float: left;
		clear: right;
	}
	.col {
		width: 150px;
	}
	.right {
		text-align: right;
	}
	.id {
		width: 120px;
	}
	.number {
		width: 150px;
	}
</style>
</head>
<body>
	<div class="img-left">
		<img src="./img/icon.png">
	</div>
	<div class="img-right">
		<img src="./img/logo.png">
	</div>
	<center><b>REPUBLIC OF THE PHILIPPINES</b><br>
		<small>QUEZON CITY<br>
			City Councilor<br>
		</small>
		<big>
			<i>Office of Councilor {{ $councilor->first_name }} {{ $councilor->middle_name }} {{ $councilor->last_name }}</i><br>
		</big></center><br><br>
		<hr>
		<hr><br>
		<b>{{ $today->format('F d, Y') }}<br>
		</b><br>
		<center><strong>BUDGET REPORT</strong></center>
		@foreach ($budget as $budgets)
		<br>
		<p>The budget allocated for Semester in <b>{{ $budgets->budget_date->format('F d, Y') }}</b> is <b>Php {{ $budgets->amount }}</b></p>
		<p>The breakdown of budget is stated below:</p>
		<table width="100%">
			<thead>
				<tr>
					<th class="col id">ID</th>
					<th>Name</th>
					<?php $type = collect([]); ?>
					@foreach ($allocation as $allocations)
					@if ($allocations->budget_id == $budgets->id)
					<?php $type->push($allocations->id);?>
					<th class="number">{{ $allocations->description }}</th>
					@endif
					@endforeach
					<th class="number">Total</th>
				</tr>
			</thead>
			<tbody>
				@foreach ($user as $users)
				@if ($users->budget_id == $budgets->id)
				<tr>
					<td>{{ $users->user_id }}</td>
					<td>{{ $users->strUserName }}</td>
					@foreach ($type as $types)
					<td class="right">{{ $allocate->where('user_id',$users->user_id)->where('allocation_id',$types)->sum('amount') }}</td>
					@endforeach
					<td class="right">{{ $allocate->where('user_id',$users->user_id)->where('budget_id',$budgets->id)->sum('amount') }}</td>
				</tr>
				@endif
				@endforeach
			</tbody>
			<tfoot>
				<tr>
					<td></td>
					<td>{{ $users->where('budget_id',$budgets->id)->count() }} student/s</td>
					@foreach ($allocation as $allocations)
					@if ($allocations->budget_id == $budgets->id)
					<td class="right">{{ $allocate->where('allocation_id',$allocations->id)->sum('amount') }}</td>
					@endif
					@endforeach
					<td class="right">{{ $allocate->where('budget_id',$budgets->id)->sum('amount') }}</td>
				</tr>
			</tfoot>
		</table>
		<p class="right">Remaining Budget: <b><?php $result = $budgets->amount - $allocate->where('budget_id',$budgets->id)->sum('amount'); ?>{{ $result }}</b></p>
		@endforeach
		<br>
		<br>
		Legislative Staff Officer<br>
		COUNCILOR {{ $councilor->first_name }} {{ $councilor->middle_name }} {{ $councilor->last_name }}<br>
		City Councilor Quezon City<br>
		{{ $councilor->description }}
	</body>
	</html>