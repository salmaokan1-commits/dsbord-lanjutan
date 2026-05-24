<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kuliner Kampus - Semarang</title>
    <!-- Leaflet CSS -->
    <link rel="stylesheet" href="<?= base_url('lib/leaflet/leaflet.css') ?>" />

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

    .search-wrapper {
        flex: 1;
        max-width: 560px;
        margin: 0 14px;
        display: flex;
        align-items: center;
        justify-content: center;
        position: relative;
    }

    .search-wrapper input {
        width: 100%;
        max-width: 560px;
        min-width: 220px;
        height: 38px;
        padding: 8px 42px 8px 14px;
        border: 1px solid #fafafa;
        border-radius: 999px;
        font-size: 14px;
        color: #333;
        transition: border-color .2s, box-shadow .2s, background .2s;
        background: #fafafa;
        box-shadow: inset 0 1px 2px rgba(0, 0, 0, 0.04);
    }

    .search-wrapper input:focus {
        outline: none;
        border-color: var(--primary-orange);
        box-shadow: 0 0 0 2px rgba(243,93,7,0.12);
        background: white;
    }

    .search-wrapper .search-icon {
        position: absolute;
        right: 14px;
        display: flex;
        align-items: center;
        justify-content: center;
        width: 22px;
        height: 22px;
        pointer-events: none;
    }

    .search-wrapper .search-icon svg {
        width: 18px;
        height: 18px;
        display: block;
        fill: var(--primary-orange);
    }

    .search-wrapper input::placeholder {
        color: #999;
    }

    @media (max-width: 840px) {
        header {
            flex-wrap: wrap;
            gap: 10px;
            height: auto;
            align-items: stretch;
        }

        .logo,
        .search-wrapper,
        .nav-right {
            width: 100%;
            justify-content: center;
        }

        .search-wrapper {
            margin: 0;
        }

        .nav-right {
            justify-content: center;
            gap: 10px;
            flex-wrap: wrap;
        }
    }

    .search-alert {
        position: fixed;
        top: 78px;
        left: 50%;
        transform: translateX(-50%) translateY(-12px);
        background: var(--primary-orange);
        color: white;
        padding: 10px 18px;
        border-radius: 999px;
        box-shadow: 0 14px 30px rgba(0,0,0,0.18);
        opacity: 0;
        pointer-events: none;
        transition: opacity .25s ease, transform .25s ease;
        z-index: 1205;
        font-size: 13px;
        white-space: nowrap;
    }

    .search-alert.active {
        opacity: 1;
        transform: translateX(-50%) translateY(0);
    }

    .sidebar-actions {
        display: flex;
        flex-direction: column;
        gap: 10px;
        margin-bottom: 18px;
    }

    .btn-sidebar-primary,
    .btn-sidebar-secondary {
        width: 100%;
        border: none;
        border-radius: 12px;
        padding: 6px 14px;
        font-size: 11px;
        font-weight: 700;
        cursor: pointer;
        transition: background .2s ease;
    }

    .btn-sidebar-primary {
        background: #71dd63;
        color: white;
    }

    .btn-sidebar-primary:hover {
        background: #62c055;
    }

    .btn-sidebar-secondary {
        background: #e1eb5b;
        color: white;
    }

    .btn-sidebar-secondary:hover {
        background: #c5ce51;
    }

    .sidebar-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 12px;
        margin-bottom: 14px;
    }

    .sidebar-header h3 {
        margin: 0;
        font-size: 20px;
        color: #222;
    }

    .btn-filter-sidebar {
        white-space: nowrap;
        background: var(--primary-orange);
        color: white;
        border: none;
        border-radius: 6px;
        padding: 5px 10px;
        font-size: 10px;
        font-weight: 700;
        cursor: pointer;
        transition: background .2s ease;
    }

    .btn-filter-sidebar:hover {
        background: #d45106;
    }

    .btn-dasbor { 
        background: var(--primary-orange); 
        color: white; 
        padding: 4px 10px; 
        border-radius: 6px; 
        text-decoration: none; 
        font-weight: 600;
        transition: 0.2s;
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

    .card img,
    .popup-img {
        width: 100%; 
        max-width: 100%;
        height: 110px; 
        object-fit: cover; 
        border-radius: 8px; 
        margin-bottom: 8px; 
        display: block;
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
        left: 300px;
        top: 96px;
        width: 250px;
        min-width: 230px;
        background: rgba(255, 255, 255, 0.3);
        z-index: 1000;
        padding: 18px 18px 14px;
        border-radius: 18px;
        box-shadow: 0 18px 40px rgba(0,0,0,0.15);
        border: 1px solid rgba(255,255,255,0.3);
        backdrop-filter: blur(16px);
        -webkit-backdrop-filter: blur(16px);
        display: none;
        opacity: 0;
        transform: translateY(-8px);
        transition: opacity .18s ease, transform .18s ease;
    }

    .filter-card.open {
        display: block;
        opacity: 1;
        transform: translateY(0);
    }

    .filter-card .filter-title {
        font-size: 15px;
        font-weight: 800;
        color: #242424;
        margin-bottom: 14px;
    }

    .filter-card .filter-section {
        margin-bottom: 14px;
    }

    .filter-card .filter-section strong {
        display: block;
        font-size: 13px;
        color: #222;
        margin-bottom: 10px;
    }

    .filter-card label {
        display: flex;
        align-items: center;
        gap: 10px;
        margin-bottom: 10px;
        font-size: 13px;
        color: #333;
        cursor: pointer;
    }

    .filter-card label input[type="checkbox"] {
        accent-color: var(--primary-orange);
        width: 16px;
        height: 16px;
        border-radius: 4px;
    }

    .filter-card .range-input {
        width: 100%;
        padding: 8px 10px;
        border: 1px solid #dcdcdc;
        border-radius: 10px;
        background: #f7f7f7;
        color: #444;
        font-size: 13px;
        box-sizing: border-box;
    }

    .filter-card .btn-terapkan {
        width: 100%;
        display: inline-flex;
        justify-content: center;
        align-items: center;
        padding: 11px 0;
        border: none;
        border-radius: 12px;
        background: var(--primary-orange);
        color: white;
        font-size: 13px;
        font-weight: 700;
        cursor: pointer;
        transition: transform .2s ease, background .2s ease;
        box-shadow: 0 12px 24px rgba(243,93,7,0.18);
    }

    .filter-card .btn-terapkan:hover {
        background: #d45106;
        transform: translateY(-1px);
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

    /* Styling untuk Button Disabled */
    .btn-disabled {
        background: #cccccc !important;
        color: #999999 !important;
        cursor: not-allowed !important;
    }

    .btn-disabled:hover {
        background: #cccccc !important;
    }

    /* Styling untuk Form Tambah Tempat */
    .form-tambah-tempat input[type="text"],
    .form-tambah-tempat input[type="tel"],
    .form-tambah-tempat input[type="number"],
    .form-tambah-tempat input[type="time"],
    .form-tambah-tempat select,
    .form-tambah-tempat textarea {
        width: 100%;
        padding: 8px 10px;
        border: 1px solid #ddd;
        border-radius: 5px;
        font-size: 13px;
        box-sizing: border-box;
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    }

    .form-tambah-tempat input:focus,
    .form-tambah-tempat select:focus,
    .form-tambah-tempat textarea:focus {
        outline: none;
        border-color: var(--primary-orange);
        box-shadow: 0 0 5px rgba(243, 93, 7, 0.3);
    }

    .form-group-inline {
        display: flex;
        gap: 10px;
    }

    .form-group-inline > div {
        flex: 1;
    }

    .file-input-wrapper {
        position: relative;
        display: inline-block;
        width: 100%;
    }

    .file-input-wrapper input[type="file"] {
        display: none;
    }

    .file-input-label {
        display: block;
        padding: 8px 10px;
        background: #f5f5f5;
        border: 1px solid #ddd;
        border-radius: 5px;
        cursor: pointer;
        text-align: center;
        font-size: 13px;
        transition: 0.3s;
    }

    .file-input-label:hover {
        background: #efefef;
        border-color: var(--primary-orange);
    }

    .file-preview {
        margin-top: 10px;
        max-width: 100%;
        max-height: 150px;
        border-radius: 5px;
    }

    .form-group-file-info {
        font-size: 11px;
        color: #888;
        margin-top: 5px;
    }

    .error-message {
        color: #e74c3c;
        font-size: 12px;
        margin-top: 3px;
        display: none;
    }

    .form-group.error input,
    .form-group.error select,
    .form-group.error textarea {
        border-color: #e74c3c;
    }

    .form-group.error .error-message {
        display: block;
    }

    .not-rated {
        color: #e74c3c;
        font-weight: bold;
    }

    .location-card {
        position: absolute;
        top: 90px;
        right: 20px;
        width: 250px;
        z-index: 1200;
        background: rgba(255, 255, 255, 0.98);
        border-radius: 12px;
        padding: 14px;
        box-shadow: 0 6px 20px rgba(0, 0, 0, 0.12);
        border: 1px solid #e5e5e5;
        font-size: 13px;
        display: none;
    }

    .location-card.active {
        display: block;
    }

    .location-card-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 10px;
    }

    .location-card-header strong {
        font-size: 14px;
        color: #333;
    }

    .location-card-close {
        background: none;
        border: none;
        font-size: 16px;
        cursor: pointer;
        color: #555;
    }

    .location-card-close:hover {
        color: #000;
    }

    .location-card-body {
        margin-bottom: 10px;
        color: #444;
        line-height: 1.4;
    }

    .location-card-actions {
        display: flex;
        justify-content: flex-end;
        gap: 8px;
    }

    .location-card-actions button {
        padding: 6px 10px;
        border-radius: 6px;
        border: none;
        font-size: 12px;
        cursor: pointer;
    }

    .location-card-actions .btn-yes {
        background: #2ecc71;
        color: white;
    }

    .location-card-actions .btn-no {
        background: #e74c3c;
        color: white;
    }

    /* Styling untuk button Edit di sidebar */
    .sidebar-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .btn-edit-sidebar {
        background: var(--primary-orange);
        color: white;
        border: none;
        padding: 4px 8px;
        border-radius: 4px;
        font-size: 11px;
        cursor: pointer;
        font-weight: bold;
        transition: 0.3s;
    }

    .btn-edit-sidebar:hover {
        background: #d45106;
    }

    .btn-edit-sidebar.active {
        background: #2ecc71;
    }

    /* Edit Mode - highlight card */
    .edit-mode .card {
        cursor: pointer;
        transition: 0.3s;
    }

    .edit-mode .card:hover {
        background: #fff3e0;
        border-color: var(--primary-orange);
    }

    /* Modal Action Tempat */
    .modal-action-tempat {
        display: none;
        position: fixed;
        z-index: 2500;
        left: 0;
        top: 0;
        width: 100%;
        height: 100%;
        background-color: rgba(0, 0, 0, 0.5);
        align-items: center;
        justify-content: center;
    }

    .modal-action-tempat.active {
        display: flex;
    }

    .modal-action-content {
        background: white;
        border-radius: 10px;
        padding: 25px;
        max-width: 300px;
        width: 90%;
        box-shadow: 0 5px 25px rgba(0, 0, 0, 0.2);
        text-align: center;
    }

    #modalTambahTempat .modal-action-content {
        max-width: 520px;
        width: 85%;
        padding: 20px;
        max-height: 90vh;
        overflow-y: auto;
        text-align: left;
    }

    #modalTambahTempat .form-group-pesan,
    #modalTambahTempat .modal-action-buttons {
        text-align: left;
    }

    .modal-action-content h3 {
        margin-top: 0;
        color: #333;
        font-size: 18px;
    }

    .modal-action-content p {
        color: #666;
        font-size: 14px;
        margin: 10px 0 20px 0;
    }

    .modal-action-buttons {
        display: flex;
        gap: 10px;
        justify-content: center;
    }

    .btn-action-edit {
        flex: 1;
        padding: 10px;
        background: #2ecc71;
        color: white;
        border: none;
        border-radius: 5px;
        cursor: pointer;
        font-weight: bold;
        font-size: 13px;
    }

    .btn-action-edit:hover {
        background: #27ae60;
    }

    .btn-action-delete {
        flex: 1;
        padding: 10px;
        background: #e74c3c;
        color: white;
        border: none;
        border-radius: 5px;
        cursor: pointer;
        font-weight: bold;
        font-size: 13px;
    }

    .btn-action-delete:hover {
        background: #c0392b;
    }

    .btn-action-cancel {
        flex: 1;
        padding: 10px;
        background: #95a5a6;
        color: white;
        border: none;
        border-radius: 5px;
        cursor: pointer;
        font-weight: bold;
        font-size: 13px;
    }

    .btn-action-cancel:hover {
        background: #7f8c8d;
    }

    /* Modal Konfirmasi Hapus */
    .modal-confirm-delete {
        display: none;
        position: fixed;
        z-index: 2600;
        left: 0;
        top: 0;
        width: 100%;
        height: 100%;
        background-color: rgba(0, 0, 0, 0.5);
        align-items: center;
        justify-content: center;
    }

    .modal-confirm-delete.active {
        display: flex;
    }

    .modal-confirm-content {
        background: white;
        border-radius: 10px;
        padding: 25px;
        max-width: 300px;
        width: 90%;
        box-shadow: 0 5px 25px rgba(0, 0, 0, 0.2);
        text-align: center;
    }

    .modal-confirm-content h3 {
        margin-top: 0;
        color: #e74c3c;
        font-size: 18px;
    }

    .modal-confirm-content p {
        color: #666;
        font-size: 14px;
        margin: 10px 0 20px 0;
    }

    .confirm-buttons {
        display: flex;
        gap: 10px;
        justify-content: center;
    }

    .btn-confirm-yes {
        flex: 1;
        padding: 10px;
        background: #e74c3c;
        color: white;
        border: none;
        border-radius: 5px;
        cursor: pointer;
        font-weight: bold;
        font-size: 13px;
    }

    .btn-confirm-yes:hover {
        background: #c0392b;
    }

    .btn-confirm-no {
        flex: 1;
        padding: 10px;
        background: #2ecc71;
        color: white;
        border: none;
        border-radius: 5px;
        cursor: pointer;
        font-weight: bold;
        font-size: 13px;
    }

    .btn-confirm-no:hover {
        background: #27ae60;
    }
