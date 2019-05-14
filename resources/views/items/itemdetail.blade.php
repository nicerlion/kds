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
        @if( $item )
          <div class="table-responsive">
            <table class="table item-detail">
                <tbody>
                    <tr>
											<td><span>No. Póliza:&nbsp;&nbsp;</span><span class="id-data">{{ $item->item_number }}<span></td>
											<td><span>Tipo:&nbsp;&nbsp;</span><span>{{ $item->item_type }}</span></td>
                    </tr>
                    <tr>
                      <td><span>Estado de Póliza:&nbsp;&nbsp;</span>{{ $bstatus->name }}</td>
                      <td><span>Vencimiento:&nbsp;&nbsp;</span>{{ Carbon\Carbon::parse($item->due_date)->format('d/m/Y') }}</td>
                    </tr>
                    @foreach($iusers as $iuser)
												<tr>
														<td>
															<span>
																{{$iuser->type_name}}:&nbsp;&nbsp;
																</span>
																{{ $iuser->name }}
															</td>
													<td>
														<span>Id:&nbsp;&nbsp;</span>
														<a href="/iusers?id={{ $iuser->document }}">{{ $iuser->document }}</a>
													</td>
												</tr>
										@endforeach
                    <tr>
                      <td><span>Compañía de Seguros:&nbsp;&nbsp;</span>{{ $insuranceco->name }}</td>
											<td><span>Ramo:&nbsp;&nbsp;</span>{{ $branch->name }}</td>
                    </tr>
                    @if($item->sarlaf)
                      <tr>
                        <td><span>Sarlaft:&nbsp;&nbsp;</span>Si</td>
                        <td><span>Venc. Sarlaft:&nbsp;&nbsp;</span>{{ Carbon\Carbon::parse($item->sarlaf_duedate)->format('d/m/Y') }}</td>
                      </tr>
                    @endif
                    <tr>
											@if($item->plate)
                        <td>
                          <span>Placa:&nbsp;&nbsp;</span>
                          <a href="/plate?placa={{ $item->plate }}">{{ $item->plate }}</a>
                        </td>
											@endif
                      @if($item->comments)
                        <td><span>Comentarios:&nbsp;&nbsp;</span>{{ $item->comments }}</td>
                      @endif
                    </tr>
                    <tr>
                      <td><span>Responsable:&nbsp;&nbsp;</span>{{ $responsible->name }}</td>
                    </tr>
                </tbody>
            </table>
            @if ($files)
              <h3 class="col-sm-12 col-form-label">Documentos de la póliza</h3>
              <div class="image-container">
                <ul>
                  @foreach ($files as $image)
                    <li>
                      @if ($image['type'] == 'pdf' or $image['type'] == 'PDF')
                        <a href="{{ $image['path'] }}" class="portfolio-box" target="_blank">
                          <span class="archive-item">
                            <span class="row align-items-center">
                              <span class="col-auto">
                                <i class="fas fa-file-pdf"></i>
                              </span>
                              <span class="col">
                                {{ $image['oriname'] }}
                              </span>
                            </span>
                            <span class="boder-archive"></span>
                          </span>
                        </a>
                      @else
                        <a href="{{ $image['path']}}" class="portfolio-box img-document" target="_blank" style=" background-image: url( {{$image['path']}} ); ">
                          <span class="archive-item">
                            <span class="row align-items-center">
                              <span class="col-auto">
                                <i class="fas fa-image"></i>
                              </span>
                              <span class="col">
                                {{ $image['oriname'] }}
                              </span>
                            </span>
                            <span class="boder-archive"></span>
                          </span>
                        </a>
                      @endif
                    </li>
                  @endforeach
                </ul>
              </div>
            @endif 
          </div>
          <div class="col-sm-12 col-md-6 form-group pl0">
            <a href="{{ action('ItemController@edit', ['id' => $item->id]) }}" class="btn btn-success">Editar</a>
          </div>
        @else
          <div class="not-found">No se encontraron registros</div>
        @endif
      </div>     
    </div>
  </div>
</div>
@endsection
