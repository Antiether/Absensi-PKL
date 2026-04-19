<x-app-layout>
    <div class="p-6 max-w-2xl mx-auto space-y-6">

        {{-- HEADER --}}
        <div class="bg-gradient-to-r from-indigo-600 to-blue-500 text-white p-6 rounded-2xl shadow">
            <h2 class="text-xl font-semibold">Add New User</h2>
            <p class="text-sm opacity-80">Buat akun baru</p>
        </div>

        {{-- FORM CARD --}}
        <form method="POST" action="{{ route('admin.users.store') }}"
              class="bg-white p-6 rounded-2xl shadow space-y-5">
            @csrf

            <input name="name" placeholder="Name"
                value="{{ old('name') }}"
                class="w-full border px-3 py-2 rounded-lg">

            <input name="email" type="email" placeholder="Email"
                value="{{ old('email') }}"
                class="w-full border px-3 py-2 rounded-lg">

            <select name="role" id="role"
                class="w-full border px-3 py-2 rounded-lg">
                <option value="">Role</option>
                <option value="admin">Admin</option>
                <option value="user">User</option>
            </select>

            {{-- PARTICIPANT --}}
            <div id="participant-fields" class="hidden space-y-3">
                <input name="npm" placeholder="NPM"
                    class="w-full border px-3 py-2 rounded-lg">

                <input name="institusi" placeholder="Institution"
                    class="w-full border px-3 py-2 rounded-lg">
            </div>

            <input name="password" type="password" placeholder="Password"
                class="w-full border px-3 py-2 rounded-lg">

            <input name="password_confirmation" type="password"
                placeholder="Confirm Password"
                class="w-full border px-3 py-2 rounded-lg">

            <div class="flex justify-end gap-3">
                <a href="{{ route('admin.users') }}"
                   class="px-4 py-2 bg-gray-200 rounded-lg">
                    Cancel
                </a>

                <button class="px-4 py-2 bg-indigo-600 text-white rounded-lg">
                    Save
                </button>
            </div>
        </form>

    </div>

    <script>
        const role = document.getElementById('role');
        const field = document.getElementById('participant-fields');

        function toggle() {
            field.classList.toggle('hidden', role.value !== 'user');
        }

        role.addEventListener('change', toggle);
        toggle();
    </script>
</x-app-layout>