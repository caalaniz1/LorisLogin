<pre>
Here you are logged in...

WHAT!!!?? You don't belive me?.. pfff... 

OK, OK... here you go this is your info...    
</pre>
<br>
Username: {{Auth::user()->username}}
<br>
Social Profiles: 
<br>
@foreach(Auth::user()->socialProfiles()->get() as $e)
{{$e->provider}}
<br>
@endforeach