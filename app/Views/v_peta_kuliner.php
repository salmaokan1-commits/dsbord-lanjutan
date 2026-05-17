<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kuliner Kampus - Semarang</title>
    <!-- Leaflet CSS -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />

    <style>
    :root { --primary-orange: #f35d07; }
    
    body { 
        margin: 0; 
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; 
        display: flex; 
        flex-direction: column; 
        height: 100vh; 
        overflow: hidden; 
    }
    
    /* Navbar Header - Pastikan Muncul di Paling Atas */
    header { 
        background: white; 
        padding: 10px 25px; 
        display: flex; 
        justify-content: space-between; 
        align-items: center; 
        box-shadow: 0 2px 10px rgba(0,0,0,0.1); 
        z-index: 1100; /* Lebih tinggi dari komponen lain */
        height: 60px; /* Tinggi tetap agar tidak tertutup main */
        box-sizing: border-box;
    }

    .logo { 
        display: flex; 
        align-items: center; 
        color: var(--primary-orange); 
        font-weight: bold; 
        font-size: 18px; 
    }

    .nav-right { 
        display: flex; 
        align-items: center; 
        gap: 15px; 
        font-size: 13px; 
    }

    .btn-dasbor { 
        background: var(--primary-orange); 
        color: white; 
        padding: 6px 14px; 
        border-radius: 6px; 
        text-decoration: none; 
        font-weight: 600;
        transition: 0.3s;
    }

    .btn-dasbor:hover {
        background: #d45106;
    }
    
    main { 
        display: flex; 
        flex: 1; 
        position: relative; 
        overflow: hidden; /* Mencegah main meluap keluar body */
    }
    
    /* Sidebar List - Tetap Ramping */
    .sidebar { 
        width: 260px; /* Ramping sesuai permintaan sebelumnya */
        background: white; 
        border-right: 1px solid #eee; 
        z-index: 100; 
        padding: 15px; 
        display: flex;
        flex-direction: column;
        height: 100%;
        box-sizing: border-box;
    }

    .sidebar h3 { 
        color: #333; 
        border-bottom: 2px solid var(--primary-orange); 
        padding-bottom: 8px; 
        margin-top: 0;
        margin-bottom: 12px;
        font-size: 16px;
    }

    /* Container list yang bisa di-scroll kebawah */
    #list-kuliner {
        flex: 1;
        overflow-y: auto;
        padding-right: 5px;
    }

    #list-kuliner::-webkit-scrollbar {
        width: 4px;
    }
    #list-kuliner::-webkit-scrollbar-thumb {
        background: #ddd;
        border-radius: 10px;
    }

    /* Card Tetap Ramping */
    .card { 
        background: #fff; 
        border: 1px solid #eee; 
        padding: 8px; 
        margin-bottom: 12px; 
        border-radius: 10px; 
        transition: 0.3s; 
        cursor: pointer;
    }

    .card:hover { 
        box-shadow: 0 4px 10px rgba(0,0,0,0.08); 
        border-color: var(--primary-orange); 
    }

    .card img { 
        width: 100%; 
        height: 85px; /* Tetap kecil sesuai permintaan */
        object-fit: cover; 
        border-radius: 8px; 
        margin-bottom: 6px; 
    }

    .card h4 { 
        margin: 2px 0; 
        color: #222; 
        font-size: 14px;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }

    .card p { 
        margin: 2px 0; 
        font-size: 11px; 
        color: #666; 
        line-height: 1.2;
    }

    .card[style*="display: none"] {
        display: none !important;
    }

    /* Area Map */
    #map { flex: 1; z-index: 1; }

    /* Filter Melayang */
    .filter-card {
        position: absolute; 
        right: 20px; 
        top: 20px; 
        width: 180px;
        background: white; 
        z-index: 1000; 
        padding: 12px;
        border-radius: 10px; 
        box-shadow: 0 4px 15px rgba(0,0,0,0.1);
    }

    /* Modal Pemesanan */
    .modal {
        display: none;
        position: fixed;
        z-index: 2000;
        left: 0;
        top: 0;
        width: 100%;
        height: 100%;
        background-color: rgba(0, 0, 0, 0.5);
        align-items: center;
        justify-content: center;
    }

    .modal.active {
        display: flex;
    }

    .modal-content {
        background: white;
        border-radius: 10px;
        padding: 25px;
        max-width: 500px;
        width: 90%;
        max-height: 80vh;
        overflow-y: auto;
        box-shadow: 0 5px 25px rgba(0, 0, 0, 0.2);
    }

    .modal-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 20px;
        border-bottom: 2px solid var(--primary-orange);
        padding-bottom: 10px;
    }

    .modal-header h2 {
        margin: 0;
        color: var(--primary-orange);
        font-size: 20px;
    }

    .modal-close {
        background: none;
        border: none;
        font-size: 28px;
        cursor: pointer;
        color: #666;
    }

    .modal-close:hover {
        color: #000;
    }

    .form-group {
        margin-bottom: 15px;
    }

    .form-group label {
        display: block;
        margin-bottom: 5px;
        color: #333;
        font-weight: 600;
        font-size: 13px;
    }

    .form-group input,
    .form-group select,
    .form-group textarea {
        width: 100%;
        padding: 8px 10px;
        border: 1px solid #ddd;
        border-radius: 5px;
        font-size: 13px;
        box-sizing: border-box;
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    }

    .form-group textarea {
        resize: vertical;
        min-height: 60px;
    }

    .form-group input:focus,
    .form-group select:focus,
    .form-group textarea:focus {
        outline: none;
        border-color: var(--primary-orange);
        box-shadow: 0 0 5px rgba(243, 93, 7, 0.3);
    }

    .form-actions {
        display: flex;
        gap: 10px;
        margin-top: 20px;
    }

    .btn-submit {
        flex: 1;
        padding: 10px;
        background: var(--primary-orange);
        color: white;
        border: none;
        border-radius: 5px;
        cursor: pointer;
        font-weight: bold;
        font-size: 14px;
    }

    .btn-submit:hover {
        background: #d45106;
    }

    .btn-cancel {
        flex: 1;
        padding: 10px;
        background: #e0e0e0;
        color: #333;
        border: none;
        border-radius: 5px;
        cursor: pointer;
        font-weight: bold;
        font-size: 14px;
    }

    .btn-cancel:hover {
        background: #d0d0d0;
    }

    #order-items table {
        width: 100%;
        border-collapse: collapse;
        margin-top: 10px;
    }

    #order-items th,
    #order-items td {
        padding: 8px 6px;
        border-bottom: 1px solid #eee;
        font-size: 12px;
    }

    #order-items th {
        color: #555;
        text-align: left;
    }

    .receipt-body {
        font-family: 'Courier New', Courier, monospace;
        font-size: 13px;
        color: #222;
        white-space: pre-wrap;
    }

    .receipt-body p {
        margin: 5px 0;
    }

    .receipt-item {
        margin-bottom: 6px;
    }

    .info-pesanan {
        background: #f0f0f0;
        padding: 10px;
        border-radius: 5px;
        margin-bottom: 15px;
        border-left: 3px solid var(--primary-orange);
    }

    .info-pesanan p {
        margin: 3px 0;
        font-size: 12px;
        color: #333;
    }

    .info-pesanan strong {
        color: var(--primary-orange);
    }

    .order-table {
        width: 100%;
        border-collapse: collapse;
        margin-top: 10px;
        margin-bottom: 10px;
    }

    .order-table th,
    .order-table td {
        padding: 8px 10px;
        border: 1px solid #ddd;
        font-size: 13px;
    }

    .order-table th {
        background: #fafafa;
        text-align: left;
    }

    .order-table td button {
        padding: 4px 8px;
        background: #e74c3c;
        border: none;
        color: #fff;
        border-radius: 4px;
        cursor: pointer;
    }

    .order-table td button:hover {
        background: #c0392b;
    }
