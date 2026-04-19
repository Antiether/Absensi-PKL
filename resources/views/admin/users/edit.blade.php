<x-app-layout>
    <div class="p-6 max-w-2xl mx-auto space-y-6">

        {{-- HEADER --}}
        <div class="bg-gradient-to-r from-indigo-600 to-blue-500 text-white p-6 rounded-2xl shadow">
            <h2 class="text-xl font-semibold">Edit User</h2>
            <p class="text-sm opacity-80">{{ $user->name }}</p>
        </div>

        {{-- FORM --}}
        <form method="POST" action="{{ route('admin.users.update', $user->id) }}"
              class="bg-white p-6 rounded-2xl shadow space-y-5">
            @csrf
            @method('PATCH')

            {{-- NAME --}}
            <div>
                <label class="text-sm text-gray-600">Name</label>
                <input type="text" name="name"
                    value="{{ old('name', $user->name) }}"
                    class="w-full border px-3 py-2 rounded-lg mt-1">
            </div>

            {{-- EMAIL --}}
            <div>
                <label class="text-sm text-gray-600">Email</label>
                <input type="email" name="email"
                    value="{{ old('email', $user->email) }}"
                    class="w-full border px-3 py-2 rounded-lg mt-1">
            </div>

            {{-- ROLE --}}
            <div>
                <label class="text-sm text-gray-600">Role</label>
                <select name="role" id="role"
                    class="w-full border px-3 py-2 rounded-lg mt-1">
                    <option value="admin" {{ $user->role === 'admin' ? 'selected' : '' }}>
                        Admin
                    </option>
                    <option value="user" {{ $user->role === 'user' ? 'selected' : '' }}>
                        User
                    </option>
                </select>
            </div>

            {{-- PARTICIPANT --}}
            <div id="participant-fields" class="space-y-3">
                <div>
                    <label class="text-sm text-gray-600">NPM</label>
                    <input type="text" name="npm"
                        value="{{ old('npm', optional($user->participant)->npm) }}"
                        class="w-full border px-3 py-2 rounded-lg mt-1">
                </div>

                <div>
                    <label class="text-sm text-gray-600">Institution</label>
                    <input type="text" name="institusi"
                        value="{{ old('institusi', optional($user->participant)->institusi) }}"
                        class="w-full border px-3 py-2 rounded-lg mt-1">
                </div>
            </div>

            {{-- PASSWORD --}}
            <div>
                <label class="text-sm text-gray-600">
                    Password (leave blank if no change)
                </label>
                <input type="password" name="password"
                    class="w-full border px-3 py-2 rounded-lg mt-1">
            </div>

            <div>
                <label class="text-sm text-gray-600">Confirm Password</label>
                <input type="password" name="password_confirmation"
                    class="w-full border px-3 py-2 rounded-lg mt-1">
            </div>

            {{-- ACTION --}}
            <div class="flex justify-end gap-3 pt-4">
                <a href="{{ route('admin.users') }}"
                   class="px-4 py-2 bg-gray-200 rounded-lg">
                    Cancel
                </a>

                <button type="submit"
                    class="px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700">
                    Update
                </button>
            </div>

        </form>

    </div>

    {{-- SCRIPT --}}
    <script>
        const role = document.getElementById('role');
        const field = document.getElementById('participant-fields');

        function toggle() {
            if (role.value === 'user') {
                field.style.display = 'block';
            } else {
                field.style.display = 'none';
            }
        }

        role.addEventListener('change', toggle);
        toggle();
    </script>
</x-app-layout>