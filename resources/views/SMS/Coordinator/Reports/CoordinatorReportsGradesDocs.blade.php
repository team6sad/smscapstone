<!DOCTYPE html>
<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
	<title>Student Grade Report</title>
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
		width: 70px;
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
		<b>{{ $today->format('F d, Y') }}<br></b><br>
		<big><center><b>List of Grades Submitted</b></center></big><br>
		@foreach ($application as $applications)<br><br>
		Scholarship ID: <b>{{ $applications->id }}</b><br>
		Name: <b>{{ $applications->strUserName }}</b><br>
		School: <b>{{ $applications->schools_description }}</b><br>
		Course: <b>{{ $applications->courses_description }}</b><br><br>
		@foreach ($allgrade as $allgrades)
		@if ($allgrades->student_detail_user_id == $applications->user_id)
		@if ($allgrades->getOriginal('year') != 1 && $allgrades->getOriginal('semester') != '')
		<b>{{ $allgrades->year }} Year {{ $allgrades->semester }} Semester</b>
		<table width="100%">
			<thead>
				<tr>
					<th>Subject</th>
					<th class="col">Units</th>
					<th class="col">Grade</th>
					<th class="col">Status</th>
				</tr>
			</thead>
			<tbody>
				@foreach ($grade as $grades)
				@if ($allgrades->id == $grades->grade_id)
				<tr>
					<td>{{$grades->description}}</td>
					<td>{{$grades->units}}</td>
					<td>{{$grades->grade}}</td>
					@foreach ($grading as $gradings)
					@if ($allgrades->grading_id == $gradings->grading_id)
					@if ($grades->grade == $gradings->grade)
					@if ($gradings->status == 'P')
					<td>Passed</td>
					@elseif ($gradings->status == 'D')
					<td>Drop</td>
					@elseif ($gradings->status == 'W')
					<td>Withdraw</td>
					@else
					<td>Failed</td>
					@endif
					@endif
					@endif
					@endforeach
				</tr>
				@endif
				@endforeach
			</tbody>
		</table>
		@endif
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