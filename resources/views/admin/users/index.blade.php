<x-app-layout>
    <div class="p-6 space-y-6">

        {{-- HEADER --}}
        <div class="bg-gradient-to-r from-indigo-600 to-blue-500 text-white p-6 rounded-2xl shadow flex justify-between items-center">
            <div>
                <h2 class="text-xl font-semibold">Manage Users</h2>
                <p class="text-sm opacity-80">Kelola akun admin & peserta</p>
            </div>

            <a href="{{ route('admin.users.create') }}"
               class="bg-white text-indigo-600 px-4 py-2 rounded-lg text-sm font-medium hover:bg-gray-100">
                + Add User
            </a>
        </div>

        {{-- ALERT --}}
        @if($message = session('success'))
            <div class="bg-green-100 text-green-700 px-4 py-3 rounded-lg">
                {{ $message }}
            </div>
        @endif

        {{-- TABLE CARD --}}
        <div class="bg-white rounded-2xl shadow overflow-hidden">

            <div class="px-6 py-4 border-b font-semibold text-gray-700">
                User List
            </div>

            <table class="w-full text-sm">
                <thead class="bg-gray-50 text-gray-500 uppercase text-xs">
                    <tr>
                        <th class="p-4 text-left">User</th>
                        <th class="p-4">Role</th>
                        <th class="p-4">Type</th>
                        <th class="p-4 text-right">Action</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse ($users as $user)
                    <tr class="border-t hover:bg-gray-50">

                        {{-- USER --}}
                        <td class="p-4 flex items-center gap-3">
                            <div class="w-10 h-10 rounded-full bg-indigo-100 flex items-center justify-center text-indigo-600 font-bold">
                                {{ strtoupper(substr($user->name,0,1)) }}
                            </div>
                            <div>
                                <p class="font-medium text-gray-800">{{ $user->name }}</p>
                                <p class="text-xs text-gray-500">{{ $user->email }}</p>
                            </div>
                        </td>

                        {{-- ROLE --}}
                        <td class="p-4 text-center">
                            <span class="px-3 py-1 rounded-full text-xs
                                {{ $user->role === 'admin' 
                                    ? 'bg-red-100 text-red-600' 
                                    : 'bg-blue-100 text-blue-600' }}">
                                {{ ucfirst($user->role) }}
                            </span>
                        </td>

                        {{-- TYPE --}}
                        <td class="p-4 text-center">
                            {{ $user->participant ? 'Participant' : '-' }}
                        </td>

                        {{-- ACTION --}}
                        <td class="p-4 text-right">
                            <div class="flex justify-end gap-3 text-sm">
                                <a href="{{ route('admin.users.edit', $user) }}"
                                   class="text-indigo-600 hover:underline">
                                   Edit
                                </a>

                                <form method="POST" action="{{ route('admin.users.destroy', $user) }}">
                                    @csrf
                                    @method('DELETE')
                                    <button onclick="return confirm('Delete user?')"
                                        class="text-red-500 hover:underline">
                                        Delete
                                    </button>
                                </form>
                            </div>
                        </td>

                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="text-center py-10 text-gray-400">
                            No users found 🥀
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>

        </div>

    </div>
</x-app-layout>