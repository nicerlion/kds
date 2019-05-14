@extends('layouts.app')

@section('content')
<div class="container">
  <div class="row section-header">
      <h1>Borrar Ramo</h1>
  </div>
  @if (session('status'))
      <div class="alert alert-success" role="alert">
          {{ session('status') }}
      </div>
  @endif
  <div class="row justify-content-center form-container">
    <div class="col-12">
        <div class="data-container">
            {!! Form::model($branch, ['method'=>'DELETE', 'action'=>['AdminController@destroybranch', $branch->id]]) !!}
                {{ csrf_field() }}
                <div class="form-group row">
                    {!! Form::label('titleid', 'Ramo:', ['class'=>'col-sm-2 col-form-label']) !!}
                    <div class="col-sm-10">
                        {!! Form::text('name', null, ['class'=>'form-control', 'disabled'=>'disabled']) !!}
                    </div>
                </div>
                <div class="form-group row">
                    <div class="offset-sm-2 col-sm-10">
                        {!! Form::submit('Submit',['class'=>'btn btn-primary', 'onclick'=>'return confirm("Está seguro?")']) !!}
                    </div>
                </div>
            {!! Form::close() !!}
        </div>
    </div>
  </div>
</div>
@endsection
