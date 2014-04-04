@extends("LorisLogin::blueprint")

@section('content')
<div class="col-md-4">

    <h1>Create a local user</h1>
    {{Form::open(array(
                'route'=>'localSignup', 
                'method' => 'POST',
                'role' => 'form',
                ))}}
    <div class ="form-group">
        {{Form::label('username', 'Username');}}
        {{Form::text('username', 
        NULL,
        array(
        'class'=>'form-control',
        'placeholder'=>'Username'
        ))}}
    </div>
    <div class ="form-group">
        {{Form::label('password', 'Password');}}
        {{Form::password('password', 
        array(
        'class'=>'form-control',
        'placeholder'=>'Password'
        ))}}
    </div>
    <div class ="form-group">
        {{Form::label('password', 'Confirm Password');}}
        {{Form::password('confirm-password', 
        array(
        'class'=>'form-control',
        'placeholder'=>'Confirm password'
        ))}}
    </div>
    {{Form::close()}}
    <h3>OR!..Create and link with:</h3>
    {{Form::open(array(
                'route'=>'socialSignup', 
                'method' => 'GET',
                'role' => 'form',
                ))}}
    {{Form::submit('facebook', array('class'=> 'btn btn-primary', 
        'name'=>'network','value'=>'facebook'))}}
    {{Form::submit('twitter', array('class'=> 'btn btn-info', 
        'name'=>'network','value'=>'twitter'))}}
    {{Form::close()}}


</div>
<div class="col-md-4"></div>
<div class="col-md-4">
    @if(Session::has('message'))
    <div class="alert alert-warning">
        <span class="glyphicon glyphicon-warning-sign" style="color: #EE3322"></span>
        {{Session::get('message')}}
    </div>
    @endif

</div>


@stop