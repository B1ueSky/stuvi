<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    {{--<title>Laravel</title>--}}

    <link href="{{ asset('/css/app.css') }}"                rel="stylesheet">
    <link href="{{ asset('/css/navigation.css') }}"         rel="stylesheet">
    {{--<link href="{{ asset('/css/footer.css') }}"         rel="stylesheet">--}}
    <link href="{{ asset('css/footer-distributed.css') }}"  rel="stylesheet">   <!-- Footer required -->
    <link href="{{ asset('css/font-awesome.min.css') }}"    rel="stylesheet">   <!-- Footer & Nav required -->
    <link href="{{ asset('css/font-awesome.css') }}"        rel="stylesheet">   <!-- Footer & Nav required -->

    <!-- Fonts -->
    <link href='//fonts.googleapis.com/css?family=Roboto:400,300' rel='stylesheet' type='text/css'>


</head>
<body>

<!-- NAV BAR -->
<nav class="navbar navbar-default" id="nav" role ="navigation">
    <div class="container-fluid">               <!-- Expand to full width -->
        <div class="navbar-header">
            <!-- Toggle Nav into hamburger menu for small screens -->
            <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1">
                <span class="sr-only">Toggle Navigation</span>
                <i class="fa fa-bars fa-lg"></i>
                {{--<span class="icon-bar"></span>--}}
                {{--<span class="icon-bar"></span>--}}
                {{--<span class="icon-bar"></span>--}}
            </button>
            <div class="logo-container">
                <a href="{{url('/home')}}">   <img src="{{asset('/img/stuvi-logo.png')}}" class="img-responsive">  </a>
            </div>
        </div>  <!-- End Navbar header -->

        <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
            <!-- Nav Bar Links-->
            <ul class="nav navbar-nav">
                <li><a href="{{ url('/admin/user') }}" class="" id="textbook-nav">User</a></li>
            </ul>
            <!-- Navbar right -->
            <ul class="nav navbar-nav navbar-right">
                @if (Auth::guest())
                    <li><a href="{{ url('/login') }}">
                            <i class="fa fa-sign-in"></i> Login</a></li>     <!-- added font awesome icons -->
                    <li><a href="{{ url('/register') }}">
                            <i class="fa fa-user"></i> Register</a></li>
                @else
                    <li class="dropdown">
                        <a href="#" class="dropdown-toggle nav-dropdown" data-toggle="dropdown" role="button"
                           aria-expanded="true"><span nav-caret id = "account-name">{{ Auth::user()->first_name }}</span><span class="caret nav-caret"></span></a>
                        <ul class="dropdown-menu" role="menu" aria-labelledby="nav-dropdown">
                            <li role="presentation"><a role="menuitem" tabindex="-1" href="{{ url('/user/profile') }}">
                                    Profile</a></li>
                            <li role="presentation"><a role="menuitem" tabindex="-1" href="{{ url('/user/account') }}">
                                    Your Account</a></li>
                            <li role="presentation"><a role="menuitem" tabindex="-1" href="{{ url('/auth/logout') }}">
                                    Logout</a></li>
                        </ul>
                    </li>
                @endif
            </ul>
        </div>  <!-- End collapse container -->
    </div>  <!-- End navbar container -->
</nav>

<!-- End Nav Bar -->



<!-- Displays page content -->
@yield('content')



</body>

<!--- Scripts at bottom for faster page loading-->

<!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
<!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
<!--[if lt IE 9]>
<script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
<script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
<![endif]-->

<script src="//cdnjs.cloudflare.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>
<script src="//cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/3.3.1/js/bootstrap.min.js"></script>
<script src="{{asset('/js/navigation.js')}}" type="text/javascript"></script>


</html>
