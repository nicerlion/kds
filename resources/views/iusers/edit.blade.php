@extends('layouts.app')

@section('content')
<div class="container">
  <div class="row section-header">
      <h1>Editar Cliente</h1>
  </div>
  @if (session('status'))
      <div class="alert alert-success" role="alert">
          {{ session('status') }}
      </div>
  @endif
  <div class="row justify-content-center form-container">
    <div class="col-12">
        <div class="data-container">
            {!! Form::model($iuser, ['method'=>'PUT', 'action'=>['IuserController@update', $iuser->id], 'files'=>true, 'onsubmit' => 'return validationIuserDetail()' ]) !!}
                {{ csrf_field() }}
                <div class="form-row">
                    <div class="col form-group">
                        {!! Form::label('name', 'Nombre', ['class'=>'col-sm-12 col-form-label']) !!}
                        <div class="col">
                            @if(auth()->user()->isAdmin())
                                {!! Form::text('name', $iuser->name, ['class'=>'form-control']) !!}
                            @else
                                {!! Form::text('name', $iuser->name, ['class'=>'form-control', 'readonly']) !!}
                            @endif
                        </div>
                    </div>
                    <div class="col form-group">
                        {!! Form::label('document', 'Identificación', ['class'=>'col-sm-12 col-form-label']) !!}
                        <div class="col">
                            @if(auth()->user()->isAdmin())
                                {!! Form::text('document', $iuser->document, ['class'=>'form-control', 'data-id' => $iuser->id]) !!}
                            @else
                                {!! Form::text('document', $iuser->document, ['class'=>'form-control', 'readonly', 'data-id' => $iuser->id]) !!}
                            @endif
                        </div>
                    </div>
                </div>
                <div class="form-row">
                  <div class="col-sm-6 form-group">
                      {!! Form::label('file', 'Subir archivos', ['class'=>'col-sm-12 col-form-label']) !!}
                      <div class="col-sm-12 input-group control-group increment">
                          {!! Form::file('filename[]', ['class'=> 'form-control']) !!}
                          <div class="input-group-btn"> 
                              <button class="btn btn-success" type="button"><i class="glyphicon glyphicon-plus"></i>Agregar</button>
                          </div>
                          <div class="clone hide">
                              <div class="col-sm-12 control-group input-group" style="margin-top:10px">
                                  {!! Form::file('filename[]', ['class'=> 'form-control']) !!}
                                  <div class="input-group-btn"> 
                                  <button class="btn btn-danger" type="button"><i class="glyphicon glyphicon-remove"></i>Remover</button>
                                  </div>
                              </div>
                          </div>
                      </div>
                  </div>
                  <div class="col-sm-12 col-md-6 form-group">
                    <div class="flex-left" id="sarlaf-container">
                        {!! Form::checkbox('sarlaf', $iuser->sarlaf, null, ['id' => 'sarlaft']) !!}
                        {!! Form::label('sarlaf', 'Sarlaf Adjunto', ['class'=>'col-form-label']) !!}
                    </div>
                    
                    <div class="flex-right hide" id="sarlaf-due-date">
                        {!! Form::label('sarlafduedate', 'Sarlaf Vence', ['class'=>'col-form-label']) !!}
                        {!! Form::text('sarlaf_duedate', Carbon\Carbon::parse($iuser->sarlaf_duedate)->format('d/m/Y'), ['class' => 'col-sm-12 col-form-label form-control date','id' => 'sarlafduedate']) !!}
                    </div>
                  </div>
                </div>
                <div class="form-row">
                  <div class="col-sm-12 form-group">
                    <hr>
                    <div class="image-container">
                      @if($files)
                      <h3 class="col-sm-12 col-form-label">Documentos anexos</h3>
                        <ul>
                            @foreach ($files as $image)
                              <li>
                                  @if (auth()->user()->isAdmin())
                                    <label>
                                      {!! Form::checkbox($image['name'], '1', false, ['class' => 'checkboximg']) !!}
                                      @if ($image['type'] == 'pdf' or $image['type'] == 'PDF')
                                        <span class="portfolio-box">
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
                                          <span class="delete"></span>
                                        </span>
                                      @else
                                        <span class="portfolio-box img-document" style=" background-image: url( {{$image['path']}} );">
                                          <span class="archive-item">
                                            <span class="row align-items-center">
                                              <span class="col-auto">
                                                <i class="fas fa-image"></i>
                                              </span>
                                              <span class="col">
                                                <span data-toggle="tooltip" data-placement="top" title="{{ $image['oriname'] }}">{{ $image['oriname'] }}</span>
                                              </span>
                                            </span>
                                            <span class="boder-archive"></span>
                                          </span>
                                          <span class="delete"></span>
                                        </span>
                                      @endif
                                    </label>
                                  @else
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
                                      <a href="{{ $image['path'] }}" class="portfolio-box img-document" target="_blank" style=" background-image: url({{ $image['path'] }}); ">
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
                                  @endif
                              </li>
                            @endforeach
                        </ul>
                      @endif
                    </div>
                  </div>
                </div>
                <div class="form-row">
                    <div class="col form-group">
                    <div class="col">
                        {!! Form::submit('Guardar',['class'=>'btn btn-primary']) !!}
                    </div>
                    </div>
                </div>
            {!! Form::close() !!}
        </div>
        @if (count($errors) > 0)
            <div class="alert alert-danger">
                <strong>Whoops!</strong> Hay campos vacios o con información equivocada. Por favor llénelos correctamente.<br><br>
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
    </div>
  </div>
</div>
@endsection
