<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl">
                Manage Users
            </h2>
            <a href="{{ route('admin.users.create') }}" class="bg-green-600 text-white px-4 py-2 rounded">
                + Add New User
            </a>
        </div>
    </x-slot>

    <div class="py-6 max-w-6xl mx-auto">
        @if($message = session('success'))
            <div class="mb-4 p-4 bg-green-100 border border-green-400 text-green-700 rounded">
                {{ $message }}
            </div>
        @endif

        <div class="overflow-x-auto">
            <table class="w-full border">
                <thead>
                    <tr class="bg-gray-200">
                        <th class="border p-2">Name</th>
                        <th class="border p-2">Email</th>
                        <th class="border p-2">Role</th>
                        <th class="border p-2">Type</th>
                        <th class="border p-2">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($users as $user)
                    <tr class="hover:bg-gray-100">
                        <td class="border p-2">{{ $user->name }}</td>
                        <td class="border p-2">{{ $user->email }}</td>
                        <td class="border p-2">
                            <span class="px-2 py-1 rounded text-sm {{ $user->role === 'admin' ? 'bg-red-100 text-red-700' : 'bg-blue-100 text-blue-700' }}">
                                {{ ucfirst($user->role) }}
                            </span>
                        </td>
                        <td class="border p-2">
                            @if($user->participant)
                                <span class="text-sm">Participant</span>
                            @else
                                <span class="text-sm text-gray-600">-</span>
                            @endif
                        </td>
                        <td class="border p-2">
                            <div class="flex gap-2">
                                <a href="{{ route('admin.users.edit', $user) }}" class="text-blue-600 hover:underline">Edit</a>
                                <form method="POST" action="{{ route('admin.users.destroy', $user) }}" style="display: inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-600 hover:underline" onclick="return confirm('Are you sure?')">Delete</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        @if($users->isEmpty())
            <div class="text-center py-8 text-gray-500">
                No users found
            </div>
        @endif
    </div>
</x-app-layout>
