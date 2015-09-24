<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Stuvi - @yield('title')</title>

    <link rel="icon" type="image/ico" href="https://s3.amazonaws.com/stuvi-logo/favicon.ico"/>
    <link rel="stylesheet" href="{{ asset('build/css/textbook.css') }}">

    @yield('css')
</head>

<body>
    {{-- Session flash messages --}}
    @include('includes.alerts')

    @yield('content')

    <script src="{{ asset('build/js/textbook.js') }}"></script>
</body>
</html>