<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - Restaurant AI</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="min-h-screen bg-slate-950 flex items-center justify-center p-4">
    <div class="w-full max-w-md">
        <!-- Logo/Header -->
        <div class="text-center mb-8">
            <h1 class="text-4xl font-black text-white uppercase tracking-tighter">
                Restaurant <span class="text-orange-500">AI</span>
            </h1>
            <p class="text-slate-500 mt-2">Create your account</p>
        </div>

        <!-- Registration Form -->
        <div class="bg-slate-900 border border-slate-800 rounded-2xl p-8">
            @if ($errors->any())
                <div class="mb-6 p-4 bg-red-500/10 border border-red-500/20 rounded-lg">
                    @foreach ($errors->all() as $error)
                        <p class="text-red-400 text-sm">{{ $error }}</p>
                    @endforeach
                </div>
            @endif

            <form action="{{ route('register') }}" method="POST">
                @csrf
                <div class="space-y-6">
                    <div>
                        <label for="name" class="block text-sm font-medium text-slate-300 mb-2">
                            Full Name
                        </label>
                        <input 
                            type="text" 
                            id="name" 
                            name="name" 
                            required
                            value="{{ old('name') }}"
                            class="w-full px-4 py-3 bg-slate-800 border border-slate-700 rounded-lg text-white placeholder-slate-500 focus:outline-none focus:border-orange-500 focus:ring-1 focus:ring-orange-500 transition-all"
                            placeholder="John Doe"
                        >
                    </div>

                    <div>
                        <label for="email" class="block text-sm font-medium text-slate-300 mb-2">
                            Email Address
                        </label>
                        <input 
                            type="email" 
                            id="email" 
                            name="email" 
                            required
                            value="{{ old('email') }}"
                            class="w-full px-4 py-3 bg-slate-800 border border-slate-700 rounded-lg text-white placeholder-slate-500 focus:outline-none focus:border-orange-500 focus:ring-1 focus:ring-orange-500 transition-all"
                            placeholder="you@example.com"
                        >
                    </div>

                    <div>
                        <label for="password" class="block text-sm font-medium text-slate-300 mb-2">
                            Password
                        </label>
                        <input 
                            type="password" 
                            id="password" 
                            name="password" 
                            required
                            minlength="8"
                            class="w-full px-4 py-3 bg-slate-800 border border-slate-700 rounded-lg text-white placeholder-slate-500 focus:outline-none focus:border-orange-500 focus:ring-1 focus:ring-orange-500 transition-all"
                            placeholder="••••••••"
                        >
                    </div>

                    <div>
                        <label for="password_confirmation" class="block text-sm font-medium text-slate-300 mb-2">
                            Confirm Password
                        </label>
                        <input 
                            type="password" 
                            id="password_confirmation" 
                            name="password_confirmation" 
                            required
                            minlength="8"
                            class="w-full px-4 py-3 bg-slate-800 border border-slate-700 rounded-lg text-white placeholder-slate-500 focus:outline-none focus:border-orange-500 focus:ring-1 focus:ring-orange-500 transition-all"
                            placeholder="••••••••"
                        >
                    </div>

                    <div>
                        <label for="dietary_tags" class="block text-sm font-medium text-slate-300 mb-2">
                            Dietary Preferences (Optional)
                        </label>
                        <select 
                            id="dietary_tags" 
                            name="dietary_tags[]" 
                            multiple
                            class="w-full px-4 py-3 bg-slate-800 border border-slate-700 rounded-lg text-white focus:outline-none focus:border-orange-500 focus:ring-1 focus:ring-orange-500 transition-all"
                            size="4"
                        >
                            <option value="vegetarian">Vegetarian</option>
                            <option value="vegan">Vegan</option>
                            <option value="gluten-free">Gluten-Free</option>
                            <option value="dairy-free">Dairy-Free</option>
                            <option value="nut-free">Nut-Free</option>
                            <option value="halal">Halal</option>
                            <option value="kosher">Kosher</option>
                            <option value="low-carb">Low-Carb</option>
                        </select>
                        <p class="text-slate-500 text-xs mt-1">Hold Ctrl/Cmd to select multiple</p>
                    </div>

                    <button 
                        type="submit" 
                        class="w-full py-3 bg-orange-600 hover:bg-orange-500 text-white font-black text-sm uppercase tracking-widest rounded-lg shadow-lg shadow-orange-900/40 transition-all active:scale-95"
                    >
                        Create Account
                    </button>
                </div>
            </form>

            <div class="mt-6 text-center">
                <p class="text-slate-400 text-sm">
                    Already have an account? 
                    <a href="{{ route('login') }}" class="text-orange-500 hover:text-orange-400 font-medium transition-colors">
                        Sign in
                    </a>
                </p>
            </div>
        </div>
    </div>
</body>
</html>
