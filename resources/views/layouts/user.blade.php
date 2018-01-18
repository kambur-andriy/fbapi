<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="x-ua-compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Facebook Advertising API - User Page</title>

    <link rel="shortcut icon" href="favicon.ico" type="image/x-icon">
    <link rel="icon" href="favicon.ico" type="image/x-icon">

    <link rel="stylesheet" href="/css/materialize.min.css">
    <link rel="stylesheet" href="/css/application.css">
</head>
<body>

<nav>
    <div class="nav-wrapper blue">
        <a href="/" class="brand-logo">Slash Digital</a>

        <ul id="nav-mobile" class="right hide-on-med-and-down">
            @yield('main-menu')

            <li><a href="" id="logout-btn"><i class="material-icons">directions_run</i></a></li>
        </ul>
    </div>
</nav>

<div class="container">

    <section class="row">

        @yield('content')

    </section>

</div>


<!-- JS scripts -->
<script src="/js/user.js" type="text/javascript"></script>
<script src="/js/materialize.min.js" type="text/javascript"></script>

</body>
</html>