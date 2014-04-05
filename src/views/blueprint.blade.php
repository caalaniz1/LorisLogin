<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Slow Loris User</title>

        <!-- Bootstrap -->

        <link href="{{ URL::asset('/packages/red-panda/loris-login/css/bootstrap.min.css') }}" rel="stylesheet">

        <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
        <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
        <!--[if lt IE 9]>
          <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
          <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
        <![endif]-->
    </head>
    <body>

        <div class="navbar navbar-inverse" role="navigation">
            <div class="container">
                <div class="navbar-header">
                    <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
                        <span class="sr-only">Toggle navigation</span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                    </button>
                    <a class="navbar-brand" href="#">Slow Loris Login</a>
                </div>
                <div class="collapse navbar-collapse">
                    <ul class="nav navbar-nav">
                        @if(Auth::check())
                        <li><a href="{{URL::route('logout')}}">Logout</a></li>
                        <li><a href="{{URL::route('admin-user-landing')}}">Profile</a></li>
                        @else
                        <li><a href="{{URL::route('login')}}">Login</a></li>
                        <li><a href="{{URL::route('signup')}}">Signup</a></li>
                        @endif
                        <li><a href="{{URL::route('landing')}}">About</a></li>
                    </ul>
                </div><!--/.nav-collapse -->
            </div>
        </div>
        <div>
            @if(Session::has('message'))
            @if(Session::has('success'))
            <div class="alert alert-success">
                <span class="glyphicon glyphicon-check" style="color: #030"></span>
                @else
                <div class="alert alert-warning">
                    <span class="glyphicon glyphicon-warning-sign" style="color: #EE3322"></span>
                    @endif
                    {{Session::get('message')}}
                </div>
                @endif
            </div>
        </div>

        <div class="container">
            @yield('content')
        </div><!-- /.container -->

        <!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.0/jquery.min.js"></script>
        <!-- Include all compiled plugins (below), or include individual files as needed -->
        <script src="{{ URL::asset('/packages/red-panda/loris-login/js/bootstrap.min.js') }}"></script>
    </body>
</html>