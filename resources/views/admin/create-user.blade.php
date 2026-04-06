<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800">
            Add New User
        </h2>
    </x-slot>

    <div class="py-6 max-w-xl mx-auto">

        <form method="POST" action="{{ route('admin.users.store') }}">
            @csrf

            {{-- Name --}}
            <label class="font-medium block mb-1">Name</label>
            <input type="text" name="name" required
                class="w-full border rounded p-2 mb-4">

            {{-- Email --}}
            <label class="font-medium block mb-1">Email</label>
            <input type="email" name="email" required
                class="w-full border rounded p-2 mb-4">

            {{-- Role --}}
            <label class="font-medium block mb-1">Role</label>
            <select name="role" required
                class="w-full border rounded p-2 mb-4 bg-white">
                <option value="">-- Select Role --</option>
                <option value="user">User</option>
                <option value="admin">Admin</option>
            </select>

            {{-- Password --}}
            <label class="font-medium block mb-1">Password</label>
            <input type="password" name="password" required
                class="w-full border rounded p-2 mb-4">

            {{-- Confirm Password --}}
            <label class="font-medium block mb-1">Confirm Password</label>
            <input type="password" name="password_confirmation" required
                class="w-full border rounded p-2 mb-4">

            {{-- Tombol --}}
            <div class="mt-2">
                <button type="submit" 
                    class="bg-blue-600 text-white py-3 px-6 rounded hover:bg-blue-700 transition font-medium w-full sm:w-auto">
                    Create User
                </button>
            </div>

        </form>
        
    </div>
</x-app-layout>