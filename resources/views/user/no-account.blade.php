@extends('layouts.user')

@section('main-menu')
    <li><a href="profile">Profile</a></li>
    <li><a href="campaigns">Campaigns</a></li>
    <li><a href="sets">Sets</a></li>
    <li><a href="creatives">Creatives</a></li>
    <li><a href="ads">Ads</a></li>
@endsection

@section('content')

    <div class="section">

        Your advertising account is empty. <a href="profile"> Edit Account </a>

    </div>

@endsection
