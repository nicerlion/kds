@extends('layouts.app')

@section('content')
<div class="container">
  <div class="row section-header">
      <h1>Busqueda por placa</h1>
  </div>
  @if (session('status'))
      <div class="alert alert-success" role="alert">
          {{ session('status') }}
        </div>
        @endif
        <div class="form-container">
          <div class="data-container">
            <h6>Digite la placa del vehículo.</h6>
            {!! Form::text('search', null, ['class'=>'form-control col-sm-12', 'placeholder' => 'Placa No.', 'id' => 'search-input']) !!}  
            <p class="required-field" style="display: none;">Campo requerido</p>
            <button class="btn btn-primary plate-search" id="plate-search">Buscar</button>
            <div class="plate-none" style="display: none;"><p>La placa no existe</p></div>
            <div class="plate-detail" style="display: none;">
              <table class="table">
                <thead>
                  <tr>
                    <th scope="col">No. Póliza</th>
                    <th scope="col">Estado</th>
                    <th scope="col">Fecha</th>
                  </tr>
                </thead>
                <tbody id="item-list"></tbody>
              </table>
            </div>
      </div>
    </div>
  </div>
</div>
@endsection
