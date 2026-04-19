<x-app-layout>
    <div class="p-6 space-y-6 max-w-5xl mx-auto">

        {{-- HEADER --}}
        <div class="bg-gradient-to-r from-indigo-600 to-blue-500 text-white p-6 rounded-2xl shadow">
            <h2 class="text-xl font-semibold">Setting Lokasi Kantor</h2>
            <p class="text-sm opacity-80">Atur lokasi dan radius absensi</p>
        </div>

        {{-- ALERT --}}
        @if(session('success'))
            <div class="bg-green-100 text-green-700 px-4 py-3 rounded-lg">
                {{ session('success') }}
            </div>
        @endif

        @if($errors->any())
            <div class="bg-red-100 text-red-700 px-4 py-3 rounded-lg">
                {{ $errors->first() }}
            </div>
        @endif

        {{-- MAP CARD --}}
        <div class="bg-white rounded-2xl shadow p-4">
            <div id="map" style="height: 350px;" class="rounded-xl"></div>
            <p class="text-xs text-gray-400 mt-2">
                Klik atau drag marker untuk menentukan lokasi kantor 📍
            </p>
        </div>

        {{-- FORM CARD --}}
        <form method="POST" action="{{ route('admin.setting.update') }}"
              class="bg-white rounded-2xl shadow p-6 space-y-5">
            @csrf

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">

                <div>
                    <label class="text-sm text-gray-500">Latitude</label>
                    <input type="number" step="any" name="office_lat" id="office_lat"
                        value="{{ old('office_lat', $setting->office_lat ?? '') }}"
                        class="w-full border px-3 py-2 rounded-lg mt-1 bg-gray-50 focus:bg-white">
                </div>

                <div>
                    <label class="text-sm text-gray-500">Longitude</label>
                    <input type="number" step="any" name="office_lng" id="office_lng"
                        value="{{ old('office_lng', $setting->office_lng ?? '') }}"
                        class="w-full border px-3 py-2 rounded-lg mt-1 bg-gray-50 focus:bg-white">
                </div>

            </div>

            <div>
                <label class="text-sm text-gray-500">Radius (meter)</label>
                <input type="number" name="radius_meter" id="radius_meter"
                    value="{{ old('radius_meter', $setting->radius_meter ?? 100) }}"
                    class="w-full border px-3 py-2 rounded-lg mt-1 bg-gray-50 focus:bg-white">
                <p class="text-xs text-gray-400 mt-1">
                    Radius area valid untuk check-in
                </p>
            </div>

            <div class="flex justify-between items-center pt-4">
                <a href="{{ route('dashboard') }}"
                   class="text-gray-500 hover:underline text-sm">
                    ← Kembali
                </a>

                <button type="submit"
                    class="bg-indigo-600 hover:bg-indigo-700 text-white px-5 py-2 rounded-lg">
                    Simpan Setting
                </button>
            </div>
        </form>

    </div>

    {{-- LEAFLET --}}
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css">
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function () {

            const savedLat = {{ $setting->office_lat ?? -5.4290 }};
            const savedLng = {{ $setting->office_lng ?? 105.2520 }};
            const savedRadius = {{ $setting->radius_meter ?? 100 }};

            const map = L.map('map').setView([savedLat, savedLng], 16);

            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                attribution: '© OpenStreetMap'
            }).addTo(map);

            let marker = L.marker([savedLat, savedLng], { draggable: true }).addTo(map);

            let circle = L.circle([savedLat, savedLng], {
                radius: savedRadius,
                color: '#4f46e5',
                fillColor: '#4f46e5',
                fillOpacity: 0.1
            }).addTo(map);

            function update(lat, lng) {
                document.getElementById('office_lat').value = lat;
                document.getElementById('office_lng').value = lng;

                marker.setLatLng([lat, lng]);
                circle.setLatLng([lat, lng]);
            }

            map.on('click', e => update(e.latlng.lat, e.latlng.lng));

            marker.on('dragend', () => {
                const pos = marker.getLatLng();
                update(pos.lat, pos.lng);
            });

            document.getElementById('radius_meter')
                .addEventListener('input', function () {
                    circle.setRadius(parseInt(this.value) || 100);
                });

        });
    </script>
</x-app-layout>