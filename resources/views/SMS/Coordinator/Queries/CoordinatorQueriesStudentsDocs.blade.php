<!DOCTYPE html>
<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
	<title>Students Query</title>
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
	.para {
		padding-left: 5em;
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
		<p class="para">Transmitted herewith is the list of students. </p>
		<table width="100%">
			<thead>
				<tr>
					<th>ID</th>
					<th>Name</th>
					<th>School</th>
					<th>Course</th>
				</tr>
			</thead>
			@foreach ($application as $applications)
			<tbody>
				<tr>
					<td>{{$applications->id}}</td>
					<td>{{$applications->strUserName}}</td>
					<td>{{$applications->schools_description}}</td>
					<td>{{$applications->courses_description}}</td>
				</tr>
			</tbody>
			@endforeach
		</table>
		<br>
		<br>
		Legislative Staff Officer<br>
		COUNCILOR {{ $councilor->first_name }} {{ $councilor->middle_name }} {{ $councilor->last_name }}<br>
		City Councilor Quezon City<br>
		{{ $councilor->description }}
	</body>
	</html>