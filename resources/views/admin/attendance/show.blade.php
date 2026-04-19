<x-app-layout>

    <div class="bg-gray-100 min-h-screen py-6 px-4 md:px-6">

        <div class="max-w-4xl mx-auto space-y-5">

            {{-- HEADER --}}
            <div class="bg-white rounded-xl shadow-sm p-5">
                <h2 class="text-lg font-semibold text-gray-800">
                    Detail Absensi
                </h2>
                <p class="text-sm text-gray-500">
                    Informasi lengkap kehadiran peserta
                </p>
            </div>

            {{-- DATA CARD --}}
            <div class="bg-white rounded-xl shadow-sm p-5 space-y-4">

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm">

                    <div>
                        <p class="text-gray-400 text-xs">Nama</p>
                        <p class="font-medium text-gray-800">
                            {{ $attendance->participant->user->name }}
                        </p>
                    </div>

                    <div>
                        <p class="text-gray-400 text-xs">Tanggal</p>
                        <p class="font-medium text-gray-800">
                            {{ \Carbon\Carbon::parse($attendance->date)->translatedFormat('d M Y') }}
                        </p>
                    </div>

                    <div>
                        <p class="text-gray-400 text-xs">Status</p>

                        @if($attendance->status == 'hadir')
                            <span class="inline-flex items-center gap-1 bg-green-100 text-green-700 text-xs font-semibold px-2.5 py-1 rounded-full">
                                <span class="w-1.5 h-1.5 bg-green-500 rounded-full"></span>
                                Hadir
                            </span>
                        @elseif($attendance->status == 'pulang')
                            <span class="inline-flex items-center gap-1 bg-blue-100 text-blue-700 text-xs font-semibold px-2.5 py-1 rounded-full">
                                <span class="w-1.5 h-1.5 bg-blue-500 rounded-full"></span>
                                Pulang
                            </span>
                        @else
                            <span class="text-gray-500 text-sm">
                                {{ ucfirst($attendance->status) }}
                            </span>
                        @endif
                    </div>

                    <div>
                        <p class="text-gray-400 text-xs">Check-in</p>
                        <p class="font-medium text-gray-800">
                            {{ $attendance->checkin_time ?? '—' }}
                        </p>
                    </div>

                    <div>
                        <p class="text-gray-400 text-xs">Check-out</p>
                        <p class="font-medium text-gray-800">
                            {{ $attendance->checkout_time ?? '—' }}
                        </p>
                    </div>

                </div>

            </div>

            {{-- FOTO --}}
            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">

                {{-- CHECKIN --}}
                <div class="bg-white rounded-xl shadow-sm p-5">
                    <p class="text-sm font-semibold text-gray-700 mb-3">
                        Foto Check-in
                    </p>

                    @if ($attendance->checkin_photo)
                        <img src="{{ asset('storage/' . $attendance->checkin_photo) }}"
                             class="w-full h-64 object-cover rounded-lg border">
                    @else
                        <div class="h-64 flex items-center justify-center text-gray-300 border rounded-lg">
                            Tidak ada foto
                        </div>
                    @endif
                </div>

                {{-- CHECKOUT --}}
                <div class="bg-white rounded-xl shadow-sm p-5">
                    <p class="text-sm font-semibold text-gray-700 mb-3">
                        Foto Check-out
                    </p>

                    @if ($attendance->checkout_photo)
                        <img src="{{ asset('storage/' . $attendance->checkout_photo) }}"
                             class="w-full h-64 object-cover rounded-lg border">
                    @else
                        <div class="h-64 flex items-center justify-center text-gray-300 border rounded-lg">
                            Tidak ada foto
                        </div>
                    @endif
                </div>

            </div>

            {{-- ACTION --}}
            <div>
                <a href="{{ route('dashboard') }}"
                   class="inline-flex items-center gap-1 text-sm text-blue-600 hover:text-blue-800 transition">
                    
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M15 19l-7-7 7-7"/>
                    </svg>

                    Kembali ke Dashboard
                </a>
            </div>

        </div>

    </div>

</x-app-layout>