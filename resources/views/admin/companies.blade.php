@extends('layouts.app')

@section('content')
<div class="container">
  <div class="row section-header">
      <h1>Compañías de Seguros</h1>
  </div>
  @if (session('status'))
      <div class="alert alert-success" role="alert">
          {{ session('status') }}
      </div>
  @endif
  <div class="row justify-content-center form-container">
    <div class="col-12">
      <div class="data-container">
        <ul class="list-btn-border">
          @foreach($companies as $ic)
            <li>
              {{ $ic->name }}<br/>
              <a href="{{ action('AdminController@editcompany', ['id' => $ic->id]) }}">Edit</a>
            </li>
          @endforeach
        </ul>
      </div>
    </div>
  </div>
</div>
@endsection
