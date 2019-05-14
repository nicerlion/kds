@extends('layouts.app')

@section('content')
<div class="container">
  <div class="row section-header">
      <h1>Crear Tipo de usuario</h1>
  </div>
  @if (session('status'))
      <div class="alert alert-success" role="alert">
          {{ session('status') }}
      </div>
  @endif
  <div class="row justify-content-center form-container">
    <div class="col-12">
        <div class="data-container">
            {!! Form::open(['method'=>'POST', 'action'=>'AdminController@storeiusertype']) !!}
            {{ csrf_field() }}
            <div class="form-row">
                <div class="col form-group">
                    {!! Form::label('titleid', 'Tipo de Usuario', ['class'=>'col-sm-12 col-form-label']) !!}
                    <div class="col">
                        {!! Form::text('name', null, ['class'=>'form-control']) !!}
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
        @if(count($errors) > 0)
            <ul class="offset-sm-2 col-sm-10">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        @endif
    </div>
  </div>
</div>
@endsection
