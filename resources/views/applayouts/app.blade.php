<!DOCTYPE html>
<html lang="en">
<head>
    @include('partials.header')
</head>
<body>

@auth
    @php
        $role = auth()->user()->getRoleNames()->first() ?? 'default';
    @endphp

    <div id="appWrapper" class="app">
        {{-- Sidebar & Header (contained in one partial for now) --}}
        @includeIf('partials.sidebar.' . $role)

        {{-- Main Content --}}
        <main class="main-content">
            @yield('contents')
        </main>
    </div>

    {{-- Mobile overlay (outside app grid) --}}
    <div id="sidebarOverlay" class="sb-overlay"></div>
@else
    <div class="app-wrapper">
        <main class="main-content">
            @yield('contents')
        </main>
    </div>
@endauth

@include('partials.footer')
@stack('scripts')
</body>
</html>
