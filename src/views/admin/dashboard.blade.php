<?php
/**
 * Login Form
 * 
 * @uses Twitter Bootstrap Library Css FrameWork
 * @requires User Object
 * @author Carlos A. Alaniz <carlos@redpandadev.com>
 */
//Route to post to
$form_route = "test";
$social_action_route = "test";

if (!Auth::check()) {
    return NULL;
}

$user = Auth::user();
$localProfile = $user->localProfile()->getResults();
?>



<div class="row">
    <div class="col-md-6">
        @if($localProfile)
        <h2>Local Profile</h2>
        <div class='row'>
            <div class='col-sm-4'>
                <p><img style='width: 100%' src='{{$localProfile->photo_url}}'
                        alt='{{$localProfile->first_name}} {{$localProfile->last_name}}'></p>
            </div>
            <div class='col-xs-8'>
                <p><span style="font-weight: bold">Name: </span>{{$localProfile->first_name}} {{$localProfile->last_name}}</p>
                <p><span style="font-weight: bold">Gender: </span>{{$localProfile->gender}}</p>
                <p><span style="font-weight: bold">Address: </span>{{$localProfile->address}} {{$localProfile->city}}
                    {{$localProfile->zip}}
                </p>
                <p><span style="font-weight: bold">E-mail: </span>{{$localProfile->email}}</p>
                <p><span style="font-weight: bold">Date of BIrth:</span>{{$localProfile->date_of_birth}}</p>
            </div>
        </div>
        <div class='row'>
            <div class='col-sm-12'>
                <span style="font-weight: bold">Bio </span>
                <p>{{$localProfile->description}}</p>
            </div>
        </div>
        @else
        You don't have a Local Profile yet <a href="{{URL::route("edit-profile")}}">Fill it out here</a>
        @endif
    </div>
    <div class="col-sm-6">
        <div class='row'>
            <h2>Registered Social Service Providers</h2>
            @foreach($user->socialProfiles()->getResults() as $as)
            <div class="col-sm-12">{{$as->provider}} 
                <a href="{{route('tests')}}?p={{$as->provider}}">
                    <button class="btn btn-default">Test</button></a>
            </div>
            @endforeach
        </div>
    </div>
    <div class="col-sm-4">
    </div>
</div>

