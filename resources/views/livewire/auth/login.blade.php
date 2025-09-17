<div>
    <div class="flex items-center justify-center min-h-screen bg-gray-100">
        <div class="w-full max-w-sm bg-white shadow-lg rounded-xl p-6">
            <h2 class="text-2xl font-bold text-center mb-6">Login</h2>

            <form wire:submit.prevent="login">
                <div class="mb-4">
                    <label class="block text-sm font-medium mb-1">Email</label>
                    <input type="email" wire:model="email"
                        class="w-full border rounded-lg px-3 py-2 focus:outline-none focus:ring focus:border-blue-300">
                    @error('email') <span class="text-red-600 text-xs">{{ $message }}</span> @enderror
                </div>

                <div class="mb-4">
                    <label class="block text-sm font-medium mb-1">Password</label>
                    <input type="password" wire:model="password"
                        class="w-full border rounded-lg px-3 py-2 focus:outline-none focus:ring focus:border-blue-300">
                    @error('password') <span class="text-red-600 text-xs">{{ $message }}</span> @enderror
                </div>

                <button type="submit"
                        class="w-full bg-blue-600 text-white py-2 rounded-lg hover:bg-blue-700">
                    Login
                </button>
            </form>
        </div>
    </div>

</div>
