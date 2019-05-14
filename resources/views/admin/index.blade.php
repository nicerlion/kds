@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-12 section-header">
            <h1>Admin Dashboard</h1>
        </div>
    </div>
    @if (session('status'))
        <div class="alert alert-success" role="alert">
            {{ session('status') }}
        </div>
    @endif
    <div class="row justify-content-center form-container">
        <div class="col-sm-12 col-md-6">
            <div class="data-container">
                <h2>Pólizas Recientes</h2>
                <div class="table-responsive">
                    <table class="table">
                        <thead>
                        <tr>
                            <th scope="col">No. Póliza</th>
                            <th scope="col">Fecha</th>
                        </tr>
                        </thead>
                        <tbody>
                            @if( $items )
                                @foreach($items as $item)
                                <tr>
                                    <td><a href="{{ action('ItemController@show', ['id' => $item->id]) }}">{{ $item->item_number }}</a></td>
                                    <td>{{ $item->created_at }}</td>
                                </tr>
                                @endforeach
                            @else
                                <tr>
                                    <td>No hay pólizas disponibles.</td>
                                </tr>
                            @endif
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <div class="col-sm-12 col-md-6">
            <div class="data-container">
                <h2>Asegurados</h2>
                <div class="table-responsive">
                    <table class="table">
                        <thead>
                        <tr>
                            <th scope="col">Nombre</th>
                            <th scope="col">Cédula</th>
                        </tr>
                        </thead>
                        <tbody>
                            @if( $iusers )
                                @foreach($iusers as $iuser)
                                <tr>
                                    <td>{{ $iuser->name }}</td>
                                    <td>{{ $iuser->document }}</td>
                                </tr>
                                @endforeach
                            @else
                                <tr>
                                    <td>No hay usuarios disponibles.</td>
                                </tr>
                            @endif
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <div class="row justify-content-center form-container">
        <div class="col-sm-12 col-md-6">
            <div class="data-container">
                <h2>Ramo</h2>
                <div class="table-responsive">
                    <table class="table">
                        <thead>
                        <tr>
                            <th scope="col">Nombre</th>
                        </tr>
                        </thead>
                        <tbody>
                            @if( $branches )
                                @foreach($branches as $branch)
                                <tr>
                                    <td>{{ $branch->name }}</td>
                                </tr>
                                @endforeach
                            @else
                                <tr>
                                    <td>No hay ramos disponibles.</td>
                                </tr>
                            @endif
                        </tbody>
                    </table>
                </div>
                <div>
                    <a href="{{ route('showbranches') }}" class="btn btn-primary home-btn">Ver más</a>
                </div>
            </div>
        </div>
        <div class="col-sm-12 col-md-6">
            <div class="data-container">
                <h2>Compañías Aseguradoras</h2>
                <div class="table-responsive">
                    <table class="table">
                        <thead>
                        <tr>
                            <th scope="col">Nombre</th>
                        </tr>
                        </thead>
                        <tbody>
                            @if( $companies )
                                @foreach($companies as $company)
                                <tr>
                                    <td>{{ $company->name }}</td>
                                </tr>
                                @endforeach
                            @else
                                <tr>
                                    <td>No hay Aseguradoras disponibles.</td>
                                </tr>
                            @endif
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
