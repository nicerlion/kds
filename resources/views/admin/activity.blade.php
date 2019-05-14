@extends('layouts.app')

@section('content')
<div class="container">
  <div class="row no-flex">
    <div class="col-12 section-header">
      <h1 class="float-left">Detalles de Póliza</h1>
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
        @if( $records )
          <div class="table-responsive">
            <table class="table item-detail">
                <tbody>
                    <tr>
											<th><span>Usuario</span></th>
											<th><span>Comentario</span></th>
                      <td><span>Modelo</span></th>
                      <td><span>Modelo Id</span></th>
                      <td><span>Creación</span></th>
                    </tr>
                    @foreach($records as $a)
												<tr>
														<td>
                              {{$a->name}}
														</td>
                            <td>
                              {{$a->comments}}
														</td>
                            <td>
                              {{$a->model}}
														</td>
                            <td>
                              {{$a->model_id}}
														</td>
                            <td>
                              {{$a->created_at}}
														</td>
												</tr>
										@endforeach
                </tbody>
            </table>
          </div>
          <div class="col-sm-12 col-md-6 form-group pl0">
            <a href="{{ action('RecordController@activityreport') }}" class="btn btn-success">Descargar Todos</a>
          </div>
        @else
          <div class="not-found">No se encontraron registros</div>
        @endif
      </div>     
    </div>
  </div>
</div>
@endsection
