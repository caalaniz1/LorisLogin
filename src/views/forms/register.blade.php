<?php
/**
 * Register Form
 * 
 * @uses Twitter Bootstrap Library Css FrameWork
 * @author Carlos A. Alaniz <carlos@redpandadev.com>
 */
//Route to post to
$form_route = "registerlocal";
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
    @if($errors->has('username'))
    <div class="alert alert-warning">
        @foreach($errors->get('username') as $error)
        <p><span class="glyphicon glyphicon-remove" style="color: #C52F24"></span>{{$error}}</p>
        @endforeach
    </div>
    @endif
</div>
<div class="form-group">
    {{Form::label('email', 'Email')}}
    {{Form::email('email', $value = NULL , array
        ('class' => 'form-control', 'maxlength'=> 110,
        'placeholder'=>'email@domain.com'))}}
    @if($errors->has('email'))
    <div class="alert alert-warning">
        @foreach($errors->get('email') as $error)
        <p><span class="glyphicon glyphicon-remove" style="color: #C52F24"></span>{{$error}}</p>
        @endforeach
    </div>
    @endif
</div>
<div style='
     border:1px solid #ccc; width: 100%; height: 0px; margin: 4px 0px;'></div>
<div class="form-group">
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
    {{Form::label('password-r', 'Repeat Password')}}
    {{Form::password('password-r' , array
        ('class' => 'form-control', 'maxlength'=> 20,
        'placeholder'=>'Password'))}}
    @if($errors->has('password-r'))
    <div class="alert alert-warning">
        @foreach($errors->get('password-r') as $error)
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
    ('route' => $social_action_route , 'method' => 'GET', 'role'=>'form'))}}
    {{Form::submit('Facebook', array
        ('class'=>'btn btn btn-primary','name'=>'network' , 
        'value'=>'facebook'))}}
    {{Form::submit('Twitter', array
        ('class'=>'btn btn btn-info','name'=>'network' , 
        'value'=>'twitter'))}}   
</div>
{{Form::close()}}



