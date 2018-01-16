@extends('layouts.user')

@section('main-menu')
    <li class="active"><a href="profile">Profile</a></li>
    <li><a href="companies">Companies</a></li>
    <li><a href="sets">Sets</a></li>
    <li><a href="advertising">Advertising</a></li>
@endsection

@section('content')

    <div class="section">

        <form id="adv_account_form" class="col s12 m12 l8 xl-8 offset-m2 offset-xl2">

            <div class="row">
                <div class="input-field col s12">
                    <i class="material-icons prefix">assignment</i>
                    <input name="account_id" type="text" class="validate" value="{{ $adAccount->account_id }}">
                    <label for="account_id">Account ID</label>
                </div>
            </div>

            <button class="btn waves-effect waves-light right grey" type="submit" name="action">
                Save Account ID
            </button>

        </form>

    </div>

    <div class="section">

        <form id="profile_form" class="col s12 m12 l8 xl-8 offset-m2 offset-xl2">

            <div class="row">
                <div class="input-field col s12">
                    <i class="material-icons prefix">face</i>
                    <input name="first_name" type="text" class="validate" value="{{ $profile->first_name }}">
                    <label for="first_name">First Name</label>
                </div>
            </div>

            <div class="row">
                <div class="input-field col s12">
                    <i class="material-icons prefix">face</i>
                    <input name="last_name" type="text" class="validate" value="{{ $profile->last_name }}">
                    <label for="last_name">Last Name</label>
                </div>
            </div>

            <button class="btn waves-effect waves-light right grey" type="submit" name="action">
                Save Profile
            </button>

        </form>

    </div>

@endsection
