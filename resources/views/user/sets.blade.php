@extends('layouts.user')

@section('main-menu')
    <li><a href="profile">Profile</a></li>
    <li><a href="campaigns">Campaigns</a></li>
    <li class="active"><a href="sets">Sets</a></li>
    <li><a href="advertising">Advertising</a></li>
@endsection

@section('content')

    <div class="section">

        <form id="set_form" class="col s12 m12 l8 xl-8 offset-m2 offset-xl2">

            <div class="row">
                <div class="input-field col s12">
                    <i class="material-icons prefix">chat_bubble</i>
                    <input name="name" type="text" class="validate">
                    <label for="name">Name</label>
                </div>
            </div>

            <div class="row">
                <div class="input-field col s12">
                    <i class="material-icons prefix">chevron_right</i>
                    <input name="start_date" type="text" class="datepicker">
                    <label for="start_date">Start Date</label>
                </div>
            </div>

            <div class="row">
                <div class="input-field col s12">
                    <i class="material-icons prefix">chevron_right</i>
                    <input name="end_date" type="text" class="datepicker">
                    <label for="end_date">End Date</label>
                </div>
            </div>

            <div class="row">
                <div class="input-field col s12">
                    <i class="material-icons prefix">chevron_right</i>
                    <input name="bid_amount" type="text" class="validate">
                    <label for="bid_amount">BID Amount</label>
                </div>
            </div>

            <div class="row">
                <div class="input-field col s12">
                    <i class="material-icons prefix">chevron_right</i>
                    <input name="daily_budget" type="text" class="validate">
                    <label for="daily_budget">Daily Budget</label>
                </div>
            </div>

            <div class="row">
                <div class="input-field col s12">
                    <i class="material-icons prefix">chevron_right</i>

                    <select name="optimization_goal">
                        @foreach($optimizationGoals as $optimizationGoalID => $optimizationGoal)
                            <option value="{{ $optimizationGoalID }}">{{ $optimizationGoal }}</option>
                        @endforeach
                    </select>

                    <label for="optimization_goal">Optimization Goal</label>
                </div>
            </div>

            <div class="row">
                <div class="input-field col s12">
                    <i class="material-icons prefix">chevron_right</i>

                    <select name="billing_event">
                        @foreach($billingEvents as $billingEventID => $billingEvent)
                            <option value="{{ $billingEventID }}">{{ $billingEvent }}</option>
                        @endforeach
                    </select>

                    <label for="billing_event">Billing Event</label>
                </div>
            </div>

            <div class="row">
                <div class="input-field col s12">
                    <i class="material-icons prefix">chevron_right</i>
                    <input name="interest" type="text" class="validate">
                    <label for="name">Interest</label>
                </div>
            </div>

            <div class="row">
                <div class="input-field col s12">
                    <i class="material-icons prefix">chat_bubble_outline</i>

                    <select name="campaign">
                        @foreach($adCampaigns as $adCampaign)
                            <option value="{{ $adCampaign->id }}">{{ $adCampaign->name }}</option>
                        @endforeach
                    </select>

                    <label for="billing_event">Campaign</label>
                </div>
            </div>

            <button class="btn waves-effect waves-light right grey" type="submit" name="action">
                Create Set
            </button>

        </form>

    </div>

    <div class="section">

        <table id="ad_sets" class="highlight">
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
