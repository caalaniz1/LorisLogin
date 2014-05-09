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

        <div class="navbar-static-top navbar-inverse" role="navigation">
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
                        @if(Auth::guest())
                        <li><a href="{{URL::route("login-user")}}">Login</a></li>
                        <li><a href="{{URL::route("register-user")}}">Register</a></li>
                        @elseif(Auth::check())
                        <li><a href="{{URL::route("dashboard")}}">Dashboard</a></li>
                        <li><a href="{{URL::route("edit-profile")}}">Edit Profile</a></li>
                        <li><a href="{{URL::route("logout-user")}}">Logout</a></li>
                        <form action="{{URL::route("addProvider")}}" method="GET"class="navbar-form navbar-left right" role="network">
                            <div class="form-group">
                                <input name = "network" type="radio" value="facebook">Facebook
                                <input name = "network" type="radio" value="twitter">Twitter
                            </div>
                            <button type="submit" class="btn btn-default">Add Social Profile</button>
                        </form>
                        @endif
                    </ul>
                </div><!--/.nav-collapse -->
            </div>        

        </div>
        @if($errors->has('error'))
        <div class="alert alert-warning">
            @foreach($errors->get('error') as $error)
            <p><span class="glyphicon glyphicon-remove" style="color: #C52F24"></span>{{$error}}</p>
            @endforeach

        </div>
        @endif
        <div class="container">
            @yield('content')
            {{isset($content)?$content:NULL}}
        </div><!-- /.container -->

        <!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.0/jquery.min.js"></script>
        <!-- Include all compiled plugins (below), or include individual files as needed -->
        <script src="{{ URL::asset('/packages/red-panda/loris-login/js/bootstrap.min.js') }}"></script>
    </body>
</html>