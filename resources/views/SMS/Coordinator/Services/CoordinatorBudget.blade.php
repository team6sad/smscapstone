@extends('SMS.Coordinator.CoordinatorMain')
@section('content')
<div class="content-wrapper">
  <section class="content-header">
    <h1>
      Budget
    </h1>
    <ol class="breadcrumb">
      <li><a href="{{ url('coordinator/dashboard') }}"><i class="fa fa-dashboard"></i> Coordinator</a></li>
      <li class="active"><i class="fa fa-money"></i> Budget</li>
    </ol>
  </section>
  <section class="content">
    <div class="row">
      <div class="container col-sm-12">
        <div class="box box-danger">
          <div class="modal fade" id="add_budget">
            <div class="modal-dialog">
              <div class="modal-content">
                <div class="modal-header">
                  {{ Form::button('&times;', [
                    'class' => 'close',
                    'type' => '',
                    'data-dismiss' => 'modal'
                  ]) 
                }}
                <h4 id="h4">Add Budget</h4>
              </div>
              <div class="modal-body">
                {{ Form::open([
                  'id' => 'frmBudget',
                  'data-parsley-whitespace' => 'squish'])
                }}
                <div class="form-group">
                  {{ Form::label('name', 'Budget Amount') }}
                  {{ Form::text('budget_amount', null, [
                    'id' => 'budget_amount',
                    'class' => 'form-control peso',
                    'maxlength' => '15',
                    'required' => 'required',
                    'data-parsley-pattern' => '^[0-9.]+$',
                    'autocomplete' => 'off'
                  ]) 
                }}
              </div>
              @foreach ($budgtype as $type)
              <div class="form-group">
                {{ Form::label('name', $type->description.' Amount') }}
                {{ Form::hidden('id[]', $type->id) }}
                {{ Form::text('amount[]', null, [
                  'id' => 'id'.$type->id,
                  'class' => 'form-control peso',
                  'maxlength' => '15',
                  'required' => 'required',
                  'data-parsley-pattern' => '^[0-9.]+$',
                  'autocomplete' => 'off'
                ]) 
              }}
            </div>
            @endforeach
            <div class="form-group">
              {{ Form::label('name', 'Scholar Budget') }}
              {{ Form::text('budget_per_student', null, [
                'id' => 'budget_per_student',
                'class' => 'form-control peso',
                'maxlength' => '15',
                'required' => 'required',
                'data-parsley-pattern' => '^[0-9.]+$',
                'autocomplete' => 'off',
                'readonly' => 'readonly'
              ]) 
            }}
          </div>
          <div class="form-group allocate">
            {{ Form::label('name', 'Slot') }}
            {{ Form::text('slot_count', null, [
              'id' => 'slot_count',
              'class' => 'form-control',
              'maxlength' => '15',
              'readonly' => 'readonly',
              'data-parsley-pattern' => '^[0-9]+$',
              'autocomplete' => 'off'
            ]) 
          }}
        </div>
        <div class="form-group">
          {{ Form::button('Submit', [
            'id' => 'btn-save',
            'class' => 'btn btn-success btn-block',
            'value' => 'add',
            'type' => ''
          ]) 
        }}
      </div>
      {{ Form::close() }}
    </div>
  </div>
</div>
</div>
<div class="box-body table-responsive">
  @if ($utility->phase_status)
  <div class="callout callout-success">
    <h4><i class="icon fa fa-info"></i> Semester Status</h4>
    <h5>Ongoing</h5>
    <button type="button" value="{{ $utility->phase_status }}" class="btn btn-danger btn-status"><i class='fa fa-remove'></i> End</button>
  </div>
  @else
  <div class="callout callout-danger">
    <h4><i class="icon fa fa-info"></i> Semester Status</h4>
    <h5>Closed</h5>
    <button type="button" value="{{ $utility->phase_status }}" class="btn btn-success btn-status"><i class='fa fa-refresh'></i> Start</button>
  </div>
  @endif
  <table id="budget-table" class="table table-bordered table-striped table-hover" cellspacing="0" width="100%">
    <thead>
      <th>Amount</th>
      <th>Scholar Budget</th>
      <th>Slots</th>
      <th>Date</th>
      <th>Action</th>
    </thead>
    <tbody id="budget-list">
    </tbody>
  </table>
</div>
</div>
</div>
</div>
</section>
</div>
@endsection
@section('script')
{!! Html::script("plugins/maskMoney/jquery.maskMoney.min.js") !!} 
{!! Html::script("js/bootbox.min.js") !!} 
{!! Html::script("custom/BudgetAjax.min.js") !!}
<script type="text/javascript">
  var dataurl = "{!! route('budget.data') !!}";
</script>
@endsection
