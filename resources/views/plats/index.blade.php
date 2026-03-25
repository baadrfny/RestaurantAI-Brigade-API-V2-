<div class="min-h-screen bg-slate-950 p-8 text-slate-200">
    <div class="max-w-7xl mx-auto mb-12 flex justify-between items-end">
        <div>
            <h1 class="text-4xl font-black text-white uppercase tracking-tighter">
                Discover <span class="text-orange-500">Menu</span>
            </h1>
            <p class="text-slate-500 mt-2 font-medium">Personalized recommendations based on your dietary profile.</p>
        </div>
        <div class="bg-slate-900 px-4 py-2 rounded-lg border border-slate-800">
            <span class="text-xs text-slate-500 block uppercase font-bold">Your Profile</span>
            <span class="text-cyan-400 font-mono text-sm">
                {{ implode(', ', auth()->user()->dietary_tags ?? ['No Tags Set']) }}
            </span>
        </div>
    </div>

    <div class="max-w-7xl mx-auto grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
        @foreach($plates as $plate)
            <div class="bg-slate-900 border border-slate-800 rounded-3xl overflow-hidden hover:border-orange-500/40 transition-all duration-300 group">
                <div class="p-6">
                    <div class="flex justify-between items-start mb-4">
                        <span class="bg-cyan-950/50 text-cyan-400 text-[10px] font-black uppercase px-3 py-1 rounded-full border border-cyan-500/20">
                            {{ $plate->category->name }}
                        </span>
                        <span class="text-orange-500 font-black text-xl">${{ number_format($plate->price, 2) }}</span>
                    </div>
                    
                    <h3 class="text-2xl font-bold text-white mb-2 group-hover:text-orange-500 transition-colors">
                        {{ $plate->name }}
                    </h3>
                    <p class="text-slate-400 text-sm leading-relaxed mb-6 line-clamp-2">
                        {{ $plate->description }}
                    </p>

                    <div class="bg-black/40 border border-slate-800 rounded-2xl p-5">
                        @php
                            $recommendation = $plate->recommendations->where('user_id', auth()->id())->first();
                        @endphp

                        @if($recommendation)
                            @if($recommendation->status === 'ready')
                                <div class="flex items-center justify-between mb-3">
                                    <span class="text-[10px] font-bold text-slate-500 uppercase">Compatibility Score</span>
                                    <span class="text-[10px] font-bold text-green-500 bg-green-500/10 px-2 py-0.5 rounded">READY</span>
                                </div>
                                
                                <div class="flex items-baseline gap-2">
                                    <span class="text-4xl font-black {{ $recommendation->score >= 80 ? 'text-green-500' : ($recommendation->score >= 50 ? 'text-orange-500' : 'text-red-500') }}">
                                        {{ $recommendation->score }}%
                                    </span>
                                    <span class="text-sm font-bold text-slate-300 uppercase tracking-tight">
                                        {{ $recommendation->label }}
                                    </span>
                                </div>

                                @if($recommendation->warning_message)
                                    <div class="mt-4 p-3 bg-red-500/10 border border-red-500/20 rounded-lg">
                                        <p class="text-[11px] text-red-400 leading-tight">
                                            <span class="font-bold">WARNING:</span> {{ $recommendation->warning_message }}
                                        </p>
                                    </div>
                                @endif

                            @else
                                <div class="flex flex-col items-center py-4">
                                    <div class="w-8 h-8 border-4 border-cyan-500/20 border-t-cyan-500 rounded-full animate-spin mb-3"></div>
                                    <p class="text-cyan-400 text-xs font-bold uppercase animate-pulse">AI is analyzing ingredients...</p>
                                </div>
                            @endif

                        @else
                            <form action="{{ route('recommendations.analyze', $plate->id) }}" method="POST">
                                @csrf
                                <button type="submit" class="w-full py-3 bg-orange-600 hover:bg-orange-500 text-white font-black text-xs uppercase tracking-widest rounded-xl shadow-lg shadow-orange-900/40 transition-all active:scale-95">
                                    Start AI Analysis
                                </button>
                            </form>
                        @endif
                    </div>
                </div>
            </div>
        @endforeach
    </div>
</div>