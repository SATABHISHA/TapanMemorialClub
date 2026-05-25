<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $title ?? 'TMC Admin' }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
</head>
<body class="admin-body">
    <div class="admin-shell">
        <aside class="admin-sidebar glass-card">
            <h4 class="gradient-text">TMC Admin</h4>
            <a href="{{ route('admin.dashboard') }}">Dashboard</a>
            <a href="{{ route('admin.menus.index') }}">Menus</a>
            <a href="{{ route('admin.submenus.index') }}">Submenus</a>
            <a href="{{ route('admin.sliders.index') }}">Sliders</a>
            <a href="{{ route('admin.gallery.index') }}">Gallery</a>
            <a href="{{ route('admin.performances.index') }}">Performances</a>
            <a href="{{ route('admin.achievements.index') }}">Achievements</a>
            <a href="{{ route('admin.blogs.index') }}">Blogs</a>
            <a href="{{ route('admin.dynamic-pages.index') }}">Dynamic Pages</a>
            <a href="{{ route('admin.vlogs.index') }}">Vlogs</a>
            <a href="{{ route('admin.sponsors.index') }}">Sponsors</a>
            <a href="{{ route('admin.media-library.index') }}">Media</a>
            <a href="{{ route('admin.settings.index') }}">Settings</a>
            <a href="{{ route('admin.contacts.index') }}">Contacts</a>
            <form method="POST" action="{{ route('logout') }}" class="mt-3">@csrf<button class="btn btn-sm btn-outline-light w-100">Logout</button></form>
        </aside>
        <div class="admin-main">
            <header class="glass-card p-3 mb-4 d-flex justify-content-between align-items-center">
                <h5 class="mb-0">{{ $pageTitle ?? 'Dashboard' }}</h5>
                <a href="{{ route('home') }}" class="btn btn-sm btn-gold">View Site</a>
            </header>
            @if(session('status'))
                <div class="alert alert-success">{{ session('status') }}</div>
            @endif
            @yield('content')
        </div>
    </div>
    @stack('scripts')
</body>
</html>
