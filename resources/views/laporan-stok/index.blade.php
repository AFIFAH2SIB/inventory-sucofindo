@extends('layouts.dashboard')

@section('title', 'Laporan Stok')
@section('page-title', 'Laporan Stok')

@section('header-icon') <svg width="22" height="22" viewBox="0 0 24 24" fill="none"
  stroke="white" stroke-width="2"
  stroke-linecap="round" stroke-linejoin="round"> <path d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z"/> </svg>
@endsection

@section('header-actions') <button class="btn-header" onclick="exportPdf()">
Export PDF </button>
@endsection

@section('content')

<div class="glass-panel table-panel">

<div class="table-toolbar">

    <div class="table-show">
        Tampilan
        <select id="perPage" onchange="changePerPage()">
            <option value="10" selected>10</option>
            <option value="25">25</option>
            <option value="50">50</option>
        </select>
        data
    </div>

    <div style="display:flex;align-items:center;gap:10px;">

        <div class="filter-status-wrap">
            <select id="filterStatus" onchange="filterTable()">
                <option value="">Semua Data</option>
                <option value="Minimum">Stok Minimum</option>
            </select>
        </div>

        <div class="search-box">
            <svg width="14" height="14"
                 viewBox="0 0 24 24"
                 fill="none"
                 stroke="#9aa5bb"
                 stroke-width="2">
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
            <th>Stok</th>
        </tr>
    </thead>

    <tbody id="tableBody">

    @forelse($stokBarang as $i => $item)

        <tr data-status="{{ $item->status }}">

            <td>{{ $i + 1 }}</td>

            <td>{{ $item->id_barang }}</td>

            <td>{{ $item->nama_barang }}</td>

            <td>{{ $item->stok }}</td>

        </tr>

    @empty

        <tr>
            <td colspan="4"
                style="text-align:center;color:#9aa5bb;">
                Belum ada data stok.
            </td>
        </tr>

    @endforelse

    <tr id="emptyFilterRow" style="display:none;">
        <td colspan="4"
            style="text-align:center;color:#9aa5bb;padding:24px 0;">
            Data stok tidak tersedia.
        </td>
    </tr>

    </tbody>

</table>

<div class="table-footer" id="tableFooter">
    Menampilkan {{ count($stokBarang) }} dari {{ count($stokBarang) }} data
</div>

<div class="pagination-wrap"
     id="paginationWrap">

</div>

</div>

@endsection

@push('styles')

<style>

.filter-status-wrap select{
    padding:7px 12px;
    border-radius:8px;
    border:1px solid #e2e8f0;
    background:#fff;
    color:#374151;
    font-size:.85rem;
}

.pagination-wrap{
    display:flex;
    justify-content:center;
    align-items:center;
    gap:6px;
    margin-top:20px;
}

.pagination-btn{
    width:26px;
    height:26px;

    padding:0;

    border:none;
    border-radius:6px;

    background:#f8fafc;
    color:#475569;

    font-size:12px;
    font-weight:600;

    cursor:pointer;
}

.pagination-btn:hover{
    background:#e0e7ff;
    color:#2563eb;
}

.pagination-btn.active{
    background:linear-gradient(
        135deg,
        #1e3a8a,
        #2563eb
    );

    color:#fff;

    box-shadow:
        0 4px 12px rgba(37,99,235,.25);
}

.pagination-btn.disabled{
    opacity:.4;
    cursor:not-allowed;
}

.page-btn:hover{
    background:#e0e7ff;
}

</style>

@endpush

@push('scripts')

<script>

let currentPage = 1;

function filterTable()
{
    currentPage = 1;
    renderTable();
}

function changePerPage()
{
    currentPage = 1;
    renderTable();
}

function renderTable()
{
    const q =
        document.getElementById('searchInput')
        .value.toLowerCase();

    const filter =
        document.getElementById('filterStatus')
        .value;

    const max =
        parseInt(
            document.getElementById('perPage').value
        );

    const rows =
        [...document.querySelectorAll(
            '#tableBody tr:not(#emptyFilterRow)'
        )];

    const filtered =
        rows.filter(r => {

            const txt =
                r.textContent.toLowerCase();

            const status =
                r.dataset.status;

            const matchSearch =
                txt.includes(q);

            const matchFilter =
                !filter ||
                (
                    filter === 'Minimum'
                    &&
                    (
                        status === 'Perlu Pengadaan'
                        ||
                        status === 'Habis'
                    )
                );

            return matchSearch && matchFilter;
        });

    rows.forEach(r => r.style.display='none');

    const start =
        (currentPage - 1) * max;

    const end =
        start + max;

    filtered
        .slice(start,end)
        .forEach(r => r.style.display='');

    const totalPage =
        Math.ceil(filtered.length / max);

    // render tombol pagination modern
    renderPagination(totalPage || 1);

    document.getElementById('emptyFilterRow')
        .style.display =
        filtered.length === 0
            ? ''
            : 'none';

    updateFooter(
        Math.min(filtered.length,max),
        filtered.length
    );
}

function nextPage()
{
    currentPage++;
    renderTable();
}

function prevPage()
{
    if(currentPage > 1)
    {
        currentPage--;
        renderTable();
    }
}

function updateFooter(s, t)
{
    document.getElementById('tableFooter')
        .textContent =
        `Menampilkan ${s} dari ${t} data`;
}

function exportPdf()
{
    const filter =
        document.getElementById('filterStatus')
        .value;

    window.location.href =
    "{{ route('laporan-stok.export-pdf') }}?filter=" + filter;
}

function renderPagination(totalPage)
{
    const wrap =
        document.getElementById(
            'paginationWrap'
        );

    wrap.innerHTML = '';

    // tombol prev
    wrap.innerHTML += `
        <button
            class="pagination-btn
            ${currentPage === 1 ? 'disabled' : ''}"
            onclick="prevPage()">
            ‹
        </button>
    `;

    for(let i = 1; i <= totalPage; i++)
    {
        wrap.innerHTML += `
            <button
                class="pagination-btn
                ${i === currentPage ? 'active' : ''}"
                onclick="goPage(${i})">
                ${i}
            </button>
        `;
    }

    wrap.innerHTML += `
        <button
            class="pagination-btn
            ${currentPage === totalPage ? 'disabled' : ''}"
            onclick="nextPage()">
            ›
        </button>
    `;
}

document.addEventListener(
    'DOMContentLoaded',
    renderTable
);

</script>

@endpush
