@extends('adminlte::page')

@section('title', 'Lista de empleados')

@section('content')
	<div id="app">
		<employees-list></employees-list>
	</div>
@stop
@section('css')
    <link rel="stylesheet" href="{{ mix('/css/app.css') }}">
@stop

@section('js')
    <script src="{{ mix('/js/app.js') }}"> </script>
@stop
