@extends('layouts.dashboard')

@section('title', 'Dashboard')
@section('page-title', 'Dashboard')

@section('header-icon')
<svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="2"
     stroke-linecap="round" stroke-linejoin="round">
    <path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/>
    <polyline points="9 22 9 12 15 12 15 22"/>
</svg>
@endsection

@section('content')

<div class="stats-row">

    {{-- Total Barang --}}
    <div class="glass-panel stat-card">
        <div class="stat-card-icon">
            <svg width="26" height="26" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                 stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                <path d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z"/>
            </svg>
        </div>
        <div>
            <div class="stat-card-label">Data Stock</div>
            <div class="stat-card-value">{{ $totalBarang }}</div>
        </div>
    </div>

    {{-- Total Masuk --}}
    <div class="glass-panel stat-card">
        <div class="stat-card-icon">
            <svg width="26" height="26" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                 stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                <circle cx="12" cy="12" r="10"/>
                <polyline points="12 8 16 12 12 16"/>
                <line x1="8" y1="12" x2="16" y2="12"/>
            </svg>
        </div>
        <div>
            <div class="stat-card-label">Barang Masuk</div>
            <div class="stat-card-value">{{ $totalMasuk }}</div>
        </div>
    </div>

    {{-- Total Keluar --}}
    <div class="glass-panel stat-card">
        <div class="stat-card-icon">
            <svg width="26" height="26" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                 stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                <circle cx="12" cy="12" r="10"/>
                <polyline points="12 16 8 12 12 8"/>
                <line x1="16" y1="12" x2="8" y2="12"/>
            </svg>
        </div>
        <div>
            <div class="stat-card-label">Barang Keluar</div>
            <div class="stat-card-value">{{ $totalKeluar }}</div>
        </div>
    </div>

</div>

{{-- Alert Stok Minimum --}}
@if($stokMinimum->count() > 0)
<div class="alert-info">
    <svg width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="#92400e" stroke-width="2"
         stroke-linecap="round" stroke-linejoin="round">
        <circle cx="12" cy="12" r="10"/>
        <line x1="12" y1="8" x2="12" y2="12"/>
        <line x1="12" y1="16" x2="12.01" y2="16"/>
    </svg>

    Ada {{ $stokMinimum->count() }} barang yang stoknya mencapai batas minimum.
</div>
@endif

<div class="glass-panel table-panel">

    <div class="table-toolbar">

        <div class="table-show">
            Tampilan
            <select id="perPage" onchange="changePerPage(this)">
                <option value="10" selected>10</option>
                <option value="25">25</option>
                <option value="50">50</option>
            </select>
            data
        </div>

        <div class="search-box">
            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="#9aa5bb"
                 stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <circle cx="11" cy="11" r="8"/>
                <line x1="21" y1="21" x2="16.65" y2="16.65"/>
            </svg>

            <input type="text"
                   id="searchInput"
                   placeholder="Cari"
                   oninput="filterTable()">
        </div>

    </div>

    <table class="data-table">

        <thead>
            <tr>
                <th>No.</th>
                <th>ID Barang</th>
                <th>Nama Barang</th>
                <th>Stock</th>
            </tr>
        </thead>

        <tbody id="tableBody">

            @forelse($stokList as $item)

            <tr>
                <td>{{ $loop->iteration }}</td>
                <td>{{ $item->id_barang }}</td>
                <td>{{ $item->nama_barang }}</td>
                <td>{{ $item->stok }}</td>
            </tr>

            @empty

            <tr>
                <td colspan="4" style="text-align:center;">
                    Belum ada data stok.
                </td>
            </tr>

            @endforelse

        </tbody>

    </table>

    <div class="table-footer" id="tableFooter">
        Menampilkan {{ $stokList->count() }} dari {{ $stokList->count() }} data
    </div>

</div>

@endsection

@push('scripts')
<script>

function filterTable() {

    const q = document.getElementById('searchInput').value.toLowerCase();
    const rows = document.querySelectorAll('#tableBody tr');

    let visible = 0;

    rows.forEach(row => {

        const show = row.textContent.toLowerCase().includes(q);

        row.style.display = show ? '' : 'none';

        if(show) visible++;

    });

    updateFooter(visible, rows.length);
}

function changePerPage(select) {

    const max = parseInt(select.value);

    const rows = document.querySelectorAll('#tableBody tr');

    let visible = 0;

    rows.forEach((row,index)=>{

        if(index < max){

            row.style.display = '';
            visible++;

        }else{

            row.style.display = 'none';

        }

    });

    updateFooter(visible, rows.length);
}

function updateFooter(show,total){

    document.getElementById('tableFooter').innerText =
        'Menampilkan ' + show + ' dari ' + total + ' data';
}

</script>
@endpush