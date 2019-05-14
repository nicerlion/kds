@extends('layouts.app')

@section('content')
<div class="container">
  <div class="row section-header">
      <h1>Usuarios (Tomadores, Asegurados y Beneficiarios)</h1>
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
          @foreach($iusers as $iu)
            <li>
              {{ $iu->name }}<br/>
              <a href="{{ action('AdminController@editiuser', ['id' => $iu->id]) }}">Edit</a>
            </li>
          @endforeach
        </ul>
      </div>
    </div>
  </div>
</div>
@endsection
