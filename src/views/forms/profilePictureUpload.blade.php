<?php
$profile = Auth::user()->localProfile()->getResults();
?>
<h2>Upload</h2>
Allowed file types: png, jpeg, jpg<br>
Max size: 800Kb
{{Form::open(array('action'=>'ProfilePicture@upload', 'files' => true))}}
{{Form::file('profile_picture');}}
{{Form::submit("Upload")}}
{{Form::close()}}
