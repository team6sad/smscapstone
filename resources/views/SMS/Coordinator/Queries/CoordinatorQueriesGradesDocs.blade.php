<!DOCTYPE html>
<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
	<title>Student Grade Query</title>
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
		width: 30px;
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
		<p>All grades {{ $application->strUserName }} ,taking {{ $application->courses_description }} at {{ $application->schools_description }} are listed below.</p>
		@foreach ($allgrade as $allgrades)
		{{ $allgrades->year }} Year {{ $allgrades->semester }} Semester
		<table width="100%">
			<thead>
				<tr>
					<th>Subject</th>
					<th class="col">Units</th>
					<th class="col">Grade</th>
					<th class="col">Status</th>
				</tr>
			</thead>
			@foreach ($grade as $grades)
			@if ($allgrades->id == $grades->grade_id)
			<tbody>
				<tr>
					<td>{{$grades->description}}</td>
					<td>{{$grades->units}}</td>
					<td>{{$grades->grade}}</td>
					@foreach ($grading as $gradings)
					@if ($allgrades->grading_id == $gradings->grading_id)
					@if ($grades->grade == $gradings->grade)
					@if ($gradings->is_passed)
					<td>Passed</td>
					@else
					<td>Failed</td>
					@endif
					@endif
					@endif
					@endforeach
				</tr>
			</tbody>
			@endif
			@endforeach
		</table>
		@endforeach
		<br>
		<br>
		Legislative Staff Officer<br>
		COUNCILOR {{ $councilor->first_name }} {{ $councilor->middle_name }} {{ $councilor->last_name }}<br>
		City Councilor Quezon City<br>
		{{ $councilor->description }}
	</body>
	</html>