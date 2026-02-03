<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl">QR Absensi Hari Ini</h2>
    </x-slot>

    <div class="py-6 text-center">
        <img
            src="https://api.qrserver.com/v1/create-qr-code/?size=300x300&data={{ $token->token }}"
            alt="QR Absensi">

        <p class="mt-4 text-sm text-gray-600">
            Token berlaku hanya untuk hari ini
        </p>

        <p class="mt-2 font-mono text-xs">
            {{ $token->token }}
        </p>
    </div>
</x-app-layout>