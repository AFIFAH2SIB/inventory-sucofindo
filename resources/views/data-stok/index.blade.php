@extends('layouts.dashboard')

@section('title', 'Data Stok')
@section('page-title', 'Data Stok')

@section('header-icon')
    <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="2"
         stroke-linecap="round" stroke-linejoin="round">
        <path d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z"/>
    </svg>
@endsection

@section('header-actions')
   @section('header-actions')
@endsection
    </div>
@endsection

@section('content')
    {{-- Table --}}
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

        <div style="display:flex;align-items:center;gap:10px;">

            <div class="filter-status-wrap">
                <select id="filterStatus" onchange="filterTable()">
                    <option value="">Semua Status</option>
                    <option value="Aman">Aman</option>
                    <option value="Perlu Pengadaan">Perlu Pengadaan</option>
                    <option value="Habis">Habis</option>
                </select>
            </div>

            <div class="search-box">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="#9aa5bb" stroke-width="2"
                     stroke-linecap="round" stroke-linejoin="round">
                    <circle cx="11" cy="11" r="8"/>
                    <line x1="21" y1="21" x2="16.65" y2="16.65"/>
                </svg>

                <input
                    type="text"
                    id="searchInput"
                    placeholder="Cari Barang..."
                    oninput="filterTable()">
            </div>

        </div>

    </div>

    <table class="data-table">

        <thead>
            <tr>
                <th>No.</th>
                <th>ID Barang</th>
                <th>Nama Barang</th>
                <th>Stok Saat Ini</th>
                <th>Batas Minimum</th>
                <th>Status</th>
            </tr>
        </thead>

        <tbody id="tableBody">
                @forelse($stokBarang as $i => $item)
                @php
                    $stokVal = max(0, $item->stok);

                    $stokMinimum = $item->stok_minimum;

                    if ($stokVal <= 0) {

                        $statusLabel = 'Habis';
                        $statusClass = 'badge-habis';

                    } elseif ($stokVal <= $stokMinimum) {

                        $statusLabel = 'Perlu Pengadaan';
                        $statusClass = 'badge-reorder';

                    } else {

                        $statusLabel = 'Aman';
                        $statusClass = 'badge-tersedia';
                    }
                @endphp
                <tr data-status="{{ $statusLabel }}">
    <td>{{ $i + 1 }}</td>
    <td>{{ $item->id_barang }}</td>
    <td>{{ $item->nama_barang }}</td>

    <td>{{ $stokVal }}</td>

    <td>{{ $stokMinimum }}</td>

    <td>
        <span class="badge-status {{ $statusClass }}">
            {{ $statusLabel }}
        </span>
    </td>
</tr>

@empty

<tr>
    <td colspan="6" style="text-align:center; color:#9aa5bb;">
        Belum ada data stok.
    </td>
</tr>

@endforelse

<tr id="emptyFilterRow" style="display:none;">
    <td colspan="6" style="text-align:center; color:#9aa5bb; padding:24px 0;">
        Data stok tidak tersedia.
    </td>
</tr>

</tbody>

</table>

<div class="table-footer" id="tableFooter">
    Menampilkan {{ count($stokBarang) }} dari {{ count($stokBarang) }} data
</div>

</div>

@endsection

@push('styles')
<style>
    .badge-status {
        display: inline-block;
        padding: 4px 12px;
        border-radius: 20px;
        font-size: .75rem;
        font-weight: 600;
        white-space: nowrap;
    }
    .badge-tersedia { background: rgba(34,197,94,.12);  color: #16a34a; }
    .badge-reorder  { background: rgba(99,102,241,.13); color: #4f46e5; }
    .badge-habis    { background: rgba(239,68,68,.12);  color: #dc2626; }
    .filter-status-wrap select {
        padding: 7px 12px;
        border-radius: 8px;
        border: 1px solid #e2e8f0;
        background: #fff;
        color: #374151;
        font-size: .85rem;
        cursor: pointer;
        outline: none;
    }
    .filter-status-wrap select:focus {
        border-color: #6366f1;
    }
</style>
@endpush

@push('scripts')
<script>
    function toggleExportDropdown(e) {
        e.stopPropagation();
        document.getElementById('exportDropdown').classList.toggle('open');
    }

    function exportAs(type) {
        document.getElementById('exportDropdown').classList.remove('open');
        // TODO: implement actual export
        alert('Export ' + type.toUpperCase() + ' belum diimplementasikan.');
    }

    document.addEventListener('click', function () {
        const dd = document.getElementById('exportDropdown');
        if (dd) dd.classList.remove('open');
    });

    function filterTable() {
        const q = document.getElementById('searchInput').value.toLowerCase();
        const statusFilter = document.getElementById('filterStatus').value;
        const max = parseInt(document.getElementById('perPage').value);
        const allRows = document.querySelectorAll('#tableBody tr:not(#emptyFilterRow)');
        const emptyRow = document.getElementById('emptyFilterRow');
        let v = 0;
        allRows.forEach(r => {
            const matchSearch = r.textContent.toLowerCase().includes(q);
            const matchStatus = !statusFilter || (r.dataset.status === statusFilter);
            const show = matchSearch && matchStatus && v < max;
            r.style.display = show ? '' : 'none';
            if (show) v++;
        });
        const totalMatch = [...allRows].filter(r => {
            const matchSearch = r.textContent.toLowerCase().includes(q);
            const matchStatus = !statusFilter || (r.dataset.status === statusFilter);
            return matchSearch && matchStatus;
        }).length;
        emptyRow.style.display = (totalMatch === 0) ? '' : 'none';
        updateFooter(v, totalMatch);
    }
    function changePerPage(s) {
        filterTable();
    }
   function updateFooter(s, t) {
    document.getElementById('tableFooter').textContent =
        `Menampilkan ${s} dari ${t} data`;
    }
</script>
@endpush
