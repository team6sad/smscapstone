@extends('SMS.Student.StudentMain')
@section('content')
<div class="content-wrapper">
	<section class="content-header">
		<h1>
		</h1>
	</section>
	<section class="content">
		<div class="row">
			Account deactivated because you are either forfeited or graduated. Contact Coordinator for further instruction. <a href="{{ route('studentmessage.index') }}">go to message</a>
		</div>
	</section>
</div>
@endsection