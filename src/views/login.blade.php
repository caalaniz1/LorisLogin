@extends("LorisLogin::blueprint")

@section('content')
    <h1>Login</h1>
    {{Form::open(array('route'=>'hybridauth', 'method' => 'get'))}}
    {{Form::text('network')}}
    {{Form::submit('Submit!')}}
    {{Form::close()}}
@stop