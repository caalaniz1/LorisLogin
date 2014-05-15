<?php
/**
 * Profile Form
 * 
 * @uses Twitter Bootstrap Library Css FrameWork
 * @author Carlos A. Alaniz <carlos@redpandadev.com>
 */
//Route to post to
$form_route = "valid";
$form_action = "localProfileController@update";
$social_action_route = "test";
$user = Auth::user();
$localProfile = $user->localProfile()->getResults();

function ca($key, $profile) {
    return ($profile) ? $profile[$key] : NULL;
}

?>

{{Form::open(array
    ('action' => $form_action , 'method' => 'POST', 'role'=>'form'))}}

<!--Form Name-->
<div class="form-group"> <h1>Profile</h2> </div>

<!--First Name-->
<div class="form-group">
    {{Form::label('firstname', 'Firstname')}} 
    {{Form::text('firstName', $value = ca('first_name', $localProfile) , 
        array('class' => 'form-control', 'maxlength'=> 20, 
        'placeholder'=>'First Name',
        ))}}
    @if($errors->has('firstName'))
    <div class="alert alert-warning">
        @foreach($errors->get('firstName') as $error)
        <p><span class="glyphicon glyphicon-remove" style="color: #C52F24"></span>{{$error}}</p>
        @endforeach
    </div>
    @endif
</div>

<!--Last Name-->
<div class="form-group">
    {{Form::label('lastname', 'Lastname')}}
    {{Form::text('lastName', $value = ca('last_name', $localProfile), 
        array('class' => 'form-control', 'maxlength'=> 20,
        'placeholder'=>'Last Name'))}}
    @if($errors->has('lastName'))
    <div class="alert alert-warning">
        @foreach($errors->get('lastName') as $error)
        <p><span class="glyphicon glyphicon-remove" style="color: #C52F24"></span>{{$error}}</p>
        @endforeach
    </div>
    @endif
</div>

<!--Birth Day-->
<div class="form-group">
    {{Form::label('birthday', 'Birth Day')}}
    <br>
    {{Form::text('birthDay',$value = ca('birth_day', $localProfile) , array
        ('class' => 'form-control', 'placeholder'=>'dd',
        'value' => ca('birth_day', $localProfile),
        'maxlength'=> 2 , 'style'=>'width:4em; display:inline'))}}
    {{Form::text('birthMonth', $value = ca('birth_month', $localProfile) , array
        ('class' => 'form-control', 'placeholder'=>'mm', 
        'maxlength'=> 2 ,'style'=>'width:4em; display:inline'))}}
    {{Form::text('birthYear', $value = ca('birth_year', $localProfile) , array
        ('class' => 'form-control', 'placeholder'=>'yyyy', 
        'maxlength'=> 4 ,'style'=>'width:5em; display:inline'))}}

    @if($errors->has('birthDay') || $errors->has('birthMonth')
    || $errors->has('birthYear'))
    <div class="alert alert-warning">
        @foreach($errors->get('birthDay') as $error)
        <p><span class="glyphicon glyphicon-remove" style="color: #C52F24"></span>{{$error}}</p>
        @endforeach
        @foreach($errors->get('birthMonth') as $error)
        <p><span class="glyphicon glyphicon-remove" style="color: #C52F24"></span>{{$error}}</p>
        @endforeach
        @foreach($errors->get('birthYear') as $error)
        <p><span class="glyphicon glyphicon-remove" style="color: #C52F24"></span>{{$error}}</p>
        @endforeach
    </div>
    @endif
</div>

<!--Description-->
<div class="form-group">
    {{Form::label('description', 'Bio')}}
    {{Form::textarea('description', $value = ca('description', $localProfile) , 
        array('class' => 'form-control','maxlength'=> 250*5, 
        'placeholder'=>'Please tell us about yourself'))}}
    @if($errors->has('description'))
    <div class="alert alert-warning">
        @foreach($errors->get('description') as $error)
        <p><span class="glyphicon glyphicon-remove" style="color: #C52F24"></span>{{$error}}</p>
        @endforeach
    </div>
    @endif
</div>

<!--Gender-->
<div class="form-group">
    {{Form::label('gender', 'Gender')}}
    {{Form::select('gender', array('male'=>'Male','female'=>'Female'), 
        $selected = ca('gender', $localProfile),
        array('class' => 'form-control'))}}
    @if($errors->has('gender'))
    <div class="alert alert-warning">
        @foreach($errors->get('gender') as $error)
        <p><span class="glyphicon glyphicon-remove" style="color: #C52F24"></span>{{$error}}</p>
        @endforeach
    </div>
    @endif
</div>


<!--E-mail-->
<div class="form-group">
    {{Form::label('email', 'Email')}}
    {{Form::email('email', $value = ca('email', $localProfile) , array
        ('class' => 'form-control', 'maxlength'=> 50,
        'placeholder'=>'email@domain.com'))}}
    @if($errors->has('email'))
    <div class="alert alert-warning">
        @foreach($errors->get('email') as $error)
        <p><span class="glyphicon glyphicon-remove" style="color: #C52F24"></span>{{$error}}</p>
        @endforeach
    </div>
    @endif
</div>

<!--Address-->
<div class="form-group">
    {{Form::label('Address', 'Address')}}
    <small>Unit number Street name Apartment number 
        <small>< optional ></small>, Estate. Zip code 
    </small>
    {{Form::text('address', $value = ca('address', $localProfile) , array
        ('class' => 'form-control', 'maxlength'=> 150,
        'placeholder'=>'110 Jungle Street Apt 4, Estate. 78521'))}}
    @if($errors->has('address'))
    <div class="alert alert-warning">
        @foreach($errors->get('address') as $error)
        <p><span class="glyphicon glyphicon-remove" style="color: #C52F24"></span>{{$error}}</p>
        @endforeach
    </div>
    @endif
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