</style>
</head>
<body>

<header>
    <div class="logo">
        Kuliner Kampus
    </div>
    <div class="nav-right">
        <span>Halo, <strong><?= session()->get('nama') ?? 'Tamu' ?></strong></span>
        <a href="#" class="btn-dasbor">Dasbor</a>
        <a href="<?= base_url('logout') ?>" style="text-decoration:none; color: #666;">Keluar</a>
    </div>
</header>

<main>
    <!-- BAGIAN SIDEBAR YANG SUDAH DIRAPIKAN -->
<div class="sidebar">
    <!-- Tombol ini akan tetap di atas -->
    <button onclick="bukaModal()" class="btn-dasbor" style="width: 100%; margin-bottom: 15px; padding: 10px; font-size: 14px;">
        + Tambah Restoran
    </button>

    <h3 style="margin-top: 0; font-size: 18px;">Daftar Kuliner</h3>
    
    <!-- Div inilah yang akan memiliki fungsi scroll -->
    <div id="list-kuliner">
        <?php foreach($kuliner ?? [] as $k): ?>
            <div class="card" data-kategori="<?= $k['kategori'] ?>" onclick="focusMarker(<?= $k['latitude'] ?>, <?= $k['longitude'] ?>)">
                <img src="<?= base_url('img/' . $k['foto']) ?>" alt="<?= $k['nama_tempat'] ?>">
                <div class="card-info">
                    <h4 style="margin: 2px 0; font-size: 14px;"><?= $k['nama_tempat'] ?></h4>
                    <p style="color: #f39c12; font-weight: bold; font-size: 12px; margin: 2px 0;">
                        ★ <?= $k['rating'] ?> | <span style="color: #666; font-weight: normal;"><?= $k['kategori'] ?></span>
                    </p>
                    <p style="font-size: 10px; color: #888; line-height: 1.2;">
                        <?= substr($k['alamat_lengkap'], 0, 60) ?>... <!-- Membatasi teks alamat agar tidak terlalu panjang -->
                    </p>
                    <button onclick='bukaModalPesan(<?= json_encode($k, JSON_HEX_TAG|JSON_HEX_APOS|JSON_HEX_QUOT|JSON_HEX_AMP) ?>)' style="width: 100%; padding: 6px; margin-top: 6px; background: var(--primary-orange); color: white; border: none; border-radius: 5px; cursor: pointer; font-weight: bold; font-size: 12px;">Pesan</button>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</div>

    <div id="map"></div>

    <!-- Sidebar Filter Melayang -->
    <div class="filter-card">
    <div class="filter-group">
        <strong>Kategori</strong><br>
        <label><input type="checkbox" class="filter-check" value="Cafe" checked> Cafe</label><br>
        <label><input type="checkbox" class="filter-check" value="Angkringan" checked> Angkringan</label><br>
        <label><input type="checkbox" class="filter-check" value="Resto" checked> Resto</label>
    </div>
    <button class="btn-terapkan" id="btn-filter">Terapkan Filter</button>
