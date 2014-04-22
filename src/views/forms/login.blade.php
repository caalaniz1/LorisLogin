<?php
/**
 * Login Form
 * 
 * @uses Twitter Bootstrap Library Css FrameWork
 * @author Carlos A. Alaniz <carlos@redpandadev.com>
 */
//Route to post to
$form_route = "test";
$social_action_route = "test";
?>


{{Form::open(array
    ('route' => $form_route, 'method' => 'POST', 'role'=>'form'))}}

<!--Form Name-->
<div class="form-group"> <h1>Login</h2> </div>

<div class="form-group">
    <span class = "glyphicon glyphicon-user"></span>
    {{Form::label('username', 'Username')}}
    {{Form::text('username', $value = NULL , array
        ('class' => 'form-control', 'maxlength'=> 20,
        'placeholder'=>'Username'))}}
</div>
<div class="form-group">
    <span class = "glyphicon glyphicon-asterisk"></span>
    {{Form::label('password', 'Password')}}
    {{Form::password('password' , array
        ('class' => 'form-control', 'maxlength'=> 20,
        'placeholder'=>'Password'))}}
</div>
<div class="form-group">
    {{Form::submit('GO', array('class'=>'btn btn-default'))}}
</div>
{{Form::close()}}


<h3>Or</h3>
<div class="form-group">
    {{Form::open(array
    ('route' => $social_action_route , 'method' => 'GET', 'role'=>'form'))}}
    {{Form::submit('Facebook', array
        ('class'=>'btn btn btn-primary','name'=>'network' , 
        'value'=>'facebook'))}}
    {{Form::submit('Twitter', array
        ('class'=>'btn btn btn-info','name'=>'network' , 
        'value'=>'twitter'))}}   
</div>
{{Form::close()}}



