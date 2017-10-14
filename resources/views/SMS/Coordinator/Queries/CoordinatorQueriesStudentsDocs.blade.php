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
		<p>Transmitted herewith is the list of {{ $request->status }} students. </p>
		<table width="100%">
			<thead>
				<tr>
					<th>ID</th>
					<th>Name</th>
					<th>School</th>
					<th>Course</th>
				</tr>
			</thead>
			<tbody>
				@foreach ($application as $applications)
				<tr>
					<td>{{$applications->id}}</td>
					<td>{{$applications->strUserName}}</td>
					<td>{{$applications->schools_abbreviation}}</td>
					<td>{{$applications->courses_abbreviation}}</td>
				</tr>
				@endforeach
			</tbody>
		</table>
		<br>
		<br>
		Legislative Staff Officer<br>
		COUNCILOR {{ $councilor->first_name }} {{ $councilor->middle_name }} {{ $councilor->last_name }}<br>
		City Councilor Quezon City<br>
		{{ $councilor->description }}
	</body>
	</html>