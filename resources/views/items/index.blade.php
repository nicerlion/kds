@extends('layouts.app')

@section('content')
<div class="container">
  <div class="row no-flex">
    <div class="col-12 section-header">
      <h1 class="float-left">Pólizas</h1>
    </div>
  </div>
  @if (session('status'))
      <div class="alert alert-success" role="alert">
          {{ session('status') }}
      </div>
  @endif
  <div class="row justify-content-center form-container">
    <div class="col-12">
      <div class="data-container">
        <h6>Seleccione o busque una póliza.</h6>
        {!! Form::text('search', null, ['class'=>'form-control col-sm-12', 'placeholder' => 'Buscar', 'id' => 'search']) !!}
        @if( $items )
          <div class="table-responsive">
            <table class="table">
                <thead>
                  <tr>
                      <th scope="col">No. Póliza</th>
                      <th scope="col">Fecha</th>
                  </tr>
                </thead>
                <tbody id="tb"></tbody>
                <tbody id="default-item-list">
                    @foreach($items as $item)
                    <tr>
                      <td>
                          <a href="{{ action('ItemController@show', ['id' => $item->id]) }}">{{ $item->item_number }}</a>
                      </td>
                      <td>
                          {{ $item->created_at }}
                      </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            <button class="btn btn-primary more-search" style="display: none;">Ver más</button>
          </div>
        @endif
      </div>  
    </div>
  </div>
</div>
@endsection
