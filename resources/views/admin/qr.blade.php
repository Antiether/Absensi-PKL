<x-app-layout>
    <div class="p-6 space-y-6">

        {{-- HEADER CARD --}}
        <div class="bg-gradient-to-r from-indigo-600 to-blue-500 text-white p-6 rounded-2xl shadow flex justify-between items-center">
            <div>
                <h2 class="text-xl font-semibold">QR Absensi</h2>
                <p class="text-sm opacity-80">Scan untuk check-in hari ini</p>
            </div>

            <a href="{{ route('dashboard') }}"
               class="bg-white/20 hover:bg-white/30 px-4 py-2 rounded-lg text-sm">
                ← Kembali
            </a>
        </div>

        {{-- QR CARD --}}
        <div class="bg-white rounded-2xl shadow p-8 text-center max-w-xl mx-auto">

            {{-- QR --}}
            <div class="flex justify-center">
                <div class="p-4 border rounded-xl shadow-sm bg-gray-50">
                    <img
                        src="https://api.qrserver.com/v1/create-qr-code/?size=250x250&data={{ $token->token }}"
                        class="mx-auto"
                    >
                </div>
            </div>

            {{-- TOKEN --}}
            <div class="mt-6">
                <p class="text-gray-500 text-sm">Token Hari Ini</p>

                <div class="mt-2 flex justify-center gap-2">
                    <span class="font-mono bg-gray-100 px-4 py-2 rounded-lg text-sm">
                        {{ $token->token }}
                    </span>

                    <button onclick="navigator.clipboard.writeText('{{ $token->token }}')"
                        class="bg-gray-200 hover:bg-gray-300 px-3 rounded-lg text-sm">
                        Copy
                    </button>
                </div>
            </div>

            {{-- INFO --}}
            <p class="mt-4 text-xs text-gray-400">
                Berlaku hanya untuk hari ini
            </p>

            {{-- ACTION --}}
            <div class="mt-6">
                <a href="{{ route('qr') }}"
                   class="bg-indigo-600 text-white px-4 py-2 rounded-lg hover:bg-indigo-700">
                    Generate Ulang
                </a>
            </div>

        </div>

    </div>
</x-app-layout>