@extends('layouts.dashboard')

@section('title', 'Manajemen Barang')
@section('page-title', 'Manajemen Barang')

@section('header-icon')
    <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="2"
         stroke-linecap="round" stroke-linejoin="round">
        <path d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z"/>
        <polyline points="3.27 6.96 12 12.01 20.73 6.96"/>
        <line x1="12" y1="22.08" x2="12" y2="12"/>
    </svg>
@endsection

@section('header-actions')
    <button type="button" class="btn-header" onclick="openTambahModal()">
        <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"
             stroke-linecap="round" stroke-linejoin="round">
            <line x1="12" y1="5" x2="12" y2="19"/>
            <line x1="5" y1="12" x2="19" y2="12"/>
        </svg>
        Tambah Barang
    </button>
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
                    <th>ID Barang</th>
                    <th>Nama Barang</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody id="tableBody">
                @forelse($barang as $i => $item)
                <tr>
                    <td>{{ $i + 1 }}</td>
                    <td>{{ $item->id_barang }}</td>
                    <td>{{ $item->nama_barang }}</td>
                    <td>
                        <a href="#" class="btn-aksi-edit" title="Edit"
                           onclick="openEditModal({{ $item->id }}, '{{ addslashes($item->id_barang) }}', '{{ addslashes($item->nama_barang) }}'); return false;">
                            <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2"
                                 stroke-linecap="round" stroke-linejoin="round">
                                <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/>
                                <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/>
                            </svg>
                        </a>
                        <button type="button" class="btn-aksi-hapus" title="Hapus"
                            onclick="openHapusModal({{ $item->id }}, '{{ addslashes($item->nama_barang) }}')">
                            <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2"
                                 stroke-linecap="round" stroke-linejoin="round">
                                <polyline points="3 6 5 6 21 6"/>
                                <path d="M19 6l-1 14a2 2 0 0 1-2 2H8a2 2 0 0 1-2-2L5 6"/>
                                <path d="M10 11v6M14 11v6"/>
                                <path d="M9 6V4a1 1 0 0 1 1-1h4a1 1 0 0 1 1 1v2"/>
                            </svg>
                        </button>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="4" style="text-align:center; color:#9aa5bb;">Belum ada data barang.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
        <div class="table-footer" id="tableFooter">Menampilkan {{ count($barang) }} dari {{ count($barang) }} data</div>
    </div>

    {{-- Modal Tambah Barang --}}
    <div id="modalTambahBarang" class="modal-overlay" style="display:none;">
        <div class="modal-box">
            <div class="modal-header">
                <h3 class="modal-title">Tambah Barang</h3>
                <button type="button" class="modal-close" onclick="closeTambahModal()">&times;</button>
            </div>
            <form action="{{ route('manajemen-barang.store') }}" method="POST">
                @csrf
                <input type="hidden" name="_form" value="tambah">
                <div class="modal-body">
                    <div class="form-group">
                        <label class="form-label">ID Barang <span class="required">*</span></label>
                        <input type="text" name="id_barang" class="form-input {{ $errors->has('id_barang') ? 'input-error' : '' }}"
                               placeholder="Contoh: PKB/BPG-24/001" value="{{ old('id_barang') }}" required>
                        @if($errors->has('id_barang'))
                            <p class="field-error">{{ $errors->first('id_barang') }}</p>
                        @endif
                    </div>
                    <div class="form-group">
                        <label class="form-label">Nama Barang <span class="required">*</span></label>
                        <input type="text" name="nama_barang" class="form-input" placeholder="Masukkan nama barang"
                               value="{{ old('nama_barang') }}" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn-cancel" onclick="closeTambahModal()">Batal</button>
                    <button type="submit" class="btn-simpan">
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"
                             stroke-linecap="round" stroke-linejoin="round">
                            <path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"/>
                            <polyline points="17 21 17 13 7 13 7 21"/>
                            <polyline points="7 3 7 8 15 8"/>
                        </svg>
                        Simpan
                    </button>
                </div>
            </form>
        </div>
    </div>

    {{-- Modal Edit Barang --}}
    <div id="modalEditBarang" class="modal-overlay" style="display:none;">
        <div class="modal-box">
            <div class="modal-header">
                <h3 class="modal-title">Edit Barang</h3>
                <button type="button" class="modal-close" onclick="closeEditModal()">&times;</button>
            </div>
            <form id="formEditBarang" method="POST">
                @csrf
                @method('PUT')
                <input type="hidden" name="_form" value="edit">
                <input type="hidden" name="_edit_id" id="edit_hidden_id">
                <div class="modal-body">
                    <div class="form-group">
                        <label class="form-label">ID Barang <span class="required">*</span></label>
                        <input type="text" name="id_barang" id="edit_id_barang" class="form-input {{ $errors->has('id_barang_edit') ? 'input-error' : '' }}"
                               placeholder="Contoh: PKB/BPG-24/001" required>
                        @if($errors->has('id_barang_edit'))
                            <p class="field-error">{{ $errors->first('id_barang_edit') }}</p>
                        @endif
                    </div>
                    <div class="form-group">
                        <label class="form-label">Nama Barang <span class="required">*</span></label>
                        <input type="text" name="nama_barang" id="edit_nama_barang" class="form-input" placeholder="Masukkan nama barang" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn-cancel" onclick="closeEditModal()">Batal</button>
                    <button type="submit" class="btn-simpan">
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"
                             stroke-linecap="round" stroke-linejoin="round">
                            <path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"/>
                            <polyline points="17 21 17 13 7 13 7 21"/>
                            <polyline points="7 3 7 8 15 8"/>
                        </svg>
                        Simpan
                    </button>
                </div>
            </form>
        </div>
    </div>

    {{-- Modal Konfirmasi Hapus --}}
    <div id="modalHapusBarang" class="modal-overlay" style="display:none;">
        <div class="modal-box" style="max-width:420px;">
            <div class="modal-header">
                <h3 class="modal-title">Konfirmasi Hapus</h3>
                <button type="button" class="modal-close" onclick="closeHapusModal()">&times;</button>
            </div>
            <div class="modal-body" style="text-align:center; padding:24px 20px 8px;">
                <svg width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="#ef4444" stroke-width="1.8"
                     stroke-linecap="round" stroke-linejoin="round" style="margin-bottom:14px;">
                    <circle cx="12" cy="12" r="10"/>
                    <line x1="12" y1="8" x2="12" y2="12"/>
                    <line x1="12" y1="16" x2="12.01" y2="16"/>
                </svg>
                <p style="font-size:15px; color:#1e2a45; font-weight:600; margin:0 0 6px;">Hapus Barang?</p>
                <p style="font-size:13px; color:#6b7a99; margin:0;">Data <strong id="hapus_nama_barang"></strong> akan dihapus secara permanen.</p>
            </div>
            <div class="modal-footer" style="justify-content:center; gap:10px;">
                <button type="button" class="btn-cancel" onclick="closeHapusModal()">Batal</button>
                <form id="formHapusBarang" method="POST" style="display:inline;">
                    @csrf
                    @method('DELETE')
                    <button type="submit" style="height:38px; padding:0 20px; background:#ef4444; color:#fff; border:none; border-radius:8px; font-size:13px; font-weight:600; cursor:pointer;">
                        Hapus
                    </button>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
