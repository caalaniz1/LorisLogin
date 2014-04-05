@extends("LorisLogin::blueprint")

@section('content')

<div class="col-md-4">
    <h1>Login</h1>
    <!--Local Login Form-->
    {{Form::open(array(
                'route'=>'localLogin', 
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
        {{Form::submit('Login', array('class'=> 'btn btn-default'))}}
    </div>
    {{Form::close()}}


    <!--Social Login Form-->

    {{Form::open(array(
                'route'=>'socialLogin', 
                'method' => 'GET',
                'role' => 'form',
                ))}}
    {{Form::submit('facebook', array('class'=> 'btn btn-primary', 
        'name'=>'network','value'=>'facebook'))}}
    {{Form::submit('twitter', array('class'=> 'btn btn-info', 
        'name'=>'network','value'=>'twitter'))}}
    {{Form::close()}}


</div>
<div class="col-md-4">
</div>
<div class="col-md-4">
    <div class="alert alert-info">
        <span class="glyphicon glyphicon-star" style="color: #e38d13;"></span>
        You can login either with a local account or using your social network 
        credentials
    </div>
</div>
@stop