</div>
</main>

<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<script>
    // Inisialisasi peta menggunakan tema Voyager agar bersih
    const map = L.map('map').setView([-6.966667, 110.416664], 13);
    L.tileLayer('https://{s}.basemaps.cartocdn.com/rastertiles/voyager/{z}/{x}/{y}{r}.png', {
        attribution: '&copy; OpenStreetMap'
    }).addTo(map);

    const dataKuliner = <?= json_encode($kuliner ?? []) ?>;
    const markers = {};
    const markerData = {}; // Simpan data marker untuk filter

    dataKuliner.forEach(item => {
        if(item.latitude && item.longitude) {
            const m = L.marker([item.latitude, item.longitude]).addTo(map);
            
            // Info-box (Popup) sesuai desain target
            m.bindPopup(`
                <img src="<?= base_url('img/') ?>/${item.foto}" class="popup-img">
                <div class="popup-body">
                    <strong>${item.nama_tempat}</strong><br>
                    <span style="color: #f39c12;">★ ${item.rating}/5</span><br>
                    <small>${item.jam_operasional}</small>
                    <a class="btn-detail" href="https://www.google.com/maps/search/?api=1&query=${item.latitude},${item.longitude}" target="_blank">Lihat di Maps</a>
                </div>
            `);
            
            const key = `${item.latitude}${item.longitude}`;
            markers[key] = m;
            markerData[key] = item; // Simpan data untuk filter
        }
    });

    // Fungsi klik di sidebar otomatis zoom ke peta
    function focusMarker(lat, lng) {
        map.setView([lat, lng], 17);
        markers[`${lat}${lng}`].openPopup();
    }
    // Fungsi untuk memfilter daftar kuliner
    function terapkanFilter() {
        // 1. Ambil semua kategori yang dicentang
        const checkboxes = document.querySelectorAll('.filter-check:checked');
        const kategoriTerpilih = Array.from(checkboxes).map(cb => cb.value);

        // 2. Ambil semua elemen kartu kuliner di sidebar
        const cards = document.querySelectorAll('#list-kuliner .card');

        cards.forEach(card => {
            const kategoriCard = card.getAttribute('data-kategori');

            // 3. Logika Tampilkan/Sembunyikan
            if (kategoriTerpilih.length === 0) {
                // Jika tidak ada yang dicentang, sembunyikan semua
                card.style.display = 'none';
            } else if (kategoriTerpilih.includes(kategoriCard)) {
                // Jika kategori card ada di dalam daftar yang dicentang, tampilkan
                card.style.display = 'block';
            } else {
                // Jika tidak, sembunyikan
                card.style.display = 'none';
            }
        });

        // 4. Filter marker di peta juga
        Object.keys(markerData).forEach(key => {
            const item = markerData[key];
            const marker = markers[key];

            if (kategoriTerpilih.length === 0) {
                map.removeLayer(marker);
            } else if (kategoriTerpilih.includes(item.kategori)) {
                if (!map.hasLayer(marker)) {
                    map.addLayer(marker);
                }
            } else {
                map.removeLayer(marker);
            }
        });
    }

    // Event listener untuk tombol filter
    document.getElementById('btn-filter').addEventListener('click', terapkanFilter);

    // Jalankan filter saat halaman mula-mula dimuat
    document.addEventListener('DOMContentLoaded', terapkanFilter);

    const menuOptions = {
        Angkringan: [
            { name: 'Nasi Kucing', price: 12000 },
            { name: 'Sate Usus', price: 15000 },
            { name: 'Es Teh Manis', price: 7000 }
        ],
        Cafe: [
            { name: 'Kopi Latte', price: 28000 },
            { name: 'Chicken Sandwich', price: 45000 },
            { name: 'Pancake Coklat', price: 38000 }
        ],
        Resto: [
            { name: 'Nasi Goreng Spesial', price: 35000 },
            { name: 'Steak Daging', price: 85000 },
            { name: 'Aneka Seafood', price: 90000 }
        ]
    };

    function formatRupiah(value) {
        return 'Rp ' + Number(value).toLocaleString('id-ID');
    }

    let orderItems = [];
    let selectedKuliner = null;

    function populateMenuOptions(kategori) {
        const select = document.getElementById('menu_pesanan');
        select.innerHTML = '<option value="">-- Pilih Menu --</option>';
        const options = menuOptions[kategori] || [];

        options.forEach(option => {
            const el = document.createElement('option');
            el.value = option.name;
            el.dataset.price = option.price;
            el.textContent = `${option.name} - ${formatRupiah(option.price)}`;
            select.appendChild(el);
        });

        renderOrderItems();
    }

    function addMenuItem() {
        const select = document.getElementById('menu_pesanan');
        const menuName = select.value;
        const selectedOption = select.selectedOptions[0];
        const quantity = Number(document.getElementById('jumlah').value) || 1;

        if (!menuName) {
            alert('Silakan pilih menu terlebih dahulu.');
            return;
        }

        const price = Number(selectedOption.dataset.price || 0);
        const existingIndex = orderItems.findIndex(item => item.name === menuName);

        if (existingIndex !== -1) {
            orderItems[existingIndex].quantity += quantity;
            orderItems[existingIndex].subtotal = orderItems[existingIndex].quantity * orderItems[existingIndex].price;
        } else {
            orderItems.push({
                name: menuName,
                quantity: quantity,
                price: price,
                subtotal: price * quantity
            });
        }

        renderOrderItems();
        document.getElementById('jumlah').value = 1;
        select.value = '';
    }

    function renderOrderItems() {
        const tbody = document.querySelector('#order-items-table tbody');
        tbody.innerHTML = '';

        if (!orderItems.length) {
            tbody.innerHTML = '<tr><td colspan="5" style="text-align:center; color:#666;">Belum ada menu yang ditambahkan.</td></tr>';
        } else {
            orderItems.forEach((item, index) => {
                const row = document.createElement('tr');
                row.innerHTML = `
                    <td>${item.name}</td>
                    <td>${item.quantity}</td>
                    <td>${formatRupiah(item.price)}</td>
                    <td>${formatRupiah(item.subtotal)}</td>
                    <td><button type="button" onclick="removeMenuItem(${index})">Hapus</button></td>
                `;
                tbody.appendChild(row);
            });
        }

        calculateTotal();
    }

    function removeMenuItem(index) {
        orderItems.splice(index, 1);
        renderOrderItems();
    }

    function calculateTotal() {
        const subtotal = orderItems.reduce((sum, item) => sum + item.subtotal, 0);
        const serviceFee = Math.round(subtotal * 0.02);
        const tax = Math.round(subtotal * 0.10);
        const totalBayar = subtotal + serviceFee + tax;
        const totalQty = orderItems.reduce((sum, item) => sum + item.quantity, 0);

        document.getElementById('summary-subtotal').textContent = formatRupiah(subtotal);
        document.getElementById('summary-service').textContent = formatRupiah(serviceFee);
        document.getElementById('summary-tax').textContent = formatRupiah(tax);
        document.getElementById('summary-total').textContent = formatRupiah(totalBayar);
        document.getElementById('summary-qty').textContent = totalQty;
        document.getElementById('summary-change').textContent = formatRupiah(0);

        document.getElementById('input_harga_perkiraan').value = totalBayar;
        document.getElementById('input_menu_pesanan').value = JSON.stringify(orderItems);
        document.getElementById('input_subtotal').value = subtotal;
        document.getElementById('input_service_fee').value = serviceFee;
        document.getElementById('input_tax').value = tax;
        document.getElementById('input_total_bayar').value = totalBayar;
        document.getElementById('input_total_qty').value = totalQty;
    }

    function bukaModalPesan(kulinerData) {
        selectedKuliner = kulinerData;
        document.getElementById('modalPesan').classList.add('active');
        document.getElementById('nama-tempat-pesan').textContent = kulinerData.nama_tempat;
        document.getElementById('alamat-tempat-pesan').textContent = kulinerData.alamat_lengkap;
        document.getElementById('kategori-pesan').textContent = kulinerData.kategori;
        document.getElementById('rating-pesan').textContent = kulinerData.rating;
        document.getElementById('input_id_tempat').value = kulinerData.id || kulinerData.id_tempat || '';
        document.getElementById('input_nama_tempat').value = kulinerData.nama_tempat;
        document.getElementById('input_kategori').value = kulinerData.kategori;
        orderItems = [];
        document.getElementById('form-pesanan').reset();
        document.getElementById('meja').value = '';
        populateMenuOptions(kulinerData.kategori);
        renderOrderItems();
    }

    function tutupModalPesan() {
        document.getElementById('modalPesan').classList.remove('active');
        selectedKuliner = null;
    }

    document.addEventListener('DOMContentLoaded', function() {
        document.getElementById('btn-tutup-pesan').addEventListener('click', tutupModalPesan);
        document.getElementById('btn-batal-pesan').addEventListener('click', tutupModalPesan);
        document.getElementById('btn-tambah-menu').addEventListener('click', addMenuItem);

        // Klik di luar modal untuk menutup
        document.getElementById('modalPesan').addEventListener('click', function(e) {
            if (e.target === this) {
                tutupModalPesan();
            }
        });

        document.getElementById('modalStruk').addEventListener('click', function(e) {
            if (e.target === this) {
                tutupModalStruk();
            }
        });

        // Submit form pemesanan
        document.getElementById('form-pesanan').addEventListener('submit', function(e) {
            e.preventDefault();

            if (!orderItems.length) {
                alert('Silakan tambahkan minimal satu menu pada pesanan.');
                return;
            }

            const formData = new FormData(this);
            formData.set('harga_perkiraan', document.getElementById('input_harga_perkiraan').value || '0');
            formData.set('menu_pesanan', document.getElementById('input_menu_pesanan').value || '[]');
            formData.set('total_bayar', document.getElementById('input_total_bayar').value || '0');
            formData.set('subtotal', document.getElementById('input_subtotal').value || '0');
            formData.set('service_fee', document.getElementById('input_service_fee').value || '0');
            formData.set('tax', document.getElementById('input_tax').value || '0');
            formData.set('total_qty', document.getElementById('input_total_qty').value || '0');

            fetch('<?= base_url('pesanan/simpan') ?>', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    tampilkanStruk({
                        pesanan_id: data.pesanan_id,
                        nama_tempat: document.getElementById('input_nama_tempat').value,
                        kategori: document.getElementById('input_kategori').value,
                        items: orderItems.slice(),
                        subtotal: Number(document.getElementById('input_subtotal').value || 0),
                        service_fee: Number(document.getElementById('input_service_fee').value || 0),
                        tax: Number(document.getElementById('input_tax').value || 0),
                        total_bayar: Number(document.getElementById('input_total_bayar').value || 0),
                        bayar: Number(document.getElementById('input_total_bayar').value || 0),
                        kembali: 0,
                        nama_pemesan: document.getElementById('nama_pemesan').value,
                        nomor_hp: document.getElementById('nomor_hp').value,
                        metode_pembayaran: document.getElementById('metode_pembayaran').value,
                        catatan: document.getElementById('catatan').value,
                        meja: document.getElementById('meja').value || '-',
                        tanggal_pesan: new Date().toLocaleString('id-ID')
                    });
                    orderItems = [];
                    document.getElementById('form-pesanan').reset();
                    renderOrderItems();
                } else {
                    alert('Gagal membuat pesanan: ' + (data.message || 'Error tidak diketahui'));
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Terjadi kesalahan saat membuat pesanan');
            });
        });
    });

    function tampilkanStruk(data) {
        document.getElementById('modalPesan').classList.remove('active');
        document.getElementById('modalStruk').classList.add('active');

        document.getElementById('struk_id').textContent = data.pesanan_id;
        document.getElementById('struk_phone').textContent = data.nomor_hp;
        document.getElementById('struk_nama_pemesan').textContent = data.nama_pemesan;
        document.getElementById('struk_meja').textContent = data.meja;
        document.getElementById('struk_tanggal').textContent = data.tanggal_pesan;
        document.getElementById('struk_items').innerHTML = data.items.map((item, index) => {
            return `
                <div class="receipt-item">
                    <div>${index + 1}. ${item.name}</div>
                    <div style="margin-left: 12px;">${item.quantity} x ${formatRupiah(item.price)} = ${formatRupiah(item.subtotal)}</div>
                </div>`;
        }).join('');
        document.getElementById('struk_qty').textContent = data.items.reduce((sum, item) => sum + item.quantity, 0);
        document.getElementById('struk_subtotal').textContent = formatRupiah(data.subtotal);
        document.getElementById('struk_service').textContent = formatRupiah(data.service_fee);
        document.getElementById('struk_tax').textContent = formatRupiah(data.tax);
        document.getElementById('struk_total').textContent = formatRupiah(data.total_bayar);
        document.getElementById('struk_bayar').textContent = formatRupiah(data.bayar);
        document.getElementById('struk_change').textContent = formatRupiah(data.kembali);
        document.getElementById('struk_catatan').textContent = data.catatan || '-';
    }

    function tutupModalStruk() {
        document.getElementById('modalStruk').classList.remove('active');
    }
