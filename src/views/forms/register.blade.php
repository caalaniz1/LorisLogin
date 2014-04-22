<?php
/**
 * Register Form
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
<div class="form-group"> <h1>Register</h2> </div>

<div class="form-group">
    {{Form::label('username', 'Username')}}
    {{Form::text('username', $value = NULL , array
        ('class' => 'form-control', 'maxlength'=> 20,
        'placeholder'=>'Username'))}}
</div>
<div class="form-group">
    {{Form::label('email', 'Email')}}
    {{Form::email('emai', $value = NULL , array
        ('class' => 'form-control', 'maxlength'=> 110,
        'placeholder'=>'email@domain.com'))}}
</div>
<div style='
     border:1px solid #ccc; width: 100%; height: 0px; margin: 4px 0px;'></div>
<div class="form-group">
    {{Form::label('password', 'Password')}}
    {{Form::password('password' , array
        ('class' => 'form-control', 'maxlength'=> 20,
        'placeholder'=>'Password'))}}
</div>
<div class="form-group">
    {{Form::label('password-r', 'Repeat Password')}}
    {{Form::password('password-r' , array
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



