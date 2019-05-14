@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-12 section-header">
            
              {!! Form::text('search', null, ['class'=>'form-control col-sm-12', 'placeholder' => 'Buscar', 'id' => 'search']) !!}
            
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
                <h2 id="search-title" class="hide">Resultados</h2>
                <div class="table-responsive">
                    <table class="table table-striped table-bordered">
                        <thead id="table-header" class="hide">
                            <tr>
                                <th>No. Póliza</th>
                                <th>Fecha</th>
                            </tr>
                        </thead>
                        <tbody id="tb"></tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection