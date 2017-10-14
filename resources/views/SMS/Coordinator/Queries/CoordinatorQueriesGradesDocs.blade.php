<!DOCTYPE html>
<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
	<title>Grades Query</title>
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
		<p>Transmitted herewith is the list of students who have 
			@if ($request->status == 'P')
			Passed
			@else
			Failed
		@endif subjects. </p>
		@foreach ($budget as $budgets)
		<br>
		<p>Semester in <b>{{ $budgets->budget_date->format('F d, Y') }}</b></p>
		<table width="100%">
			<thead>
				<tr>
					<th class="col">ID</th>
					<th>Name</th>
					<th class="col">School</th>
					<th class="col">Course</th>
				</tr>
			</thead>
			<?php $current = null ?>
			<?php $ctr = 0 ?>
			<tbody>
				@foreach ($grade as $grades)
				@if ($grades->budget_id == $budgets->id && $current != $grades->user_id)
				<?php $ctr++ ?>
				<tr>
					<td>{{ $grades->user_id }}</td>
					<td>{{ $grades->strUserName }}</td>
					<td>{{ $grades->schools_abbreviation }}</td>
					<td>{{ $grades->courses_abbreviation }}</td>
				</tr>
				<?php $current = $grades->user_id; ?>
				@endif
				@endforeach
			</tbody>
		</table>
		<p class="right">Number of 
			@if ($request->status == 'P')
			Passed
			@else
			Failed
			@endif Students: <b>{{ $ctr }}/{{ $userbudget->where('budget_id',$budgets->id)->count() }}</b>
		</p>
		@endforeach
		<br>
		<br>
		Legislative Staff Officer<br>
		COUNCILOR {{ $councilor->first_name }} {{ $councilor->middle_name }} {{ $councilor->last_name }}<br>
		City Councilor Quezon City<br>
		{{ $councilor->description }}
	</body>
	</html>