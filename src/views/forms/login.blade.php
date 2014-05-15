<?php
/**
 * Login Form
 * 
 * @uses Twitter Bootstrap Library Css FrameWork
 * @author Carlos A. Alaniz <carlos@redpandadev.com>
 */
//Route to post to
$form_route = "test";
$form_action = "LoginController@loginWithLocalCredentials";
$social_action_action = "LoginController@loginWithSocialNetwork";
?>
@if($errors->has('login'))
<div class="alert alert-warning">
    @foreach($errors->get('login') as $error)
    <p><span class="glyphicon glyphicon-remove" style="color: #C52F24"></span>{{$error}}</p>
    @endforeach
</div>
@endif
{{Form::open(array
    ('action' => $form_action, 'method' => 'POST', 'role'=>'form'))}}

<!--Form Name-->
<div class="form-group"> <h1>Login</h2> </div>

<div class="form-group">
    <span class = "glyphicon glyphicon-user"></span>
    {{Form::label('username', 'Username')}}
    {{Form::text('username', $value = NULL , array
        ('class' => 'form-control', 'maxlength'=> 20,
        'placeholder'=>'Username'))}}
    @if($errors->has('username'))
    <div class="alert alert-warning">
        @foreach($errors->get('username') as $error)
        <p><span class="glyphicon glyphicon-remove" style="color: #C52F24"></span>{{$error}}</p>
        @endforeach
    </div>
    @endif
</div>
<div class="form-group">
    <span class = "glyphicon glyphicon-asterisk"></span>
    {{Form::label('password', 'Password')}}
    {{Form::password('password' , array
        ('class' => 'form-control', 'maxlength'=> 20,
        'placeholder'=>'Password'))}}
    @if($errors->has('password'))
    <div class="alert alert-warning">
        @foreach($errors->get('password') as $error)
        <p><span class="glyphicon glyphicon-remove" style="color: #C52F24"></span>{{$error}}</p>
        @endforeach
    </div>
    @endif
</div>
<div class="form-group">
    {{Form::submit('GO', array('class'=>'btn btn-default'))}}
</div>
{{Form::close()}}


<h3>Or</h3>
<div class="form-group">
    {{Form::open(array
    ('action' => $social_action_action , 'method' => 'GET', 'role'=>'form'))}}
    {{Form::submit('Facebook', array
        ('class'=>'btn btn btn-primary','name'=>'network' , 
        'value'=>'facebook'))}}
    {{Form::submit('Twitter', array
        ('class'=>'btn btn btn-info','name'=>'network' , 
        'value'=>'twitter'))}}   
</div>
{{Form::close()}}