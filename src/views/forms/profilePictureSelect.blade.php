<?php
$profile = Auth::user()->localProfile()->getResults();
?>
<h2>Select</h2>
Click on the picture that you want to set.
{{Form::open(array('action'=>'ProfilePictureController@select', 'files' => true))}}
<div>
    @foreach(Auth::user()->getFileSettings()['paths'] as $key => $value)
    <?php $url = Auth::user()->getPictureUrl($key); ?>
    <div style = "display: inline-table; width: 200px">
        <label for="select.{{$key}}">
            <img 
                src = "{{Auth::user()->getPictureUrl($key)}}" 
                style ="width:150px;"alt = "{{
                ($profile)?($profile->first_name.' '.$profile->last_name):""
                }}">
        </label><br>
        <input id ="select.{{$key}}" type="radio" name="select" 
               value="{{$key}}">
    </div>
    @endforeach
</div>
<div>
    <p>
        {{Form::submit("select")}}
    </p>
</div>
{{Form::close()}}