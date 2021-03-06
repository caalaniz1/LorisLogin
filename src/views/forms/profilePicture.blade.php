<?php
$profile = Auth::user()->localProfile()->getResults();
?>
@if(($profile)?($profile->photo_url):false)
<p>Current Picture:</p>
<p>
    <img src="{{$profile->photo_url}}" 
         alt="{{{$profile->first_name}}} {{{$profile->last_name}}}" 
         style = "width: 250px; height: auto">
</p>
@else
You don't have a profile picture yet
@endif

@include('LoriLoginController::forms/profilePictureUpload')
@include('LoriLoginController::forms/profilePictureSelect')
@include('LoriLoginController::forms/profilePictureDelete')