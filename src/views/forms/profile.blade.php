<?php
/**
 * Profile Form
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
<div class="form-group"> <h1>Profile</h2> </div>

<!--First Name-->
<div class="form-group">
    {{Form::label('firstname', 'Firstname')}}
    {{Form::text('firstName', $value = NULL , array
        ('class' => 'form-control', 'maxlength'=> 20, 
        'placeholder'=>'First Name'))}}

</div>

<!--Last Name-->
<div class="form-group">
    {{Form::label('lastname', 'Lastname')}}
    {{Form::text('lastName', $value = NULL , array
        ('class' => 'form-control', 'maxlength'=> 20,
        'placeholder'=>'Last Name'))}}
</div>

<!--Birth Day-->
<div class="form-group">
    {{Form::label('birthday', 'Birth Day')}}
    <br>
    {{Form::text('birthDay', $value = NULL , array
        ('class' => 'form-control', 'placeholder'=>'dd', 
        'maxlength'=> 2 , 'style'=>'width:4em; display:inline'))}}
    {{Form::text('birthMonth', $value = NULL , array
        ('class' => 'form-control', 'placeholder'=>'mm', 
        'maxlength'=> 2 ,'style'=>'width:4em; display:inline'))}}
    {{Form::text('birthYear', $value = NULL , array
        ('class' => 'form-control', 'placeholder'=>'yyyy', 
        'maxlength'=> 4 ,'style'=>'width:5em; display:inline'))}}
</div>

<!--Description-->
<div class="form-group">
    {{Form::label('description', 'Bio')}}
    {{Form::textarea('description', $value = NULL , array
        ('class' => 'form-control','maxlength'=> 250*5, 
        'placeholder'=>'Please tell us about yourself'))}}
</div>

<!--Gender-->
<div class="form-group">
    {{Form::label('gender', 'Gender')}}
    {{Form::select('gender', array('male'=>'Male','female'=>'Female'),'sex',
        array('class' => 'form-control', 'placeholder'=>'Description'))}}
</div>

<!--Profile Picture-->
<div class="form-group">
    {{Form::label('photoUrl', 'Profile Picture')}}
    MAKE THIS WORK!
    {{Form::file('photoUrl')}}
</div>

<!--Profile Picture-->
<div class="form-group">
    {{Form::label('email', 'Email')}}
    {{Form::email('emai', $value = NULL , array
        ('class' => 'form-control', 'maxlength'=> 50,
        'placeholder'=>'email@domain.com'))}}
</div>

<!--Address-->
<div class="form-group">
    {{Form::label('Address', 'Address')}}
    <small>Unit number Street name Apartment number 
        <small>< optional ></small>, Estate. Zip code 
    </small>
    {{Form::text('address line1', $value = NULL , array
        ('class' => 'form-control', 'maxlength'=> 150,
        'placeholder'=>'110 Jungle Street Apt 4, Estate. 78521'))}}
</div>


<div class="form-group">
    {{Form::submit('GO', array('class'=>'btn btn-default'))}}
</div>
{{Form::close()}}


{{Form::open(array
    ('route' => $social_action_route , 'method' => 'GET', 'role'=>'form'))}}
<!--Form Name-->
<div class="form-group"> <h2>Or</h2> </div>

<div class="form-group">
    {{Form::submit('Facebook', array
        ('class'=>'btn btn btn-primary','name'=>'network' , 
        'value'=>'facebook'))}}
    {{Form::submit('Twitter', array
        ('class'=>'btn btn btn-info','name'=>'network' , 
        'value'=>'twitter'))}}   
</div>
{{Form::close()}}



