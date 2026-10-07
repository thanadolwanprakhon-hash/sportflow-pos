<!doctype html>
<html lang="th">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'SportFlow POS') · SportFlow</title>
    <link rel="icon" type="image/png" href="{{ asset('images/sportflow-logo-transparent.png') }}">
    <link rel="stylesheet" href="{{ asset('css/sportflow.css') }}?v={{ filemtime(public_path('css/sportflow.css')) }}">
    @stack('head')
</head>
<body>
<div class="app-shell">
    <aside class="sidebar">
        <a class="brand" href="{{ route('dashboard') }}">
            <img class="brand-logo" src="{{ asset('images/sportflow-logo-transparent.png') }}" alt="SportFlow Smart Retail POS">
        </a>

        <div class="nav-label">เมนูหลัก</div>
        <nav class="nav">
            <a class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}" href="{{ route('dashboard') }}">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M4 13h6V4H4v9Zm0 7h6v-4H4v4Zm10 0h6v-9h-6v9Zm0-16v4h6V4h-6Z"/></svg> แดชบอร์ด
            </a>
            <a class="nav-link {{ request()->routeIs('pos') ? 'active' : '' }}" href="{{ route('pos') }}">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M3 5h2l2.1 9.2a2 2 0 0 0 2 1.6h7.8a2 2 0 0 0 2-1.6L20 8H6M10 20h.01M17 20h.01"/></svg> จุดขายสินค้า
            </a>
            <a class="nav-link {{ request()->routeIs('products.*') ? 'active' : '' }}" href="{{ route('products.index') }}">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="m4 7 8-4 8 4-8 4-8-4Zm0 0v10l8 4m0-10v10m8-14v10l-8 4"/></svg> สินค้าและสต็อก
            </a>
            <a class="nav-link {{ request()->routeIs('members.*') ? 'active' : '' }}" href="{{ route('members.index') }}">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2m7-10a4 4 0 1 0 0-8 4 4 0 0 0 0 8Zm13 10v-2a4 4 0 0 0-3-3.87m-2-12a4 4 0 0 1 0 7.75"/></svg> สมาชิก
            </a>
            <a class="nav-link {{ request()->routeIs('employees.*') ? 'active' : '' }}" href="{{ route('employees.index') }}">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2m8-10a4 4 0 1 0 0-8 4 4 0 0 0 0 8Z"/></svg> พนักงาน
            </a>
            <a class="nav-link {{ request()->routeIs('sales.*') ? 'active' : '' }}" href="{{ route('sales.index') }}">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M4 2v20l2-2 2 2 2-2 2 2 2-2 2 2 2-2 2 2V2l-2 2-2-2-2 2-2-2-2 2-2-2-2 2-2-2Zm4 7h8m-8 4h6"/></svg> ประวัติการขาย
            </a>
            <a class="nav-link {{ request()->routeIs('reports') ? 'active' : '' }}" href="{{ route('reports') }}">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M4 19V9m5 10V5m5 14v-7m5 7V3"/></svg> รายงานการขาย
            </a>
            <a class="nav-link {{ request()->routeIs('settings.*') ? 'active' : '' }}" href="{{ route('settings.edit') }}">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M12 15.5a3.5 3.5 0 1 0 0-7 3.5 3.5 0 0 0 0 7Z"/><path d="M19.4 15a1.7 1.7 0 0 0 .34 1.88l.06.06-2.83 2.83-.06-.06A1.7 1.7 0 0 0 15 19.4a1.7 1.7 0 0 0-1 .6 1.7 1.7 0 0 0-.4 1.1V21h-4v-.1A1.7 1.7 0 0 0 8.6 19.4a1.7 1.7 0 0 0-1.88.34l-.06.06-2.83-2.83.06-.06A1.7 1.7 0 0 0 4.6 15a1.7 1.7 0 0 0-.6-1 1.7 1.7 0 0 0-1.1-.4H3v-4h.1A1.7 1.7 0 0 0 4.6 8.6a1.7 1.7 0 0 0-.34-1.88l-.06-.06 2.83-2.83.06.06A1.7 1.7 0 0 0 9 4.6a1.7 1.7 0 0 0 1-.6 1.7 1.7 0 0 0 .4-1.1V3h4v.1a1.7 1.7 0 0 0 1 1.5 1.7 1.7 0 0 0 1.88-.34l.06-.06 2.83 2.83-.06.06A1.7 1.7 0 0 0 19.4 9c.18.38.4.7.6 1 .3.26.68.4 1.1.4h.1v4h-.1c-.42 0-.8.14-1.1.4-.2.3-.42.62-.6 1Z"/></svg> ตั้งค่าร้าน
            </a>
        </nav>

        <div class="sidebar-bottom">
            <div class="profile-card">
                <div class="row">
                    <span class="avatar">{{ mb_substr(auth()->user()->name, 0, 1) }}</span>
                    <span><strong>{{ auth()->user()->name }}</strong><small>{{ ['manager'=>'ผู้จัดการร้าน','cashier'=>'พนักงานขาย','stock'=>'พนักงานคลัง','admin'=>'ผู้ดูแลระบบ'][auth()->user()->role] ?? auth()->user()->role }}</small></span>
                </div>
                <form method="POST" action="{{ route('logout') }}">@csrf<button class="logout-btn" type="submit">↪ ออกจากระบบ</button></form>
            </div>
        </div>
    </aside>

    <main class="main">
        <header class="topbar">
            <button class="mobile-menu" id="mobileMenu" type="button">☰</button>
            <span class="topbar-title">@yield('title', 'SportFlow POS')</span>
            <span class="date-chip">{{ now()->locale('th')->translatedFormat('j F Y') }}</span>
        </header>
        <section class="content">
            @if(session('success'))<div class="flash flash-success"><span>✓ {{ session('success') }}</span></div>@endif
            @if($errors->any())
                <div class="flash flash-error"><div><strong>กรุณาตรวจสอบข้อมูล</strong><ul>@foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul></div></div>
            @endif
            @yield('content')
        </section>
    </main>
</div>
<script src="{{ asset('js/app.js') }}"></script>
@stack('scripts')
</body>
</html>
