<!DOCTYPE html>
<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
	<title>Official Receipt</title>
	<style type="text/css">
	.text-right {
		text-align: right;
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
		<b>{{ $receipt->date_claimed->format('F d, Y') }}<br>
		</b>
		<p>OR# {{ sprintf("%010d", $receipt->id) }}</p><br>
		<p>{{ $application->strUserName }} from {{ $application->schools_abbreviation }} taking {{ $application->courses_abbreviation }} has claimed the ff:</p>
		<table width="100%">
			<thead>
				<tr>
					<th>Description</th>
					<th class="text-right">Amount</th>
				</tr>
			</thead>
			@foreach ($detail as $details)
			<tbody>
				<tr>
					<td>{{$details->description}}</td>
					<td class="text-right">{{$details->amount}}</td>
				</tr>
			</tbody>
			@endforeach
		</table>
		<hr>
		<p class="text-right">Total: {{ $detail->sum('amount') }}</p>
		<br>
		<br>
		Legislative Staff Officer<br>
		COUNCILOR {{ $councilor->first_name }} {{ $councilor->middle_name }} {{ $councilor->last_name }}<br>
		City Councilor Quezon City<br>
		{{ $councilor->description }}
	</body>
	</html>