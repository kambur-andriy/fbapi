<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="x-ua-compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Facebook Advertising API</title>

    <link rel="shortcut icon" href="favicon.ico" type="image/x-icon">
    <link rel="icon" href="favicon.ico" type="image/x-icon">

    <link rel="stylesheet" href="/css/materialize.min.css">
    <link rel="stylesheet" href="/css/application.css">
</head>
<body>

<div class="parallax-container">
    <div class="parallax"><img src="/images/paralax.jpg"></div>
</div>

<div class="container">

    <section class="row account-section">

        @yield('main')

    </section>

</div>

<footer class="page-footer grey lighten-2">

    <div class="container">
        <div class="row">
            <div class="col s12 m12 l8 xl6">
                <h5 class="white-text">Contact us</h5>

                <form id="contact_form" class="col s12">

                    <div class="row">
                        <div class="input-field col s12">
                            <i class="material-icons prefix">account_box</i>
                            <input name="name" class="contact_name" type="text" />
                            <label for="name">Name</label>
                        </div>
                    </div>

                    <div class="row">
                        <div class="input-field col s12">
                            <i class="material-icons prefix">mail_outline</i>
                            <input name="email" type="email" class="contact_email" />
                            <label for="email">Email</label>
                        </div>
                    </div>


                    <div class="row">
                        <div class="input-field col s12">
                            <i class="material-icons prefix">description</i>
                            <textarea name="message" class="materialize-textarea contact_message"></textarea>
                            <label for="message">Message</label>
                        </div>
                    </div>

                    <button class="btn waves-effect waves-light grey" type="submit" name="action">
                        Send
                    </button>

                </form>

            </div>
        </div>
    </div>

    <div class="footer-copyright">
        <div class="container">
            © 2018 Slash Digital
        </div>
    </div>

</footer>


<!-- JS scripts -->
<script src="/js/main.js" type="text/javascript"></script>
<script src="/js/materialize.min.js" type="text/javascript"></script>

</body>
</html>