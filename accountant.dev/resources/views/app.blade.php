<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
        <title>Accountant</title>

        <link href="{{ asset('/css/app.css') }}" rel="stylesheet">
        
        <!-- Latest compiled and minified CSS -->
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.4/css/bootstrap.min.css">

        <!-- Optional theme -->
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.4/css/bootstrap-theme.min.css">

        <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
        <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
        <!--[if lt IE 9]>
          <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
          <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
        <![endif]-->
    </head>
    <body>
        <input type="hidden" id="site_url" name="site_url" value="{{ url('') }}" />
        <div class="container">
            <nav class="navbar navbar-inverse navbar-fixed-top">
                <div class="container-fluid">
                    <div class="navbar-header">
                        <a href="{{ url('') }}" class="navbar-brand">Accountant App</a>
                    </div>
                    @if (!Auth::guest())
                    <ul class="nav navbar-nav">
                        <li @if($selected_page =='user_kveds') class="active" @endif><a href="{{ url('pages/user-kveds') }}">User Kveds</a></li>
                    </ul>
                    <ul class="nav navbar-nav">
                        <li @if($selected_page =='kveds') class="active" @endif><a href="{{ url('pages/kved-list') }}">Kved List</a></li>
                    </ul>
                    @endif
                    <div class="navbar-collapse collapse" id="navbar">
                        <ul class="nav navbar-nav navbar-right">
                            @if (Auth::guest())
                            <li @if($selected_page =='login') class="active" @endif><a href="{{ url('auth/login') }}">Login</a></li>
                            <li @if($selected_page =='register') class="active" @endif><a href="{{ url('auth/register') }}">Register</a></li>
                            @else
                            <li><a href="{{ url('auth/logout') }}">Log Out</a></li>
                            @endif
                        </ul>
                    </div>
                </div>
            </nav>
            <div role="main" class="container theme-showcase" style='margin-top: 60px;'>
                <div>
                    @yield('content')
                </div>
            </div>
        </div>
        <!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
        <!-- Latest compiled and minified JavaScript -->
        <script src="//cdnjs.cloudflare.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.4/js/bootstrap.min.js"></script>
         @yield('footer')
    </body>
</html>