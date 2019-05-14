@extends('layouts.app')

@section('content')
<div class="container">
  <div class="row section-header">
      <h1>Ramos</h1>
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
          @foreach($branches as $branch)
            <li>
              {{ $branch->name }}&nbsp;&nbsp;|&nbsp;&nbsp;
              @if($branch->sarlaf == 1) 
                Requiere Sarlaft
              @else
                No Requiere Sarlaft
              @endif
              <br/>
              <a href="{{ action('AdminController@editbranch', ['id' => $branch->id]) }}">Edit</a>
            </li>
          @endforeach
        </ul>
      </div>
    </div>
  </div>
</div>
@endsection
