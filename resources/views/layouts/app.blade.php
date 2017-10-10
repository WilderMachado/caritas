<!DOCTYPE html>
<html lang="{{ config('app.locale') }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="shortcut icon" href="images/logo_caritas_pequeno.ico" type="image/x-icon" />
    <title> {{ config('app.name', 'Laravel') }}</title>

    <!-- Styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">

</head>
<body>
<div id="app">
    <nav class="navbar navbar-default navbar-static-top">
        <div class="container">

            <div class="navbar-header">

                <!-- Collapsed Hamburger -->
                <button type="button" class="navbar-toggle collapsed" data-toggle="collapse"
                        data-target="#app-navbar-collapse">
                    <span class="sr-only">Toggle Navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>

                </button>

                <!-- Branding Image -->

                <a href="{{ url('/') }}">
                    <img src="images/logo_caritas_micro.jpg" alt="Logo marca da Cáritas"/>
                </a>

            </div>
            <div class="collapse navbar-collapse" id="navbar">
                <!-- Left Side Of Navbar -->
                <ul class="nav navbar-nav">
                    &nbsp;
                    @can('visualizar','caritas\Instituicao')
                    <li><a href="{{route('instituicoes')}}">Instituições</a></li>
                    @endcan
                    @can('visualizar','caritas\Membro')
                    <li><a href="{{route('membros')}}">Membros</a></li>
                    @endcan
                    @can('visualizar','caritas\User')
                    <li><a href="{{route('users')}}">Usuários</a></li>
                    @endcan
                </ul>

                <!-- Right Side Of Navbar -->
                <ul class="nav navbar-nav navbar-right">
                    <!-- Authentication Links -->
                    @if (Auth::guest())
                        <li><a href="{{ route('login') }}">Login</a></li>

                    @else
                        <li class="dropdown">
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button"
                               aria-expanded="false">
                                {{ Auth::user()->name }} <span class="caret"></span>
                            </a>

                            <ul class="dropdown-menu" role="menu">
                                <li>
                                    <a href="{{ route('logout') }}"
                                       onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();">
                                        Logout
                                    </a>

                                    <form id="logout-form" action="{{ route('logout') }}" method="POST"
                                          style="display: none;">
                                        {{ csrf_field() }}
                                    </form>
                                </li>
                            </ul>
                        </li>
                    @endif
                </ul>
            </div>
        </div>
    </nav>
<div class="container">
    @yield('content')
</div>
</div>

<!-- Scripts -->
<script src="{{ asset('js/caritas.js') }}"></script>
<script src="{{ asset('js/app.js') }}"></script>

</body>
</html>
