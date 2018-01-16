@extends('layouts.main')


@section('main')

    <div class="col s12 m12 l12 xl12">

        <ul id="tabs-swipe-demo" class="tabs">
            <li class="tab col s6"><a class="active" href="#user_section">Sign In</a></li>
            <li class="tab col s6"><a href="#admin_section">Manage</a></li>
        </ul>

        <div id="user_section" class="col s12 m12 l8 xl6 offset-l2 offset-xl3 account-action">

            <a href="/account/fb" class="btn fb-button">
                <i class="fa fa-facebook" aria-hidden="true"></i>

                Sign In With Facebook
            </a>

        </div>

        <div id="admin_section" class="col s12 m12 l8 xl-8 offset-l2 offset-xl2 account-action">

            <form id="login_form" class="col s12">

                <div class="row">
                    <div class="input-field col s12">
                        <i class="material-icons prefix">mail_outline</i>
                        <input name="email" type="email" class="validate">
                        <label for="email">Email</label>
                    </div>
                </div>

                <div class="row">
                    <div class="input-field col s12">
                        <i class="material-icons prefix">done</i>
                        <input name="password" type="password" class="validate">
                        <label for="password">Password</label>
                    </div>
                </div>

                <div class="row">

                    <button class="btn waves-effect waves-light right grey" type="submit" name="action">
                        Submit
                    </button>

                </div>

            </form>

        </div>

    </div>

@endsection