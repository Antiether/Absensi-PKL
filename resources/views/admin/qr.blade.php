<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl">QR Absensi Hari Ini</h2>
    </x-slot>

    <div class="py-10 flex justify-center">
        <div class="bg-white p-8 rounded-lg shadow text-center">

            <img
                class="mx-auto border p-2 rounded"
                src="https://api.qrserver.com/v1/create-qr-code/?size=300x300&data={{ $token->token }}"
                alt="QR Absensi">

            <p class="mt-4 text-sm text-gray-600">
                Token berlaku hanya untuk hari ini
            </p>

            <p class="mt-2 font-mono text-sm bg-gray-100 inline-block px-3 py-1 rounded">
                {{ $token->token }}
            </p>

        </div>
    </div>
</x-app-layout>