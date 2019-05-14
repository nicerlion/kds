@extends('layouts.app')

@section('content')
<div class="container">
  <div class="row no-flex">
    <div class="col-12 section-header">
      <h1 class="float-left">Sarlaft Vencidos</h1>
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
        @if( $resuser )
          <div class="table-responsive">
            <table class="table item-detail">
                <tbody>
                    <tr>
											<th><span>Identificación</span></th>
											<th><span>Nombre</span></th>
                      <td><span>Tipo</span></th>
                      <td><span>Ramo</span></th>
                      <td><span>Status</span></th>
                      <td><span>Vencimiento</span></th>
                    </tr>
                    @foreach($resuser as $a)
												<tr>
														<td>
                              {{$a[0]}}
														</td>
                            <td>
                              {{$a[1]}}
														</td>
                            <td>
                              {{$a[2]}}
														</td>
                            <td>
                              {{$a[3]}}
														</td>
                            <td>
                              {{$a[4]}}
														</td>
                            <td>
                              {{$a[5]}}
														</td>
												</tr>
										@endforeach
                </tbody>
            </table>
          </div>
          <div class="col-sm-12 col-md-6 form-group pl0">
            <a href="{{ action('IuserController@expirationreport') }}" class="btn btn-success">Descargar Reporte</a>
          </div>
        @else
          <div class="not-found">No se encontraron registros</div>
        @endif
      </div>     
    </div>
  </div>
</div>
@endsection
