<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="icon" type="image/png" href="{{ asset('assets/images/muni-logo.png') }}">
    <link rel="apple-touch-icon" href="{{ asset('assets/images/muni-logo.png') }}">
    <title>{{ $title ?? 'Admin' }} - {{ config('app.name', 'Muni University News & Media Portal') }}</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Merriweather:wght@400;700;900&family=Source+Sans+Pro:wght@400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css" integrity="sha512-z3gLpd7yknf1YoNbCzqRKc4qyor8gaKU1qmn+CShxbuBusANI9QpRohGBreCFkKxLhei6S9CQXFEbbKuqLg0DA==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        :root { --muni-red:#8B0000; --muni-red-dark:#5C0000; --muni-gold:#ffde00; --muni-blue:#24AAE1; }
        body{font-family:'Source Sans Pro',system-ui,sans-serif;} h1,h2,h3,h4,h5,h6{font-family:'Merriweather',Georgia,serif;}
        .sidebar-bg{background:var(--muni-red-dark);} .sidebar-link{transition:all 150ms ease-in-out; border-left:3px solid transparent; padding: 0.75rem 1.25rem !important;}
        .sidebar-link:hover{background:rgba(255,255,255,0.08); border-left-color:var(--muni-gold);}
        .sidebar-link.active{background:var(--muni-red); border-left-color:var(--muni-gold);}
        .admin-topbar{border-bottom:3px solid var(--muni-gold);} .btn-muni{background:var(--muni-red); color:#fff; border-radius:2px; text-transform:uppercase; font-weight:600; letter-spacing:0.5px; min-height:44px; padding: 0.75rem 1.5rem !important;}
        .btn-muni:hover{background:var(--muni-red-dark); color:#fff;}
        .btn, button.btn, a.btn { padding: 0.75rem 1.5rem !important; }
        .card-body { padding: 1.5rem; }
        @media (min-width: 1024px) { .card-body { padding: 2rem; } }
        table th, table td { padding: 1rem 1.25rem !important; }
        .form-control, .form-select, input[type="text"], input[type="email"], input[type="password"], input[type="number"], input[type="datetime-local"], textarea, select { padding: 0.75rem 1rem !important; border: 1px solid #e2e8f0 !important; transition: all 150ms ease-in-out; border-radius:2px !important; }
        .form-control:focus, .form-select:focus, input:focus, textarea:focus, select:focus { border-color: var(--muni-blue) !important; box-shadow: 0 0 0 3px rgba(36,170,225,0.15) !important; outline: none !important; }
        .sidebar-scroll::-webkit-scrollbar{width:6px;} .sidebar-scroll::-webkit-scrollbar-thumb{background:rgba(255,255,255,0.2); border-radius:3px;}
    </style>
</head>
<body class="bg-gray-100 antialiased" x-data="{ open: false }">
    <div class="flex min-h-screen">
        <aside class="hidden lg:flex lg:flex-shrink-0 w-64 sidebar-bg text-white flex-col fixed inset-y-0 z-30">
            <div class="flex items-center gap-3 px-6 py-5 border-b border-white/10" style="border-bottom:2px solid var(--muni-gold);">
                <img src="/assets/images/muni-logo.png" alt="Muni Logo" class="h-10 w-10 object-contain bg-white rounded-sm p-1">
                <div><h2 class="font-bold text-sm leading-tight" style="font-family:'Merriweather',serif;">Muni University</h2><p class="text-xs tracking-widest uppercase opacity-80">Transforming Lives</p></div>
            </div>
            <nav class="flex-1 overflow-y-auto sidebar-scroll py-4 px-3 space-y-6">
                <div>
                    <p class="px-3 mb-2 text-xs font-semibold uppercase tracking-wider opacity-60">Main</p>
                    <a href="{{ route('admin.dashboard') }}" class="sidebar-link flex items-center gap-3 px-3 py-2.5 rounded-sm text-sm {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}"><i class="fa-solid fa-gauge w-5 text-center"></i> Dashboard</a>
                </div>
                <div>
                    <p class="px-3 mb-2 text-xs font-semibold uppercase tracking-wider opacity-60">Content</p>
                    <a href="{{ route('admin.articles.index') }}" class="sidebar-link flex items-center gap-3 px-3 py-2.5 rounded-sm text-sm {{ request()->routeIs('admin.articles.*') ? 'active' : '' }}"><i class="fa-solid fa-newspaper w-5 text-center"></i> Articles</a>
                    <a href="{{ route('admin.categories.index') }}" class="sidebar-link flex items-center gap-3 px-3 py-2.5 rounded-sm text-sm {{ request()->routeIs('admin.categories.*') ? 'active' : '' }}"><i class="fa-solid fa-layer-group w-5 text-center"></i> Categories</a>
                    <a href="{{ route('admin.events.index') }}" class="sidebar-link flex items-center gap-3 px-3 py-2.5 rounded-sm text-sm {{ request()->routeIs('admin.events.*') ? 'active' : '' }}"><i class="fa-solid fa-calendar-days w-5 text-center"></i> Events</a>
                    <a href="{{ route('admin.newsletters.index') }}" class="sidebar-link flex items-center gap-3 px-3 py-2.5 rounded-sm text-sm {{ request()->routeIs('admin.newsletters.*') ? 'active' : '' }}"><i class="fa-solid fa-file-pdf w-5 text-center"></i> Newsletters</a>
                    <a href="{{ route('admin.downloads.index') }}" class="sidebar-link flex items-center gap-3 px-3 py-2.5 rounded-sm text-sm {{ request()->routeIs('admin.downloads.*') ? 'active' : '' }}"><i class="fa-solid fa-download w-5 text-center"></i> Downloads</a>
                    <a href="{{ route('admin.comments.index') }}" class="sidebar-link flex items-center gap-3 px-3 py-2.5 rounded-sm text-sm {{ request()->routeIs('admin.comments.*') ? 'active' : '' }}"><i class="fa-solid fa-comments w-5 text-center"></i> Comments</a>
                </div>
                <div>
                    <p class="px-3 mb-2 text-xs font-semibold uppercase tracking-wider opacity-60">Media</p>
                    <a href="{{ route('admin.media.index') }}" class="sidebar-link flex items-center gap-3 px-3 py-2.5 rounded-sm text-sm {{ request()->routeIs('admin.media.*') ? 'active' : '' }}"><i class="fa-solid fa-photo-film w-5 text-center"></i> Media Library</a>
                </div>
                <div>
                    <p class="px-3 mb-2 text-xs font-semibold uppercase tracking-wider opacity-60">Management</p>
                    @hasanyrole('super_admin|comm_admin')
                    <a href="{{ route('admin.users.index') }}" class="sidebar-link flex items-center gap-3 px-3 py-2.5 rounded-sm text-sm {{ request()->routeIs('admin.users.*') ? 'active' : '' }}"><i class="fa-solid fa-users w-5 text-center"></i> Users @role('super_admin')<span class="ml-auto text-xs bg-white/20 px-2 py-0.5 rounded-sm">Admin</span>@endrole</a>
                    @endhasanyrole
                    <a href="{{ route('admin.subscribers.index') }}" class="sidebar-link flex items-center gap-3 px-3 py-2.5 rounded-sm text-sm {{ request()->routeIs('admin.subscribers.*') ? 'active' : '' }}"><i class="fa-solid fa-envelope w-5 text-center"></i> Subscribers</a>
                    <a href="{{ route('admin.reports.index') }}" class="sidebar-link flex items-center gap-3 px-3 py-2.5 rounded-sm text-sm {{ request()->routeIs('admin.reports.*') ? 'active' : '' }}"><i class="fa-solid fa-chart-line w-5 text-center"></i> Reports</a>
                </div>
                <div>
                    <p class="px-3 mb-2 text-xs font-semibold uppercase tracking-wider opacity-60">System</p>
                    @hasanyrole('super_admin|comm_admin')
                    <a href="{{ route('admin.settings.index') }}" class="sidebar-link flex items-center gap-3 px-3 py-2.5 rounded-sm text-sm {{ request()->routeIs('admin.settings.*') ? 'active' : '' }}"><i class="fa-solid fa-gear w-5 text-center"></i> Settings</a>
                    @endhasanyrole
                    <a href="{{ url('/') }}" target="_blank" class="sidebar-link flex items-center gap-3 px-3 py-2.5 rounded-sm text-sm"><i class="fa-solid fa-external-link w-5 text-center"></i> View Site</a>
                </div>
            </nav>
            <div class="p-4 border-t border-white/10 text-xs opacity-60 text-center">&copy; {{ date('Y') }} Muni University<br>news.muni.ac.ug</div>
        </aside>

        <div x-show="open" x-transition.opacity class="fixed inset-0 bg-black/50 z-20 lg:hidden" @click="open=false"></div>
        <aside x-show="open" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="-translate-x-full" x-transition:enter-end="translate-x-0" x-transition:leave="transition ease-in duration-150" x-transition:leave-start="translate-x-0" x-transition:leave-end="-translate-x-full" class="fixed inset-y-0 left-0 w-64 sidebar-bg text-white flex flex-col z-30 lg:hidden overflow-y-auto">
            <div class="flex items-center justify-between px-6 py-5 border-b border-white/10" style="border-bottom:2px solid var(--muni-gold);">
                <div class="flex items-center gap-3"><img src="/assets/images/muni-logo.png" alt="Muni Logo" class="h-10 w-10 object-contain bg-white rounded-sm p-1"><div><h2 class="font-bold text-sm">Muni University</h2><p class="text-xs tracking-widest uppercase opacity-80">Transforming Lives</p></div></div>
                <button @click="open=false" class="p-2 rounded-sm hover:bg-white/10" aria-label="Close menu"><i class="fa-solid fa-xmark text-xl"></i></button>
            </div>
            <nav class="flex-1 py-4 px-3 space-y-1">
                <a href="{{ route('admin.dashboard') }}" class="sidebar-link flex items-center gap-3 px-3 py-2.5 rounded-sm text-sm {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}"><i class="fa-solid fa-gauge w-5"></i> Dashboard</a>
                <a href="{{ route('admin.articles.index') }}" class="sidebar-link flex items-center gap-3 px-3 py-2.5 rounded-sm text-sm"><i class="fa-solid fa-newspaper w-5"></i> Articles</a>
                <a href="{{ route('admin.categories.index') }}" class="sidebar-link flex items-center gap-3 px-3 py-2.5 rounded-sm text-sm"><i class="fa-solid fa-layer-group w-5"></i> Categories</a>
                <a href="{{ route('admin.events.index') }}" class="sidebar-link flex items-center gap-3 px-3 py-2.5 rounded-sm text-sm"><i class="fa-solid fa-calendar-days w-5"></i> Events</a>
                <a href="{{ route('admin.newsletters.index') }}" class="sidebar-link flex items-center gap-3 px-3 py-2.5 rounded-sm text-sm"><i class="fa-solid fa-file-pdf w-5"></i> Newsletters</a>
                <a href="{{ route('admin.downloads.index') }}" class="sidebar-link flex items-center gap-3 px-3 py-2.5 rounded-sm text-sm"><i class="fa-solid fa-download w-5"></i> Downloads</a>
                <a href="{{ route('admin.users.index') }}" class="sidebar-link flex items-center gap-3 px-3 py-2.5 rounded-sm text-sm"><i class="fa-solid fa-users w-5"></i> Users</a>
                <a href="{{ route('admin.media.index') }}" class="sidebar-link flex items-center gap-3 px-3 py-2.5 rounded-sm text-sm"><i class="fa-solid fa-photo-film w-5"></i> Media Library</a>
                <a href="{{ route('admin.comments.index') }}" class="sidebar-link flex items-center gap-3 px-3 py-2.5 rounded-sm text-sm"><i class="fa-solid fa-comments w-5"></i> Comments</a>
                <a href="{{ route('admin.subscribers.index') }}" class="sidebar-link flex items-center gap-3 px-3 py-2.5 rounded-sm text-sm"><i class="fa-solid fa-envelope w-5"></i> Subscribers</a>
                <a href="{{ route('admin.settings.index') }}" class="sidebar-link flex items-center gap-3 px-3 py-2.5 rounded-sm text-sm"><i class="fa-solid fa-gear w-5"></i> Settings</a>
                <a href="{{ route('admin.reports.index') }}" class="sidebar-link flex items-center gap-3 px-3 py-2.5 rounded-sm text-sm"><i class="fa-solid fa-chart-line w-5"></i> Reports</a>
            </nav>
        </aside>

        <div class="flex-1 lg:ml-64 flex flex-col min-h-screen">
            <header class="sticky top-0 z-10 bg-white admin-topbar shadow-sm">
                <div class="flex items-center justify-between px-4 lg:px-6 py-3">
                    <div class="flex items-center gap-3">
                        <button @click="open=!open" class="lg:hidden p-2 rounded-sm border border-gray-300 hover:bg-gray-50" aria-label="Toggle menu" style="min-width:44px; min-height:44px;"><i class="fa-solid fa-bars"></i></button>
                        <div class="hidden sm:block"><h1 class="text-lg font-bold" style="font-family:'Merriweather',serif; color: var(--muni-red);">@yield('header', 'Dashboard')</h1><p class="text-xs text-gray-500">Muni University News & Media Portal</p></div>
                    </div>
                    <div class="flex items-center gap-2 sm:gap-4">
                        <a href="{{ route('admin.articles.create') }}" class="hidden sm:inline-flex items-center gap-2 btn-muni px-4 py-2 text-xs"><i class="fa-solid fa-plus"></i> New Article</a>
                        <button class="relative p-2 text-gray-500 hover:text-[var(--muni-red)]" aria-label="Notifications" style="min-width:44px; min-height:44px;"><i class="fa-solid fa-bell text-lg"></i><span class="absolute top-1 right-1 w-2 h-2 rounded-full" style="background: var(--muni-gold);"></span></button>
                        <div class="relative" x-data="{ userMenuOpen: false }">
                            <button @click="userMenuOpen=!userMenuOpen" class="flex items-center gap-3 p-1 rounded-sm hover:bg-gray-50 border border-transparent hover:border-gray-200" style="min-height:44px;">
                                @if(auth()->user()->avatar)<img src="{{ asset('storage/' . auth()->user()->avatar) }}" alt="Avatar" class="w-8 h-8 rounded-full object-cover">@else<div class="w-8 h-8 rounded-full flex items-center justify-center text-white text-sm font-bold" style="background: var(--muni-red);">{{ strtoupper(substr(auth()->user()->full_name ?? auth()->user()->username ?? 'A',0,1)) }}</div>@endif
                                <div class="hidden sm:block text-left"><p class="text-sm font-semibold leading-none" style="color: var(--muni-red-dark);">{{ auth()->user()->full_name ?? auth()->user()->username }}</p><p class="text-xs text-gray-500 capitalize">{{ auth()->user()->getRoleNames()->first() ?? 'user' }}</p></div>
                                <i class="fa-solid fa-chevron-down text-xs text-gray-400 hidden sm:block"></i>
                            </button>
                            <div x-show="userMenuOpen" @click.away="userMenuOpen=false" x-transition class="absolute right-0 mt-2 w-56 bg-white rounded-sm shadow-lg border border-gray-200 py-2 z-50" style="border-top:3px solid var(--muni-gold);">
                                <div class="px-4 py-2 border-b border-gray-100"><p class="text-sm font-semibold">{{ auth()->user()->full_name }}</p><p class="text-xs text-gray-500">{{ auth()->user()->email }}</p></div>
                                <a href="{{ route('profile.edit') }}" class="flex items-center gap-2 px-4 py-2 text-sm hover:bg-gray-50"><i class="fa-solid fa-user w-4"></i> Profile</a>
                                <a href="{{ url('/') }}" class="flex items-center gap-2 px-4 py-2 text-sm hover:bg-gray-50"><i class="fa-solid fa-house w-4"></i> View Site</a>
                                <form method="POST" action="{{ route('logout') }}" class="border-t border-gray-100 mt-2 pt-2">@csrf<button type="submit" class="w-full text-left flex items-center gap-2 px-4 py-2 text-sm hover:bg-gray-50" style="color: var(--muni-red);"><i class="fa-solid fa-right-from-bracket w-4"></i> Log Out</button></form>
                            </div>
                        </div>
                    </div>
                </div>
            </header>
            <main class="flex-1 p-4 lg:p-6">
                @if(session('success'))<div class="mb-4 bg-green-50 border-l-4 p-4 rounded-sm flex items-center gap-2" style="border-color: var(--muni-emerald, #00b9f1);"><i class="fa-solid fa-circle-check" style="color: var(--muni-blue);"></i><span class="text-sm font-medium text-green-800">{{ session('success') }}</span></div>@endif
                @if(session('error'))<div class="mb-4 bg-red-50 border-l-4 p-4 rounded-sm flex items-center gap-2" style="border-color: var(--muni-red);"><i class="fa-solid fa-circle-exclamation" style="color: var(--muni-red);"></i><span class="text-sm font-medium text-red-800">{{ session('error') }}</span></div>@endif
                @if($errors->any() && !isset($noErrorDisplay))<div class="mb-4 bg-red-50 border-l-4 p-4 rounded-sm" style="border-color: var(--muni-red);"><ul class="text-sm text-red-800 list-disc ms-4">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul></div>@endif
                {{ $slot ?? '' }} @yield('content')
            </main>
            <footer class="bg-white border-t border-gray-200 px-6 py-3 text-center text-xs text-gray-500">&copy; {{ date('Y') }} Muni University &mdash; Transforming Lives | news.muni.ac.ug | Admin Panel</footer>
        </div>
    </div>
</body>
</html>
