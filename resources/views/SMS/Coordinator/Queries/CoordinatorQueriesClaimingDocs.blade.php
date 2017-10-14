<!DOCTYPE html>
<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
	<title>Claimings Query</title>
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
	img {
		height: 20px;
		width: 20px;
	}
	.col {
		width: 150px;
	}
</style>
</head>
<body>
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
		<p>Transmitted herewith is the list of scholars claiming status. </p>
		@foreach ($budget as $budgets)
		<br>
		<p>Semester in <b>{{ $budgets->budget_date->format('F d, Y') }}</b></p>
		@foreach ($allocation as $allocations)
		@if ($allocations->budget_id == $budgets->id)
		<b>{{ $allocations->description }}</b>
		<table width="100%">
			<thead>
				<tr>
					<th class="col">ID</th>
					<th>Name</th>
					<th class="col">Status</th>
				</tr>
			</thead>
			<tbody>
				<?php $current = collect([]); ?>
				@foreach ($custom as $customs)
				@if ($allocations->id == $customs['allocation_id'])
				<?php $current->push($customs['user_id']); ?>
				<tr>
					<td>{{ $customs['user_id'] }}</td>
					<td>{{ $customs['strUserName'] }}</td>
					<td>Claimed</td>
				</tr>
				@endif
				@endforeach
				@foreach ($user as $users)
				@if ($users->budget_id == $budgets->id && !$current->contains($users->user_id))
				<tr>
					<td>{{ $users->user_id }}</td>
					<td>{{ $users->strUserName }}</td>
					<td>Not Claimed</td>
				</tr>
				@endif
				@endforeach
			</tbody>
		</table>
		@endif
		@endforeach
		@endforeach
		<br>
		<br>
		Legislative Staff Officer<br>
		COUNCILOR {{ $councilor->first_name }} {{ $councilor->middle_name }} {{ $councilor->last_name }}<br>
		City Councilor Quezon City<br>
		{{ $councilor->description }}
	</body>
	</html>