</script>

<!-- Modal Pemesanan -->
<div id="modalPesan" class="modal">
    <div class="modal-content">
        <div class="modal-header">
            <h2>Form Pemesanan Makanan</h2>
            <button id="btn-tutup-pesan" class="modal-close">&times;</button>
        </div>

        <!-- Info Tempat -->
        <div class="info-pesanan">
            <p><strong>Tempat:</strong> <span id="nama-tempat-pesan"></span></p>
            <p><strong>Kategori:</strong> <span id="kategori-pesan"></span></p>
            <p><strong>Rating:</strong> <span id="rating-pesan"></span>/5</p>
            <p><strong>Alamat:</strong> <span id="alamat-tempat-pesan"></span></p>
        </div>

        <!-- Form Pemesanan -->
        <form id="form-pesanan">
            <input type="hidden" id="input_id_tempat" name="id_tempat" value="">
            <input type="hidden" id="input_nama_tempat" name="nama_tempat" value="">
            <input type="hidden" id="input_kategori" name="kategori" value="">

            <div class="form-group">
                <label for="nama_pemesan">Nama Pemesan</label>
                <input type="text" id="nama_pemesan" name="nama_pemesan" value="<?= session()->get('nama') ?? '' ?>" required>
            </div>

            <div class="form-group">
                <label for="nomor_hp">Nomor HP</label>
                <input type="tel" id="nomor_hp" name="nomor_hp" placeholder="Contoh: 08123456789" required>
            </div>

            <div class="form-group" style="display: flex; gap: 10px; align-items: flex-end;">
                <div style="flex: 2;">
                    <label for="menu_pesanan">Pilih Menu</label>
                    <select id="menu_pesanan">
                        <option value="">-- Pilih Menu --</option>
                    </select>
                </div>
                <div style="flex: 1;">
                    <label for="jumlah">Jumlah</label>
                    <input type="number" id="jumlah" min="1" value="1" min="1">
                </div>
                <div style="flex: 1; display: flex; align-items: center;">
                    <button type="button" id="btn-tambah-menu" class="btn-submit" style="margin-top: 25px; width: 100%;">Tambah Menu</button>
                </div>
            </div>

            <div class="form-group">
                <label>Daftar Item Pesanan</label>
                <table class="order-table" id="order-items-table">
                    <thead>
                        <tr>
                            <th>Menu</th>
                            <th>Qty</th>
                            <th>Harga</th>
                            <th>Subtotal</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr><td colspan="5" style="text-align:center; color:#666;">Belum ada menu yang ditambahkan.</td></tr>
                    </tbody>
                </table>
            </div>

            <div class="form-group">
                <label for="meja">Meja</label>
                <input type="text" id="meja" name="meja" placeholder="Contoh: 12/5">
            </div>

            <div class="form-group">
                <label>Ringkasan Harga</label>
                <div id="summary-info" style="font-size:13px; color:#333; line-height:1.5;">
                    <p>Sub Total: <strong id="summary-subtotal">Rp 0</strong></p>
                    <p>Biaya Layanan (2%): <strong id="summary-service">Rp 0</strong></p>
                    <p>Pajak PPN (10%): <strong id="summary-tax">Rp 0</strong></p>
                    <p>Total Bayar: <strong id="summary-total">Rp 0</strong></p>
                    <p>Total QTY: <strong id="summary-qty">0</strong></p>
                    <p>Kembali: <strong id="summary-change">Rp 0</strong></p>
                </div>
            </div>

            <input type="hidden" id="input_menu_pesanan" name="menu_pesanan" value="[]">
            <input type="hidden" id="input_harga_perkiraan" name="harga_perkiraan" value="0">
            <input type="hidden" id="input_subtotal" name="subtotal" value="0">
            <input type="hidden" id="input_service_fee" name="service_fee" value="0">
            <input type="hidden" id="input_tax" name="tax" value="0">
            <input type="hidden" id="input_total_bayar" name="total_bayar" value="0">
            <input type="hidden" id="input_total_qty" name="total_qty" value="0">

            <div class="form-group">
                <label for="catatan">Catatan / Permintaan Khusus</label>
                <textarea id="catatan" name="catatan" placeholder="Contoh: Tidak pake cabai, Pesan untuk 3 orang..."></textarea>
            </div>

            <div class="form-group">
                <label for="metode_pembayaran">Metode Pembayaran</label>
                <select id="metode_pembayaran" name="metode_pembayaran" required>
                    <option value="">-- Pilih Metode Pembayaran --</option>
                    <option value="Tunai">Tunai</option>
                    <option value="Transfer Bank">Transfer Bank</option>
                    <option value="E-Wallet">E-Wallet (GCash, Dana, OVO, dll)</option>
                </select>
            </div>

            <div class="form-actions">
                <button type="submit" class="btn-submit">Buat Pesanan</button>
                <button type="button" id="btn-batal-pesan" class="btn-cancel">Batal</button>
            </div>
        </form>
    </div>
