<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="icon" type="image/png" href="{{ asset('assets/images/muni-logo.png') }}">
    <link rel="apple-touch-icon" href="{{ asset('assets/images/muni-logo.png') }}">
    <title>{{ $meta_title ?? ($title ?? 'Muni University News & Media Portal') }} - {{ config('app.name', 'Muni University') }}</title>
    <meta name="description" content="{{ $meta_description ?? 'Transforming Lives - Official News Portal of Muni University, Arua City, Uganda' }}">
    <link rel="canonical" href="{{ url()->current() }}">
    <!-- Open Graph -->
    <meta property="og:title" content="{{ $meta_title ?? ($title ?? 'Muni University News & Media Portal') }}">
    <meta property="og:description" content="{{ $meta_description ?? 'Transforming Lives' }}">
    <meta property="og:image" content="{{ $og_image ?? asset('assets/images/muni-logo.png') }}">
    <meta property="og:url" content="{{ url()->current() }}">
    <meta property="og:type" content="website">
    <!-- Twitter -->
    <meta name="twitter:card" content="summary_large_image">
    <!-- Fonts - Muni Branding -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Merriweather:wght@400;700;900&family=Source+Sans+Pro:wght@400;600;700&display=swap" rel="stylesheet">
    <!-- Font Awesome 6.4 -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css" integrity="sha512-z3gLpd7yknf1YoNbCzqRKc4qyor8gaKU1qmn+CShxbuBusANI9QpRohGBreCFkKxLhei6S9CQXFEbbKuqLg0DA==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        body { font-family: var(--font-body); color: var(--color-text-primary); line-height: 1.6; }
        h1,h2,h3,h4,h5,h6 { font-family: var(--font-heading); }
        .top-bar { background: var(--muni-red-dark); color: #fff; font-size: 0.8rem; }
        .header-main { background: #fff; border-bottom: 3px solid var(--muni-gold); box-shadow: var(--shadow-sm); }
        .nav-link { font-weight: 600; text-transform: uppercase; font-size: 0.8rem; letter-spacing: 0.5px; color: var(--muni-red-dark); position:relative; padding: 0.5rem 0.75rem; border-radius:2px; transition: var(--transition-fast); min-height:44px; display:inline-flex; align-items:center; }
        .nav-link:hover, .nav-link.active { color: var(--muni-red); background: var(--color-bg-secondary); }
        .nav-link.active::after { content:''; position:absolute; bottom:-3px; left:0; right:0; height:3px; background: var(--muni-red); }
        .breaking-ticker { background: var(--muni-red); color:#fff; }
        .breaking-ticker span.label { background: var(--muni-gold); color: var(--muni-red-dark); font-weight:800; text-transform:uppercase; }
        .card-muni { border-radius:2px; box-shadow: var(--shadow-sm); transition: var(--transition-base); background:#fff; overflow:hidden; display:flex; flex-direction:column; }
        .card-muni:hover { transform: translateY(-4px); box-shadow: var(--shadow-lg); }
        .card-muni img { aspect-ratio:16/9; object-fit:cover; width:100%; }
        .card-title { display:-webkit-box; -webkit-line-clamp:2; -webkit-box-orient:vertical; overflow:hidden; font-weight:700; line-height:1.3; }
        .card-excerpt { display:-webkit-box; -webkit-line-clamp:3; -webkit-box-orient:vertical; overflow:hidden; color: var(--color-text-secondary); font-size:0.9rem; }
        .badge-muni { background: var(--muni-red); color:#fff; font-size:0.75rem; text-transform:uppercase; letter-spacing:0.5px; border-radius:2px; padding:0.25rem 0.5rem; font-weight:700; }
        .badge-category { background: var(--muni-red-dark); }
        .btn-muni { background: var(--muni-red); color:#fff; border-radius:2px; text-transform:uppercase; font-weight:700; letter-spacing:0.5px; min-height:44px; padding:0.5rem 1.25rem; border:none; }
        .btn-muni:hover { background: var(--muni-red-dark); color:#fff; }
        .btn-muni:focus { outline:2px solid var(--muni-blue); outline-offset:2px; }
        .newsletter-band { background: var(--muni-red); color:#fff; }
        .footer-muni { background: #1a1a1a; color:#cbd5e0; }
        .footer-muni a { color:#e2e8f0; }
        .footer-muni a:hover { color: var(--muni-gold); }
        .footer-bottom { border-top:1px solid #2d3748; background: #111; }
        /* Lead paragraph */
        .lead { font-size:1.15rem; border-left:4px solid var(--muni-gold); padding-left:1rem; color: var(--color-text-secondary); }
        blockquote { border-left:4px solid var(--muni-red); padding-left:1rem; font-family: var(--font-heading); font-style:italic; color: var(--color-text-secondary); }
        .article-content img { max-width:100%; height:auto; display:block; margin:1.5rem auto; box-shadow: var(--shadow-md); border-radius:2px; }
        .article-content { max-width:75ch; line-height:1.8; }
        @media (max-width:768px){ .article-content{ max-width:100%; } }
        /* Accessibility */
        a:focus-visible, button:focus-visible { outline:2px solid var(--muni-blue); outline-offset:2px; }
        /* Transitions */
        * { scrollbar-width: thin; }
    </style>
    @stack('styles')
    @yield('head')
    <script type="application/ld+json">
    {!! json_encode([
        '@context' => 'https://schema.org',
        '@type' => 'Organization',
        'name' => 'Muni University',
        'url' => 'https://news.muni.ac.ug',
        'logo' => asset('assets/images/muni-logo.png'),
        'sameAs' => ['https://www.muni.ac.ug', 'https://news.muni.ac.ug'],
    ], JSON_UNESCAPED_SLASHES|JSON_UNESCAPED_UNICODE) !!}
    </script>
</head>
<body class="antialiased bg-white" x-data="{ mobileMenu:false, searchOpen:false }">
    <!-- Top Bar -->
    <div class="top-bar py-1 px-4">
        <div class="max-w-7xl mx-auto flex flex-wrap justify-between items-center gap-2">
            <div class="flex items-center gap-4">
                <span><i class="fa-solid fa-location-dot me-1"></i> Arua City, Uganda</span>
                <span class="hidden sm:inline opacity-75">|</span>
                <span class="hidden sm:inline"><i class="fa-regular fa-calendar me-1"></i> {{ now()->format('l, F j, Y') }}</span>
            </div>
            <div class="flex items-center gap-3">
                <a href="https://www.muni.ac.ug" target="_blank" class="hover:text-[var(--muni-gold)]">Main Site</a>
                <span class="opacity-50">|</span>
                <a href="{{ route('login') }}" class="hover:text-[var(--muni-gold)]">@auth Dashboard @else Staff Login @endauth</a>
                <div class="hidden sm:flex items-center gap-2 ms-2">
                    <a href="#" aria-label="Facebook" class="w-7 h-7 rounded-sm flex items-center justify-center hover:bg-white/10" style="min-width:28px;min-height:28px;"><i class="fa-brands fa-facebook-f text-xs"></i></a>
                    <a href="#" aria-label="X Twitter" class="w-7 h-7 rounded-sm flex items-center justify-center hover:bg-white/10"><i class="fa-brands fa-x-twitter text-xs"></i></a>
                    <a href="#" aria-label="YouTube" class="w-7 h-7 rounded-sm flex items-center justify-center hover:bg-white/10"><i class="fa-brands fa-youtube text-xs"></i></a>
                </div>
            </div>
        </div>
    </div>

    <!-- Header Main -->
    <header class="header-main sticky top-0 z-40" role="banner">
        <div class="max-w-7xl mx-auto px-4">
            <div class="flex items-center justify-between py-3 gap-4">
                <!-- Logo -->
                <a href="{{ url('/') }}" class="flex items-center gap-3 flex-shrink-0" aria-label="Muni University Home">
                    <img src="/assets/images/muni-logo.png" alt="Muni University Logo" class="h-14 w-14 object-contain">
                    <div class="hidden sm:block">
                        <h1 class="text-lg font-black leading-none" style="color: var(--muni-red); font-family:var(--font-heading);">Muni University</h1>
                        <p class="text-xs tracking-[0.2em] uppercase font-bold" style="color: var(--muni-red-dark);">Transforming Lives</p>
                        <p class="text-xs" style="color: var(--muni-blue);">news.muni.ac.ug</p>
                    </div>
                    <div class="sm:hidden">
                        <h1 class="text-sm font-black" style="color: var(--muni-red);">MUNI</h1>
                        <p class="text-xs font-bold" style="color: var(--muni-red-dark);">NEWS</p>
                    </div>
                </a>

                <!-- Desktop Nav -->
                <nav class="hidden lg:flex items-center gap-1" aria-label="Main Navigation">
                    <a href="{{ url('/') }}" class="nav-link {{ request()->is('/') ? 'active' : '' }}">Home</a>
                    <a href="{{ route('news.index') }}" class="nav-link {{ request()->routeIs('news.*') ? 'active' : '' }}">News</a>
                    @php $navCats = \App\Models\Category::where('slug','!=','news')->orderBy('sort_order')->take(4)->get(); @endphp
                    @foreach($navCats as $cat)
                        <a href="{{ route('category.show', $cat->slug) }}" class="nav-link {{ request()->is('category/'.$cat->slug) ? 'active' : '' }}">{{ $cat->name }}</a>
                    @endforeach
                    <div class="relative" x-data="{ open:false }">
                        <button @click="open=!open" class="nav-link" aria-expanded="false" aria-haspopup="true">More <i class="fa-solid fa-chevron-down ms-1 text-xs"></i></button>
                        <div x-show="open" @click.away="open=false" x-transition class="absolute left-0 mt-2 w-48 bg-white rounded-sm shadow-lg border border-gray-200 py-2 z-50" style="border-top:3px solid var(--muni-gold);">
                            @foreach(\App\Models\Category::where('slug','!=','news')->orderBy('sort_order')->skip(4)->take(10)->get() as $cat)
                                <a href="{{ route('category.show', $cat->slug) }}" class="block px-4 py-2 text-sm hover:bg-gray-50">{{ $cat->name }}</a>
                            @endforeach
                            <div class="border-t my-1"></div>
                            <a href="{{ route('events.index') }}" class="block px-4 py-2 text-sm hover:bg-gray-50"><i class="fa-solid fa-calendar-days me-2"></i>Events</a>
                            <a href="{{ route('newsletters.index') }}" class="block px-4 py-2 text-sm hover:bg-gray-50"><i class="fa-solid fa-file-pdf me-2"></i>Newsletters</a>
                            <a href="{{ route('downloads.index') }}" class="block px-4 py-2 text-sm hover:bg-gray-50"><i class="fa-solid fa-download me-2"></i>Downloads</a>
                            <a href="{{ route('gallery.index') }}" class="block px-4 py-2 text-sm hover:bg-gray-50"><i class="fa-solid fa-images me-2"></i>Gallery</a>
                        </div>
                    </div>
                </nav>

                <!-- Actions -->
                <div class="flex items-center gap-2">
                    <button @click="searchOpen=!searchOpen" class="p-2 rounded-sm border border-gray-300 hover:bg-gray-50" aria-label="Search" style="min-width:44px;min-height:44px;"><i class="fa-solid fa-magnifying-glass"></i></button>
                    <a href="#subscribe" class="hidden sm:inline-flex btn-muni text-xs">Subscribe</a>
                    <button @click="mobileMenu=!mobileMenu" class="lg:hidden p-2 rounded-sm border border-gray-300 hover:bg-gray-50" aria-label="Toggle menu" :aria-expanded="mobileMenu.toString()" style="min-width:44px;min-height:44px;">
                        <i class="fa-solid" :class="mobileMenu ? 'fa-xmark' : 'fa-bars'"></i>
                    </button>
                </div>
            </div>

            <!-- Search Bar -->
            <div x-show="searchOpen" x-transition class="pb-4">
                <form action="{{ route('news.index') }}" method="GET" class="flex gap-2 max-w-xl ms-auto">
                    <label for="q" class="sr-only">Search</label>
                    <input type="search" name="q" id="q" value="{{ request('q') }}" placeholder="Search news..." class="flex-1 border-2 rounded-sm px-4 py-2 focus:border-[var(--muni-blue)] focus:ring-[var(--muni-blue)]" style="border-radius:2px;">
                    <button type="submit" class="btn-muni">Search</button>
                </form>
            </div>

            <!-- Mobile Menu -->
            <div x-show="mobileMenu" x-transition class="lg:hidden border-t border-gray-200 py-4">
                <nav class="flex flex-col gap-1">
                    <a href="{{ url('/') }}" class="px-3 py-2 rounded-sm font-semibold hover:bg-gray-50 {{ request()->is('/') ? 'bg-gray-100' : '' }}">Home</a>
                    <a href="{{ route('news.index') }}" class="px-3 py-2 rounded-sm hover:bg-gray-50">News</a>
                    @foreach(\App\Models\Category::where('slug','!=','news')->orderBy('sort_order')->get() as $cat)
                        <a href="{{ route('category.show', $cat->slug) }}" class="px-3 py-2 rounded-sm hover:bg-gray-50">• {{ $cat->name }}</a>
                    @endforeach
                    <a href="{{ route('events.index') }}" class="px-3 py-2 rounded-sm hover:bg-gray-50">Events</a>
                    <a href="{{ route('newsletters.index') }}" class="px-3 py-2 rounded-sm hover:bg-gray-50">Newsletters</a>
                    <a href="{{ route('downloads.index') }}" class="px-3 py-2 rounded-sm hover:bg-gray-50">Downloads</a>
                    <a href="{{ route('gallery.index') }}" class="px-3 py-2 rounded-sm hover:bg-gray-50">Gallery</a>
                </nav>
            </div>
        </div>
    </header>

    <!-- Breaking News Ticker -->
    @php $breaking = \App\Models\Article::where('is_breaking', true)->where('is_published', true)->latest('published_at')->take(5)->get(); @endphp
    @if($breaking->count())
    <div class="breaking-ticker py-2 px-4 overflow-hidden">
        <div class="max-w-7xl mx-auto flex items-center gap-3">
            <span class="label px-3 py-1 rounded-sm text-xs shrink-0"><i class="fa-solid fa-bolt me-1"></i> Breaking</span>
            <div class="flex-1 overflow-hidden">
                <div class="whitespace-nowrap animate-marquee flex gap-8" style="animation: marquee 30s linear infinite;">
                    @foreach($breaking as $b)
                        <a href="{{ route('article.show', $b->slug) }}" class="hover:underline text-sm font-semibold">{{ $b->title }} &nbsp;•&nbsp; </a>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
    <style>@keyframes marquee { 0% { transform: translateX(100%); } 100% { transform: translateX(-100%); } }</style>
    @endif

    <!-- Flash Messages -->
    @if(session('success'))
        <div class="max-w-7xl mx-auto px-4 mt-4">
            <div class="bg-green-50 border-l-4 p-4 rounded-sm flex items-center gap-2" style="border-color: var(--muni-blue);">
                <i class="fa-solid fa-circle-check" style="color: var(--muni-blue);"></i>
                <span class="text-sm font-medium text-green-800">{{ session('success') }}</span>
            </div>
        </div>
    @endif
    @if(session('error'))
        <div class="max-w-7xl mx-auto px-4 mt-4">
            <div class="bg-red-50 border-l-4 p-4 rounded-sm flex items-center gap-2" style="border-color: var(--muni-red);">
                <i class="fa-solid fa-circle-exclamation" style="color: var(--muni-red);"></i>
                <span class="text-sm font-medium text-red-800">{{ session('error') }}</span>
            </div>
        </div>
    @endif

    <!-- Main Content -->
    <main id="main" role="main">
        @yield('content')
        {{ $slot ?? '' }}
    </main>

    <!-- Newsletter CTA Band -->
    <section id="subscribe" class="newsletter-band py-10 px-4 mt-12">
        <div class="max-w-7xl mx-auto grid md:grid-cols-2 gap-6 items-center">
            <div>
                <h2 class="text-2xl font-bold" style="font-family:var(--font-heading);">Stay Informed</h2>
                <p class="mt-2 opacity-90">Subscribe to Muni University newsletter and never miss an update from the campus.</p>
            </div>
            <form action="{{ route('subscribe') }}" method="POST" class="flex gap-2 max-w-md ms-auto w-full">
                @csrf
                <label for="subscribe_email" class="sr-only">Email</label>
                <input type="email" name="email" id="subscribe_email" required placeholder="Your email address" class="flex-1 rounded-sm px-4 py-3 text-gray-900 focus:outline-none focus:ring-2 focus:ring-[var(--muni-gold)]" style="min-height:44px; border-radius:2px;">
                <button type="submit" class="bg-white font-bold uppercase text-xs tracking-wider px-6 py-3 rounded-sm hover:bg-gray-100" style="color: var(--muni-red); min-height:44px; border-radius:2px;">Subscribe</button>
            </form>
        </div>
    </section>

    <!-- Footer -->
    <footer class="footer-muni" role="contentinfo">
        <div class="max-w-7xl mx-auto px-4 py-10 grid md:grid-cols-4 gap-8">
            <div>
                <div class="flex items-center gap-3 mb-4">
                    <img src="/assets/images/muni-logo.png" alt="Muni Logo" class="h-10 w-10 bg-white rounded-sm p-1">
                    <div>
                        <h3 class="font-bold" style="font-family:var(--font-heading); color:#fff;">Muni University</h3>
                        <p class="text-xs tracking-widest uppercase" style="color: var(--muni-gold);">Transforming Lives</p>
                    </div>
                </div>
                <p class="text-sm opacity-80">Muni University is a public university in Arua City, Uganda. Committed to excellence in teaching, research and community service.</p>
                <div class="flex gap-3 mt-4">
                    <a href="#" aria-label="Facebook" class="w-9 h-9 rounded-sm flex items-center justify-center bg-white/10 hover:bg-[var(--muni-red)]"><i class="fa-brands fa-facebook-f"></i></a>
                    <a href="#" aria-label="X" class="w-9 h-9 rounded-sm flex items-center justify-center bg-white/10 hover:bg-[var(--muni-red)]"><i class="fa-brands fa-x-twitter"></i></a>
                    <a href="#" aria-label="LinkedIn" class="w-9 h-9 rounded-sm flex items-center justify-center bg-white/10 hover:bg-[var(--muni-red)]"><i class="fa-brands fa-linkedin-in"></i></a>
                    <a href="#" aria-label="YouTube" class="w-9 h-9 rounded-sm flex items-center justify-center bg-white/10 hover:bg-[var(--muni-red)]"><i class="fa-brands fa-youtube"></i></a>
                </div>
            </div>
            <div>
                <h4 class="font-bold mb-3" style="color: var(--muni-gold);">Quick Links</h4>
                <ul class="space-y-2 text-sm">
                    <li><a href="{{ url('/') }}">Home</a></li>
                    <li><a href="{{ route('news.index') }}">News</a></li>
                    <li><a href="{{ route('events.index') }}">Events</a></li>
                    <li><a href="{{ route('newsletters.index') }}">Newsletters</a></li>
                    <li><a href="{{ route('downloads.index') }}">Downloads</a></li>
                    <li><a href="{{ route('gallery.index') }}">Gallery</a></li>
                </ul>
            </div>
            <div>
                <h4 class="font-bold mb-3" style="color: var(--muni-gold);">Categories</h4>
                <ul class="space-y-2 text-sm">
                    @foreach(\App\Models\Category::orderBy('sort_order')->take(6)->get() as $cat)
                        <li><a href="{{ route('category.show', $cat->slug) }}">{{ $cat->name }}</a></li>
                    @endforeach
                </ul>
            </div>
            <div>
                <h4 class="font-bold mb-3" style="color: var(--muni-gold);">Contact</h4>
                <ul class="space-y-2 text-sm opacity-80">
                    <li><i class="fa-solid fa-location-dot me-2"></i> P.O. Box 725, Arua City, Uganda</li>
                    <li><i class="fa-solid fa-phone me-2"></i> +256 123 456 789</li>
                    <li><i class="fa-solid fa-envelope me-2"></i> info@muni.ac.ug</li>
                    <li><i class="fa-solid fa-globe me-2"></i> news.muni.ac.ug</li>
                </ul>
            </div>
        </div>
        <div class="footer-bottom py-4 px-4 text-center text-sm">
            <div class="max-w-7xl mx-auto flex flex-col sm:flex-row justify-between items-center gap-2">
                <span>&copy; {{ date('Y') }} Muni University. All rights reserved.</span>
                <span>Designed for <span style="color: var(--muni-gold);">Transforming Lives</span></span>
            </div>
        </div>
    </footer>

    @stack('scripts')

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
</body>
</html>