</style>
</head>
<body>

<header>
    <div class="logo">
        Kuliner Kampus
    </div>
    <div class="search-wrapper">
        <input id="map-search-input" type="search" placeholder="cari berdasarkan nama tempat" aria-label="Cari berdasarkan nama tempat">
        <span class="search-icon" aria-hidden="true">
            <svg viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg" role="img" aria-hidden="true"><path d="M15.5 14h-.79l-.28-.27a6.471 6.471 0 001.48-5.34C15.17 5.53 12.64 3 9.5 3S3.83 5.53 3.83 8.83 6.36 14.67 9.5 14.67c1.61 0 3.09-.59 4.23-1.57l.27.28v.79l5 4.99L20.49 19l-4.99-5zm-6 0C7.01 14 5 11.99 5 9.5S7.01 5 9.5 5 14 7.01 14 9.5 11.99 14 9.5 14z"/></svg>
        </span>
    </div>
    <div class="nav-right">
        <span>Halo, <strong><?= isset($nama_user) ? $nama_user : (session()->get('nama') ?? 'Tamu') ?></strong> (<strong style="color: #d45106;"><?= ucfirst(session()->get('role') ?? 'User') ?></strong>)</span>
        <a href="#" class="btn-dasbor">Dasbor</a>
        <a href="<?= base_url('logout') ?>" class="btn-keluar" style="text-decoration:none; color: #666;">Keluar</a>
    </div>
