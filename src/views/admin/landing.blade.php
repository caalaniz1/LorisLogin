@extends("LorisLogin::blueprint")

@section('content')
{{getSessionData()}}
<div class="col-md-4">
    <?php $lprofile = Auth::user()->localProfile()->getResults() ?>
    <?php $sprofile = Auth::user()->socialProfiles()->getResults() ?>
    @if($lprofile != NULL)
    <div>
        <h3>{{$lprofile->first_name}}{{$lprofile->last_name}}</h3>
        <img src="{{$lprofile->photo_url}}" 
             alt="{{$lprofile->first_name}} {{$lprofile->last_name}}">
    </div>
    <div>
        @if($sprofile)
        <h4>Linked Social Accounts</h4>
        <table border="1">
            <tr>
                <th>Provider</th>
                <th>Unique Identifier</th>
            </tr>
            @foreach($sprofile as $sp)
            <tr>
                <td>{{$sp->provider}}</td>
                <td>{{$sp->identifier}}</td>
            </tr>
            @endforeach
        </table>
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
    @if($lprofile)
        
        <p>Created at: {{Auth::user()->created_at}}</p>
        <p>Name: {{$lprofile->first_name}} {{$lprofile->last_name}}</p>
        <p>Description: {{$lprofile->description}}</p>
        <p>Gender: {{$lprofile->gender}}</p>
        <p>Birthday : {{$lprofile->birth_day}}/{{sprintf('%02d',$lprofile->birth_month)}}/{{$lprofile->birth_year}}</p>
        <p>Email: {{$lprofile->email}}</p>
        <p>Address: {{$lprofile->address}}</p>
        <p>Country: {{$lprofile->country}}</p>
        <p>City: {{$lprofile->city}}</p>
        <p>Zip: {{$lprofile->zip}}</p>
        <p>Last Update: {{$lprofile->updated_at}}</p>
    @endif
</div>
<div class="col-md-4">
    <ul>
        <li><a href='{{URL::action('linkSocialProfile', array('network'=>'facebook'))}}'>Link a Facebook account</a></li>
        <li><a href='{{URL::action('linkSocialProfile', array('network'=>'twitter'))}}'>Link a Twitter  account</a></li>
    </ul>
</div>
@stop