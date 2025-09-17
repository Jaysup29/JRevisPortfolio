<x-layouts.app>
    <x-slot:header>
        <nav class="bg-gray-800 text-white p-4">
            <div class="container mx-auto flex justify-between items-center">
                <span class="font-bold">Jay-ar Revis Portfolio</span>
                <div>
                    <a href="{{ route('dashboard') }}" class="px-3">Dashboard</a>
                    <form method="POST" action="{{ route('logout') }}" class="inline">
                        @csrf
                        <button type="submit" class="px-3 hover:underline">Logout</button>
                    </form>
                </div>
            </div>
        </nav>
    </x-slot:header>

    <div class="p-8">
        <h1 class="text-2xl font-bold mb-4">Dashboard</h1>
        <p class="text-gray-700">
            Welcome back, <strong>{{ auth()->user()->name ?? 'Guest' }}</strong> 👋
        </p>
    </div>

    <x-slot:footer>
        <div class="text-center text-sm text-gray-500 py-4">
            &copy; {{ date('Y') }} Jay-ar Revis. All rights reserved.
        </div>
    </x-slot:footer>
</x-layouts.app>
