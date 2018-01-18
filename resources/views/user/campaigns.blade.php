@extends('layouts.user')

@section('main-menu')
    <li><a href="profile">Profile</a></li>
    <li class="active"><a href="campaigns">Campaigns</a></li>
    <li><a href="sets">Sets</a></li>
    <li><a href="advertising">Advertising</a></li>
@endsection

@section('content')

    <div class="section">

        <form id="company_form" class="col s12 m12 l8 xl-8 offset-m2 offset-xl2">

            <div class="row">
                <div class="input-field col s12">
                    <i class="material-icons prefix">chat_bubble_outline</i>
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

            <button class="btn waves-effect waves-light right grey" type="submit" name="action">
                Create Campaign
            </button>

        </form>

    </div>

    <div class="section">

        <table id="ad_companies" class="highlight">
            <thead>
            <tr>
                <th>Name</th>
                <th>Objective</th>
                <th>Status</th>
            </tr>
            </thead>
            <tbody>
            @foreach($adCampaigns as $adCampaign)
                <tr id="{{ $adCampaign->id }}">
                    <td> {{ $adCampaign->name }} </td>
                    <td> {{ $adCampaign->objective }} </td>
                    <td> {{ $adCampaign->status }} </td>
                </tr>
            @endforeach
            </tbody>
        </table>

    </div>

@endsection