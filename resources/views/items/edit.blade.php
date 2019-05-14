@extends('layouts.app')

@section('content')
<div class="container">
  <div class="row section-header">
      <h1>Editar Póliza</h1>
  </div>
  @if (session('status'))
      <div class="alert alert-success" role="alert">
          {{ session('status') }}
      </div>
  @endif
  <div class="row justify-content-center form-container">
    <div class="col-12">
        {!! Form::model($item, ['method'=>'PUT', 'action'=>['ItemController@update', $item->id], 'files'=>true, 'onsubmit' => 'return validationEditPolicy()']) !!}
        {{ csrf_field() }}
        <div class="data-container">
            <div class="form-row">
                <div class="col form-group">
                    {!! Form::label('itemnumber', 'No. Póliza', ['class'=>'col-sm-12 col-form-label']) !!}
                    <div class="col">
                        @if(auth()->user()->isAdmin())
                          {!! Form::text('item_number', $item->item_number, ['class'=>'form-control']) !!}
                        @else
                          {!! Form::text('item_number', $item->item_number, ['class'=>'form-control', 'readonly']) !!}
                        @endif
                        <div id="item-number-error"></div>
                    </div>
                </div>
                <div class="col form-group">
                    {!! Form::label('branch', 'Ramo', ['class'=>'col-sm-12 col-form-label']) !!}
                    <div class="col">
                      @if(auth()->user()->isAdmin())
                        {!! Form::select('branch_id', $branch, null) !!}
                      @else
                        {!! Form::text('branch', $currentbranch->name, ['class'=>'form-control','readonly']) !!}
                        {{ Form::hidden('branch_id', $currentbranch->id) }}
                      @endif
                    </div>
                </div>
            </div>
            <div class="form-row">
                <div class="col form-group">
                  
                    {!! Form::label('company', 'Aseguradora', ['class'=>'col-sm-12 col-form-label']) !!}
                    <div class="col">
                      @if(auth()->user()->isAdmin())
                        {!! Form::select('insco_id', $company , null) !!}
                      @else
                        {!! Form::text('company', $currentcompany->name, ['class'=>'form-control','readonly']) !!}
                        {{ Form::hidden('insco_id', $currentcompany->id) }}
                      @endif
                    </div>
                </div>
                <div class="col form-group">
                    {!! Form::label('duedate', 'Póliza Vence', ['class'=>'col-sm-12 col-form-label']) !!}
                    <div class="col">
                      {!! Form::text('due_date', Carbon\Carbon::parse($item->due_date)->format('d/m/Y'), ['class' => 'col-sm-12 col-form-label date','id' => 'dt', 'autocomplete' => 'off']) !!}
                    </div>
                </div>
            </div>
            <div class="form-row">
                <div class="col-sm-12 col-md-6 form-group">
                    {!! Form::label('plate', 'No. Placa', ['class'=>'col-sm-12 col-form-label']) !!}
                    <div class="col">
                        @if(auth()->user()->isAdmin())
                          {!! Form::text('plate', $item->plate, ['class'=>'form-control']) !!}
                        @else
                          {!! Form::text('plate', $item->plate, ['class'=>'form-control', 'readonly']) !!}
                        @endif
                    </div>
                </div>
                <div class="col-sm-12 col-md-6 form-group">
                  <div class="flex-left" id="sarlaf-container">
                      {!! Form::checkbox('sarlaf', 1, null, ['id' => 'sarlaft']) !!}
                      {!! Form::label('sarlaf', 'Sarlaf Adjunto', ['class'=>'col-form-label']) !!}
                  </div>
                  
                  <div class="flex-right hide" id="sarlaf-due-date">
                      {!! Form::label('sarlafduedate', 'Sarlaf Vence', ['class'=>'col-form-label']) !!}
                      {!! Form::text('sarlaf_duedate', Carbon\Carbon::parse($item->sarlaf_duedate)->format('d/m/Y'), ['class'=>'col-sm-12 col-form-label form-control date', 'id' => 'sarlafduedate', 'autocomplete' => 'off']) !!}
                  </div>
                </div>
            </div>
            <div class="form-row">
              <div class="col-sm-12 col-md-6 form-group">
                  {!! Form::label('comments', 'Comentarios', ['class'=>'col-sm-12 col-form-label']) !!}
                  <div class="col">
                      {!! Form::textarea('comments', null, ['class'=>'form-control', 'rows' => 1]) !!}
                  </div>
              </div>
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
            </div>
            <div class="form-row">
              <div class="col-sm-6 form-group">
                {!! Form::label('bstatus', 'Estado de la Póliza', ['class'=>'col-sm-12 col-form-label']) !!}
                  <div class="col">
                      {!! Form::select('bs_id', $bstatus , null) !!}
                  </div>
              </div>
              <div class="col-sm-12 col-md-6 form-group">
                {!! Form::label('responsibles', 'Responsable', ['class'=>'col-sm-12 col-form-label']) !!}
                <div class="col">
                  @if(auth()->user()->isAdmin())
                    {!! Form::select('responsible_id', $responsible, null) !!}
                  @else
                    {!! Form::text('responsible', $currentresponsible->name, ['class'=>'form-control','readonly']) !!}
                    {{ Form::hidden('responsible_id', $currentresponsible->id) }}
                  @endif
                </div>
              </div>
            </div>
            <div class="form-row">
              <div class="col-sm-12 form-group">
                <hr>
                <div class="image-container">
                  @if($files)
                    <h3 class="col-sm-12 col-form-label">Documentos de la póliza</h3>
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
                <div class="col-sm-12 col-md-6 form-group">
                    <button type="submit" class="btn btn-primary btn-smt">Guardar</button>
                </div>
            </div>
        </div>
        {!! Form::close() !!}
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
