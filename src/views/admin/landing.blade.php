@extends("LorisLogin::blueprint")

@section('content')
<div class="col-md-4">
    <?php $lprofile = Auth::user()->localProfile()->getResults() ?>
    <?php $sprofile = Auth::user()->socialProfiles()->getResults() ?>
    @if($lprofile != NULL)
    <div>
        {{Auth::user()->id}}
        <h3>{{$lprofile->first_name}}{{$lprofile->last_name}}</h3>
        <img src="{{$lprofile->photo_url}}" 
             alt="{{$lprofile->first_name}} {{$lprofile->last_name}}">
    </div>
    <div>
        @if($sprofile)
        <h4>Linked Social Accounts</h4>
        @foreach($sprofile as $sp)
        {{$sp->provider}}
        <br>
        @endforeach
        @endif
    </div>
    @else
    <div class="alert alert-warning">
        <span class="glyphicon glyphicon-warning-sign" style="color: #EE3322"></span>
        <span style="font-weight: bold">Oops...</span> Looks like you haven't
        filled your profile yet! {{HTML::link(action('landing'), 'Click here')}}
        to complete it!
    </div>
    @endif

</div>
<div class="col-md-4">
    <ul>
        <li><a href='{{URL::action('linkSocialProfile', array('network'=>'facebook'))}}'>Link a Facebook account</a></li>
        <li><a href='{{URL::action('linkSocialProfile', array('network'=>'twitter'))}}'>Link a Twitter  account</a></li>
    </ul>
</div>
<div class="col-md-4">

</div>
@stop