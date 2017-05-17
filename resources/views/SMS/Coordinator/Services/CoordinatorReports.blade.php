@extends('SMS.Coordinator.CoordinatorMain')
@section('content')
<div class="content-wrapper">
  <section class="content-header">
    <h1>
      Reports
      {{ Form::button("<i class='fa fa-plus'></i> Generate PDF", [
        'id' => 'studentreport',
        'class' => 'btn btn-primary btn-sm',
        'value' => 'add',
        'type' => '',
        'style' => 'margin-bottom: 10px;'
        ]) 
      }}
    </h1>
    <ol class="breadcrumb">
      <li><a href="{{ url('coordinator/index') }}"><i class="fa fa-dashboard"></i> Coordinator</a></li>
      <li class="active">Reports</li>
    </ol>
  </section>
  <section class="content">
  </section>
</div>
@endsection
@section('script')
<script type="text/javascript">
  $('#studentreport').click(function() {
    var win = window.open("{{ route('reports.create') }}", '_blank');
    win.focus();
  });
</script>
@endsection
