@extends('layouts.app')

@section('content')
<div class="container">
  <div class="row section-header">
      <h1>Busqueda por Cliente</h1>
  </div>
  @if (session('status'))
      <div class="alert alert-success" role="alert">
          {{ session('status') }}
        </div>
        @endif
        <div class="form-container">
          <div class="data-container">
            <h6>Digite el número de identificación.</h6>
            {!! Form::text('search', null, ['class'=>'form-control col-sm-12', 'placeholder' => 'Buscar', 'id' => 'search-input']) !!}  
            <p class="required-field" style="display: none;">Campo requerido</p>
            <button class="btn btn-primary iuser-search" id="iuser-search">Buscar</button>
            <div class="user-none" style="display: none;"><p>El usuario no existe</p></div>
            <div class="user-detail" style="display: none;">
              <div class="row">
                <div class="content-user-data col-12 col-sm-6">
                  <div class="row">
                    <div class="col-12">
                      <h6>Datos del usuario:</h6>
                    </div>
                    <div class="data-item col-12"><p><span class="title">Nombre:</span> <span id="name"></span></p></div>
                    <div class="data-item col-12"><p><span class="title">Identificación:</span> <span id="identification"></span></p></div>
                  </div>
                </div>
                <div class="col-12 col-sm-6 content-sarlaft-data">
                  <div class="row">
                    <div class="col-12">
                      <h6>Sarlaft:</h6>
                    </div>
                    <div class="col-12"><p><span class="title">Vence:</span> <span id="expires"></span></p></div>
                    <div class="col-12"><p><span class="title">Anexo:</span></p>
                      <div id="annexed"></div>
                    </div>
                  </div>
                </div>
                <div class="col-12 col-sm-6">
                    <a href="#" id="btn-edit" class="btn btn-success">Editar</a>
                </div>
              </div>
              <table class="table">
                <thead>
                  <tr>
                    <th scope="col">No. Póliza</th>
                    <th scope="col">Estado</th>
                    <th scope="col">Tipo de usuario</th>
                    <th scope="col">Fecha</th>
                  </tr>
                </thead>
                <tbody id="item-list"></tbody>
              </table>
            </div>
        {{-- <ul class="list-btn-border">
          @foreach($iusers as $iuser)
            <li>
                {{ $iuser->name }}, {{ $iuser->document }}<br/>
                <a href="{{ action('IuserController@edit', ['id' => $iuser->id]) }}">Edit</a>
                <a href="{{ action('IuserController@deleteform', ['id' => $iuser->id]) }}">Delete</a>
            </li>
            
          @endforeach
        </ul> --}}
      </div>
    </div>
  </div>
</div>
@endsection