</header>

<main>
    <!-- BAGIAN SIDEBAR YANG SUDAH DIRAPIKAN -->
<div class="sidebar">
    <div class="sidebar-actions">
        <?php if(session()->get('role') === 'admin'): ?>
        <button id="btn-tambah-tempat" type="button" class="btn-sidebar-primary">
            Tambah Tempat
        </button>
        <?php endif; ?>

        <?php if(session()->get('role') === 'admin'): ?>
        <button id="btn-edit-mode" type="button" class="btn-sidebar-secondary">
            Edit Tempat
        </button>
        <?php endif; ?>
    </div>

    <div class="sidebar-header">
        <h3>Daftar Kuliner</h3>
        <button id="btn-filter-sidebar" type="button" class="btn-filter-sidebar">Filter</button>
    </div>
    
    <!-- Div inilah yang akan memiliki fungsi scroll -->
    <div id="list-kuliner">
        <?php foreach($kuliner ?? [] as $k): ?>
            <div class="card" data-id="<?= $k['id'] ?>" data-kategori="<?= $k['kategori'] ?>" data-lat="<?= $k['latitude'] ?>" data-lng="<?= $k['longitude'] ?>" data-rating="<?= $k['rating'] === 'Belum Dirating' ? 0 : floatval($k['rating']) ?>" data-harga="<?= isset($k['harga_rata_rata']) ? intval($k['harga_rata_rata']) : 0 ?>">
                <img src="<?= base_url('img/' . $k['foto']) ?>" alt="<?= $k['nama_tempat'] ?>">
                <div class="card-info">
                    <h4 style="margin: 2px 0; font-size: 14px;"><?= $k['nama_tempat'] ?></h4>
                    <p style="color: #f39c12; font-weight: bold; font-size: 12px; margin: 2px 0;">
                        <?php if($k['rating'] === 'Belum Dirating'): ?>
                            <span class="not-rated">Belum Dirating</span>
                        <?php else: ?>
                            ★ <?= $k['rating'] ?>
                        <?php endif; ?>
                        | <span style="color: #666; font-weight: normal;"><?= $k['kategori'] ?></span>
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

    <!-- Modal Konfirmasi Tambah Tempat -->
    <div id="modalAddConfirm" class="modal-action-tempat">
        <div class="modal-action-content">
            <h3>Tambahkan Tempat Makan?</h3>
            <p>Jika kamu pilih Iya, klik lokasi di peta untuk menempatkan tempat makan baru.</p>
            <div class="modal-action-buttons">
                <button type="button" class="btn-action-edit" onclick="confirmAddPlace()">Iya</button>
                <button type="button" class="btn-action-cancel" onclick="cancelAddPlacePrompt()">Tidak</button>
            </div>
        </div>
    </div>

    <!-- Modal Tambah / Edit Tempat -->
    <div id="modalTambahTempat" class="modal-action-tempat">
        <div class="modal-action-content">
            <h3 id="modalTambahTitle">Tambah Tempat</h3>
            <form id="form-tambah-tempat" onsubmit="submitTambahTempat(event)">
                <input type="hidden" id="input_tempat_id" name="id" value="">
                <div class="form-group-pesan">
                    <label>Nama Tempat</label>
                    <input type="text" id="input_tempat_nama" name="nama_tempat" placeholder="Masukkan nama tempat" required>
                </div>
                <div class="form-group-pesan">
                    <label>Kategori</label>
                    <select id="input_tempat_kategori" name="kategori" required>
                        <option value="">-- Pilih Kategori --</option>
                        <option value="Cafe">Cafe</option>
                        <option value="Angkringan">Angkringan</option>
                        <option value="Resto">Resto</option>
                    </select>
                </div>
                <div class="form-group-pesan">
                    <label>Alamat Lengkap</label>
                    <input type="text" id="input_tempat_alamat" name="alamat_lengkap" placeholder="Alamat lengkap" required>
                </div>
                <div class="form-group-pesan">
                    <label>Latitude</label>
                    <input type="text" id="input_tempat_lat" name="latitude" placeholder="Latitude" readonly required>
                </div>
                <div class="form-group-pesan">
                    <label>Longitude</label>
                    <input type="text" id="input_tempat_lng" name="longitude" placeholder="Longitude" readonly required>
                </div>
                <div class="form-group-pesan">
                    <label>No. Telepon</label>
                    <input type="tel" id="input_tempat_no_telp" name="no_telp" placeholder="0812xxxxxxx" required>
                </div>
                <div class="form-group-pesan">
                    <label>Rating</label>
                    <input type="number" step="0.1" min="0" max="5" id="input_tempat_rating" name="rating" placeholder="Contoh: 4.5" required>
                </div>
                <div class="form-group-pesan">
                    <label>Harga Rata-Rata</label>
                    <input type="number" id="input_tempat_harga" name="harga_rata_rata" min="0" placeholder="Contoh: 25000" required>
                </div>
                <div class="form-group-pesan">
                    <label>Jam Operasional</label>
                    <input type="text" id="input_tempat_jam" name="jam_operasional" placeholder="Contoh: 08:00 - 22:00" required>
                </div>
                <div class="form-group-pesan">
                    <label>Foto Tempat</label>
                    <input type="file" id="input_tempat_foto" name="foto" accept="image/*" required>
                </div>
                <div class="modal-action-buttons">
                    <button type="submit" class="btn-action-edit">Simpan</button>
                    <button type="button" class="btn-action-cancel" onclick="closeModalTambahTempat()">Batal</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Modal Pilih Aksi Edit/Hapus -->
    <div id="modalActionTempat" class="modal-action-tempat">
        <div class="modal-action-content">
            <h3>Pilih Aksi</h3>
            <p>Silakan pilih apakah ingin mengedit atau menghapus data tempat.</p>
            <div class="modal-action-buttons">
                <button type="button" class="btn-action-edit" onclick="selectEditAction()">Edit</button>
                <button type="button" class="btn-action-delete" onclick="selectDeleteAction()">Hapus</button>
                <button type="button" class="btn-action-cancel" onclick="closeEditChoiceModal()">Batal</button>
            </div>
        </div>
    </div>

    <!-- Modal Konfirmasi Hapus -->
    <div id="modalConfirmDelete" class="modal-confirm-delete">
        <div class="modal-confirm-content">
            <h3>Hapus Tempat?</h3>
            <p>Apakah Anda yakin ingin menghapus tempat makan ini dari daftar dan database?</p>
            <div class="confirm-buttons">
                <button type="button" class="btn-confirm-yes" onclick="confirmDeletePlace()">Iya</button>
                <button type="button" class="btn-confirm-no" onclick="closeDeleteConfirm()">Tidak</button>
            </div>
        </div>
    </div>

    <div id="map"></div>

    <!-- Sidebar Filter Melayang -->
    <div class="filter-card">
        <div class="filter-title">Kategori</div>
        <div class="filter-section">
            <label><input type="checkbox" class="filter-check" value="Cafe" checked> Cafe</label>
            <label><input type="checkbox" class="filter-check" value="Angkringan" checked> Angkringan</label>
            <label><input type="checkbox" class="filter-check" value="Resto" checked> Resto</label>
        </div>
        <div class="filter-section">
            <strong>Rating</strong>
            <label><input type="checkbox" class="filter-check" value="Rating"> ≥ 4.5</label>
        </div>
        <div class="filter-section">
            <strong>Rentang Harga</strong>
            <div style="display:grid; gap:8px;">
                <input class="range-input" id="price-min" type="number" placeholder="Min" min="0">
                <input class="range-input" id="price-max" type="number" placeholder="Max" min="0">
            </div>
        </div>
        <button class="btn-terapkan" id="btn-filter">Terapkan Filter</button>
    </div>

    <!-- Modal Pemesanan -->
    <div id="modalPesan" class="modal-pesan">
        <div class="modal-content-pesan">
            <div class="modal-header-pesan">
                <h2>Form Pemesanan</h2>
                <button class="close-modal-pesan" onclick="closeModalPesan()">&times;</button>
            </div>
            
            <div class="modal-body-pesan">
                <div class="info-tempat" style="background: #f9f9f9; padding: 12px; border-radius: 8px; margin-bottom: 16px;">
                    <h4 id="nama-tempat-pesan" style="margin: 0 0 4px 0; color: var(--primary-orange);">Nama Tempat</h4>
                    <p id="alamat-tempat-pesan" style="margin: 0 0 4px 0; font-size: 12px; color: #666;">Alamat</p>
                    <p style="margin: 0; font-size: 12px;">
                        <span>Kategori: <span id="kategori-pesan">-</span></span> | 
                        <span>Rating: <span id="rating-pesan">-</span></span>
                    </p>
                </div>

                <form id="form-pesanan" onsubmit="submitPesanan(event)">
                    <!-- Hidden Inputs -->
                    <input type="hidden" id="input_id_tempat" name="id_tempat" value="">
                    <input type="hidden" id="input_nama_tempat" name="nama_tempat" value="">
                    <input type="hidden" id="input_kategori" name="kategori" value="">
                    <input type="hidden" id="input_harga_perkiraan" name="harga_perkiraan" value="0">
                    <input type="hidden" id="input_menu_pesanan" name="menu_pesanan" value="[]">
                    <input type="hidden" id="input_subtotal" name="subtotal" value="0">
                    <input type="hidden" id="input_service_fee" name="service_fee" value="0">
                    <input type="hidden" id="input_tax" name="tax" value="0">
                    <input type="hidden" id="input_total_bayar" name="total_bayar" value="0">
                    <input type="hidden" id="input_total_qty" name="total_qty" value="0">
                    <input type="hidden" id="input_nama_pemesan" name="nama_pemesan" value="">
                    <input type="hidden" id="input_telepon" name="telepon" value="">
                    <input type="hidden" id="input_metode_pembayaran" name="metode_pembayaran" value="">

                    <!-- Customer Info -->
                    <div class="form-group-pesan">
                        <label>Nama Pemesan</label>
                        <input type="text" id="nama_pemesan" name="nama_pemesan_visible" placeholder="Nama lengkap" required>
                    </div>
                    <div class="form-group-pesan">
                        <label>No. Telepon</label>
                        <input type="tel" id="telepon" name="telepon_visible" placeholder="0812xxxxxxx" required>
                    </div>
                    <div class="form-group-pesan">
                        <label>Metode Pembayaran</label>
                        <select id="metode_pembayaran" name="metode_pembayaran_visible" required>
                            <option value="">-- Pilih Metode --</option>
                            <option value="Tunai">Tunai</option>
                            <option value="QRIS">QRIS</option>
                            <option value="Debit/Kredit">Debit/Kredit</option>
                        </select>
                    </div>
                    <div class="form-group-pesan">
                        <label>Nomor Meja</label>
                        <input type="number" id="meja" name="meja" min="1" placeholder="Masukkan nomor meja" required>
                    </div>

                    <!-- Menu Selection -->
                    <div class="form-group-pesan">
                        <label>Pilih Menu</label>
                        <select id="menu_pesanan" name="menu_pesanan_select" required>
                            <option value="">-- Pilih Menu --</option>
                        </select>
                    </div>

                    <div class="form-group-pesan">
                        <label>Jumlah</label>
                        <input type="number" id="jumlah" name="jumlah" min="1" value="1">
                    </div>

                    <button type="button" class="btn-add-menu" onclick="addMenuItem()">Tambah Menu</button>

                    <!-- Order Items Table -->
                    <div style="margin-top: 16px;">
                        <table id="order-items-table" style="width: 100%; border-collapse: collapse; font-size: 12px;">
                            <thead>
                                <tr style="background: #f0f0f0; border-bottom: 1px solid #ddd;">
                                    <th style="padding: 8px; text-align: left; font-weight: bold;">Menu</th>
                                    <th style="padding: 8px; text-align: center; font-weight: bold;">Qty</th>
                                    <th style="padding: 8px; text-align: right; font-weight: bold;">Harga</th>
                                    <th style="padding: 8px; text-align: right; font-weight: bold;">Subtotal</th>
                                    <th style="padding: 8px; text-align: center; font-weight: bold;">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td colspan="5" style="text-align:center; color:#666; padding:8px;">Belum ada menu yang ditambahkan.</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <!-- Summary -->
                    <div class="order-summary" style="background: #f9f9f9; padding: 12px; border-radius: 8px; margin-top: 16px; font-size: 12px;">
                        <div style="display: flex; justify-content: space-between; margin-bottom: 4px;">
                            <span>Subtotal:</span>
                            <span id="summary-subtotal">Rp 0</span>
                        </div>
                        <div style="display: flex; justify-content: space-between; margin-bottom: 4px;">
                            <span>Service Fee (2%):</span>
                            <span id="summary-service">Rp 0</span>
                        </div>
                        <div style="display: flex; justify-content: space-between; margin-bottom: 4px;">
                            <span>Jumlah Item:</span>
                            <span id="summary-qty">0</span>
                        </div>
                        <div style="display: flex; justify-content: space-between; margin-bottom: 8px; border-bottom: 1px solid #ddd; padding-bottom: 8px;">
                            <span>Pajak (10%):</span>
                            <span id="summary-tax">Rp 0</span>
                        </div>
                        <div style="display: flex; justify-content: space-between; font-weight: bold; color: var(--primary-orange); font-size: 14px;">
                            <span>Total:</span>
                            <span id="summary-total">Rp 0</span>
                        </div>
                        <div style="display: flex; justify-content: space-between; margin-top: 8px; font-size: 12px; color: #666;">
                            <span>Kembalian:</span>
                            <span id="summary-change">Rp 0</span>
                        </div>
                    </div>

                    <!-- Submit Button -->
                    <button type="submit" class="btn-submit-pesan" style="width: 100%; margin-top: 16px; padding: 10px; background: var(--primary-orange); color: white; border: none; border-radius: 5px; cursor: pointer; font-weight: bold; font-size: 14px;">Pesan Sekarang</button>
                </form>

                <div id="receipt-section" class="receipt-section" style="display:none; margin-top: 20px; background: #fff9f1; padding: 14px; border-radius: 10px; border: 1px solid #ffe1cd;">
                    <h3 style="margin-top:0; color: var(--primary-orange);">Struk Pemesanan</h3>
                    <div id="receipt-content" style="font-size: 13px; color: #333;"></div>
                    <button type="button" class="btn-submit-pesan" style="width: 100%; margin-top: 16px; padding: 10px; background: #333; color: white; border: none; border-radius: 5px; cursor: pointer; font-weight: bold; font-size: 14px;" onclick="closeModalPesan()">Tutup Struk</button>
                </div>
            </div>
        </div>
    </div>