<script>
    // ── Tambah ──
    function openTambahModal() {
        document.getElementById('modalTambahBarang').style.display = 'flex';
        document.body.style.overflow = 'hidden';
    }
    function closeTambahModal() {
        document.getElementById('modalTambahBarang').style.display = 'none';
        document.body.style.overflow = '';
    }
    document.getElementById('modalTambahBarang').addEventListener('click', function(e) {
        if (e.target === this) closeTambahModal();
    });

    // ── Edit ──
    function openEditModal(id, idBarang, namaBarang) {
        const baseUrl = '{{ url('/manajemen-barang') }}';
        document.getElementById('formEditBarang').action = baseUrl + '/' + id;
        document.getElementById('edit_hidden_id').value  = id;
        document.getElementById('edit_id_barang').value   = idBarang;
        document.getElementById('edit_nama_barang').value = namaBarang;
        document.getElementById('modalEditBarang').style.display = 'flex';
        document.body.style.overflow = 'hidden';
    }
    function closeEditModal() {
        document.getElementById('modalEditBarang').style.display = 'none';
        document.body.style.overflow = '';
    }
    document.getElementById('modalEditBarang').addEventListener('click', function(e) {
        if (e.target === this) closeEditModal();
    });

    // ── Hapus ──
    function openHapusModal(id, nama) {
        const baseUrl = '{{ url('/manajemen-barang') }}';
        document.getElementById('formHapusBarang').action = baseUrl + '/' + id;
        document.getElementById('hapus_nama_barang').textContent = nama;
        document.getElementById('modalHapusBarang').style.display = 'flex';
        document.body.style.overflow = 'hidden';
    }
    function closeHapusModal() {
        document.getElementById('modalHapusBarang').style.display = 'none';
        document.body.style.overflow = '';
    }
    document.getElementById('modalHapusBarang').addEventListener('click', function(e) {
        if (e.target === this) closeHapusModal();
    });

    // ── Auto-reopen modal jika ada error validasi ──
    @if($errors->has('id_barang'))
    document.addEventListener('DOMContentLoaded', function() { openTambahModal(); });
    @endif
    @if($errors->has('id_barang_edit'))
    document.addEventListener('DOMContentLoaded', function() {
        openEditModal(
            '{{ old('_edit_id') }}',
            '{{ addslashes(old('id_barang', '')) }}',
            '{{ addslashes(old('nama_barang', '')) }}'
        );
    });
    @endif

    // ── Search & pagination ──
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
            'Menampilkan ' + shown + ' dari ' + total + ' data';
    }
</script>
@endpush
