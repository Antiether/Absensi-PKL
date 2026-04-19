<x-app-layout>
    <x-slot name="header">
        {{-- Header BTN-style: biru tua dengan aksen oranye --}}
        <div class="bg-gradient-to-r from-blue-900 to-blue-700 -mx-4 -mt-4 px-6 pt-6 pb-10 relative overflow-hidden rounded-b-2xl">
            {{-- Decorative circles --}}
            <div class="absolute right-0 bottom-0 w-52 h-52 rounded-full bg-white/5 translate-x-16 translate-y-16"></div>
            <div class="absolute right-20 bottom-0 w-36 h-36 rounded-full bg-white/5 translate-y-20"></div>

            <div class="relative z-10 flex justify-between items-start flex-wrap gap-3">
                <div>
                    <h2 class="text-xl font-semibold text-white">Dashboard Admin</h2>
                    <p class="text-sm text-blue-200 mt-0.5">Monitoring kehadiran peserta PKL</p>
                </div>

                <div class="flex gap-2 flex-wrap">
                    <a href="{{ route('admin.setting') }}"
                        class="inline-flex items-center gap-1.5 bg-white/15 hover:bg-white/25 border border-white/25 text-white text-sm font-medium px-4 py-2 rounded-lg transition backdrop-blur-sm">

                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 8.5a3.5 3.5 0 100 7 3.5 3.5 0 000-7zm7.5 3.5c0-.613-.078-1.207-.222-1.775l1.677-1.31a.75.75 0 00.18-.958l-1.588-2.748a.75.75 0 00-.92-.324l-1.975.79a7.483 7.483 0 00-1.534-.89l-.3-2.1A.75.75 0 0013.5 2h-3a.75.75 0 00-.743.652l-.3 2.1a7.5 7.5 0 00-1.534.89l-1.975-.79a.75.75 0 00-.92.324L2.065 8.957a.75.75 0 00.18.958l1.677 1.31c-.144.568-.222 1.162-.222 1.775s.078 1.207.222 1.775l-1.677 1.31a.75.75 0 00-.18.958l1.588 2.748c.228.395.716.559 1.12.324l1.975-.79c.486.38 1.01.692 1.534.89l.3 2.1c.073.484.49.652.743.652h3c.253 0 .67-.168.743-.652l.3-2.1a7.483 7.483 0 001.534-.89l1.975.79c.404.235.892.071 1.12-.324l1.588-2.748a.75.75 0 00-.18-.958l-1.677-1.31c.144-.568.222-1.162.222-1.775z"/>
                        </svg>

                        Settings
                    </a>

                    <a href="{{ route('qr') }}"
                        class="inline-flex items-center gap-1.5 bg-orange-500 hover:bg-orange-600 text-white text-sm font-medium px-4 py-2 rounded-lg transition">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z"/>
                        </svg>
                        QR Code
                    </a>

                    <a href="{{ route('admin.export.excel') }}"
                        class="inline-flex items-center gap-1.5 bg-white/15 hover:bg-white/25 border border-white/25 text-white text-sm font-medium px-4 py-2 rounded-lg transition backdrop-blur-sm">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
                        </svg>
                        Export
                    </a>

                    <a href="{{ route('admin.users') }}"
                        class="inline-flex items-center gap-1.5 bg-white/15 hover:bg-white/25 border border-white/25 text-white text-sm font-medium px-4 py-2 rounded-lg transition backdrop-blur-sm">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/>
                        </svg>
                        Users
                    </a>

                    <a href="{{ route('admin.users.create') }}"
                        class="inline-flex items-center gap-1.5 bg-orange-500 hover:bg-orange-600 text-white text-sm font-medium px-4 py-2 rounded-lg transition">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                        </svg>
                        User
                    </a>
                </div>
            </div>
        </div>
    </x-slot>

    <div class="bg-gray-100 min-h-screen">
        <div class="px-4 md:px-6 py-4 space-y-5">

            {{-- FILTER CARD --}}
            <div class="bg-white rounded-xl shadow-sm p-4">
                <form method="GET" class="flex flex-wrap gap-3 items-end">

                    <div class="flex flex-col gap-1">
                        <label class="text-[11px] font-semibold text-gray-400 uppercase tracking-wider">Nama</label>
                        <input type="text" name="nama"
                            value="{{ request('nama') }}"
                            placeholder="Cari nama..."
                            class="border border-gray-200 bg-gray-50 focus:bg-white focus:border-blue-600 rounded-lg px-3 py-2 text-sm text-gray-700 w-40 transition outline-none">
                    </div>

                    <div class="flex flex-col gap-1">
                        <label class="text-[11px] font-semibold text-gray-400 uppercase tracking-wider">Tanggal</label>
                        <input type="date" name="tanggal"
                            value="{{ request('tanggal') }}"
                            class="border border-gray-200 bg-gray-50 focus:bg-white focus:border-blue-600 rounded-lg px-3 py-2 text-sm text-gray-700 transition outline-none">
                    </div>

                    <div class="flex flex-col gap-1">
                        <label class="text-[11px] font-semibold text-gray-400 uppercase tracking-wider">Status</label>
                        <select name="status"
                            class="border border-gray-200 bg-gray-50 focus:bg-white focus:border-blue-600 rounded-lg pl-3 pr-10 py-2 text-sm text-gray-700 transition outline-none cursor-pointer">
                            <option value="">Semua</option>
                            <option value="hadir"  {{ request('status') == 'hadir'  ? 'selected' : '' }}>Hadir</option>
                            <option value="pulang" {{ request('status') == 'pulang' ? 'selected' : '' }}>Pulang</option>
                        </select>
                    </div>

                    <div class="flex gap-2">
                        <button type="submit"
                            class="inline-flex items-center gap-1.5 bg-blue-700 hover:bg-blue-800 text-white text-sm font-medium px-4 py-2 rounded-lg transition">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                            </svg>
                            Filter
                        </button>

                        <a href="{{ route('dashboard') }}"
                            class="inline-flex items-center gap-1.5 bg-gray-100 hover:bg-gray-200 text-gray-600 text-sm font-medium px-4 py-2 rounded-lg transition">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                            </svg>
                            Reset
                        </a>
                    </div>

                </form>
            </div>

            {{-- TABLE CARD --}}
            <div class="bg-white rounded-xl shadow-sm overflow-hidden">

                <div class="px-5 py-4 border-b border-gray-100 flex items-center justify-between">
                    <h3 class="font-semibold text-blue-900">Data Absensi</h3>
                    <span class="text-xs text-gray-400 bg-gray-100 px-3 py-1 rounded-full">
                        {{ $attendances->total() }} data
                    </span>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-sm">
                        <thead>
                            <tr class="bg-gray-50 border-b border-gray-100">
                                <th class="px-5 py-3 text-left text-[11px] font-semibold text-gray-400 uppercase tracking-wider">Nama</th>
                                <th class="px-5 py-3 text-left text-[11px] font-semibold text-gray-400 uppercase tracking-wider">Tanggal</th>
                                <th class="px-5 py-3 text-left text-[11px] font-semibold text-gray-400 uppercase tracking-wider">Check-in</th>
                                <th class="px-5 py-3 text-left text-[11px] font-semibold text-gray-400 uppercase tracking-wider">Check-out</th>
                                <th class="px-5 py-3 text-left text-[11px] font-semibold text-gray-400 uppercase tracking-wider">Status</th>
                                <th class="px-5 py-3 text-left text-[11px] font-semibold text-gray-400 uppercase tracking-wider">Aksi</th>
                            </tr>
                        </thead>

                        <tbody class="divide-y divide-gray-50">
                            @forelse ($attendances as $a)
                            <tr class="hover:bg-blue-50/40 transition">

                                {{-- Nama dengan avatar inisial --}}
                                <td class="px-5 py-3.5">
                                    <div class="flex items-center gap-2.5">
                                        <div class="w-8 h-8 rounded-full bg-blue-100 text-blue-700 text-xs font-semibold flex items-center justify-center flex-shrink-0">
                                            {{ strtoupper(substr($a->participant->user->name, 0, 1)) }}{{ strtoupper(substr(strstr($a->participant->user->name, ' '), 1, 1)) }}
                                        </div>
                                        <span class="font-medium text-gray-800">{{ $a->participant->user->name }}</span>
                                    </div>
                                </td>

                                <td class="px-5 py-3.5 text-gray-600">
                                    {{ \Carbon\Carbon::parse($a->date)->translatedFormat('d M Y') }}
                                </td>

                                <td class="px-5 py-3.5">
                                    @if($a->checkin_time)
                                        <span class="text-gray-700 font-medium">{{ $a->checkin_time }}</span>
                                    @else
                                        <span class="text-gray-300">—</span>
                                    @endif
                                </td>

                                <td class="px-5 py-3.5">
                                    @if($a->checkout_time)
                                        <span class="text-gray-700 font-medium">{{ $a->checkout_time }}</span>
                                    @else
                                        <span class="text-gray-300">—</span>
                                    @endif
                                </td>

                                <td class="px-5 py-3.5">
                                    @if($a->status == 'hadir')
                                        <span class="inline-flex items-center gap-1 bg-green-100 text-green-700 text-xs font-semibold px-2.5 py-1 rounded-full">
                                            <span class="w-1.5 h-1.5 rounded-full bg-green-500 inline-block"></span>
                                            Hadir
                                        </span>
                                    @elseif($a->status == 'pulang')
                                        <span class="inline-flex items-center gap-1 bg-blue-100 text-blue-700 text-xs font-semibold px-2.5 py-1 rounded-full">
                                            <span class="w-1.5 h-1.5 rounded-full bg-blue-500 inline-block"></span>
                                            Pulang
                                        </span>
                                    @else
                                        <span class="inline-flex items-center gap-1 bg-orange-100 text-orange-700 text-xs font-semibold px-2.5 py-1 rounded-full">
                                            <span class="w-1.5 h-1.5 rounded-full bg-orange-500 inline-block"></span>
                                            {{ ucfirst($a->status) }}
                                        </span>
                                    @endif
                                </td>

                                <td class="px-5 py-3.5">
                                    <a href="{{ route('admin.attendance.show', $a->id) }}"
                                        class="inline-flex items-center gap-1 text-orange-500 hover:text-orange-700 text-xs font-semibold transition">
                                        Lihat
                                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                        </svg>
                                    </a>
                                </td>

                            </tr>
                            @empty
                            <tr>
                                <td colspan="6" class="text-center py-12">
                                    <div class="flex flex-col items-center gap-2 text-gray-300">
                                        <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                                d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                                        </svg>
                                        <span class="text-sm">Tidak ada data absensi</span>
                                    </div>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                {{-- Pagination --}}
                <div class="px-5 py-4 border-t border-gray-100 flex items-center justify-between flex-wrap gap-2">
                    <p class="text-xs text-gray-400">
                        Menampilkan {{ $attendances->firstItem() }}–{{ $attendances->lastItem() }}
                        dari {{ $attendances->total() }} data
                    </p>
                    <div class="[&_.pagination]:flex [&_.pagination]:gap-1
                                [&_a]:text-xs [&_a]:px-3 [&_a]:py-1.5 [&_a]:rounded-lg [&_a]:border [&_a]:border-gray-200 [&_a]:text-gray-600 [&_a]:hover:bg-blue-700 [&_a]:hover:text-white [&_a]:hover:border-blue-700 [&_a]:transition
                                [&_span.page-item.active_span]:text-xs [&_span.page-item.active_span]:px-3 [&_span.page-item.active_span]:py-1.5 [&_span.page-item.active_span]:rounded-lg [&_span.page-item.active_span]:bg-blue-700 [&_span.page-item.active_span]:text-white
                                [&_span.disabled_span]:text-xs [&_span.disabled_span]:px-3 [&_span.disabled_span]:py-1.5 [&_span.disabled_span]:rounded-lg [&_span.disabled_span]:border [&_span.disabled_span]:border-gray-100 [&_span.disabled_span]:text-gray-300">
                        {{ $attendances->links() }}
                    </div>
                </div>

            </div>

        </div>
    </div>
</x-app-layout>