@extends('layouts.user')

@section('main-menu')
    <li><a href="profile">Profile</a></li>
    <li><a href="campaigns">Campaigns</a></li>
    <li class="active"><a href="sets">Sets</a></li>
    <li><a href="creatives">Creatives</a></li>
    <li><a href="ads">Ads</a></li>
@endsection

@section('content')

    <div class="section">

        <form id="set_form" class="col s12 m12 l12 xl12">
            <div class="row">

                <div class="input-field col s12 m12 l12 xl12">
                    <i class="material-icons prefix">chat_bubble_outline</i>

                    <select name="campaign">
                        @foreach($adCampaigns as $adCampaign)
                            <option value="{{ $adCampaign->id }}">{{ $adCampaign->name }}</option>
                        @endforeach
                    </select>

                    <label for="billing_event">Campaign</label>
                </div>

            </div>

            <div class="row">

                <div class="col s12 m12 l6 xl6">

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

                            <select name="status">
                                @foreach($statuses as $statusID => $statusName)
                                    <option value="{{ $statusID }}">{{ $statusName }}</option>
                                @endforeach
                            </select>

                            <label for="status">Status</label>
                        </div>
                    </div>

                </div>


                <div class="col s12 m12 l6 xl6">

                    <div class="row">
                        <div class="input-field col s12">
                            <i class="material-icons prefix">gps_fixed</i>
                            <input name="interest" type="text" class="validate">
                            <label for="name">Targeting Interest</label>
                        </div>
                    </div>

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
                <th>Daily Budget</th>
                <th>Campaign</th>
                <th>Status</th>
                <th></th>
            </tr>
            </thead>
            <tbody>
            @foreach($adSets as $adSet)
                <tr id="{{ $adSet->id }}">
                    <td> {{ $adSet->name }} </td>
                    <td> {{ $adSet->daily_budget }} </td>
                    <td> {{ $adCampaigns[$adSet->campaign['id']] }} </td>
                    <td> {{ $adSet->status }} </td>
                    <td>
                        <a class="btn-floating btn-small waves-effect waves-light red right delete-set">
                            <i class="material-icons">delete</i>
                        </a>
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>

    </div>

@endsection
