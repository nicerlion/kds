@extends('layouts.app')

@section('content')
<div class="container">
  <div class="row section-header">
      <h1>Estados de Póliza</h1>
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
          @foreach($bss as $bs)
            <li>
              {{ $bs->name }}<br/>
              <a href="{{ action('AdminController@editbs', ['id' => $bs->id]) }}">Edit</a>
            </li>
          @endforeach
        </ul>
      </div>
    </div>
  </div>
</div>
@endsection
