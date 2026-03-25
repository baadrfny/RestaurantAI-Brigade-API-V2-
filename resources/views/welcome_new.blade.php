<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Restaurant AI - Personalized Menu Recommendations</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="min-h-screen bg-slate-950 text-slate-200">
    <!-- Navigation -->
    <nav class="bg-slate-900/50 backdrop-blur-sm border-b border-slate-800">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-16">
                <div class="flex items-center">
                    <h1 class="text-2xl font-black text-white uppercase tracking-tighter">
                        Restaurant <span class="text-orange-500">AI</span>
                    </h1>
                </div>
                <div class="flex items-center space-x-4">
                    @guest
                        <a href="{{ route('login') }}" class="px-4 py-2 text-sm font-medium text-slate-300 hover:text-white transition-colors">
                            Login
                        </a>
                        <a href="{{ route('register') }}" class="px-4 py-2 bg-orange-600 hover:bg-orange-500 text-white font-medium text-sm rounded-lg transition-colors">
                            Sign Up
                        </a>
                    @else
                        <a href="/menu" class="px-4 py-2 bg-orange-600 hover:bg-orange-500 text-white font-medium text-sm rounded-lg transition-colors">
                            View Menu
                        </a>
                        <form action="{{ route('logout') }}" method="POST" class="inline">
                            @csrf
                            <button type="submit" class="px-4 py-2 text-sm font-medium text-slate-300 hover:text-white transition-colors">
                                Logout
                            </button>
                        </form>
                    @endguest
                </div>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <main class="flex-1">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-20">
            <div class="text-center">
                <h1 class="text-5xl md:text-6xl font-black text-white uppercase tracking-tighter mb-6">
                    Discover Your <span class="text-orange-500">Perfect</span> Meal
                </h1>
                <p class="text-xl text-slate-400 mb-8 max-w-3xl mx-auto leading-relaxed">
                    Get personalized menu recommendations powered by AI. Our system analyzes your dietary preferences 
                    and health needs to suggest the perfect dishes for you.
                </p>
                
                @guest
                    <div class="flex flex-col sm:flex-row gap-4 justify-center">
                        <a href="{{ route('register') }}" class="px-8 py-4 bg-orange-600 hover:bg-orange-500 text-white font-black text-lg uppercase tracking-widest rounded-xl shadow-lg shadow-orange-900/40 transition-all active:scale-95">
                            Get Started
                        </a>
                        <a href="{{ route('login') }}" class="px-8 py-4 bg-slate-800 hover:bg-slate-700 text-white font-black text-lg uppercase tracking-widest rounded-xl border border-slate-700 transition-all active:scale-95">
                            Sign In
                        </a>
                    </div>
                @else
                    <div class="flex justify-center">
                        <a href="/menu" class="px-8 py-4 bg-orange-600 hover:bg-orange-500 text-white font-black text-lg uppercase tracking-widest rounded-xl shadow-lg shadow-orange-900/40 transition-all active:scale-95">
                            View Menu
                        </a>
                    </div>
                @endguest
            </div>

            <!-- Features -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8 mt-20">
                <div class="text-center">
                    <div class="w-16 h-16 bg-cyan-500/10 rounded-full flex items-center justify-center mx-auto mb-4">
                        <svg class="w-8 h-8 text-cyan-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"></path>
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold text-white mb-2">AI-Powered Analysis</h3>
                    <p class="text-slate-400">Advanced artificial intelligence analyzes ingredients and nutritional information</p>
                </div>
                
                <div class="text-center">
                    <div class="w-16 h-16 bg-orange-500/10 rounded-full flex items-center justify-center mx-auto mb-4">
                        <svg class="w-8 h-8 text-orange-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold text-white mb-2">Personalized Profile</h3>
                    <p class="text-slate-400">Customized recommendations based on your dietary preferences and restrictions</p>
                </div>
                
                <div class="text-center">
                    <div class="w-16 h-16 bg-green-500/10 rounded-full flex items-center justify-center mx-auto mb-4">
                        <svg class="w-8 h-8 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold text-white mb-2">Health Scores</h3>
                    <p class="text-slate-400">Get compatibility scores and health warnings for each menu item</p>
                </div>
            </div>

            <!-- How it works -->
            <div class="mt-20 text-center">
                <h2 class="text-3xl font-bold text-white mb-12">How It Works</h2>
                <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
                    <div class="relative">
                        <div class="text-4xl font-black text-orange-500 mb-4">1</div>
                        <h3 class="text-lg font-bold text-white mb-2">Create Account</h3>
                        <p class="text-slate-400 text-sm">Sign up and set your dietary preferences</p>
                    </div>
                    <div class="relative">
                        <div class="text-4xl font-black text-orange-500 mb-4">2</div>
                        <h3 class="text-lg font-bold text-white mb-2">Browse Menu</h3>
                        <p class="text-slate-400 text-sm">Explore our restaurant menu items</p>
                    </div>
                    <div class="relative">
                        <div class="text-4xl font-black text-orange-500 mb-4">3</div>
                        <h3 class="text-lg font-bold text-white mb-2">Get Analysis</h3>
                        <p class="text-slate-400 text-sm">AI analyzes compatibility with your profile</p>
                    </div>
                    <div class="relative">
                        <div class="text-4xl font-black text-orange-500 mb-4">4</div>
                        <h3 class="text-lg font-bold text-white mb-2">Enjoy</h3>
                        <p class="text-slate-400 text-sm">Make informed dining decisions</p>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <!-- Footer -->
    <footer class="bg-slate-900 border-t border-slate-800 py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <p class="text-slate-500 text-sm">
                © 2024 Restaurant AI. Powered by advanced artificial intelligence.
            </p>
        </div>
    </footer>
</body>
</html>
