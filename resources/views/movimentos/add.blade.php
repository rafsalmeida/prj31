@extends('master')
@section('title',"Adicionar Movimento")
@section('content')
@include('movimentos.partials.add-edit')
@if (count($errors) > 0)
    @include('partials.errors')
@endif
<form method="POST" action="{{ action('MovimentoController@store')}}" class="form-group" enctype="multipart/form-data">
	@csrf
    <div class="container" style="padding-top: 15px">
    	@yield('form')
    </div>
    <div class="form-group" style="padding-left: 15px;">
        <button type="submit" class="btn btn-success" name="ok">Adicionar</button>
        <a class="btn btn-default" href="{{action('MovimentoController@index')}}" name="cancel">Cancelar</a>
    </div>
</form>

@endsection