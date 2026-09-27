<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>MarketLink – Fresh from Local Farms</title>

        <!-- Styles / Scripts -->
        @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
            @vite(['resources/css/app.css', 'resources/js/app.js'])
        @else
            <style>
                *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
                body { font-family: ui-sans-serif, system-ui, sans-serif; background: #f9fafb; color: #111827; }
                .hero { background: linear-gradient(135deg, #16a34a 0%, #15803d 100%); color: white; padding: 5rem 1.5rem; text-align: center; }
                .hero h1 { font-size: 2.5rem; font-weight: 800; margin-bottom: 1rem; }
                .hero p { font-size: 1.125rem; opacity: .9; max-width: 560px; margin: 0 auto 2rem; }
                .btn { display: inline-block; padding: .75rem 1.75rem; border-radius: .5rem; font-weight: 600; text-decoration: none; margin: .35rem; transition: opacity .15s; }
                .btn:hover { opacity: .88; }
                .btn-white { background: white; color: #16a34a; }
                .btn-outline { background: transparent; color: white; border: 2px solid white; }
                .features { max-width: 1100px; margin: 4rem auto; padding: 0 1.5rem; display: grid; grid-template-columns: repeat(auto-fit, minmax(280px, 1fr)); gap: 1.5rem; }
                .card { background: white; border-radius: 1rem; padding: 2rem; box-shadow: 0 1px 4px rgba(0,0,0,.08); }
                .card-icon { font-size: 2.5rem; margin-bottom: 1rem; }
                .card h3 { font-size: 1.125rem; font-weight: 700; color: #15803d; margin-bottom: .5rem; }
                .card p { color: #6b7280; line-height: 1.6; }
                .nav-bar { background: white; border-bottom: 1px solid #e5e7eb; padding: .75rem 1.5rem; display: flex; justify-content: space-between; align-items: center; }
                .nav-brand { font-weight: 800; font-size: 1.125rem; color: #16a34a; text-decoration: none; }
                .nav-links a { margin-left: 1rem; color: #374151; text-decoration: none; font-size: .9rem; }
                .nav-links a:hover { color: #16a34a; }
                footer { text-align: center; padding: 2rem; color: #9ca3af; font-size: .875rem; border-top: 1px solid #e5e7eb; }
            </style>
        @endif
    </head>
    <body class="antialiased bg-gray-50 text-gray-900">

        <!-- Navigation Bar -->
        <nav class="bg-white border-b border-gray-100 shadow-sm">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 flex items-center justify-between h-16">
                <a href="/" class="font-bold text-xl text-green-600">🌿 MarketLink</a>
                <div class="flex items-center gap-4">
                    @auth
                        <a href="{{ url('/dashboard') }}"
                           class="inline-block px-5 py-2 bg-green-600 hover:bg-green-700 text-white text-sm font-semibold rounded-lg transition">
                            Go to Dashboard
                        </a>
                    @else
                        @if (Route::has('login'))
                            <a href="{{ route('login') }}"
                               class="text-sm text-gray-600 hover:text-green-700 font-medium">
                                Log in
                            </a>
                        @endif
                        @if (Route::has('register'))
                            <a href="{{ route('register') }}"
                               class="inline-block px-5 py-2 bg-green-600 hover:bg-green-700 text-white text-sm font-semibold rounded-lg transition">
                                Register
                            </a>
                        @endif
                    @endauth
                </div>
            </div>
        </nav>

        <!-- Hero Section -->
        <section class="bg-gradient-to-br from-green-600 to-green-800 text-white">
            <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-24 text-center">
                <h1 class="text-4xl sm:text-5xl font-extrabold tracking-tight mb-4">
                    Welcome to MarketLink
                </h1>
                <p class="text-lg sm:text-xl text-green-100 max-w-2xl mx-auto mb-10">
                    Fresh from local farms, direct to your table. Pre-order from local farmers markets near you.
                </p>

                @auth
                    <a href="{{ url('/dashboard') }}"
                       class="inline-block px-8 py-3 bg-white text-green-700 font-bold rounded-xl shadow-lg hover:bg-green-50 transition text-lg">
                        Go to Dashboard →
                    </a>
                @else
                    <div class="flex flex-col sm:flex-row items-center justify-center gap-4">
                        @if (Route::has('register'))
                            <a href="{{ route('register') }}"
                               class="inline-block px-8 py-3 bg-white text-green-700 font-bold rounded-xl shadow-lg hover:bg-green-50 transition text-lg">
                                Browse as Customer
                            </a>
                            <a href="{{ route('register') }}"
                               class="inline-block px-8 py-3 bg-transparent text-white font-bold rounded-xl border-2 border-white hover:bg-white hover:text-green-700 transition text-lg">
                                Register as Farmer
                            </a>
                        @endif
                    </div>
                @endauth
            </div>
        </section>

        <!-- Feature Cards -->
        <section class="py-20 bg-gray-50">
            <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
                <h2 class="text-3xl font-bold text-center text-gray-800 mb-12">
                    How MarketLink Works
                </h2>
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-8">
                    <!-- Card 1 -->
                    <div class="bg-white rounded-2xl p-8 shadow-sm border border-gray-100 text-center">
                        <div class="text-5xl mb-4">🗺️</div>
                        <h3 class="text-xl font-bold text-green-700 mb-3">Discover Local Markets</h3>
                        <p class="text-gray-500 leading-relaxed">
                            Find farmers markets in your area. Browse schedules, locations, and the farmers who sell there.
                        </p>
                    </div>
                    <!-- Card 2 -->
                    <div class="bg-white rounded-2xl p-8 shadow-sm border border-gray-100 text-center">
                        <div class="text-5xl mb-4">🥦</div>
                        <h3 class="text-xl font-bold text-green-700 mb-3">Browse Fresh Products</h3>
                        <p class="text-gray-500 leading-relaxed">
                            Explore seasonal produce, artisan goods, and specialty items offered by local farmers.
                        </p>
                    </div>
                    <!-- Card 3 -->
                    <div class="bg-white rounded-2xl p-8 shadow-sm border border-gray-100 text-center">
                        <div class="text-5xl mb-4">📦</div>
                        <h3 class="text-xl font-bold text-green-700 mb-3">Place Pre-Orders</h3>
                        <p class="text-gray-500 leading-relaxed">
                            Reserve your favourite products ahead of market day and pick them up at a convenient time slot.
                        </p>
                    </div>
                </div>
            </div>
        </section>

        <!-- Footer -->
        <footer class="bg-white border-t border-gray-100 py-8 text-center text-gray-400 text-sm">
            &copy; {{ date('Y') }} MarketLink. Fresh, local, and direct.
        </footer>

    </body>
</html>
