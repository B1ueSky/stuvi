<!DOCTYPE html>
<html lang="en">

@include('includes.textbook.head')

<body>

<div class="container-wrapper">
    @section('textbook-header')
        {{-- Nav bar --}}
        @include('includes.textbook.header')
    @show

    {{-- Session flash messages --}}
    @include('includes.alerts')

    {{-- Page content --}}
    @yield('content')
</div>

{{--loader shade--}}
{{--@include('includes.loader')--}}
@include('includes.textbook.footer')

@include('auth.login-signup-modal')

{{-- Page modals --}}
@yield('modals')


<!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
<!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
<!--[if lt IE 9]>
<script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
<script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
<![endif]-->

<script src="{{ asset('libs/jquery/dist/jquery.min.js') }}"></script>
<script src="{{ asset('libs/bootstrap/dist/js/bootstrap.min.js') }}"></script>
<script src="{{ asset('libs/bootstrap-select/dist/js/bootstrap-select.min.js') }}"></script>
<script src="{{ asset('libs/bootstrap-hover-dropdown/bootstrap-hover-dropdown.min.js') }}"></script>

@if(Auth::guest())
    {{-- FormValidation --}}
    <script src="{{asset('libs-paid/formvalidation-dist-v0.6.3/dist/js/formValidation.min.js')}}"></script>
    <script src="{{asset('libs-paid/formvalidation-dist-v0.6.3/dist/js/framework/bootstrap.min.js')}}"></script>
    <script src="{{asset('js/auth/login.js')}}"></script>
@endif

<script src="{{ asset('js/alert.js') }}"></script>

@if(\App::environment('production'))
    <script src="{{ asset('js/googleanalytics.js') }}"></script>
@endif

@yield('javascript')
</body>

</html>
