<head>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
</head>

@extends('adminlte::page')

@section('title', 'Lsita de consultorios')

@section('content')
    <div id="app">
        <clinic-list></clinic-list>
    </div>
@endsection

@section('css')
    <link rel="stylesheet" href="{{ mix('/css/app.css') }}">
@stop

@section('js')
    <script src="{{ mix('/js/app.js') }}"> </script>
@stop