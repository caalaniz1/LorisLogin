<?php
$profile = Auth::user()->localProfile()->getResults();
?>
<h2>Delete</h2>
Click on the picture that you want to set.
{{Form::open(array('action'=>'ProfilePicture@delete', 'files' => true))}}
<div>
    @foreach(Auth::user()->getFileSettings()['paths'] as $key => $value)
    <?php $url = Auth::user()->getPictureUrl($key); ?>
    <div style = "display: inline-table; width: 200px">
        <label for="delete.{{$key}}">
            <img 
                src = "{{Auth::user()->getPictureUrl($key)}}" 
                style ="width:150px;"alt = "{{
                ($profile)?($profile->first_name.' '.$profile->last_name):""
                }}">
        </label><br>
        <input id ="delete.{{$key}}" type="radio" name="delete" 
               value="{{$key}}">
    </div>
    @endforeach
</div>
<div>
    <p>
        {{Form::submit("delete")}}
    </p>
</div>
{{Form::close()}}