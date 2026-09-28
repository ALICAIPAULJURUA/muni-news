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

        /* =========================================
           SECURE DELETE MODAL & BRANDED TOASTS
           ========================================= */
        .sdm { position:fixed; inset:0; z-index:1000; display:flex; align-items:center; justify-content:center; visibility:hidden; opacity:0; transition:opacity .2s ease; }
        .sdm.sdm-open { visibility:visible; opacity:1; }
        .sdm-backdrop { position:absolute; inset:0; background:rgba(0,0,0,0.45); }
        .sdm-dialog { position:relative; width:100%; max-width:480px; margin:0 16px; background:#fff; border-radius:8px; box-shadow:0 10px 30px rgba(0,0,0,0.15); transform:translateY(12px) scale(0.98); transition:transform .2s ease; }
        .sdm.sdm-open .sdm-dialog { transform:translateY(0) scale(1); }
        .sdm-header { display:flex; align-items:center; justify-content:space-between; padding:1rem 1.25rem; background:#fff3cd; border-bottom:2px solid #ffde00; border-radius:8px 8px 0 0; }
        .sdm-title { margin:0; color:#856404; font-weight:700; font-size:1rem; }
        .sdm-close { background:none; border:none; font-size:1.1rem; color:#856404; cursor:pointer; padding:4px 8px; border-radius:4px; }
        .sdm-close:hover { background:rgba(0,0,0,0.06); }
        .sdm-body { padding:1.25rem; }
        .sdm-desc { color:#4a5568; margin:0 0 0.75rem; }
        .sdm-prompt { margin:0 0 0.75rem; font-weight:700; color:var(--muni-red); }
        .sdm-chip { background:#f8f9fa; padding:2px 8px; border-radius:4px; border:1px solid #e2e8f0; font-family:ui-monospace,SFMono-Regular,Menlo,monospace; }
        .sdm-input { width:100%; border:2px solid #e2e8f0; border-radius:4px; padding:0.6rem 0.85rem; font-weight:600; font-size:0.95rem; }
        .sdm-input:focus { outline:none; border-color:var(--muni-blue); box-shadow:0 0 0 3px rgba(36,170,225,0.15); }
        .sdm-warn { margin-top:0.5rem; font-size:0.8125rem; color:#dc2626; }
        .sdm-footer { display:flex; justify-content:flex-end; gap:0.5rem; padding:0.9rem 1.25rem; background:#f8f9fa; border-top:1px solid #e9ecef; border-radius:0 0 8px 8px; }
        .sdm-btn { padding:0.5rem 1.1rem; border-radius:4px; border:none; font-weight:600; cursor:pointer; font-size:0.9rem; }
        .sdm-btn-cancel { background:#6c757d; color:#fff; }
        .sdm-btn-cancel:hover { background:#5a6268; }
        .sdm-btn-danger { background:var(--muni-red); color:#fff; }
        .sdm-btn-danger:hover { background:var(--muni-red-dark); }
        .sdm-btn-danger:disabled { opacity:0.5; pointer-events:none; }

        .toast-container { position:fixed; top:80px; right:16px; display:flex; flex-direction:column; gap:10px; max-width:calc(100vw - 32px); }
        .custom-toast { border:none; border-radius:8px; box-shadow:0 10px 25px rgba(0,0,0,0.15); min-width:300px; overflow:hidden; animation:slideInRight .4s ease-out; }
        .custom-toast.custom-toast-hide { opacity:0; transform:translateX(100%); transition:opacity .4s ease, transform .4s ease; }
        .toast-header { display:flex; align-items:center; gap:8px; padding:0.7rem 1rem; color:#fff; font-weight:700; }
        .toast-header.success { background: #198754; }
        .toast-header.error { background: #dc2626; }
        .toast-header.warning { background: #d97706; }
        .toast-close { background:none; border:none; color:#fff; cursor:pointer; margin-left:auto; padding:2px 6px; font-size:0.9rem; opacity:0.8; }
        .toast-close:hover { opacity:1; }
        .toast-body { padding:0.85rem 1rem; font-weight:600; font-size:0.9rem; }
        .custom-toast-success .toast-body { color:#155724; background:#d4edda; }
        .custom-toast-error .toast-body { color:#721c24; background:#f8d7da; }
        .custom-toast-warning .toast-body { color:#856404; background:#fff3cd; }
        @keyframes slideInRight { from { transform:translateX(100%); opacity:0; } to { transform:translateX(0); opacity:1; } }
        @media (max-width:480px) { .custom-toast { min-width:0; width:100%; } }
    </style>
</head>
<body class="bg-gray-100 antialiased" x-data="{ open: false }">
    <!-- Custom Toast Container -->
    <div class="toast-container" id="toastContainer" style="z-index:9999;">
        @if(session('success'))
        <div class="custom-toast custom-toast-success" role="alert" aria-live="assertive" aria-atomic="true">
            <div class="toast-header success"><i class="fas fa-check-circle"></i><strong class="me-auto">Success</strong><button type="button" class="toast-close" aria-label="Close"><i class="fas fa-xmark"></i></button></div>
            <div class="toast-body">{{ session('success') }}</div>
        </div>
        @endif
        @if(session('error'))
        <div class="custom-toast custom-toast-error" role="alert" aria-live="assertive" aria-atomic="true">
            <div class="toast-header error"><i class="fas fa-exclamation-circle"></i><strong class="me-auto">Error</strong><button type="button" class="toast-close" aria-label="Close"><i class="fas fa-xmark"></i></button></div>
            <div class="toast-body">{{ session('error') }}</div>
        </div>
        @endif
    </div>
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
                    @hasanyrole('super_admin|comm_admin')
                    <a href="{{ route('admin.patterns.index') }}" class="sidebar-link flex items-center gap-3 px-3 py-2.5 rounded-sm text-sm {{ request()->routeIs('admin.patterns.*') ? 'active' : '' }}"><i class="fa-solid fa-border-all w-5 text-center"></i> Section Patterns</a>
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
                <a href="{{ route('admin.patterns.index') }}" class="sidebar-link flex items-center gap-3 px-3 py-2.5 rounded-sm text-sm"><i class="fa-solid fa-border-all w-5"></i> Section Patterns</a>
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
                @if($errors->any() && !isset($noErrorDisplay))<div class="mb-4 bg-red-50 border-l-4 p-4 rounded-sm" style="border-color: var(--muni-red);"><ul class="text-sm text-red-800 list-disc ms-4">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul></div>@endif
                {{ $slot ?? '' }} @yield('content')
            </main>
            <footer class="bg-white border-t border-gray-200 px-6 py-3 text-center text-xs text-gray-500">&copy; {{ date('Y') }} Muni University &mdash; Transforming Lives | news.muni.ac.ug | Admin Panel</footer>
        </div>
    </div>
    <button id="scrollToTop" class="fixed bottom-6 right-6 bg-[#8B0000] text-white p-3 rounded-full shadow-lg opacity-0 pointer-events-none transition-opacity duration-300 hover:bg-[#5C0000]" aria-label="Scroll to top" style="position: fixed; bottom: 24px; right: 24px; background: #8B0000; color: white; padding: 12px; border-radius: 50%; box-shadow: 0 4px 6px rgba(0,0,0,0.1); opacity: 0; pointer-events: none; transition: opacity 0.3s; z-index: 9999; width: 44px; height: 44px; display: flex; align-items: center; justify-content: center; border: none;">
        <i class="fas fa-chevron-up"></i>
    </button>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const btn = document.getElementById('scrollToTop');
            if(!btn) return;
            window.addEventListener('scroll', function() {
                if(window.scrollY > 300){
                    btn.classList.remove('opacity-0','pointer-events-none');
                    btn.classList.add('opacity-100','pointer-events-auto');
                    btn.style.opacity = '1';
                    btn.style.pointerEvents = 'auto';
                } else {
                    btn.classList.add('opacity-0','pointer-events-none');
                    btn.classList.remove('opacity-100','pointer-events-auto');
                    btn.style.opacity = '0';
                    btn.style.pointerEvents = 'none';
                }
            });
            btn.addEventListener('click', function(){
                window.scrollTo({ top: 0, behavior: 'smooth' });
            });
        });
    </script>

    <!-- Secure Delete Confirmation Modal -->
    <div id="secureDeleteModal" class="sdm" aria-hidden="true">
        <div class="sdm-backdrop" data-sdm-close></div>
        <div class="sdm-dialog" role="dialog" aria-modal="true" aria-labelledby="sdmTitle">
            <div class="sdm-header">
                <h5 class="sdm-title" id="sdmTitle"><i class="fas fa-exclamation-triangle me-2"></i> Confirm Permanent Deletion</h5>
                <button type="button" class="sdm-close" data-sdm-close aria-label="Close"><i class="fas fa-xmark"></i></button>
            </div>
            <div class="sdm-body">
                <p class="sdm-desc">You are about to permanently delete this item. This action cannot be undone and will remove all associated data.</p>
                <p class="sdm-prompt">To confirm, please type <span class="sdm-chip">DELETE</span> in the box below:</p>
                <input type="text" id="deleteConfirmationInput" class="sdm-input" placeholder="Type DELETE here..." autocomplete="off">
                <div id="deleteWarningText" class="sdm-warn" style="display:none;"><i class="fas fa-times-circle me-1"></i> You must type exactly "DELETE" to proceed.</div>
            </div>
            <div class="sdm-footer">
                <button type="button" class="sdm-btn sdm-btn-cancel" data-sdm-close>Cancel</button>
                <button type="button" id="confirmDeleteBtn" class="sdm-btn sdm-btn-danger" disabled><i class="fas fa-trash-alt me-1"></i> Permanently Delete</button>
            </div>
        </div>
    </div>

    <script>
    (function () {
        function hideToast(t) {
            if (!t || t.dataset.hiding) return;
            t.dataset.hiding = '1';
            t.classList.add('custom-toast-hide');
            setTimeout(function () { t.remove(); }, 400);
        }

        // Auto-dismiss session toasts (success 5s, warning 7s, error 8s).
        document.querySelectorAll('.custom-toast').forEach(function (t) {
            var delay = t.classList.contains('custom-toast-error') ? 8000
                : t.classList.contains('custom-toast-warning') ? 7000 : 5000;
            setTimeout(function () { hideToast(t); }, delay);
        });
        document.addEventListener('click', function (e) {
            var c = e.target.closest('.toast-close');
            if (c) hideToast(c.closest('.custom-toast'));
        });

        // Secure DELETE modal (vanilla JS, no Bootstrap dependency).
        var modal = document.getElementById('secureDeleteModal');
        if (modal) {
            var input = document.getElementById('deleteConfirmationInput');
            var confirmBtn = document.getElementById('confirmDeleteBtn');
            var warn = document.getElementById('deleteWarningText');
            var pendingForm = null;

            function setState(enabled) {
                confirmBtn.disabled = !enabled;
                if (input.value.trim() === '' || enabled) {
                    warn.style.display = 'none';
                } else {
                    warn.style.display = 'block';
                }
            }

            function close() {
                modal.classList.remove('sdm-open');
                modal.setAttribute('aria-hidden', 'true');
                document.body.style.overflow = '';
                input.value = '';
                setState(false);
                pendingForm = null;
            }

            function open(form) {
                pendingForm = form;
                input.value = '';
                setState(false);
                modal.classList.add('sdm-open');
                modal.setAttribute('aria-hidden', 'false');
                document.body.style.overflow = 'hidden';
                setTimeout(function () { input.focus(); }, 250);
            }

            input.addEventListener('input', function () { setState(input.value.trim() === 'DELETE'); });

            confirmBtn.addEventListener('click', function () {
                if (!pendingForm || input.value.trim() !== 'DELETE') return;
                var form = pendingForm;
                close();
                // Native submit() bypasses the intercepted 'submit' event.
                form.submit();
            });

            modal.addEventListener('click', function (e) {
                if (e.target.closest('[data-sdm-close]') || e.target.classList.contains('sdm-backdrop')) close();
            });
            document.addEventListener('keydown', function (e) {
                if (e.key === 'Escape' && modal.classList.contains('sdm-open')) close();
            });

            // Any form marked data-secure-delete opens the modal instead of submitting.
            document.addEventListener('submit', function (e) {
                var form = e.target.closest('form[data-secure-delete]');
                if (!form) return;
                e.preventDefault();
                open(form);
            });
        }

        // Global branded toast helper (replaces window.alert() in admin UI).
        window.muniToast = function (message, type) {
            var container = document.getElementById('toastContainer');
            if (!container || !message) return;
            var isError = type === 'error';
            var isWarning = type === 'warning';
            var el = document.createElement('div');
            el.className = 'custom-toast' + (isError ? ' custom-toast-error' : isWarning ? ' custom-toast-warning' : '');
            var icon = isError ? 'fa-exclamation-circle' : isWarning ? 'fa-exclamation-triangle' : 'fa-check-circle';
            var label = isError ? 'Error' : isWarning ? 'Notice' : 'Success';
            var tone = isError ? 'error' : isWarning ? 'warning' : 'success';
            el.innerHTML = '<div class="toast-header ' + tone + '"><i class="fas ' + icon + '"></i><strong>' + label + '</strong><button type="button" class="toast-close" aria-label="Close"><i class="fas fa-xmark"></i></button></div><div class="toast-body"></div>';
            el.querySelector('.toast-body').textContent = message;
            container.appendChild(el);
            var delay = isError ? 8000 : isWarning ? 7000 : 5000;
            el.querySelector('.toast-close').addEventListener('click', function () { hideToast(el); });
            setTimeout(function () { hideToast(el); }, delay);
        };
    })();
    </script>
</body>
</html>
