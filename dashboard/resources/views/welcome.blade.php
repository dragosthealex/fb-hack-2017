<!DOCTYPE html>
<html lang="en">
  <head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <!-- Meta, title, CSS, favicons, etc. -->
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title><?=config('app.name')?></title>
    <link href="vendors/bootstrap/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="vendors/font-awesome/css/font-awesome.min.css" rel="stylesheet">
    <link href="vendors/nprogress/nprogress.css" rel="stylesheet">
    <link href="vendors/iCheck/skins/flat/green.css" rel="stylesheet">
    <link href="vendors/bootstrap-progressbar/css/bootstrap-progressbar-3.3.4.min.css" rel="stylesheet">
    <link href="vendors/jqvmap/dist/jqvmap.min.css" rel="stylesheet"/>
    <link href="vendors/google-code-prettify/bin/prettify.min.css" rel="stylesheet">
    <link href="vendors/select2/dist/css/select2.min.css" rel="stylesheet">
    <link href="vendors/switchery/dist/switchery.min.css" rel="stylesheet">
    <link href="vendors/starrr/dist/starrr.css" rel="stylesheet">
    <link href="vendors/bootstrap-daterangepicker/daterangepicker.css" rel="stylesheet">
    <link href="build/css/custom.min.css" rel="stylesheet">
    <script>
        window.Laravel = {!! json_encode([
            'csrfToken' => csrf_token(),
        ]) !!};
    </script>
  </head>

  <body class="nav-md">
    <div class="container body">
<!-- top navigation -->
<div class="top_nav">
  <div class="nav_menu">
    <nav>
      <div class="nav toggle">
      </div>
      <ul class="nav navbar-nav navbar-right">
        <li class="">
          <a href="{{ url('/login') }}" class="user-profile">
            Login with Facebook
          </a>
        </li>
      </ul>
    </nav>
  </div>
</div>
<div class="container">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default">
                <div class="panel-heading">Dashboard</div>

                <div class="panel-body">
                    You are logged out!
                </div>
            </div>
        </div>
    </div>
</div>
</body>
</html>