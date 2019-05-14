@extends('layouts.app')

@section('content')
<div class="container">
  <div class="row section-header">
      <h1>Responsables de Póliza</h1>
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
          @foreach($users as $u)
            <li>
              {{ $u->name }}&nbsp;&nbsp;|&nbsp;&nbsp;{{ $u->email }}&nbsp;&nbsp;|&nbsp;&nbsp;{{ $u->type }}<br/>
              <a href="{{ action('AdminController@edituser', ['id' => $u->id]) }}">Edit</a>
            </li>
          @endforeach
        </ul>
      </div>
    </div>
  </div>
</div>
@endsection
