<!DOCTYPE html>
<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
	<title>Application Form</title>
	<style type="text/css">
	img {
		style=width: 100px; 
		height: 100px;
		border: 1px solid;
	}
	.right {
		float: right;
		clear: left;
	}
	.left {
		float: left;
		clear: right;
	}
</style>
</head>
<body>
	<center><b>REPUBLIC OF THE PHILIPPINES</b><br>
		<div class="left">
			<img src="./images/{{ $councilor->picture }}">
		</div>
		<div class="right">
			<img src="./img/{{ $setting->logo }}">
		</div>
		<small>QUEZON CITY<br>
			<i>Office of the City Mayor</i><br>
		</small>
		<b>Scholarship and Youth Development Program</b><br><br>
		<big>
			<u>Councilor {{ $councilor->first_name }} {{ $councilor->middle_name }} {{ $councilor->last_name }}</u><br>
		</big></center><br><br>
		<center><big>Application Form</big></center><br>
		<div class="right">
			<img src="./images/{{ $application->picture }}">
		</div>
		<p>Control No. {{ $application->id }}</p>
		<br>
		<p>A. PERSONAL DATA</p>
		<p><strong>Name:</strong> {{$application->last_name}}, {{$application->first_name}} {{$application->middle_name}}</p>
		<p><strong>Address:</strong> {{$application->house_no}} {{$application->street}} {{$application->barangay_description}} {{$application->districts_description}}</p>
		<p><strong>Age:</strong> {{$application->birthday->diffInYears()}}</p>
		<p><strong>Date of Birth:</strong> {{$application->birthday->format('M d, Y')}}</p>
		<p><strong>Place of Birth:</strong> {{$application->birthplace}}</p>
		<p><strong>Religion:</strong> {{$application->religion}}</p>
		@if ($application->gender == 0)
		<p><strong>Sex:</strong> Male <br></p>
		@else
		<p><strong>Sex:</strong> Female <br></p>
		@endif
		<p><strong>E-mail Address:</strong> {{$application->email}}</p>
		<p><strong>Contact No:</strong> {{$application->cell_no}}</p>
		<p>B. FAMILY DATA</p>
		<table width="100%">
			<thead>
				<tr>
					<th>Father's Name: {{$father->first_name}} {{$father->last_name}}</th>
					<th>Mother's Name: {{$mother->first_name}} {{$mother->last_name}}</th>
				</tr>
			</thead>
			<tbody>
				<tr>
					<td>Citizenship: {{$father->citizenship}}</td>
					<td>Citizenship: {{$mother->citizenship}}</td>
				</tr>
				<tr>
					<td>Highest Educ. Attainment: {{$father->highest_ed}}</td>
					<td>Highest Educ. Attainment: {{$mother->highest_ed}}</td>
				</tr>
				<tr>
					<td>Occupation: {{$father->occupation}}</td>
					<td>Occupation: {{$mother->occupation}}</td>
				</tr>
				<tr>
					<td>Mothly Income: {{$father->monthly_income}}</td>
					<td>Mothly Income: {{$mother->monthly_income}}</td>
				</tr>
			</tbody>
		</table>
		<p>No. of brother/s {{$application->brothers}} sister/s {{$application->sisters}}</p>
		<p>Sibling/s who is currently or formerly a beneficiary of SYDP:</p>
		@if ($exist != 0)
		<p>
			Name: {{ $siblings->first_name }} {{ $siblings->last_name }}
			From: {{ $siblings->date_from }} To: {{ $siblings->date_to }}
		</p>
		@else
		<p>
			Name: --------
			From: -------- To: --------
		</p>
		@endif
		<p>C. EDUCATIONAL BACKGROUND</p>
		<table width="100%">
			<thead>
				<tr>
					<th>Level</th>
					<th>School</th>
					<th>Date Enrolled</th>
					<th>Date Graduated</th>
					<th>Award/Honors received</th>
				</tr>
			</thead>
			<tbody>
				<tr>
					<td>Elementary</td>
					<td>{{$elem->school_name}}</td>
					<td>{{$elem->date_enrolled}}</td>
					<td>{{$elem->date_graduated}}</td>
					@if ($elem->awards != '')
					<td>{{$elem->awards}}</td>
					@else
					<td>N/A</td>
					@endif
				</tr>
				<tr>
					<td>Highschool</td>
					<td>{{$hs->school_name}}</td>
					<td>{{$hs->date_enrolled}}</td>
					<td>{{$hs->date_graduated}}</td>
					@if ($hs->awards != '')
					<td>{{$hs->awards}}</td>
					@else
					<td>N/A</td>
					@endif
				</tr>
			</tbody>
		</table>
		<P>D. COMMUNITY INVOLVEMENT/AFFILIATION</P><table width="100%">
			<thead>
				<tr>
					<th>Organization</th>
					<th>Position</th>
					<th>Date of Participation</th>
				</tr>
			</thead>
			<tbody>
				@if ($count!=0)
				@foreach ($affiliation as $affiliations)
				<tr>
					<td>{{$affiliations->organization}}</td>
					<td>{{$affiliations->position}}</td>
					<td>{{$affiliations->participation_date}}</td>
				</tr>
				@endforeach
				@else
				<tr>
					<td>N/A</td>
					<td>N/A</td>
					<td>N/A</td>
				</tr>
				@endif
			</tbody>
		</table>
		<p><b>Maikling Talambuhay:</b></p>
		{{ $application->essay }}
		<br>
		<br>
		Legislative Staff Officer<br>
		COUNCILOR {{ $councilor->first_name }} {{ $councilor->middle_name }} {{ $councilor->last_name }}<br>
		City Councilor Quezon City<br>
		{{ $councilor->description }}
	</body>
	</html>