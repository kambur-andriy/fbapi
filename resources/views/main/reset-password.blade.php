@extends('layouts.main')


@section('main')

    <div class="col s12 m12 l12 xl12">
        @if(!empty($resetToken))

            <ul id="tabs-swipe-demo" class="tabs">
                <li class="tab col s12"><a class="active" href="#login_section">Reset password</a></li>
            </ul>

            <div id="reset_section" class="col s12 m12 l8 xl-8 offset-m2 offset-xl2 account-action">

                <form id="reset_password_form" class="col s12">
                    <div class="row">
                        Enter new password
                    </div>

                    <input type="hidden" name="reset_token" value="{{ $resetToken }}" />

                    <div class="row">
                        <div class="input-field col s12">
                            <i class="material-icons prefix">done</i>
                            <input name="password" type="password" />
                            <label for="password">Password</label>
                        </div>
                    </div>

                    <div class="row">
                        <div class="input-field col s12">
                            <i class="material-icons prefix">done_all</i>
                            <input name="password_confirmation" type="password" />
                            <label for="password">Confirm Password</label>
                        </div>
                    </div>

                    <button class="btn waves-effect waves-light right grey" type="submit" name="action">
                        Submit
                    </button>

                </form>

            </div>

        @else

            <ul id="tabs-swipe-demo" class="tabs">
                <li class="tab col s12"><a class="active" href="#password_section">Password</a></li>
            </ul>

            <div id="password_section" class="col s12 m12 l8 xl-8 offset-m2 offset-xl2 account-action">

                <form id="password_form" class="col s12">
                    <div class="row">
                        Reset token incorrect. Please try again.
                    </div>

                    <div class="row">
                        <div class="input-field col s12">
                            <i class="material-icons prefix">mail</i>
                            <input name="email" type="email" class="validate">
                            <label for="email">Email</label>
                        </div>
                    </div>

                    <button class="btn waves-effect waves-light right grey" type="submit" name="action">
                        Submit
                    </button>

                </form>

            </div>

        @endif

    </div>

@endsection