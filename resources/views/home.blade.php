@extends('layouts.app')

@section('content')
<div class="section-header">
    <div class="container">
        <div class="row no-flex">
            <div class="col-12">
                <h1 class="float-left">Dashboard</h1>
            </div>
        </div>
    </div>
</div>
<div class="container">
    @if (session('status'))
        <div class="alert alert-success" role="alert">
            {{ session('status') }}
        </div>
    @endif
    <div class="welcome">
        <h3>Bienvenido a KDS</h3>
        <span>Keep Documents Safe</span>
    </div>
    <div class="row justify-content-center form-container">
        <div class="col-sm-12 col-md-4 col-lg-3">
            <a href="{{ url('/item/create') }}" class="box-container df dt-red">
                <span class="col-4 icon-container">
                    <i class="fas fa-file-powerpoint"></i>
                </span>
                <span class="col-8 icon-text">Crear Póliza</span>
            </a>
        </div>
        <div class="col-sm-12 col-md-4 col-lg-3">
            <a  href="{{ url('/items') }}" class="box-container df dt-green">
                <span class="col-4 icon-container">
                    <i class="fas fa-upload"></i>
                </span>
                <span class="col-8 icon-text">Agregar Documento a Póliza</span>
            </a>
        </div>
        <div class="col-sm-12 col-md-4 col-lg-3">
            <a  href="{{ url('/iusers') }}" class="box-container df dt-dark-gray">
                <span class="col-4 icon-container">
                    <i class="fas fa-file-powerpoint"></i>
                </span>
                <span class="col-8 icon-text">Agregar Documento a Usuario</span>
            </a>
        </div>
        <div class="col-sm-12 col-md-4 col-lg-3">
            <a  href="{{ url('/plate') }}" class="box-container df dt-light-gray">
                <span class="col-4 icon-container">
                    <i class="fas fa-car"></i>
                </span>
                <span class="col-8 icon-text">Historial Vehículo</span>
            </a>
        </div>
    </div>
</div>
@endsection
