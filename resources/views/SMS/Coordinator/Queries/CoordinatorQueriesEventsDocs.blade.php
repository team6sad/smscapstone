<!DOCTYPE html>
<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
	<title>Events Query</title>
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
		<p class="para">Transmitted herewith is the list of events. </p>
		<table width="100%">
			<thead>
				<tr>
					<th>Name</th>
					<th>Place Held</th>
					<th>Date</th>
					<th>Time</th>
				</tr>
			</thead>
			@foreach ($event as $events)
			<tbody>
				<tr>
					<td>{{$events->title}}</td>
					<td>{{$events->place_held}}</td>
					<td>{{$events->date_held->format('M d, Y')}}</td>
					<td>{{date('h:i A',strtotime($events->time_from))}} - {{date('h:i A',strtotime($events->time_to))}}</td>
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