</main>

<style>
    .modal-pesan {
        display: none;
        position: fixed;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: rgba(0,0,0,0.5);
        z-index: 2000;
        align-items: center;
        justify-content: center;
        overflow-y: auto;
    }

    .modal-pesan.active {
        display: flex;
    }

    .modal-content-pesan {
        background: white;
        border-radius: 12px;
        box-shadow: 0 10px 40px rgba(0,0,0,0.2);
        max-width: 500px;
        width: 90%;
        max-height: 80vh;
        overflow-y: auto;
        animation: slideUp 0.3s ease;
    }

    @keyframes slideUp {
        from {
            transform: translateY(30px);
            opacity: 0;
        }
        to {
            transform: translateY(0);
            opacity: 1;
        }
    }

    .modal-header-pesan {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 16px;
        border-bottom: 1px solid #eee;
    }

    .modal-header-pesan h2 {
        margin: 0;
        font-size: 18px;
        color: var(--primary-orange);
    }

    .close-modal-pesan {
        background: none;
        border: none;
        font-size: 24px;
        cursor: pointer;
        color: #666;
    }

    .close-modal-pesan:hover {
        color: var(--primary-orange);
    }

    .modal-body-pesan {
        padding: 16px;
    }

    .form-group-pesan {
        margin-bottom: 12px;
    }

    .form-group-pesan label {
        display: block;
        margin-bottom: 4px;
        font-size: 12px;
        font-weight: bold;
        color: #333;
    }

    .form-group-pesan input,
    .form-group-pesan select {
        width: 100%;
        padding: 8px;
        border: 1px solid #ddd;
        border-radius: 5px;
        font-size: 12px;
        box-sizing: border-box;
    }

    .form-group-pesan input:focus,
    .form-group-pesan select:focus {
        outline: none;
        border-color: var(--primary-orange);
        box-shadow: 0 0 0 2px rgba(243,93,7,0.1);
    }

    .btn-add-menu {
        width: 100%;
        padding: 8px;
        background: #4CAF50;
        color: white;
        border: none;
        border-radius: 5px;
        cursor: pointer;
        font-weight: bold;
        font-size: 12px;
    }

    .btn-add-menu:hover {
        background: #45a049;
    }
    .btn-keluar:hover {
            background: #dddddd;
            padding: 2px 6px;
            border-radius: 6px;
    }