</div>

<!-- Modal Struk -->
<div id="modalStruk" class="modal">
    <div class="modal-content">
        <div class="modal-header">
            <h2>Struk Pesanan</h2>
            <button type="button" class="modal-close" onclick="tutupModalStruk()">&times;</button>
        </div>
        <div class="receipt-body">
            <p style="text-align:center; font-weight:bold;">PESANAN</p>
            <p>No. Pesanan: <span id="struk_id"></span></p>
            <p>No Telp: <span id="struk_phone"></span></p>
            <p>Pelanggan: <span id="struk_nama_pemesan"></span></p>
            <p>Meja: <span id="struk_meja"></span></p>
            <p>Waktu: <span id="struk_tanggal"></span></p>
            <p style="margin-top: 10px; font-weight:bold;">Struk Pesanan</p>
            <p>Dine In</p>
            <div id="struk_items"></div>
            <p style="margin-top: 10px;">Total QTY : <strong id="struk_qty"></strong></p>
            <p>Sub Total : <strong id="struk_subtotal"></strong></p>
            <p>Biaya Layanan (2%) : <strong id="struk_service"></strong></p>
            <p>Pajak : PPN(10%) <strong id="struk_tax"></strong></p>
            <p>Total : <strong id="struk_total"></strong></p>
            <p>Bayar : <strong id="struk_bayar"></strong></p>
            <p>Kembali : <strong id="struk_change"></strong></p>
            <p style="margin-top: 10px;">Bukan Bukti Pembayaran</p>
            <p>Esnya dikit aja</p>
            <p>Terimakasih Telah Berbelanja di Toko Kami</p>
            <p><strong>Catatan:</strong> <span id="struk_catatan"></span></p>
        </div>
        <div class="form-actions">
            <button type="button" class="btn-submit" onclick="window.print()">Cetak Struk</button>
            <button type="button" class="btn-cancel" onclick="tutupModalStruk()">Tutup</button>
        </div>
    </div>
</div>
</body>
</html>