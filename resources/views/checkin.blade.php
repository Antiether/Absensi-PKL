<!DOCTYPE html>
<html>
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    @vite(['resources/css/app.css'])
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"/>
    <style>
        [x-cloak] { display: none; }

        .dot-live {
            width: 7px; height: 7px;
            border-radius: 50%;
            background: #16a34a;
            animation: pulse-dot 1.5s ease-in-out infinite;
            display: inline-block;
        }
        @keyframes pulse-dot {
            0%, 100% { opacity: 1; }
            50% { opacity: 0.3; }
        }

        #map { height: 220px; border-radius: 10px; z-index: 1; }

        .input-clean {
            width: 100%;
            background: #F8FAFD;
            border: 1px solid #D8E2EE;
            border-radius: 9px;
            padding: 9px 12px;
            font-size: 14px;
            color: #1A2E4A;
            outline: none;
            transition: border-color 0.15s, background 0.15s;
            font-family: inherit;
        }
        .input-clean:focus {
            border-color: #005BBB;
            background: #fff;
        }
        .input-clean::placeholder { color: #A0B0C0; }

        .file-zone {
            border: 1.5px dashed #C5D5E8;
            border-radius: 10px;
            padding: 20px 16px;
            text-align: center;
            background: #F8FAFD;
            cursor: pointer;
            transition: border-color 0.15s, background 0.15s;
        }
        .file-zone:hover {
            border-color: #005BBB;
            background: #EFF5FF;
        }
        .file-zone input[type="file"] {
            position: absolute;
            inset: 0;
            opacity: 0;
            cursor: pointer;
            width: 100%;
            height: 100%;
        }
    </style>
</head>

<body class="bg-gray-100 min-h-screen">

{{-- HEADER --}}
<div class="bg-white border-b border-gray-200 sticky top-0 z-20">
    <div class="max-w-xl mx-auto px-4">
        <div class="flex justify-between h-14 items-center">

            {{-- Kiri: Brand --}}
            <div class="flex items-center gap-2.5">
                <div class="w-8 h-8 rounded-lg bg-blue-900 flex items-center justify-center">
                    <img src="{{ asset('images/logo.png') }}" class="w-5 h-5 object-contain">
                </div>
                <span class="font-semibold text-blue-900 text-sm">Absensi PKL</span>
            </div>

            {{-- Kanan: User dropdown --}}
            <div class="relative">
                <details class="group">
                    <summary class="flex items-center gap-2 cursor-pointer list-none bg-gray-100 hover:bg-gray-200 transition rounded-full pl-1 pr-3 py-1">
                        <div class="w-7 h-7 rounded-full bg-blue-100 text-blue-700 text-xs font-semibold flex items-center justify-center">
                            {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}{{ strtoupper(substr(strstr(Auth::user()->name, ' '), 1, 1)) }}
                        </div>
                        <span class="text-sm text-gray-700">{{ Auth::user()->name }}</span>
                        <svg class="w-3.5 h-3.5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                        </svg>
                    </summary>

                    <div class="absolute right-0 mt-2 w-36 bg-white border border-gray-200 rounded-xl shadow-md overflow-hidden z-30">
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button class="w-full text-left px-4 py-2.5 text-sm text-red-600 hover:bg-red-50 transition flex items-center gap-2">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                                </svg>
                                Logout
                            </button>
                        </form>
                    </div>
                </details>
            </div>

        </div>
    </div>
</div>

<div class="max-w-xl mx-auto px-4 py-5 space-y-4">

    {{-- HERO --}}
    <div class="bg-gradient-to-br from-blue-900 to-blue-700 rounded-2xl p-5 relative overflow-hidden">
        <div class="absolute right-0 top-0 w-32 h-32 rounded-full bg-white/5 -translate-y-8 translate-x-8"></div>
        <div class="absolute right-10 bottom-0 w-20 h-20 rounded-full bg-white/5 translate-y-6"></div>

        <div class="relative z-10">
            <h2 class="text-white font-semibold text-lg">Absensi Hari Ini</h2>
            <p class="text-blue-200 text-sm mt-0.5">Pastikan kamu berada di area kantor</p>
            <div class="mt-3 inline-flex items-center gap-2 bg-white/10 border border-white/20 rounded-full px-3 py-1.5">
                <svg class="w-3.5 h-3.5 text-blue-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                </svg>
                <span class="text-blue-100 text-xs">
                    {{ \Carbon\Carbon::now()->translatedFormat('l, d F Y') }}
                </span>
            </div>
        </div>
    </div>

    {{-- FLASH MESSAGE --}}
    @if(session('success'))
    <div class="bg-green-50 border border-green-200 text-green-700 text-sm px-4 py-3 rounded-xl flex items-center gap-2">
        <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
        </svg>
        {{ session('success') }}
    </div>
    @endif

    @if(session('error'))
    <div class="bg-red-50 border border-red-200 text-red-700 text-sm px-4 py-3 rounded-xl flex items-center gap-2">
        <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
        </svg>
        {{ session('error') }}
    </div>
    @endif

    <form method="POST" action="/checkin" enctype="multipart/form-data" class="space-y-4">
        @csrf

        {{-- CARD: LOKASI --}}
        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
            <div class="flex items-center justify-between px-4 py-3 border-b border-gray-100">
                <div class="flex items-center gap-2">
                    <svg class="w-4 h-4 text-blue-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                    </svg>
                    <span class="font-semibold text-gray-800 text-sm">Lokasi</span>
                </div>
                <div class="flex items-center gap-1.5 bg-green-50 text-green-700 text-xs font-medium px-2.5 py-1 rounded-full">
                    <span class="dot-live"></span>
                    Live
                </div>
            </div>

            <div class="p-4 space-y-3">
                <div id="map"></div>

                <div class="grid grid-cols-2 gap-2">
                    <div class="bg-gray-50 border border-gray-100 rounded-lg p-2.5">
                        <p class="text-[10px] text-gray-400 uppercase tracking-wider mb-1">Latitude</p>
                        <input id="lat_display" readonly
                            class="w-full bg-transparent text-sm text-gray-700 font-medium outline-none"
                            placeholder="Mendeteksi...">
                    </div>
                    <div class="bg-gray-50 border border-gray-100 rounded-lg p-2.5">
                        <p class="text-[10px] text-gray-400 uppercase tracking-wider mb-1">Longitude</p>
                        <input id="lng_display" readonly
                            class="w-full bg-transparent text-sm text-gray-700 font-medium outline-none"
                            placeholder="Mendeteksi...">
                    </div>
                </div>

                <div id="status-jarak" class="flex items-center justify-between bg-gray-50 border border-gray-100 rounded-lg px-3 py-2.5">
                    <div class="flex items-center gap-2 text-sm text-gray-500">
                        <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7"/>
                        </svg>
                        Jarak ke kantor
                    </div>
                    <span id="status-label" class="text-xs font-medium text-gray-400 bg-gray-100 px-2.5 py-1 rounded-full">
                        Mendeteksi...
                    </span>
                </div>
            </div>

            <input type="hidden" name="lat" id="lat">
            <input type="hidden" name="lng" id="lng">
        </div>

        {{-- CARD: FOTO --}}
        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
            <div class="flex items-center gap-2 px-4 py-3 border-b border-gray-100">
                <svg class="w-4 h-4 text-blue-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"/>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"/>
                </svg>
                <span class="font-semibold text-gray-800 text-sm">Bukti Foto</span>
            </div>

            <div class="p-4">
                <div class="file-zone relative">
                    <input type="file" name="photo" accept="image/*" capture="environment">
                    <div class="pointer-events-none">
                        <div class="w-10 h-10 bg-blue-100 rounded-xl flex items-center justify-center mx-auto mb-3">
                            <svg class="w-5 h-5 text-blue-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                            </svg>
                        </div>
                        <p class="text-sm text-gray-600 font-medium">Tap untuk upload foto</p>
                        <p class="text-xs text-gray-400 mt-1">JPG / PNG, maks. 5 MB</p>
                    </div>
                </div>

                @error('photo')
                <p class="text-xs text-red-500 mt-2">{{ $message }}</p>
                @enderror
            </div>
        </div>

        {{-- CARD: QR TOKEN --}}
        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
            <div class="flex items-center gap-2 px-4 py-3 border-b border-gray-100">
                <svg class="w-4 h-4 text-blue-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z"/>
                </svg>
                <span class="font-semibold text-gray-800 text-sm">Token QR</span>
            </div>

            <div class="p-4 space-y-2.5">
                <div>
                    <label class="text-xs text-gray-400 mb-1.5 block">Hasil scan QR Code</label>
                    <input type="text" name="token"
                        class="input-clean"
                        placeholder="Token dari QR otomatis...">
                </div>
                <div>
                    <label class="text-xs text-gray-400 mb-1.5 block">Input manual</label>
                    <input type="text" name="token_manual"
                        class="input-clean"
                        placeholder="Ketik token secara manual...">
                </div>

                @error('token')
                <p class="text-xs text-red-500">{{ $message }}</p>
                @enderror
            </div>
        </div>

        {{-- CARD: CATATAN --}}
        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
            <div class="flex items-center gap-2 px-4 py-3 border-b border-gray-100">
                <svg class="w-4 h-4 text-blue-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                </svg>
                <span class="font-semibold text-gray-800 text-sm">
                    Catatan
                    <span class="text-xs text-gray-400 font-normal ml-1">(opsional)</span>
                </span>
            </div>

            <div class="p-4">
                <textarea name="note" rows="2"
                    class="input-clean resize-none"
                    placeholder="Tambahkan catatan jika diperlukan..."></textarea>
            </div>
        </div>

        {{-- ACTION BUTTONS --}}
        <div class="grid grid-cols-2 gap-3">
            <button type="submit" name="type" value="checkin"
                class="flex flex-col items-center gap-1.5 bg-blue-900 hover:bg-blue-800 active:scale-95 text-white py-4 rounded-2xl shadow-sm transition">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"/>
                </svg>
                <span class="font-semibold text-sm">Check In</span>
                <span class="text-xs text-blue-300">Mulai masuk kerja</span>
            </button>

            <button type="submit" name="type" value="checkout"
                class="flex flex-col items-center gap-1.5 bg-orange-500 hover:bg-orange-600 active:scale-95 text-white py-4 rounded-2xl shadow-sm transition">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                </svg>
                <span class="font-semibold text-sm">Check Out</span>
                <span class="text-xs text-orange-200">Selesai & pulang</span>
            </button>
        </div>

    </form>

</div>

<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function () {

    const officeLat = {{ $setting->office_lat ?? -5.4290 }};
    const officeLng = {{ $setting->office_lng ?? 105.2520 }};
    const radius    = {{ $setting->radius_meter ?? 100 }};

    const map = L.map('map', { zoomControl: true }).setView([officeLat, officeLng], 16);

    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '© OpenStreetMap'
    }).addTo(map);

    // Marker kantor
    const officeIcon = L.divIcon({
        className: '',
        html: `<div style="width:14px;height:14px;background:#003F8A;border:2px solid #fff;border-radius:50%;box-shadow:0 1px 4px rgba(0,0,0,0.3)"></div>`,
        iconAnchor: [7, 7]
    });
    L.marker([officeLat, officeLng], { icon: officeIcon }).addTo(map)
        .bindPopup('<b>Lokasi Kantor</b>');

    // Lingkaran radius
    L.circle([officeLat, officeLng], {
        radius: radius,
        color: '#F4820A',
        fillColor: '#F4820A',
        fillOpacity: 0.08,
        weight: 1.5,
        dashArray: '6 4'
    }).addTo(map);

    setTimeout(() => map.invalidateSize(), 300);

    const statusLabel = document.getElementById('status-label');

    function getDistance(lat1, lng1, lat2, lng2) {
        const R = 6371000;
        const dLat = (lat2 - lat1) * Math.PI / 180;
        const dLng = (lng2 - lng1) * Math.PI / 180;
        const a = Math.sin(dLat/2) * Math.sin(dLat/2) +
                  Math.cos(lat1 * Math.PI / 180) * Math.cos(lat2 * Math.PI / 180) *
                  Math.sin(dLng/2) * Math.sin(dLng/2);
        return R * 2 * Math.atan2(Math.sqrt(a), Math.sqrt(1-a));
    }

    if (navigator.geolocation) {
        navigator.geolocation.getCurrentPosition(function(position) {
            const lat = position.coords.latitude;
            const lng = position.coords.longitude;

            const userIcon = L.divIcon({
                className: '',
                html: `<div style="width:14px;height:14px;background:#F4820A;border:2px solid #fff;border-radius:50%;box-shadow:0 1px 4px rgba(0,0,0,0.3)"></div>`,
                iconAnchor: [7, 7]
            });

            L.marker([lat, lng], { icon: userIcon }).addTo(map)
                .bindPopup('<b>Lokasi Anda</b>');

            map.setView([lat, lng], 16);

            document.getElementById('lat_display').value = lat.toFixed(6);
            document.getElementById('lng_display').value = lng.toFixed(6);
            document.getElementById('lat').value = lat;
            document.getElementById('lng').value = lng;

            const jarak = Math.round(getDistance(lat, lng, officeLat, officeLng));

            if (jarak <= radius) {
                statusLabel.textContent = 'Dalam radius ✓';
                statusLabel.className = 'text-xs font-medium text-green-700 bg-green-100 px-2.5 py-1 rounded-full';
            } else {
                statusLabel.textContent = `Di luar radius (~${jarak}m)`;
                statusLabel.className = 'text-xs font-medium text-red-600 bg-red-50 px-2.5 py-1 rounded-full';
            }

        }, function(err) {
            statusLabel.textContent = 'Gagal mendeteksi lokasi';
            statusLabel.className = 'text-xs font-medium text-red-600 bg-red-50 px-2.5 py-1 rounded-full';
        });
    }

});
</script>

</body>
</html>