</style>

<script src="<?= base_url('lib/leaflet/leaflet.js') ?>"></script>
<script>
    // Inisialisasi peta menggunakan tema Voyager agar bersih
    const map = L.map('map').setView([-6.966667, 110.416664], 13);
    L.tileLayer('https://{s}.basemaps.cartocdn.com/rastertiles/voyager/{z}/{x}/{y}{r}.png', {
        attribution: '&copy; OpenStreetMap'
    }).addTo(map);

    const dataKuliner = <?= json_encode($kuliner ?? []) ?>;
    const markers = {};
    const markerData = {}; // Simpan data marker untuk filter
    const searchInput = document.getElementById('map-search-input');
    const searchAlert = document.createElement('div');
    let searchAlertTimeout = null;

    function showSearchAlert(message) {
        searchAlert.id = 'search-alert';
        searchAlert.className = 'search-alert active';
        searchAlert.textContent = message;
        document.body.appendChild(searchAlert);

        if (searchAlertTimeout) {
            clearTimeout(searchAlertTimeout);
        }
        searchAlertTimeout = setTimeout(() => {
            searchAlert.classList.remove('active');
        }, 2400);
    }

    function cariTempatDanZoom() {
        const query = searchInput?.value.trim().toLowerCase();
        if (!query) {
            map.setView([-6.966667, 110.416664], 13);
            return;
        }

        const found = dataKuliner.find(item => item.nama_tempat && item.nama_tempat.toLowerCase().includes(query));
        if (!found) {
            showSearchAlert('Tempat Tidak Ditemukan');
            return;
        }

        const lat = parseFloat(found.latitude);
        const lng = parseFloat(found.longitude);
        if (Number.isNaN(lat) || Number.isNaN(lng)) {
            alert('Koordinat tempat tidak valid.');
            return;
        }

        focusMarker(lat, lng, found.id);
    }

    if (searchInput) {
        searchInput.addEventListener('keydown', function(e) {
            if (e.key === 'Enter') {
                e.preventDefault();
                cariTempatDanZoom();
            }
        });
    }

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
            
            const latLngKey = `${item.latitude}${item.longitude}`;
            markers[latLngKey] = m;
            markers[item.id] = m;
            markerData[latLngKey] = item;
            markerData[item.id] = item; // Simpan data untuk filter dan edit
        }
    });

    map.on('click', handleMapClick);

    document.querySelectorAll('#list-kuliner .card').forEach(attachCardHandlers);

    // Fungsi klik di sidebar otomatis zoom ke peta
    function focusMarker(lat, lng, id = null) {
        const key = `${lat}${lng}`;
        const marker = (id && markers[id]) ? markers[id] : markers[key];

        if (!marker) {
            console.warn('focusMarker: marker tidak ditemukan', { id, key, lat, lng });
            return;
        }

        map.setView([lat, lng], 17);
        marker.openPopup();
    }

    let addPlaceMode = false;
    let editActionMode = null;
    let activeEditCard = null;
    let activeDeleteCard = null;
    let pendingMapMarker = null;
    let editPlaceId = null;

    const btnTambahTempat = document.getElementById('btn-tambah-tempat');
    const btnEditMode = document.getElementById('btn-edit-mode');
    const modalAddConfirm = document.getElementById('modalAddConfirm');
    const modalTambahTempat = document.getElementById('modalTambahTempat');
    const modalActionTempat = document.getElementById('modalActionTempat');
    const modalConfirmDelete = document.getElementById('modalConfirmDelete');

    function showAddPlacePrompt() {
        modalAddConfirm.classList.add('active');
    }

    function cancelAddPlacePrompt() {
        modalAddConfirm.classList.remove('active');
    }

    function confirmAddPlace() {
        modalAddConfirm.classList.remove('active');
        addPlaceMode = true;
        editActionMode = null;
        showSearchAlert('Klik lokasi di peta untuk menambahkan tempat makan baru.');
    }

    function handleMapClick(e) {
        if (!addPlaceMode) {
            return;
        }

        if (pendingMapMarker) {
            map.removeLayer(pendingMapMarker);
        }

        pendingMapMarker = L.marker(e.latlng).addTo(map);
        openTambahTempatForm(false, e.latlng);
    }

    function openTambahTempatForm(edit = false, latLng = null, card = null) {
        document.getElementById('modalTambahTitle').textContent = edit ? 'Edit Tempat' : 'Tambah Tempat';
        editPlaceId = edit && card ? card.dataset.id : null;

        if (edit && card) {
            const item = markerData[card.dataset.id] || {};
            document.getElementById('input_tempat_id').value = item.id || card.dataset.id || '';
            document.getElementById('input_tempat_nama').value = item.nama_tempat || '';
            document.getElementById('input_tempat_kategori').value = item.kategori || '';
            document.getElementById('input_tempat_alamat').value = item.alamat_lengkap || '';
            document.getElementById('input_tempat_lat').value = item.latitude || card.dataset.lat || '';
            document.getElementById('input_tempat_lng').value = item.longitude || card.dataset.lng || '';
            document.getElementById('input_tempat_no_telp').value = item.no_telp || '';
            document.getElementById('input_tempat_rating').value = item.rating || '';
            document.getElementById('input_tempat_harga').value = item.harga_rata_rata || '';
            document.getElementById('input_tempat_jam').value = item.jam_operasional || '';
        } else {
            editPlaceId = null;
            document.getElementById('input_tempat_id').value = '';
            document.getElementById('input_tempat_nama').value = '';
            document.getElementById('input_tempat_kategori').value = '';
            document.getElementById('input_tempat_alamat').value = '';
            document.getElementById('input_tempat_no_telp').value = '';
            document.getElementById('input_tempat_rating').value = '';
            document.getElementById('input_tempat_harga').value = '';
            document.getElementById('input_tempat_jam').value = '';
            document.getElementById('input_tempat_foto').value = '';

            if (latLng) {
                document.getElementById('input_tempat_lat').value = latLng.lat.toFixed(6);
                document.getElementById('input_tempat_lng').value = latLng.lng.toFixed(6);
            }
        }

        modalTambahTempat.classList.add('active');
    }

    function closeModalTambahTempat() {
        modalTambahTempat.classList.remove('active');
        addPlaceMode = false;
        editActionMode = null;
        editPlaceId = null;

        if (pendingMapMarker) {
            map.removeLayer(pendingMapMarker);
            pendingMapMarker = null;
        }
    }

    function openEditChoiceModal() {
        modalActionTempat.classList.add('active');
        addPlaceMode = false;
    }

    function closeEditChoiceModal() {
        modalActionTempat.classList.remove('active');
    }

    function selectEditAction() {
        editActionMode = 'edit';
        closeEditChoiceModal();
        showSearchAlert('Klik tempat di sidebar untuk mengedit.');
    }

    function selectDeleteAction() {
        editActionMode = 'delete';
        closeEditChoiceModal();
        showSearchAlert('Klik tempat di sidebar untuk menghapus.');
    }

    function openEditPlace(card) {
        openTambahTempatForm(true, null, card);
    }

    function openDeleteConfirmForCard(card) {
        activeDeleteCard = card;
        modalConfirmDelete.classList.add('active');
    }

    function closeDeleteConfirm() {
        modalConfirmDelete.classList.remove('active');
        activeDeleteCard = null;
    }

    async function confirmDeletePlace() {
        if (!activeDeleteCard) {
            closeDeleteConfirm();
            return;
        }

        const id = activeDeleteCard.dataset.id;
        const formData = new FormData();
        formData.append('id', id);

        try {
            const response = await fetch('<?= base_url('kuliner/delete') ?>', {
                method: 'POST',
                body: formData
            });
            const data = await response.json();

            if (data.success) {
                const marker = markers[id];
                if (marker) {
                    map.removeLayer(marker);
                }

                const latLngKey = `${activeDeleteCard.dataset.lat}${activeDeleteCard.dataset.lng}`;
                if (markers[latLngKey]) {
                    map.removeLayer(markers[latLngKey]);
                }

                activeDeleteCard.remove();
                delete markers[id];
                delete markers[latLngKey];
                delete markerData[id];
                delete markerData[latLngKey];
                closeDeleteConfirm();
                editActionMode = null;
                showSearchAlert('Tempat makan berhasil dihapus.');
            } else {
                alert(data.message || 'Gagal menghapus tempat.');
            }
        } catch (error) {
            console.error(error);
            alert('Terjadi kesalahan saat menghapus tempat.');
        }
    }

    async function submitTambahTempat(event) {
        event.preventDefault();
        const form = document.getElementById('form-tambah-tempat');
        const formData = new FormData(form);
        const isEdit = Boolean(editPlaceId);
        const endpoint = isEdit ? '<?= base_url('kuliner/update') ?>' : '<?= base_url('kuliner/tambah') ?>';

        if (!formData.get('nama_tempat') || !formData.get('kategori') || !formData.get('alamat_lengkap') || !formData.get('latitude') || !formData.get('longitude') || !formData.get('no_telp') || !formData.get('rating') || !formData.get('harga_rata_rata') || !formData.get('jam_operasional')) {
            alert('Lengkapi semua data tempat makan terlebih dahulu.');
            return;
        }

        if (!isEdit && !formData.get('foto')) {
            alert('Foto tempat harus diupload.');
            return;
        }

        if (isEdit) {
            formData.append('id', editPlaceId);
        }

        try {
            const response = await fetch(endpoint, {
                method: 'POST',
                body: formData
            });
            const result = await response.json();

            if (!result.success) {
                alert(result.message || 'Gagal menyimpan data tempat.');
                return;
            }

            const tempat = result.tempat;
            if (isEdit) {
                updatePlaceCard(tempat);
                updatePlaceMarker(tempat);
                showSearchAlert('Data tempat berhasil diperbarui.');
            } else {
                addNewPlaceToSidebar(tempat);
                addNewPlaceMarker(tempat);
                showSearchAlert('Tempat makan berhasil ditambahkan.');
            }

            closeModalTambahTempat();
            editActionMode = null;
            addPlaceMode = false;
            renderOrderItems();
            terapkanFilter();
        } catch (error) {
            console.error(error);
            alert('Terjadi kesalahan saat menyimpan tempat.');
        }
    }

    function addNewPlaceToSidebar(tempat) {
        const list = document.getElementById('list-kuliner');
        const card = document.createElement('div');
        card.className = 'card';
        card.dataset.id = tempat.id;
        card.dataset.kategori = tempat.kategori;
        card.dataset.lat = tempat.latitude;
        card.dataset.lng = tempat.longitude;
        card.dataset.rating = tempat.rating === 'Belum Dirating' ? 0 : parseFloat(tempat.rating);
        card.dataset.harga = parseInt(tempat.harga_rata_rata) || 0;
        card.innerHTML = `
            <img src="<?= base_url('img/') ?>/${tempat.foto}" alt="${tempat.nama_tempat}">
            <div class="card-info">
                <h4 style="margin: 2px 0; font-size: 14px;">${tempat.nama_tempat}</h4>
                <p style="color: #f39c12; font-weight: bold; font-size: 12px; margin: 2px 0;">
                    ${tempat.rating === 'Belum Dirating' ? '<span class="not-rated">Belum Dirating</span>' : '★ ' + tempat.rating}
                    | <span style="color: #666; font-weight: normal;">${tempat.kategori}</span>
                </p>
                <p style="font-size: 10px; color: #888; line-height: 1.2;">${tempat.alamat_lengkap.substring(0, 60)}...</p>
                <button onclick='bukaModalPesan(${JSON.stringify(tempat)})' style="width: 100%; padding: 6px; margin-top: 6px; background: var(--primary-orange); color: white; border: none; border-radius: 5px; cursor: pointer; font-weight: bold; font-size: 12px;">Pesan</button>
            </div>
        `;
        list.insertBefore(card, list.firstChild);
        attachCardHandlers(card);
    }

    function addNewPlaceMarker(tempat) {
        const m = L.marker([tempat.latitude, tempat.longitude]).addTo(map);
        m.bindPopup(`
            <img src="<?= base_url('img/') ?>/${tempat.foto}" class="popup-img">
            <div class="popup-body">
                <strong>${tempat.nama_tempat}</strong><br>
                <span style="color: #f39c12;">★ ${tempat.rating}/5</span><br>
                <small>${tempat.jam_operasional}</small>
                <a class="btn-detail" href="https://www.google.com/maps/search/?api=1&query=${tempat.latitude},${tempat.longitude}" target="_blank">Lihat di Maps</a>
            </div>
        `);
        const key = `${tempat.latitude}${tempat.longitude}`;
        markers[key] = m;
        markers[tempat.id] = m;
        markerData[key] = tempat;
        markerData[tempat.id] = tempat;
    }

    function updatePlaceCard(tempat) {
        const card = document.querySelector(`#list-kuliner .card[data-id='${tempat.id}']`);
        if (!card) {
            return;
        }

        card.dataset.kategori = tempat.kategori;
        card.dataset.lat = tempat.latitude;
        card.dataset.lng = tempat.longitude;
        card.dataset.rating = tempat.rating === 'Belum Dirating' ? 0 : parseFloat(tempat.rating);
        card.dataset.harga = parseInt(tempat.harga_rata_rata) || 0;
        card.querySelector('h4').textContent = tempat.nama_tempat;
        card.querySelector('p').innerHTML = `${tempat.rating === 'Belum Dirating' ? '<span class="not-rated">Belum Dirating</span>' : '★ ' + tempat.rating} | <span style="color: #666; font-weight: normal;">${tempat.kategori}</span>`;
        card.querySelector('p + p').textContent = `${tempat.alamat_lengkap.substring(0, 60)}...`;
        card.querySelector('img').src = '<?= base_url('img/') ?>/' + tempat.foto;
        markerData[tempat.id] = tempat;
        const oldLatLng = Object.keys(markers).find(key => key !== tempat.id && key.includes(card.dataset.lat) && key.includes(card.dataset.lng));
        if (oldLatLng) {
            delete markers[oldLatLng];
            delete markerData[oldLatLng];
        }
    }

    function updatePlaceMarker(tempat) {
        const oldMarker = markers[tempat.id];
        const oldLatLngKey = Object.keys(markers).find(key => key !== tempat.id && key.includes(String(tempat.latitude)) && key.includes(String(tempat.longitude)));
        if (oldMarker) {
            map.removeLayer(oldMarker);
        }
        if (oldLatLngKey) {
            delete markers[oldLatLngKey];
            delete markerData[oldLatLngKey];
        }
        addNewPlaceMarker(tempat);
    }

    function attachCardHandlers(card) {
        card.addEventListener('click', function(event) {
            if (event.target.closest('button')) {
                return;
            }

            if (editActionMode === 'edit') {
                openEditPlace(card);
                return;
            }

            if (editActionMode === 'delete') {
                openDeleteConfirmForCard(card);
                return;
            }

            const lat = parseFloat(card.dataset.lat);
            const lng = parseFloat(card.dataset.lng);
            focusMarker(lat, lng, card.dataset.id);
        });
    }

    if (btnTambahTempat) {
        btnTambahTempat.addEventListener('click', function() {
            showAddPlacePrompt();
        });
    }

    if (btnEditMode) {
        btnEditMode.addEventListener('click', function() {
            openEditChoiceModal();
        });
    }

    function isInEditMode() {
        return editActionMode !== null;
    }

    // Fungsi untuk memfilter daftar kuliner
    function terapkanFilter() {
        // 1. Ambil kategori yang dicentang
        const categoryCheckboxes = document.querySelectorAll('.filter-check:checked:not([value="Rating"])');
        const kategoriTerpilih = Array.from(categoryCheckboxes).map(cb => cb.value);

        // 2. Ambil status Rating filter
        const ratingFilterChecked = document.querySelector('.filter-check[value="Rating"]:checked');
        const filterByRating = ratingFilterChecked !== null;

        // 3. Ambil nilai Min dan Max harga
        const minPriceInput = document.getElementById('price-min');
        const maxPriceInput = document.getElementById('price-max');
        const minPrice = minPriceInput.value ? parseInt(minPriceInput.value) : null;
        const maxPrice = maxPriceInput.value ? parseInt(maxPriceInput.value) : null;

        // 4. Ambil semua elemen kartu kuliner di sidebar
        const cards = document.querySelectorAll('#list-kuliner .card');

        cards.forEach(card => {
            const kategoriCard = card.getAttribute('data-kategori');
            const ratingCard = parseFloat(card.getAttribute('data-rating')) || 0;
            const hargaCard = parseInt(card.getAttribute('data-harga')) || 0;

            // Cek kategori (jika ada kategori terpilih)
            let passCategory = kategoriTerpilih.length === 0 || kategoriTerpilih.includes(kategoriCard);

            // Cek rating (jika filter rating dicentang, harus >= 4.5 dan bukan "Belum Dirating")
            let passRating = !filterByRating || (ratingCard >= 4.5 && ratingCard > 0);

            // Cek harga (jika input ada, harus dalam range)
            let passPrice = true;
            if (minPrice !== null || maxPrice !== null) {
                if (minPrice !== null && maxPrice !== null) {
                    passPrice = hargaCard >= minPrice && hargaCard <= maxPrice;
                } else if (minPrice !== null) {
                    passPrice = hargaCard >= minPrice;
                } else if (maxPrice !== null) {
                    passPrice = hargaCard <= maxPrice;
                }
            }

            // Logika AND: semua filter harus terpenuhi
            if (passCategory && passRating && passPrice) {
                card.style.display = 'block';
            } else {
                card.style.display = 'none';
            }
        });

        // 5. Filter marker di peta juga
        Object.keys(markerData).forEach(key => {
            const item = markerData[key];
            const marker = markers[key];
            const ratingItem = parseFloat(item.rating) || 0;
            const hargaItem = parseInt(item.harga_rata_rata) || 0;

            let passCategory = kategoriTerpilih.length === 0 || kategoriTerpilih.includes(item.kategori);
            let passRating = !filterByRating || (ratingItem >= 4.5 && item.rating !== 'Belum Dirating');
            let passPrice = true;
            if (minPrice !== null || maxPrice !== null) {
                if (minPrice !== null && maxPrice !== null) {
                    passPrice = hargaItem >= minPrice && hargaItem <= maxPrice;
                } else if (minPrice !== null) {
                    passPrice = hargaItem >= minPrice;
                } else if (maxPrice !== null) {
                    passPrice = hargaItem <= maxPrice;
                }
            }

            if (passCategory && passRating && passPrice) {
                if (!map.hasLayer(marker)) {
                    map.addLayer(marker);
                }
            } else {
                map.removeLayer(marker);
            }
        });
    }

    const filterCard = document.querySelector('.filter-card');
    const filterSidebarButton = document.getElementById('btn-filter-sidebar');

    if (filterSidebarButton && filterCard) {
        filterSidebarButton.addEventListener('click', function(event) {
            event.stopPropagation();
            filterCard.classList.toggle('open');
        });

        filterCard.addEventListener('click', function(event) {
            event.stopPropagation();
        });

        document.addEventListener('click', function() {
            if (filterCard.classList.contains('open')) {
                filterCard.classList.remove('open');
            }
        });
    }

    // Event listener untuk tombol filter
    document.getElementById('btn-filter').addEventListener('click', function(event) {
        event.preventDefault();
        terapkanFilter();
        if (filterCard) {
            filterCard.classList.remove('open');
        }
    });

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
        document.getElementById('input_nama_pemesan').value = '';
        document.getElementById('input_telepon').value = '';
        document.getElementById('input_metode_pembayaran').value = '';
        document.getElementById('nama_pemesan').value = '';
        document.getElementById('telepon').value = '';
        document.getElementById('metode_pembayaran').value = '';
        orderItems = [];
        document.getElementById('form-pesanan').reset();
        document.getElementById('meja').value = '';
        document.getElementById('receipt-section').style.display = 'none';
        document.getElementById('form-pesanan').style.display = 'block';
        populateMenuOptions(kulinerData.kategori);
        renderOrderItems();
    }

    function submitPesanan(event) {
        event.preventDefault();
        if (!orderItems.length) {
            alert('Tambahkan minimal satu menu sebelum memesan.');
            return;
        }

        const namaPemesan = document.getElementById('nama_pemesan').value.trim();
        const telepon = document.getElementById('telepon').value.trim();
        const metodePembayaran = document.getElementById('metode_pembayaran').value;
        const meja = document.getElementById('meja').value.trim();

        if (!namaPemesan || !telepon || !metodePembayaran || !meja) {
            alert('Lengkapi semua data pemesanan sebelum melanjutkan.');
            return;
        }

        document.getElementById('input_nama_pemesan').value = namaPemesan;
        document.getElementById('input_telepon').value = telepon;
        document.getElementById('input_metode_pembayaran').value = metodePembayaran;
        document.getElementById('input_menu_pesanan').value = JSON.stringify(orderItems);

        const receiptContent = document.getElementById('receipt-content');
        receiptContent.innerHTML = `
            <p><strong>Nama Pemesan:</strong> ${namaPemesan}</p>
            <p><strong>No. Telepon:</strong> ${telepon}</p>
            <p><strong>Metode Pembayaran:</strong> ${metodePembayaran}</p>
            <p><strong>Nomor Meja:</strong> ${meja}</p>
            <p><strong>Tempat:</strong> ${selectedKuliner.nama_tempat}</p>
            <p><strong>Kategori:</strong> ${selectedKuliner.kategori}</p>
            <p><strong>Rating:</strong> ${selectedKuliner.rating}</p>
            <div style="margin-top: 12px;">
                <strong>Detail Pesanan:</strong>
                <table style="width:100%; border-collapse: collapse; margin-top: 8px; font-size: 12px;">
                    <thead>
                        <tr>
                            <th style="border-bottom: 1px solid #ddd; padding: 6px; text-align:left;">Menu</th>
                            <th style="border-bottom: 1px solid #ddd; padding: 6px; text-align:center;">Qty</th>
                            <th style="border-bottom: 1px solid #ddd; padding: 6px; text-align:right;">Harga</th>
                            <th style="border-bottom: 1px solid #ddd; padding: 6px; text-align:right;">Subtotal</th>
                        </tr>
                    </thead>
                    <tbody>
                        ${orderItems.map(item => `
                            <tr>
                                <td style="padding: 6px;">${item.name}</td>
                                <td style="padding: 6px; text-align:center;">${item.quantity}</td>
                                <td style="padding: 6px; text-align:right;">${formatRupiah(item.price)}</td>
                                <td style="padding: 6px; text-align:right;">${formatRupiah(item.subtotal)}</td>
                            </tr>
                        `).join('')}
                    </tbody>
                </table>
            </div>
            <div style="margin-top:12px; font-size: 13px;">
                <p><strong>Subtotal:</strong> ${document.getElementById('summary-subtotal').textContent}</p>
                <p><strong>Service Fee:</strong> ${document.getElementById('summary-service').textContent}</p>
                <p><strong>Pajak:</strong> ${document.getElementById('summary-tax').textContent}</p>
                <p><strong>Total Bayar:</strong> ${document.getElementById('summary-total').textContent}</p>
            </div>
        `;

        document.getElementById('receipt-section').style.display = 'block';
        document.getElementById('form-pesanan').style.display = 'none';
    }

    function closeModalPesan() {
        document.getElementById('modalPesan').classList.remove('active');
    }

    // Close modal when clicking outside
    window.addEventListener('click', function(event) {
        const modal = document.getElementById('modalPesan');
        if (event.target === modal) {
            modal.classList.remove('active');
        }
    });
</script>
</body>
</html>
