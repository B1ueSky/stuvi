<!-- Prototype Homepage Copyright Stuvi 2015
     Made by Nick                            -->

@extends('app')    <!-- app.blade.php -->
<head>
    <link type="text/css" href="{{ asset('css/home.css') }}" rel="stylesheet" >               <!-- Home style sheet -->

    <!-- Content Info -->
    <title>Stuvi Home - Student Village - Textbooks, Housing, Clubs, & More </title>
    <meta name="description" content="Student Village, college service provider">
    <meta name="author" content="Stuvi">
</head>

@section('content')


    <div class = "container-fluid container-top backgnd">           <!-- Top half -->
        <div class="container col-md-12">                               <!-- container -->
            <div class = "col-md-2"></div>                                  <!-- Buffer -->
            <div class="jumbotron col-md-8" id = "jumbotron1">              <!-- Jumbotron1 -->
                <h1 id = "head1" >Get ready for Stuvi's Launch Party!</h1>
                <p id = "p1">
                    Stuvi is designed to provide convenient, and valuable services to maximize a college student’s campus
                    life experience. In order to ensure our users a streamlined, and self-sufficient college life,
                    we're dedicated to create a "Student Village" where we can learn, share and grow. Whether you're
                    new to the area, or a die-hard local, you're going to need course information, professor
                    ratings, discount textbooks, and a living space. Our goal is to provide you, the student, with all the
                    tools you need to conquer college.
                    <br/><br/>
                    We are a group of college students currently based in Boston, MA and hope to use our experience
                    to help you succeed at school.
                    <b><i> Welcome to Stuvi. Welcome home.</i></b>
                </p> <!-- end p1 -->

                <p id = "liftoff"> Liftoff on: August 2015</p>
                @if (Auth::guest())
                    <div class=" home-btn">
                        <a class="btn ghost-btn" href="{{ url('/login') }}" role="button">LOGIN</a>
                        <a class="btn ghost-btn" href="{{ url('/register') }}" role="button">SIGN UP</a>
                    </div>
                @endif
            </div> <!-- end jumbotron1 -->
        </div>    <!-- end container -->
    </div>    <!-- end contain-top backgnd -->
   <!-- End Top Half
         Begin Bottom Half-->
{{--

    <div class = "container-fluid" id = "bottom-half">   <!-- Bottom Half -->
        <div class = "container">                           <!-- container -->
            <h2 id= "head2"> Our Services </h2>

            <!-- Divider Line -->
            <div class = "row">
                <div class = "span12" id = "hr-style-one">
                    <hr>
                </div>
            </div>
            <!-- end divider -->
            <!-- Columns stack when xs -->
            <div class = "container" id = "servicesTable">                         <!-- begin table -->
                <!-- row 1 -->
                <div class = "row">
                    <!-- row 1 column 1 -->
                    <div class = "col-xs-8 col-xs-offset-2 col-sm-4 col-sm-offset-1 col-md-4 col-md-offset-1 col-buff" >                                       <!-- r1c1 -->
                        <img src = "http://placehold.it/350x350" class="img-responsive">
                    </div>  <!-- end r1c1-->

                    <div class = "col-xs-8 col-xs-offset-2 col-sm-4 col-sm-offset-1 col-md-4 col-md-offset-2 col-buff">                      <!-- r1c2 -->
                        <img src = "http://placehold.it/350x350" class="img-responsive">
                    </div>  <!-- end r1c2 -->
                </div>  <!-- end row1 -->

                <!-- row 2 -->
                <div class = "row">
                    <div class = "col-xs-8 col-xs-offset-2 col-sm-4 col-sm-offset-1 col-md-4 col-md-offset-1 col-buff" >                                       <!-- r1c1 -->
                        <img src = "http://placehold.it/350x350" class="img-responsive">
                    </div>  <!-- end r2c1-->

                    <div class = "col-xs-8 col-xs-offset-2 col-sm-4 col-sm-offset-1 col-md-4 col-md-offset-2 col-buff">                      <!-- r1c2 -->
                        <img src = "http://placehold.it/350x350" class="img-responsive">
                    </div>  <!-- end r2c2 -->
                </div>  <!-- end row2 -->
            </div>   <!-- end container for table -->
        </div>    <!-- end container for hr and table -->
    </div>   <!-- end container bottom-half -->

--}}

  <!-- new bottom half -->
    <div class="container-fluid" id="bottom-half">
        <div class="container col-sm-10 col-sm-offset-1 features">
            <h2 id= "head2"> Our Services </h2>

            <!-- Divider Line -->
            <div class = "row">
                <div class = "span12" id = "hr-style-one">
                    <hr>
                </div>
            </div>

            <div class="row services" id="service1">
                <img class="img-responsive img-service col-xs-12 col-md-8 col-md-offset-2" src="http://placehold.it/500x200"> <br>

                <p class="p-services col-xs-12 col-md-10 col-md-offset-1"> Lorem ipsum dolor sit amet, consectetur adipiscing elit. Fusce ac metus nisi.
                Praesent ac magna nunc. Nullam in imperdiet elit. In eu pellentesque tellus.
                Praesent consequat ultricies tellus, ut scelerisque tortor dictum at.
                Nullam ornare libero sed est consectetur tincidunt. Nam metus libero, ultrices a tempus in,
                posuere auctor enim. Proin eu mauris quis lectus semper viverra. Fusce accumsan nulla accumsan
                interdum convallis. Sed venenatis mauris metus, facilisis faucibus eros vestibulum ac. Phasellus
                placerat lectus porttitor aliquam commodo.
                </p> <br>
            </div>

            <div class="row services" id="service2">
                <img class="img-responsive img-service col-xs-12 col-md-8 col-md-offset-2" src="http://placehold.it/500x200"> <br>

                <p class="p-services col-xs-12 col-md-10 col-md-offset-1">  In at lacus augue. Ut efficitur turpis nec auctor consequat.
                    Donec lacinia leo ut sapien vehicula pellentesque. Etiam porta vulputate felis a venenatis.
                    Praesent quis porttitor nisi, ut eleifend orci. Aliquam aliquet tincidunt risus quis porta.
                    Fusce non auctor ante. Sed ultricies urna sit amet risus convallis, quis congue neque varius.
                    Nam a mollis neque.
                </p> <br>
            </div>

        </div>




    </div>



@endsection
