@extends('layouts.app')

@section('content')
<div class="container">
  <div class="row section-header">
      <h1>Nueva Póliza</h1>
  </div>
  @if (session('status'))
      <div class="alert alert-success" role="alert">
          {{ session('status') }}
      </div>
  @endif
  <div class="row justify-content-center form-container form-container-nm">
    <div class="col-12">
        {!! Form::open(['method'=>'POST', 'action'=>'ItemController@store', 'files'=>true, 'onsubmit' => 'return validationCreationPolicy()']) !!}
        {{ csrf_field() }}
        <p id="isuerTypesEncode">{{ $isuerTypesEncode }}</p>
        @foreach ($isuerTypes as $key => $type)
            <div class="form-group iuser-section row" user_type_section="{{$type->id}}">
                <div class="col-12 iuser-row">
                    <div class="row"  user_section_key="{{$key}}">
                        @if ($key != 0)
                            <p class="col-sm-12 col-form-label checkbox-label">{!! Form::checkbox('checkboxusertype' . $type->id, '', false, ['data_type' => $isuerTypes[0]->id]) !!} Copiar Datos del {{$isuerTypes[0]->name}}</p>
                        @endif
                        <div class="col-sm-12 col-md-6">
                            {!! Form::label('userdoctype' . $type->id . '[]', 'Cédula / Nit del ' . $type->name, ['class'=>'col-sm-12 col-form-label']) !!}
                            <div class="col-sm-12">
                                @if ($key != 0)
                                    {!! Form::hidden('userdochiddentype' . $type->id, null, ['class'=>'form-control', 'readonly']) !!}
                                @endif
                                {!! Form::text('userdoctype' . $type->id . '[]', null, ['user_data'=>'userdoc', 'class'=>'form-control', 'list'=>'userlisttype' . $type->id, 'user_type_doc' => $type->id]) !!}
                                <datalist id="userlisttype{{$type->id}}" type_user="{{$type->id}}"></datalist>
                            </div>
                        </div>
                        <div class="col-sm-12 col-md-6">
                            {!! Form::label('usernametype' . $type->id . '[]', 'Nombre del ' . $type->name, ['class'=>'col-sm-12 col-form-label']) !!}
                            <div class="col-sm-12">
                                @if ($key != 0)
                                    {!! Form::hidden('usernamehiddentype' . $type->id, null, ['class'=>'form-control', 'readonly']) !!}
                                @endif
                                {!! Form::text('usernametype' . $type->id . '[]', null, ['user_data'=>'username', 'class'=>'form-control username']) !!}
                            </div>
                        </div>
                    </div>
                    @if ($key != 0)
                        <div class="input-group-btn-row"> 
                            <div class="input-group-btn-content"> 
                                <button class="btn btn-add-row" type="button" user_type_button="{{$type->id}}"></button>
                            </div>
                        </div>
                        <div class="clone-row hide">
                            <div class="col-12 iuser-row duplicate-row">
                                <div class="row">
                                    <div class="col-sm-12 col-md-6">
                                        {!! Form::label('', 'Cédula / Nit del ' . $type->name, ['class'=>'col-sm-12 col-form-label']) !!}
                                        <div class="col-sm-12">
                                            {!! Form::text('userdoctypeclone', null, ['class'=>'form-control', 'list'=>'userlisttype' . $type->id, 'user_type_doc' => $type->id ]) !!}
                                        </div>
                                    </div>
                                    <div class="col-sm-12 col-md-6">
                                        {!! Form::label('', 'Nombre del ' . $type->name, ['class'=>'col-sm-12 col-form-label']) !!}
                                        <div class="col-sm-12">
                                            {!! Form::text('usernametypeclone', null, ['class'=>'form-control username']) !!}
                                        </div>
                                    </div>
                                </div>
                                <div class="input-group-btn-row">
                                    <div class="input-group-btn-content">
                                        <button class="btn btn-remove-row" type="button"></button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        @endforeach
        <div class="data-container">
            <div class="form-row">
                <div class="col form-group">
                    {!! Form::label('noitem', 'No. Póliza', ['class'=>'col-sm-12 col-form-label']) !!}
                    <div class="col">
                        {!! Form::text('noitem', null, ['class'=>'form-control']) !!}
                        <div id="item-number-error"></div>
                    </div>
                </div>
                <div class="col form-group">
                    {!! Form::label('branches', 'Ramo', ['class'=>'col-sm-12 col-form-label']) !!}
                    <div class="col">
                        {!! Form::select('branches', $branches, null) !!}
                    </div>
                </div>
            </div>
            <div class="form-row">
                <div class="col form-group">
                    {!! Form::label('companies', 'Aseguradora', ['class'=>'col-sm-12 col-form-label']) !!}
                    <div class="col">
                        {!! Form::select('companies', $companies, null) !!}
                    </div>
                </div>
                <div class="col form-group">
                    {!! Form::label('duedate', 'Póliza Vence', ['class'=>'col-sm-12 col-form-label']) !!}
                    <div class="col">
                        {!! Form::text('duedate', null, ['class' => 'col-sm-12 col-form-label date','id' => 'dt', 'autocomplete' => 'off']) !!}
                    </div>
                </div>
            </div>
            <div class="form-row">
                <div class="col-sm-12 col-md-6 form-group">
                    {!! Form::label('plate', 'No. Placa', ['class'=>'col-sm-12 col-form-label']) !!}
                    <div class="col">
                        {!! Form::text('plate', null, ['class'=>'form-control']) !!}
                    </div>
                </div>
                <div class="col-sm-12 col-md-6 form-group">
                        <div class="flex-left" id="sarlaf-container">
                            {!! Form::checkbox('sarlaf', 1, null, ['id' => 'sarlaft']) !!}
                            {!! Form::label('sarlaft', 'Sarlaft Adjunto', ['class'=>'col-form-label']) !!}
                        </div>
                        
                        <div class="flex-right hide" id="sarlaf-due-date">
                            {!! Form::label('sarlafduedate', 'Sarlaf Vence', ['class'=>'col-form-label']) !!}
                            {!! Form::text('sarlafduedate', null, ['class'=>'col-sm-12 col-form-label form-control date', 'id' => 'sarlafduedate', 'autocomplete' => 'off']) !!}
                        </div>
                </div>
            </div>
            <div class="form-row">
                <div class="col-sm-12 col-md-6 form-group">
                    {!! Form::label('bstatus', 'Estado de la póliza', ['class'=>'col-sm-12 col-form-label']) !!}
                    <div class="col">
                        {!! Form::select('bstatus', $bstatus, null) !!}
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
                <div class="col-sm-12 col-md-6 form-group">
                    {!! Form::label('responsibles', 'Responsable', ['class'=>'col-sm-12 col-form-label']) !!}
                    <div class="col">
                        {!! Form::select('responsibles', $responsibles, null) !!}
                    </div>
                </div>
                <div class="col-sm-12 col-md-6 form-group">
                    {!! Form::label('comments', 'Comentarios', ['class'=>'col-sm-12 col-form-label']) !!}
                    <div class="col">
                        {!! Form::textarea('comments', null, ['class'=>'form-control', 'rows' => 1]) !!}
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