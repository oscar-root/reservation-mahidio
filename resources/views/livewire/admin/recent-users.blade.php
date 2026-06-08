<?php
use App\Models\User;
use function Livewire\Volt\{with};

with(fn () => [
    'recentUsers' => User::latest()->take(5)->get(),
]);
?>

<div class="divide-y divide-slate-100">
    @foreach($recentUsers as $user)
        <div class="py-4 flex items-center justify-between">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-full bg-slate-100 flex items-center justify-center text-slate-500 font-bold">
                    {{ substr($user->name, 0, 1) }}
                </div>
                <div>
                    <p class="text-sm font-bold text-slate-800">{{ $user->name }}</p>
                    <p class="text-xs text-slate-400">{{ $user->email }}</p>
                </div>
            </div>
            <span class="px-2 py-1 rounded-lg text-[10px] font-black uppercase bg-slate-100 text-slate-500">
                {{ $user->role }}
            </span>
        </div>
    @endforeach
</div>