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

        {{-- ================= FORM ABSENSI (UNTUK CHECK-IN & CHECK-OUT) ================= --}}
        <form method="POST" action="" id="attendance-form" enctype="multipart/form-data">
            @csrf

            {{-- MAP --}}
            <div id="map" style="height:300px" class="mb-4 rounded border"></div>

            {{-- Hidden GPS --}}
            <input type="hidden" name="lat" id="lat">
            <input type="hidden" name="lng" id="lng">

            {{-- Koordinat tampil --}}
            <div class="grid grid-cols-2 gap-2 mb-4 text-sm">
                <input readonly id="lat_display" class="border p-2 rounded bg-gray-50" placeholder="Latitude">
                <input readonly id="lng_display" class="border p-2 rounded bg-gray-50" placeholder="Longitude">
            </div>

            {{-- Foto --}}
            <label class="font-medium block mb-1">Ambil Bukti Foto</label>
            <input type="file"
                   name="photo"
                   accept="image/*"
                   capture="camera"
                   required
                   class="w-full border rounded p-2 mb-4">

            {{-- Catatan (hanya untuk check-in) --}}
            <div id="note-section">
                <label class="font-medium block mb-1">Catatan Harian (Opsional)</label>
                <textarea name="note" 
                          rows="3"
                          class="border p-2 w-full rounded mb-4"
                          placeholder="Tulis kegiatan hari ini..."></textarea>
            </div>

            {{-- QR Scanner & Token (1 untuk keduanya) --}}
            <label class="font-medium block mb-1">Scan QR Token</label>
            
            {{-- QR Scanner Container --}}
            <div id="reader" class="mb-3 border rounded"></div>
            
            <input type="text"
                   name="token"
                   id="qr_token"
                   readonly
                   class="border p-2 w-full rounded mb-2 bg-gray-50"
                   placeholder="Token akan terisi otomatis setelah scan QR">

            {{-- Manual token fallback --}}
            <input type="text"
                   name="token_manual"
                   class="border p-2 w-full rounded mb-4"
                   placeholder="Atau masukkan token manual jika scan gagal">

            {{-- Tombol Check-In & Check-Out --}}
            <div class="grid grid-cols-2 gap-3">
                <button type="button" 
                        id="btn-checkin"
                        class="bg-blue-600 text-white py-3 rounded hover:bg-blue-700 transition font-medium">
                    Check In
                </button>
                <button type="button" 
                        id="btn-checkout"
                        class="bg-red-600 text-white py-3 rounded hover:bg-red-700 transition font-medium">
                    Check Out
                </button>
            </div>
        </form>
    </div>


    {{-- ================= LEAFLET CSS & JS ================= --}}
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css">
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

    {{-- ================= html5-qrcode LIBRARY ================= --}}
    <script src="https://unpkg.com/html5-qrcode" type="text/javascript"></script>

    <script>
        // ================= QR SCANNER (1 SCANNER UNTUK KEDUANYA) =================
        let html5QrcodeScanner = null;
        let scannerActive = true; // Flag untuk cegah spam
        
        function onScanSuccess(decodedText, decodedResult) {
            // Cegah scan berulang
            if (!scannerActive) return;
            
            // Log untuk debugging
            console.log(`Code matched = ${decodedText}`, decodedResult);
            
            // Isi input token dengan hasil scan
            document.getElementById('qr_token').value = decodedText;
            
            // Tampilkan alert sukses
            alert('QR Code berhasil discan!\nToken: ' + decodedText);
            
            // Set flag agar tidak scan lagi
            scannerActive = false;
            
            // Stop scanner untuk cegah spam scan
            if (html5QrcodeScanner) {
                html5QrcodeScanner.clear().then(() => {
                    console.log('Scanner stopped');
                }).catch(err => {
                    console.error('Error stopping scanner:', err);
                });
            }
        }

        function onScanFailure(error) {
            // Handle scan failure, biasanya diabaikan
            // console.warn(`Code scan error = ${error}`);
        }

        // Inisialisasi scanner saat halaman dimuat
        document.addEventListener('DOMContentLoaded', function() {
            html5QrcodeScanner = new Html5QrcodeScanner(
                "reader",
                { 
                    fps: 10, 
                    qrbox: { width: 250, height: 250 },
                    aspectRatio: 1.0
                },
                /* verbose= */ false
            );
            html5QrcodeScanner.render(onScanSuccess, onScanFailure);
        });


        // ================= HANDLE TOMBOL CHECK-IN & CHECK-OUT =================
        const form = document.getElementById('attendance-form');
        const btnCheckin = document.getElementById('btn-checkin');
        const btnCheckout = document.getElementById('btn-checkout');
        const noteSection = document.getElementById('note-section');

        // Tombol Check-In
        btnCheckin.addEventListener('click', function() {
            // Set action ke route check-in
            form.action = '/checkin';
            
            // Tampilkan section catatan
            noteSection.style.display = 'block';
            
            // Submit form
            form.submit();
        });

        // Tombol Check-Out
        btnCheckout.addEventListener('click', function() {
            // Set action ke route check-out
            form.action = '/checkout';
            
            // Sembunyikan section catatan (tidak perlu untuk checkout)
            noteSection.style.display = 'none';
            
            // Submit form
            form.submit();
        });


        // ================= GPS + MAP =================
        document.addEventListener('DOMContentLoaded', function () {
            if (!navigator.geolocation) {
                alert('Browser tidak mendukung GPS');
                return;
            }

            // Default lokasi kantor
            const officeLat = -5.4290;
            const officeLng = 105.2520;
            const radius = 100; // meter

            navigator.geolocation.getCurrentPosition(
                function (pos) {
                    const lat = pos.coords.latitude;
                    const lng = pos.coords.longitude;

                    // Set nilai GPS
                    document.getElementById('lat').value = lat;
                    document.getElementById('lng').value = lng;
                    document.getElementById('lat_display').value = lat.toFixed(6);
                    document.getElementById('lng_display').value = lng.toFixed(6);

                    // Inisialisasi peta
                    const map = L.map('map').setView([lat, lng], 16);

                    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                        attribution: '© OpenStreetMap contributors'
                    }).addTo(map);

                    // Marker lokasi user
                    L.marker([lat, lng])
                        .addTo(map)
                        .bindPopup('<b>Lokasi Anda</b>')
                        .openPopup();

                    // Marker kantor
                    L.marker([officeLat, officeLng])
                        .addTo(map)
                        .bindPopup('<b>Lokasi Instansi</b>');

                    // Circle radius kantor
                    L.circle([officeLat, officeLng], {
                        radius: radius,
                        color: 'red',
                        fillColor: '#f03',
                        fillOpacity: 0.1
                    }).addTo(map);
                },
                function (error) {
                    console.error('Error GPS:', error);
                    alert('GPS harus diaktifkan untuk absensi');
                },
                { 
                    enableHighAccuracy: true,
                    timeout: 5000,
                    maximumAge: 0
                }
            );
        });
    </script>
</x-app-layout>