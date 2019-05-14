@extends('layouts.app')

@section('content')
<div class="container">
  <div class="row section-header">
      <h1>Editar Estados de Póliza</h1>
  </div>
  @if (session('status'))
      <div class="alert alert-success" role="alert">
          {{ session('status') }}
      </div>
  @endif
  <div class="row justify-content-center form-container">
    <div class="col-12">
        <div class="data-container">
            {!! Form::model($bs, ['method'=>'PUT', 'action'=>['AdminController@updatebs', $bs->id], 'onsubmit' => 'return validationBs()']) !!}
                {{ csrf_field() }}
                <div class="form-row">
                    <div class="col form-group">
                        {!! Form::label('name', 'Nombre de Estado de Póliza', ['class'=>'col-sm-12 col-form-label']) !!}
                        <div class="col">
                            {!! Form::text('name', null, ['class'=>'form-control', 'data-id' => $bs->id]) !!}
                        </div>
                    </div>
                </div>
                <div class="form-row">
                    <div class="col form-group">
                    <div class="col">
                        {!! Form::submit('Guardar',['class'=>'btn btn-primary']) !!}
                    </div>
                    </div>
                </div>
            {!! Form::close() !!}
        </div>
        @if (count($errors) > 0)
            <div class="alert alert-danger">
                <strong>Whoops!</strong> Hay campos vacios o con información equivocada. Por favor llénelos correctamente.<br><br>
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
    </div>
  </div>
</div>
@endsection
