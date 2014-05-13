{{Form::open(array('action'=>'ProfilePicture@upload', 'files' => true))}}

{{Form::file('profile_picture');}}
{{Form::submit()}}


{{Form::close()}}