@extends('layouts.user')

@section('main-menu')
    <li><a href="profile">Profile</a></li>
    <li><a href="campaigns">Campaigns</a></li>
    <li><a href="sets">Sets</a></li>
    <li><a href="creatives">Creatives</a></li>
    <li class="active"><a href="ads">Ads</a></li>
@endsection

@section('content')

    <div class="section">

        <form id="ad_form" class="col s12 m12 l8 xl-8 offset-m2 offset-xl2">

            <div class="row">

                <div class="input-field col s12">
                    <i class="material-icons prefix">chat_bubble_outline</i>

                    <select name="set">
                        @foreach($adSets as $adSet)
                            <option value="{{ $adSet->id }}">{{ $adSet->name }}</option>
                        @endforeach
                    </select>

                    <label for="set">Set</label>
                </div>

            </div>

            <div class="row">

                <div class="input-field col s12">
                    <i class="material-icons prefix">camera_alt</i>

                    <select name="creative">
                        @foreach($adCreatives as $adCreative)
                            <option value="{{ $adCreative->id }}">{{ $adCreative->name }}</option>
                        @endforeach
                    </select>

                    <label for="name">Creative</label>
                </div>

            </div>

            <div class="row">
                <div class="input-field col s12">
                    <i class="material-icons prefix">&#xE060;</i>
                    <input name="name" type="text" class="validate">
                    <label for="name">Name</label>
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
                Create Ad
            </button>

        </form>

    </div>

    <div class="section">

        <table id="ads" class="highlight">
            <thead>
            <tr>
                <th>Name</th>
                <th>Set</th>
                <th>Status</th>
                <th></th>
            </tr>
            </thead>
            <tbody>
            @foreach($ads as $ad)
                <tr id="{{ $ad->id }}">
                    <td> {{ $ad->name }} </td>
                    <td> {{ $ad->adset_id }} </td>
                    <td> {{ $adSet->status }} </td>
                    <td>
                        <a class="btn-floating btn-small waves-effect waves-light red right delete-ad">
                            <i class="material-icons">delete</i>
                        </a>
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>

    </div>

@endsection

@section('scripts')

    <script src="/js/user_ads.js" type="text/javascript"></script>

@endsection
