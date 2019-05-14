<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'KDS') }}</title>

    <!-- Scripts -->
    <script src="{{ asset('js/app.js') }}"></script>
    <script src="{{ asset('js/custom.js') }}"></script>
    

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet" type="text/css">
    <link href="https://fonts.googleapis.com/css?family=Poppins:400,600,700,800" rel="stylesheet">
    
    <!-- Font Awesome JS -->
    <script defer src="https://use.fontawesome.com/releases/v5.0.13/js/solid.js" integrity="sha384-tzzSw1/Vo+0N5UhStP3bvwWPq+uvzCMfrN1fEFe+xBmv1C/AtVX5K0uZtmcHitFZ" crossorigin="anonymous"></script>
    <script defer src="https://use.fontawesome.com/releases/v5.0.13/js/fontawesome.js" integrity="sha384-6OIrr52G08NpOFSZdxxz1xdNSndlD4vdcf/q2myIUVO0VsqaGHJsB0RaBE01VTOY" crossorigin="anonymous"></script>
    
    <!-- Styles -->
    <link href="{{ asset('css/base.css') }}" rel="stylesheet">
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <link href="{{ asset('css/custom.css') }}" rel="stylesheet">
    

</head>
<body>
    <div id="app">
        <header>
            <nav class="navbar navbar-expand-lg navbar-light bg-light">
                <div class="container-fluid">
                    <a class="navbar-brand" href="/"><img src="/images/resources/lacouture-new-logo-ft.png" alt="Lacouture Seguros"></a>
                    @auth
                        <button type="button" id="sidebarCollapse" class="btn btn-info">
                            <i class="fas fa-align-left"></i>
                        </button>
                    @endauth
                    @guest
                        <ul class="navbar-nav ml-auto">
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('login') }}">{{ __('Login') }}</a>
                            </li>
                        </ul>
                    @else
                        <ul class="navbar-nav ml-auto">
                            <li class="nav-item dropdown">
                                <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                                    {{ Auth::user()->name }} <span class="caret"></span>
                                </a>

                                <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">
                                    <a class="dropdown-item" href="{{ route('logout') }}"
                                    onclick="event.preventDefault();
                                                    document.getElementById('logout-form').submit();">
                                        {{ __('Logout') }}
                                    </a>

                                    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                        @csrf
                                    </form>
                                </div>
                            </li>
                        </ul>
                    @endguest
                </div>
            </nav>
        </header>
        <div class="wrapper">
            @auth
                <nav id="sidebar">
                    <ul class="list-unstyled components">
                            <li class="active">
                                <a href="/home">
                                    <i class="fas fa-home"></i>
                                    Inicio
                                </a>
                            </li>
                            <li>
                                <a href="#searchSubmenu" data-toggle="collapse" aria-expanded="false" class="dropdown-toggle">
                                    <i class="fas fa-search"></i>
                                    Buscar
                                </a>
                                <ul class="collapse list-unstyled" id="searchSubmenu">
                                    <li>
                                        <a href="{{ url('/items') }}">Pólizas</a>
                                    </li>
                                    <li>
                                        <a href="{{ url('/iusers') }}">Cliente</a>
                                    </li>
                                    <li>
                                        <a href="{{ url('/plate') }}">Automovil</a>
                                    </li>
                                </ul>
                            </li>
                            <li>
                                <a href="#itemSubmenu" data-toggle="collapse" aria-expanded="false" class="dropdown-toggle">
                                    <i class="fas fa-file-powerpoint"></i>
                                    Pólizas
                                </a>
                                <ul class="collapse list-unstyled" id="itemSubmenu">
                                    <li>
                                        <a href="{{ url('/item/create') }}">Crear Póliza</a>
                                    </li>
                                </ul>
                            </li>
                            <li>
                                <a href="#reportsSubmenu" data-toggle="collapse" aria-expanded="false" class="dropdown-toggle">
                                    <i class="fas fa-search"></i>
                                    Reportes
                                </a>
                                <ul class="collapse list-unstyled" id="reportsSubmenu">
                                    <li>
                                        <a href="{{ url('iusers/showexpiredsarlaft') }}">Reporte Sarlaft Vencidos</a>
                                    </li>
                                    @if (auth()->user()->isAdmin())
                                        <li>
                                            <a href="{{ url('admin/records') }}">Reporte Actividad</a>
                                        </li>
                                    @endif
                                </ul>
                            </li>
                        @if (auth()->user()->isAdmin())
                            <li>
                                <a href="#createSubmenu" data-toggle="collapse" aria-expanded="false" class="dropdown-toggle">
                                    <i class="fas fa-plus-square"></i>
                                    Creación de Elementos
                                </a>
                                <ul class="collapse list-unstyled" id="createSubmenu">
                                    <li>
                                        <a href="{{ url('/admin/company/create') }}">Aseguradora</a>
                                    </li>
                                    <li>
                                        <a href="{{ url('/admin/bs/create') }}">Estado de Negocio</a>
                                    </li>
                                    <li>
                                        <a href="{{ url('/admin/branch/create') }}">Ramo</a>
                                    </li>
                                    <li>
                                        <a href="{{ url('/admin/responsible/create') }}">Responsable</a>
                                    </li>
                                    <li>
                                        <a href="{{ url('/admin/iusertype/create') }}">Tipo de Cliente</a>
                                    </li>
                                    <li>
                                        <a href="{{ url('/register') }}">Usuario</a>
                                    </li>
                                </ul>
                            </li>
                            <li>
                                <a href="#maintSubmenu" data-toggle="collapse" aria-expanded="false" class="dropdown-toggle">
                                    <i class="fas fa-edit"></i>
                                    Modificar Elementos
                                </a>
                                <ul class="collapse list-unstyled" id="maintSubmenu">
                                    <li>
                                        <a href="{{ url('admin/companies') }}">Aseguradoras</a>
                                    </li>
                                    <li>
                                        <a href="{{ url('admin/bs') }}">Estados de Negocio</a>
                                    </li>
                                    <li>
                                        <a href="{{ url('admin/branches') }}">Ramos</a>
                                    </li>
                                    <li>
                                        <a href="{{ url('admin/responsibles') }}">Responsables</a>
                                    </li>
                                    <li>
                                        <a href="{{ url('admin/iusertypes') }}">Tipos de Cliente</a>
                                    </li>
                                    <li>
                                        <a href="{{ url('admin/users') }}">Usuarios</a>
                                    </li>
                                </ul>
                            </li>
                            
                        @endif
                    </ul>
                </nav>
            @endauth
            <main id="content">
                @yield('content')
            </main>
        </div>
        <footer>
            <div class="container footer-content">
                Developed by <a href="http://softcompany.co" target="_blank">SoftCompany.Co. </a>
            </div>
        </footer>
    </div>
</body>
@stack('scripts')
<script src="{{ asset('js/jquery-ui.js') }}"></script>
<script src="{{ asset('js/datepicker-es.js') }}"></script>
<script>
    $( function() {
        $('.date').datepicker();        
    });
</script>
<script>
    $(document).ready(function () {

    $('#sidebarCollapse').on('click', function () {
        $('#sidebar').toggleClass('active');
    });

});
  </script>
</html>
