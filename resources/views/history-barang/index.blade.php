@extends('layouts.dashboard')

@section('title', 'History Barang - ' . $unitName)
@section('page-title', 'History Barang - ' . $unitName)

@section('header-icon')
    <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="2"
         stroke-linecap="round" stroke-linejoin="round">
        <circle cx="12" cy="12" r="10"/>
        <polyline points="12 6 12 12 16 14"/>
    </svg>
@endsection

@section('content')
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
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="#9aa5bb" stroke-width="2"
                     stroke-linecap="round" stroke-linejoin="round">
                    <circle cx="11" cy="11" r="8"/>
                    <line x1="21" y1="21" x2="16.65" y2="16.65"/>
                </svg>
                <input type="text" id="searchInput" placeholder="Cari" oninput="filterTable()">
            </div>
        </div>
        <table class="data-table">
            <thead>
                <tr>
                    <th>No.</th>
                    <th>Tanggal Keluar</th>
                    <th>ID Barang</th>
                    <th>Nama Barang</th>
                    <th>Jumlah</th>
                </tr>
            </thead>
            <tbody id="tableBody">
                @forelse($barangKeluar as $i => $item)
                <tr>
                    <td>{{ $i + 1 }}</td>
                    <td>{{ $item->tanggal_keluar->format('d/m/Y') }}</td>
                    <td>{{ $item->id_barang }}</td>
                    <td>{{ $item->nama_barang }}</td>
                    <td>{{ $item->jumlah }}</td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" style="text-align:center; color:#9aa5bb;">Belum ada data barang keluar untuk unit ini.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
        <div class="table-footer" id="tableFooter">Menampilkan {{ count($barangKeluar) }} dari {{ count($barangKeluar) }} data</div>
    </div>
@endsection

@push('scripts')
<script>
    function filterTable() {
        const q = document.getElementById('searchInput').value.toLowerCase();
        const rows = document.querySelectorAll('#tableBody tr');
        let v = 0;
        rows.forEach(r => {
            const show = r.textContent.toLowerCase().includes(q);
            r.style.display = show ? '' : 'none';
            if (show) v++;
        });
        updateFooter(v, rows.length);
    }

    function changePerPage(s) {
        const max = parseInt(s.value);
        const rows = document.querySelectorAll('#tableBody tr');
        let v = 0;
        rows.forEach((r, i) => {
            r.style.display = i < max ? '' : 'none';
            if (i < max) v++;
        });
        updateFooter(v, rows.length);
    }

    function updateFooter(shown, total) {
        document.getElementById('tableFooter').textContent =
            'Menampilkan ' + shown + ' sampai ' + shown + ' dari ' + total + ' data';
    }
</script>
@endpush
