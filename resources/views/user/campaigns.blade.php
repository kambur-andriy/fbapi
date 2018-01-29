@extends('layouts.user')

@section('main-menu')
    <li><a href="profile">Profile</a></li>
    <li class="active"><a href="campaigns">Campaigns</a></li>
    <li><a href="sets">Sets</a></li>
    <li><a href="creatives">Creatives</a></li>
    <li><a href="ads">Ads</a></li>
@endsection

@section('content')

    <div class="section">

        <form id="campaign_form" class="col s12 m12 l8 xl-8 offset-m2 offset-xl2">

            <div class="row">
                <div class="input-field col s12">
                    <i class="material-icons prefix">folder_open</i>
                    <input name="name" type="text" class="validate">
                    <label for="name">Name</label>
                </div>
            </div>


            <div class="row">
                <div class="input-field col s12">
                    <i class="material-icons prefix">chevron_right</i>

                    <select name="objective">
                        @foreach($objectives as $objectiveID => $objectiveName)
                            <option value="{{ $objectiveID }}">{{ $objectiveName }}</option>
                        @endforeach
                    </select>

                    <label for="objective">Objective</label>
                </div>
            </div>

            <div class="row">
                <div class="input-field col s12">
                    <i class="material-icons prefix">chevron_right</i>

                    <select name="status">
                        @foreach($statuses as $statusID => $statusName)
                            <option value="{{ $statusID }}">{{ $statusName }}</option>
                        @endforeach
                    </select>

                    <label for="status">Status</label>
                </div>
            </div>

            <button class="btn waves-effect waves-light right grey" type="submit" name="action">
                Create Campaign
            </button>

        </form>

    </div>

    <div class="section">

        <table id="ad_campaigns" class="highlight">
            <thead>
            <tr>
                <th>Name</th>
                <th>Objective</th>
                <th>Status</th>
                <th></th>
            </tr>
            </thead>
            <tbody>
            @foreach($adCampaigns as $adCampaign)
                <tr id="{{ $adCampaign->id }}">
                    <td> {{ $adCampaign->name }} </td>
                    <td> {{ $adCampaign->objective }} </td>
                    <td> {{ $adCampaign->status }} </td>
                    <td>
                        <a class="btn-floating btn-small waves-effect waves-light red right delete-campaign">
                            <i class="material-icons">delete</i>
                        </a>
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>

    </div>

@endsection