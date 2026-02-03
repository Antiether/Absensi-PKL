<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800">
            Absensi PKL / Magang
        </h2>
    </x-slot>

    <div class="py-6 max-w-xl mx-auto">

        {{-- Flash message --}}
        @if (session('success'))
            <div class="mb-4 bg-green-100 text-green-800 p-3 rounded">
                {{ session('success') }}
            </div>
        @endif

        @if ($errors->any())
            <div class="mb-4 bg-red-100 text-red-800 p-3 rounded">
                {{ $errors->first() }}
            </div>
        @endif

        {{-- ================= CHECK-IN ================= --}}
        <form method="POST" action="/checkin" enctype="multipart/form-data">
            @csrf

            {{-- MAP --}}
            <div id="map" style="height:300px" class="mb-4"></div>

            {{-- Hidden GPS --}}
            <input type="hidden" name="lat" id="lat">
            <input type="hidden" name="lng" id="lng">

            {{-- Koordinat tampil --}}
            <div class="grid grid-cols-2 gap-2 mb-4 text-sm">
                <input readonly id="lat_display" class="border p-2 rounded" placeholder="Latitude">
                <input readonly id="lng_display" class="border p-2 rounded" placeholder="Longitude">
            </div>

            {{-- Foto --}}
            <label class="font-medium block mb-1">Ambil Bukti Foto</label>
            <input type="file"
                   name="photo"
                   accept="image/*"
                   capture="camera"
                   required
                   class="w-full border rounded p-2">

            {{-- Catatan --}}
            <label class="font-medium block mt-4 mb-1">Catatan Harian</label>
            <textarea name="note" class="border p-2 w-full rounded"></textarea>

            {{-- QR Token --}}
            <label class="font-medium block mt-4 mb-1">QR Token</label>
            <div class="flex gap-2">
                <input type="text"
                    name="token"
                    id="qr_token"
                    required
                    class="border p-2 flex-1 rounded"
                    placeholder="Scan / masukkan token QR">
                <button type="button"
                    id="scan_qr_btn"
                    class="bg-green-600 text-white px-4 py-2 rounded">
                    Scan QR
                </button>
            </div>

            <button class="mt-4 w-full bg-blue-600 text-white py-2 rounded">
                Check In
            </button>
        </form>

        {{-- ================= CHECK-OUT ================= --}}
        <form method="POST" action="/checkout" enctype="multipart/form-data" class="mt-6">
            @csrf

            <label class="font-medium block mb-1">Foto Check-out</label>
            <input type="file"
                   name="photo"
                   accept="image/*"
                   capture="camera"
                   required
                   class="w-full border rounded p-2">

            <input type="hidden" name="lat" id="lat_out">
            <input type="hidden" name="lng" id="lng_out">

            {{-- QR Token Checkout --}}
            <label class="font-medium block mt-4 mb-1">QR Token</label>
            <div class="flex gap-2">
                <input type="text"
                    name="token"
                    id="qr_token_out"
                    required
                    class="border p-2 flex-1 rounded"
                    placeholder="Scan / masukkan token QR">
                <button type="button"
                    id="scan_qr_btn_out"
                    class="bg-green-600 text-white px-4 py-2 rounded">
                    Scan QR
                </button>
            </div>

            <button class="mt-4 w-full bg-red-600 text-white py-2 rounded">
                Check Out
            </button>
        </form>
    </div>

    {{-- ================= QR SCANNER MODAL ================= --}}
    <div id="qr_modal" class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
        <div class="bg-white p-6 rounded-lg w-full max-w-md">
            <h3 class="font-semibold text-lg mb-4">Scan QR Code</h3>
            <video id="qr_video" class="w-full border rounded mb-4"></video>
            <button type="button" 
                id="close_qr_modal" 
                class="w-full bg-gray-600 text-white py-2 rounded">
                Tutup
            </button>
        </div>
    </div>

    {{-- ================= LEAFLET ================= --}}
    <link
        rel="stylesheet"
        href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"
    >
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

    {{-- ================= jsQR LIBRARY ================= --}}
    <script src="https://cdn.jsdelivr.net/npm/jsqr@1.4.0/dist/jsQR.js"></script>

    {{-- ================= QR SCANNER + GPS SCRIPT ================= --}}
    <script>
        let currentQRInputId = null;

        // ========== QR SCANNER LOGIC ==========
        function startQRScanner(inputId) {
            currentQRInputId = inputId;
            const modal = document.getElementById('qr_modal');
            const video = document.getElementById('qr_video');
            modal.classList.remove('hidden');

            navigator.mediaDevices.getUserMedia({ video: { facingMode: 'environment' } })
                .then(stream => {
                    video.srcObject = stream;
                    video.play();
                    scanQR(video, stream);
                })
                .catch(err => {
                    alert('Tidak bisa akses kamera: ' + err.message);
                    modal.classList.add('hidden');
                });
        }

        function scanQR(video, stream) {
            const canvas = document.createElement('canvas');
            const ctx = canvas.getContext('2d');
            const interval = setInterval(() => {
                canvas.width = video.videoWidth;
                canvas.height = video.videoHeight;
                ctx.drawImage(video, 0, 0);

                const imageData = ctx.getImageData(0, 0, canvas.width, canvas.height);
                const code = jsQR(imageData.data, canvas.width, canvas.height);

                if (code) {
                    clearInterval(interval);
                    stream.getTracks().forEach(track => track.stop());
                    
                    document.getElementById(currentQRInputId).value = code.data;
                    document.getElementById('qr_modal').classList.add('hidden');
                }
            }, 100);
        }

        // QR Scanner Button Listeners
        document.getElementById('scan_qr_btn').addEventListener('click', () => {
            startQRScanner('qr_token');
        });

        document.getElementById('scan_qr_btn_out').addEventListener('click', () => {
            startQRScanner('qr_token_out');
        });

        document.getElementById('close_qr_modal').addEventListener('click', () => {
            document.getElementById('qr_modal').classList.add('hidden');
        });

        // ========== GPS + MAP LOGIC ==========
        document.addEventListener('DOMContentLoaded', function () {
            if (!navigator.geolocation) {
                alert('Browser tidak mendukung GPS');
                return;
            }

            // Default lokasi kantor (Lampung Timur, Indonesia)
            const defaultLat = -5.4290;
            const defaultLng = 105.2520;
            const defaultRadius = 100;

            const officeLat = defaultLat;
            const officeLng = defaultLng;
            const radius    = defaultRadius;

            navigator.geolocation.getCurrentPosition(
                function (pos) {
                    const lat = pos.coords.latitude;
                    const lng = pos.coords.longitude;

                    // isi semua input
                    document.getElementById('lat').value = lat;
                    document.getElementById('lng').value = lng;
                    document.getElementById('lat_display').value = lat;
                    document.getElementById('lng_display').value = lng;

                    document.getElementById('lat_out').value = lat;
                    document.getElementById('lng_out').value = lng;

                    const map = L.map('map').setView([lat, lng], 16);

                    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                        attribution: '© OpenStreetMap'
                    }).addTo(map);

                    // Marker user
                    L.marker([lat, lng])
                        .addTo(map)
                        .bindPopup('Lokasi Anda')
                        .openPopup();

                    // Marker kantor + radius
                    if (officeLat && officeLng) {
                        L.marker([officeLat, officeLng])
                            .addTo(map)
                            .bindPopup('Lokasi Instansi');

                        L.circle([officeLat, officeLng], {
                            radius: radius,
                            color: 'red',
                            fillOpacity: 0.1
                        }).addTo(map);
                    }
                },
                function () {
                    alert('GPS harus diaktifkan untuk absensi');
                },
                { enableHighAccuracy: true }
            );
        });
    </script>
</x-app-layout>