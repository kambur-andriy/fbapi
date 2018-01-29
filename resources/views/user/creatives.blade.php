@extends('layouts.user')

@section('main-menu')
    <li><a href="profile">Profile</a></li>
    <li><a href="campaigns">Campaigns</a></li>
    <li><a href="sets">Sets</a></li>
    <li class="active"><a href="creatives">Creatives</a></li>
    <li><a href="ads">Ads</a></li>
@endsection

@section('content')

    <div class="section">

        <form id="creative_form" class="col s12 m12 l8 xl-8 offset-m2 offset-xl2">

            <div class="row">
                <div class="input-field col s12">
                    <i class="material-icons prefix">camera_alt</i>
                    <input name="name" type="text" class="validate">
                    <label for="name">Name</label>
                </div>
            </div>

            <div class="row">
                <div class="input-field col s12">
                    <i class="material-icons prefix">chevron_right</i>
                    <input name="page" type="text" class="validate">
                    <label for="page">Page ID</label>
                </div>
            </div>

            <div class="row">
                <div class="input-field col s12">
                    <i class="material-icons prefix">chevron_right</i>
                    <input name="link" type="text" class="validate">
                    <label for="link">Page Link</label>
                </div>
            </div>

            <div class="row">
                <div class="input-field col s12">
                    <i class="material-icons prefix">chevron_right</i>
                    <input name="message" type="text" class="validate">
                    <label for="message">Message</label>
                </div>
            </div>

            <div class="row">
                <div class="file-field input-field col s12">

                    <div class="btn blue">
                        <span>Image</span>
                        <input name="image_file" type="file">
                    </div>

                    <div class="file-path-wrapper">
                        <input class="file-path validate" type="text">
                    </div>
                </div>
            </div>

            <button class="btn waves-effect waves-light right grey" type="submit" name="action">
                Create Creative
            </button>

        </form>

    </div>

    {{--<div class="section">--}}

        {{--<table id="ad_sets" class="highlight">--}}
            {{--<thead>--}}
            {{--<tr>--}}
                {{--<th>Name</th>--}}
                {{--<th>Daily Budget</th>--}}
                {{--<th>Campaign</th>--}}
                {{--<th>Status</th>--}}
            {{--</tr>--}}
            {{--</thead>--}}
            {{--<tbody>--}}
            {{--@foreach($adSets as $adSet)--}}
                {{--<tr id="{{ $adSet->id }}">--}}
                    {{--<td> {{ $adSet->name }} </td>--}}
                    {{--<td> {{ $adSet->daily_budget }} </td>--}}
                    {{--<td> {{ $adCampaigns[$adSet->campaign['id']] }} </td>--}}
                    {{--<td> {{ $adSet->status }} </td>--}}
                {{--</tr>--}}
            {{--@endforeach--}}
            {{--</tbody>--}}
        {{--</table>--}}

    {{--</div>--}}

@endsection
