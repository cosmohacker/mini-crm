<!DOCTYPE html>
<html lang="en">

<head>
    @include('partials.head')
    <title>@yield('title', config('app.name', 'Mini CRM'))</title>
</head>

<body class="bg-surface">
    <!-- Top Strip -->
    <div class="app-topstrip z-40 sticky top-0 py-[15px] px-6 bg-[linear-gradient(90deg,_#0f0533_0%,_#1b0a5c_100%)]">
        @include('partials.app-topstrip')
    </div>

    <!-- Main Wrapper -->
    <div id="main-wrapper" class="flex p-5 xl:pr-0">
        <!-- Sidebar -->
        <aside id="application-sidebar-brand"
            class="hs-overlay hs-overlay-open:translate-x-0 -translate-x-full transform hidden xl:block xl:translate-x-0 xl:end-auto xl:bottom-0 fixed xl:top-[90px] xl:left-auto top-0 left-0 with-vertical h-screen z-[999] shrink-0 w-[270px] shadow-md xl:rounded-md rounded-none bg-white left-sidebar transition-all duration-300">
            @include('partials.sidebar')
        </aside>

        <!-- Content -->
        <div class="w-full page-wrapper xl:px-6 px-0">
            <div class="container full-container p-0 flex flex-col gap-6">
                @yield('content')
            </div>
        </div>
    </div>

    @include('partials.scripts')
    @stack('scripts')
</body>

</html>
