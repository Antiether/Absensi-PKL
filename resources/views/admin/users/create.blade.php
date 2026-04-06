<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl">
            Add New User
        </h2>
    </x-slot>

    <div class="py-6 max-w-2xl mx-auto">
        <form method="POST" action="{{ route('admin.users.store') }}" class="space-y-6">
            @csrf

            <div>
                <label for="name" class="block text-sm font-medium text-gray-700">
                    Name
                </label>
                <input type="text" name="name" id="name" value="{{ old('name') }}" required 
                    class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md">
                @error('name')
                    <span class="text-red-600 text-sm">{{ $message }}</span>
                @enderror
            </div>

            <div>
                <label for="email" class="block text-sm font-medium text-gray-700">
                    Email
                </label>
                <input type="email" name="email" id="email" value="{{ old('email') }}" required 
                    class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md">
                @error('email')
                    <span class="text-red-600 text-sm">{{ $message }}</span>
                @enderror
            </div>

            <div>
                <label for="role" class="block text-sm font-medium text-gray-700">
                    Role
                </label>
                <select name="role" id="role" required 
                    class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md">
                    <option value="">-- Select Role --</option>
                    <option value="admin" {{ old('role') === 'admin' ? 'selected' : '' }}>Admin</option>
                    <option value="user" {{ old('role') === 'user' ? 'selected' : '' }}>User (Participant)</option>
                </select>
                @error('role')
                    <span class="text-red-600 text-sm">{{ $message }}</span>
                @enderror
            </div>

            <div id="participant-fields" style="display: none;">
                <div>
                    <label for="npm" class="block text-sm font-medium text-gray-700">
                        NPM
                    </label>
                    <input type="text" name="npm" id="npm" value="{{ old('npm') }}" 
                        class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md">
                    @error('npm')
                        <span class="text-red-600 text-sm">{{ $message }}</span>
                    @enderror
                </div>

                <div class="mt-4">
                    <label for="institusi" class="block text-sm font-medium text-gray-700">
                        Institution
                    </label>
                    <input type="text" name="institusi" id="institusi" value="{{ old('institusi') }}" 
                        class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md">
                    @error('institusi')
                        <span class="text-red-600 text-sm">{{ $message }}</span>
                    @enderror
                </div>
            </div>

            <div>
                <label for="password" class="block text-sm font-medium text-gray-700">
                    Password
                </label>
                <input type="password" name="password" id="password" required 
                    class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md">
                @error('password')
                    <span class="text-red-600 text-sm">{{ $message }}</span>
                @enderror
            </div>

            <div>
                <label for="password_confirmation" class="block text-sm font-medium text-gray-700">
                    Confirm Password
                </label>
                <input type="password" name="password_confirmation" id="password_confirmation" required 
                    class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md">
            </div>

            <div class="flex gap-4">
                <button type="submit" class="bg-blue-600 text-white px-6 py-2 rounded">
                    Create User
                </button>
                <a href="{{ route('admin.users') }}" class="bg-gray-600 text-white px-6 py-2 rounded">
                    Cancel
                </a>
            </div>
        </form>
    </div>

    <script>
        const roleSelect = document.getElementById('role');
        const participantFields = document.getElementById('participant-fields');

        function toggleParticipantFields() {
            if (roleSelect.value === 'user') {
                participantFields.style.display = 'block';
            } else {
                participantFields.style.display = 'none';
            }
        }

        roleSelect.addEventListener('change', toggleParticipantFields);
        // Initial call
        toggleParticipantFields();
    </script>
</x-app-layout>
