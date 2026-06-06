<!DOCTYPE html>
<html lang="en">
<head>
    @include('partials.header')
</head>
<body>

<div class="app-wrapper">

    @auth
        @php

            $role = auth()->user()->getRoleNames()->first() ?? 'default';
        @endphp

        @includeIf('partials.sidebar.' . $role)
    @endauth

    <main class="main-content">
        @yield('contents')
    </main>

</div>

@include('partials.footer')
@stack('scripts')
</body>
</html>
