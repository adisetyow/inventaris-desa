<!DOCTYPE html>
<html lang="id">

<head>
    @include('layouts.partials.head')

    @yield('styles')
</head>

<body>
    <!-- Navbar -->
    @include('layouts.partials.navbar')

    <!-- Sidebar -->
    @include('layouts.partials.sidebar')

    <!-- Main Content -->
    <main class="main-content">
        @include('layouts.partials.alerts')
        @yield('content')
    </main>

    <!-- Scripts -->
    @include('layouts.partials.scripts')
    @yield('scripts')
</body>

</html>