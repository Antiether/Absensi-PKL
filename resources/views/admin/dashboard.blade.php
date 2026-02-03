<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl">
                Dashboard Admin – Monitoring Kehadiran
            </h2>
            <a href="{{ route('qr') }}" class="bg-blue-600 text-white px-4 py-2 rounded">
                Generate QR Code
            </a>
        </div>
    </x-slot>

    <div class="py-6 max-w-6xl mx-auto">
        <table class="w-full border">
            <thead>
                <tr class="bg-gray-200">
                    <th class="border p-2">Nama</th>
                    <th class="border p-2">Tanggal</th>
                    <th class="border p-2">Check-in</th>
                    <th class="border p-2">Check-out</th>
                    <th class="border p-2">Status</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($attendances as $a)
                <tr>
                    <td class="border p-2">
                        {{ $a->participant->user->name }}
                    </td>
                    <td class="border p-2">
                        {{ $a->date }}
                    </td>
                    <td class="border p-2">
                        {{ $a->checkin_time ?? '-' }}
                    </td>
                    <td class="border p-2">
                        {{ $a->checkout_time ?? '-' }}
                    </td>
                    <td class="border p-2">
                        {{ $a->status }}
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</x-app-layout>