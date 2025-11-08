<div class="flex items-center justify-between">
    <div class="flex items-center gap-4">
        <a href="{{ route('users.index') }}" class="flex items-center gap-2">
            <img src="{{ asset('images/logos/favicon.png') }}" alt="Mini CRM Logo" class="h-8 w-auto" />
            <span class="text-white text-lg font-semibold">Mini CRM</span>
        </a>
    </div>
    <div class="flex items-center gap-4">
        @auth
            <span class="text-white text-sm">{{ Auth::user()->name }}</span>
            <form method="POST" action="{{ route('logout') }}" class="inline">
                @csrf
                <button type="submit" class="text-white text-sm hover:text-gray-200">Logout</button>
            </form>
        @else
            <a href="{{ route('login') }}" class="text-white text-sm hover:text-gray-200">Login</a>
        @endauth
    </div>
</div>
