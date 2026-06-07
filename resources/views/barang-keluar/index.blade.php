@extends('layouts.dashboard')

@section('title', 'Barang Keluar')
@section('page-title', 'Barang Keluar')

@section('header-icon')
    <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="2"
         stroke-linecap="round" stroke-linejoin="round">
        <circle cx="12" cy="12" r="10"/>
        <polyline points="12 16 8 12 12 8"/>
        <line x1="16" y1="12" x2="8" y2="12"/>
    </svg>
@endsection

@section('header-actions')
    <button type="button" class="btn-header" onclick="openTambahModal()">
        <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"
             stroke-linecap="round" stroke-linejoin="round">
            <line x1="12" y1="5" x2="12" y2="19"/>
            <line x1="5" y1="12" x2="19" y2="12"/>
        </svg>
        Tambah Data
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
                    <th>Nomor Faktur</th>
                    <th>Tanggal Keluar</th>
                    <th>Jumlah Item <span id="stok-header-info"></span></th>
                    <th>Bukti File</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody id="tableBody">
                @forelse($barangKeluar as $i => $item)
                <tr>
                    <td>{{ $i + 1 }}</td>
                    <td style="font-weight:600;">{{ $item->nomor_faktur ?? '-' }}</td>
                    <td>{{ $item->tanggal_keluar->format('d/m/Y') }}</td>
                    <td>{{ $item->jumlah }}</td>
                    <td>
                        @if($item->bukti_file)
                            <a href="{{ Storage::url($item->bukti_file) }}" target="_blank" class="btn-file-pill">
                                <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2"
                                     stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/>
                                    <polyline points="14 2 14 8 20 8"/>
                                </svg>
                                {{ basename($item->bukti_file) }}
                            </a>
                        @else
                            <span style="color:#9aa5bb; font-size:12px;">&mdash;</span>
                        @endif
                    </td>
                    <td>
                        {{-- Tombol Detail --}}
                        <button type="button" class="btn-aksi-detail" title="Detail"
                            data-faktur="{{ $item->nomor_faktur }}"
                            data-tanggal="{{ $item->tanggal_keluar->format('d/m/Y') }}"
                            data-jumlah="{{ $item->jumlah }}"
                            data-file="{{ $item->bukti_file ? Storage::url($item->bukti_file) : '' }}"
                            data-file-name="{{ $item->bukti_file ? basename($item->bukti_file) : '' }}"
                            data-items="{{ json_encode($item->detail_items) }}"
                            onclick="openDetailModal(this)">
                            <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2"
                                 stroke-linecap="round" stroke-linejoin="round">
                                <circle cx="12" cy="12" r="10"/>
                                <line x1="12" y1="8" x2="12" y2="12"/>
                                <line x1="12" y1="16" x2="12.01" y2="16"/>
                            </svg>
                        </button>
                        {{-- Tombol Edit --}}
                        <a href="#" class="btn-aksi-edit" title="Edit"
                           data-id="{{ $item->id }}"
                           data-faktur="{{ $item->nomor_faktur }}"
                           data-tanggal="{{ $item->tanggal_keluar->format('Y-m-d') }}"
                           data-file-path="{{ $item->bukti_file ?? '' }}"
                           data-file-name="{{ $item->bukti_file ? basename($item->bukti_file) : '' }}"
                           data-items="{{ json_encode($item->detail_items) }}"
                           onclick="openEditModal(this); return false;">
                            <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2"
                                 stroke-linecap="round" stroke-linejoin="round">
                                <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/>
                                <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/>
                            </svg>
                        </a>
                        {{-- Tombol Hapus --}}
                        <button type="button" class="btn-aksi-hapus" title="Hapus"
                            onclick="openHapusModal({{ $item->id }}, '{{ addslashes($item->nomor_faktur ?? $item->tanggal_keluar->format('d/m/Y')) }}')">
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
                    <td colspan="6" style="text-align:center; color:#9aa5bb;">Belum ada data barang keluar.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
        <div class="table-footer" id="tableFooter">Menampilkan {{ count($barangKeluar) }} dari {{ count($barangKeluar) }} data</div>
    </div>

    {{-- Modal Tambah Barang Keluar --}}
    <div id="modalTambahBarangKeluar" class="modal-overlay" style="display:none;">
        <div class="modal-box" style="max-width:820px; width:95vw;">
            <div class="modal-header">
                <h3 class="modal-title">Tambah Barang Keluar</h3>
                <button type="button" class="modal-close" onclick="closeTambahModal()">&times;</button>
            </div>
            <form id="formTambahBarangKeluar" action="{{ route('barang-keluar.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-body">
                    {{-- Baris atas: Nomor Faktur + Tanggal --}}
                    <div style="display:grid; grid-template-columns:1fr 1fr; gap:14px; margin-bottom:16px;">
                        <div class="form-group" style="margin-bottom:0;">
                            <label class="form-label">Nomor Faktur <span class="required">*</span></label>
                            <input type="text" name="nomor_faktur" class="form-input" placeholder="Contoh: F-2026-001" required>
                        </div>
                        <div class="form-group" style="margin-bottom:0;">
                            <label class="form-label">Tanggal Keluar <span class="required">*</span></label>
                            <input type="date" name="tanggal_keluar" class="form-input" required>
                        </div>
                    </div>

                    {{-- Tabel Item Barang --}}
                    <div style="margin-bottom:14px;">
                        <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:8px;">
                            <label class="form-label" style="margin:0;">Daftar Barang Keluar <span class="required">*</span></label>
                            <button type="button" onclick="tambahBarangRow()" style="display:inline-flex;align-items:center;gap:5px;padding:5px 12px;background:#6366f1;color:#fff;border:none;border-radius:6px;font-size:12px;font-weight:600;cursor:pointer;">
                                <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                                    <line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/>
                                </svg>
                                Tambah Barang
                            </button>
                        </div>
                        <div style="border:1px solid #e2e8f0;border-radius:8px;overflow-x:hidden;max-height:220px;overflow-y:auto;">
                            <table style="width:100%;border-collapse:collapse;font-size:13px;">
                                <thead>
                                    <tr style="background:#f8fafc;position:sticky;top:0;z-index:1;">
                                        <th style="padding:8px 10px;text-align:center;font-weight:600;color:#374151;border-bottom:1px solid #e2e8f0;width:44px;">No.</th>
                                        <th style="padding:8px 10px;text-align:left;font-weight:600;color:#374151;border-bottom:1px solid #e2e8f0;">ID Barang</th>
                                        <th style="padding:8px 10px;text-align:left;font-weight:600;color:#374151;border-bottom:1px solid #e2e8f0;">Unit Tujuan</th>
                                        <th style="padding:8px 10px;text-align:left;font-weight:600;color:#374151;border-bottom:1px solid #e2e8f0;width:130px;">Jumlah</th>
                                        <th style="padding:8px 10px;width:44px;border-bottom:1px solid #e2e8f0;"></th>
                                    </tr>
                                </thead>
                                <tbody id="keluar-rows"></tbody>
                            </table>
                        </div>
                    </div>
                    {{-- Bukti File --}}
                    <div class="form-group" style="margin-bottom:0;">
                        <label class="form-label">
                            Bukti File <span class="required">*</span>
                            <span style="color:#9aa5bb; font-size:11px;">
                                (PDF, maks. 5 MB)
                            </span>
                        </label>

                        <input type="file"
                            name="bukti_file"
                            id="bukti_file"
                            class="form-input"
                            accept=".pdf"
                            onchange="validateFileSize(this)"
                            required>

                        <small id="fileError"
                            style="
                                    display:none;
                                    color:#ef4444;
                                    font-size:12px;
                                    margin-top:6px;
                                    font-weight:500;">
                        </small>
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

    {{-- Modal Edit Barang Keluar --}}
    <div id="modalEditBarangKeluar" class="modal-overlay" style="display:none;">
        <div class="modal-box" style="max-width:820px; width:95vw;">
            <div class="modal-header">
                <h3 class="modal-title">Edit Barang Keluar</h3>
                <button type="button" class="modal-close" onclick="closeEditModal()">&times;</button>
            </div>
            <form id="formEditBarangKeluar" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <div class="modal-body">
                    {{-- Baris atas: Nomor Faktur + Tanggal --}}
                    <div style="display:grid; grid-template-columns:1fr 1fr; gap:14px; margin-bottom:16px;">
                        <div class="form-group" style="margin-bottom:0;">
                            <label class="form-label">Nomor Faktur <span class="required">*</span></label>
                            <input type="text" name="nomor_faktur" id="edit_nomor_faktur" class="form-input" placeholder="Contoh: F-2026-001" required>
                        </div>
                        <div class="form-group" style="margin-bottom:0;">
                            <label class="form-label">Tanggal Keluar <span class="required">*</span></label>
                            <input type="date" name="tanggal_keluar" id="edit_tanggal_keluar" class="form-input" required>
                        </div>
                    </div>

                    {{-- Tabel Item Barang --}}
                    <div style="margin-bottom:14px;">
                        <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:8px;">
                            <label class="form-label" style="margin:0;">Daftar Barang Keluar <span class="required">*</span></label>
                            <button type="button" onclick="tambahEditRow()" style="display:inline-flex;align-items:center;gap:5px;padding:5px 12px;background:#6366f1;color:#fff;border:none;border-radius:6px;font-size:12px;font-weight:600;cursor:pointer;">
                                <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                                    <line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/>
                                </svg>
                                Tambah Barang
                            </button>
                        </div>
                        <div style="border:1px solid #e2e8f0;border-radius:8px;overflow-x:hidden;max-height:220px;overflow-y:auto;">
                            <table style="width:100%; border-collapse:collapse; font-size:13px;">
                                <thead>
                                    <tr style="background:#f8fafc;position:sticky;top:0;z-index:1;">
                                        <th style="padding:8px 10px;text-align:center;font-weight:600;color:#374151;border-bottom:1px solid #e2e8f0;width:44px;">No.</th>
                                        <th style="padding:8px 10px;text-align:left;font-weight:600;color:#374151;border-bottom:1px solid #e2e8f0;">ID Barang</th>
                                        <th style="padding:8px 10px;text-align:left;font-weight:600;color:#374151;border-bottom:1px solid #e2e8f0;">Unit Tujuan</th>
                                        <th style="padding:8px 10px;text-align:left;font-weight:600;color:#374151;border-bottom:1px solid #e2e8f0;width:130px;">Jumlah</th>
                                        <th style="padding:8px 10px;width:44px;border-bottom:1px solid #e2e8f0;"></th>
                                    </tr>
                                </thead>
                                <tbody id="edit-keluar-rows"></tbody>
                            </table>
                        </div>
                    </div>
                    {{-- Bukti File --}}
                    <div class="form-group" style="margin-bottom:0;">
                        <label class="form-label">Bukti File <span id="edit_bukti_required" class="required" style="display:none;">*</span> <span style="color:#9aa5bb; font-size:11px;">(PDF, maks. 5 MB - kosongkan jika tidak diganti)</span></label>
                        <p id="edit_current_file" style="font-size:11px; color:#6b7a99; margin:0 0 4px;"></p>
                        <input type="file" id="edit_bukti_file" name="bukti_file" class="form-input" accept=".pdf">
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

    {{-- Modal Detail Barang Keluar --}}
    <div id="modalDetailBarangKeluar" class="modal-overlay" style="display:none;">
        <div class="modal-box" style="max-width:680px; width:95vw;">
            <div class="modal-header">
                <h3 class="modal-title">Detail Barang Keluar</h3>
                <button type="button" class="modal-close" onclick="closeDetailModal()">&times;</button>
            </div>
            <div class="modal-body">
                {{-- Info Cards --}}
                <div style="display:grid;grid-template-columns:1fr 1fr 1fr;gap:12px;margin-bottom:18px;">
                    <div style="background:#f8fafc;border:1px solid #e2e8f0;border-radius:8px;padding:10px 14px;">
                        <div style="font-size:11px;color:#9aa5bb;font-weight:600;text-transform:uppercase;letter-spacing:.04em;margin-bottom:4px;">Nomor Faktur</div>
                        <div id="detail_faktur" style="font-size:14px;font-weight:700;color:#1e2a45;word-break:break-all;"></div>
                    </div>
                    <div style="background:#f8fafc;border:1px solid #e2e8f0;border-radius:8px;padding:10px 14px;">
                        <div style="font-size:11px;color:#9aa5bb;font-weight:600;text-transform:uppercase;letter-spacing:.04em;margin-bottom:4px;">Tanggal Keluar</div>
                        <div id="detail_tanggal" style="font-size:14px;font-weight:700;color:#1e2a45;"></div>
                    </div>
                    <div style="background:#f8fafc;border:1px solid #e2e8f0;border-radius:8px;padding:10px 14px;">
                        <div style="font-size:11px;color:#9aa5bb;font-weight:600;text-transform:uppercase;letter-spacing:.04em;margin-bottom:4px;">Jumlah Item</div>
                        <div id="detail_jumlah" style="font-size:14px;font-weight:700;color:#1e2a45;"></div>
                    </div>
                </div>
                {{-- Bukti File --}}
                <div id="detail_file_wrap" style="margin-bottom:16px;display:none;">
                    <a id="detail_file_link" href="#" target="_blank" class="btn-file-pill">
                        <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2"
                             stroke-linecap="round" stroke-linejoin="round">
                            <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/>
                            <polyline points="14 2 14 8 20 8"/>
                        </svg>
                        <span id="detail_file_name"></span>
                    </a>
                </div>
                {{-- Tabel Items --}}
                <div style="border:1px solid #e2e8f0;border-radius:8px;overflow:hidden;">
                    <table style="width:100%;border-collapse:collapse;font-size:13px;">
                        <thead>
                            <tr style="background:#f8fafc;">
                                <th style="padding:9px 12px;text-align:center;font-weight:600;color:#374151;border-bottom:1px solid #e2e8f0;width:44px;">No.</th>
                                <th style="padding:9px 12px;text-align:left;font-weight:600;color:#374151;border-bottom:1px solid #e2e8f0;">ID Barang</th>
                                <th style="padding:9px 12px;text-align:left;font-weight:600;color:#374151;border-bottom:1px solid #e2e8f0;">Nama Barang</th>
                                <th style="padding:9px 12px;text-align:left;font-weight:600;color:#374151;border-bottom:1px solid #e2e8f0;">Unit Tujuan</th>
                                <th style="padding:9px 12px;text-align:center;font-weight:600;color:#374151;border-bottom:1px solid #e2e8f0;width:110px;">Jumlah</th>
                            </tr>
                        </thead>
                        <tbody id="detail_tbody"></tbody>
                    </table>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn-cancel" onclick="closeDetailModal()">Tutup</button>
            </div>
        </div>
    </div>

    {{-- Modal Konfirmasi Hapus Barang Keluar --}}
    <div id="modalHapusBarangKeluar" class="modal-overlay" style="display:none;">
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
                <p style="font-size:15px; color:#1e2a45; font-weight:600; margin:0 0 6px;">Hapus Barang Keluar?</p>
                <p style="font-size:13px; color:#6b7a99; margin:0;">Semua item pada Faktur <strong id="hapus_faktur_keluar"></strong> akan dihapus secara permanen.</p>
            </div>
            <div class="modal-footer" style="justify-content:center; gap:10px;">
                <button type="button" class="btn-cancel" onclick="closeHapusModal()">Batal</button>
                <form id="formHapusBarangKeluar" method="POST" style="display:inline;">
                    @csrf
                    @method('DELETE')
                    <button type="submit" style="height:38px; padding:0 20px; background:#ef4444; color:#fff; border:none; border-radius:8px; font-size:13px; font-weight:600; cursor:pointer;">
                        Hapus
                    </button>
                </form>
            </div>
        </div>
    </div>

    {{-- Popup Stok Tidak Cukup --}}
    <div id="modalStokKurang" class="modal-overlay" style="display:none;z-index:10001;">
        <div class="modal-box" style="max-width:420px;">
            <div class="modal-header" style="border-bottom:none;padding-bottom:8px;">
                <h3 class="modal-title" style="color:#ef4444;">Stok Tidak Cukup</h3>
                <button type="button" class="modal-close" onclick="closeStokPopup()">&times;</button>
            </div>
            <div class="modal-body" style="text-align:center;padding-top:4px;">
                <svg width="44" height="44" viewBox="0 0 24 24" fill="none" stroke="#ef4444" stroke-width="1.8"
                     stroke-linecap="round" stroke-linejoin="round" style="margin-bottom:12px;">
                    <path d="M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z"/>
                    <line x1="12" y1="9" x2="12" y2="13"/>
                    <line x1="12" y1="17" x2="12.01" y2="17"/>
                </svg>
                <p style="font-size:14px;font-weight:600;color:#1e2a45;margin:0 0 12px;">Jumlah melebihi stok tersedia</p>
                <div id="popup-stok-list" style="text-align:left;border:1px solid #fee2e2;border-radius:8px;overflow:hidden;"></div>
            </div>
            <div class="modal-footer" style="justify-content:center;border-top:none;padding-top:8px;">
                <button type="button" class="btn-simpan" onclick="closeStokPopup()" style="padding:8px 28px;">OK</button>
            </div>
        </div>
    </div>
@endsection

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/barang-keluar.css') }}">
    <style>
        .btn-file-pill {
            display: inline-flex;
            align-items: center;
            gap: 5px;
            padding: 4px 10px;
            background: rgba(99, 102, 241, 0.1);
            color: #6366f1;
            border: 1px solid rgba(99, 102, 241, 0.3);
            border-radius: 20px;
            font-size: 12px;
            font-weight: 500;
            text-decoration: none;
            max-width: 180px;
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
            transition: background 0.2s;
        }
        .btn-file-pill:hover { background: rgba(99, 102, 241, 0.2); }

        .btn-aksi-detail {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 32px;
            height: 32px;
            background: rgba(99,102,241,0.1);
            color: #6366f1;
            border: 1px solid rgba(99,102,241,0.25);
            border-radius: 8px;
            cursor: pointer;
            transition: background 0.15s;
            margin-right: 3px;
        }
        .btn-aksi-detail:hover { background: rgba(99,102,241,0.2); }

        .row-select {
            width: 100%;
            padding: 6px 8px;
            border: 1px solid #d1d5db;
            border-radius: 7px;
            font-size: 13px;
            background: #fff;
            color: #1e2a45;
            outline: none;
            transition: border-color .15s;
        }
        .row-select:focus { border-color: #3b5bdb; }

        .row-input {
            width: 100%;
            padding: 6px 8px;
            border: 1px solid #d1d5db;
            border-radius: 7px;
            font-size: 13px;
            background: #fff;
            color: #1e2a45;
            outline: none;
            transition: border-color .15s;
            box-sizing: border-box;
        }
        .row-input:focus { border-color: #3b5bdb; }
    </style>
@endpush

@push('scripts')
<script>
    const stokData            = @json($stokData);
    const ManajemenBarangOptions = @json($ManajemenBarang);
    const unitOptions         = @json($units);

    /* ===================================================
       SHARED HELPERS
    =================================================== */
    function buildBarangOptions(selectedId) {
        let html = '<option value="" disabled' + (selectedId ? '' : ' selected') + '>Pilih ID Barang</option>';
        ManajemenBarangOptions.forEach(function(b) {
            const sel = (b.id_barang === selectedId) ? ' selected' : '';
            html += `<option value="${b.id_barang}" data-nama="${b.nama_barang}"${sel}>${b.id_barang} - ${b.nama_barang}</option>`;
        });
        return html;
    }

    function buildUnitOptions(selectedUnit) {
        let html = '<option value="" disabled' + (selectedUnit ? '' : ' selected') + '>Pilih Unit</option>';
        unitOptions.forEach(function(u) {
            const sel = (u === selectedUnit) ? ' selected' : '';
            html += `<option value="${u}"${sel}>${u}</option>`;
        });
        return html;
    }

    function buildRow(tbodyId, prefix, idx, idBarang, unit, jumlah) {
        const tbody = document.getElementById(tbodyId);
        const num   = tbody.querySelectorAll('tr').length + 1;
        const tr    = document.createElement('tr');
        tr.id = prefix + '-row-' + idx;
        tr.style.borderBottom = '1px solid #f1f5f9';
        tr.innerHTML = `
            <td style="padding:7px 10px; color:#9aa5bb; font-size:13px; text-align:center;" class="row-num">${num}</td>
            <td style="padding:7px 10px;">
                <select name="items[${idx}][id_barang]" id="${prefix}-id-${idx}" class="row-select" onchange="onRowChange(this, '${prefix}', ${idx})" required>
                    ${buildBarangOptions(idBarang || '')}
                </select>
                <input type="hidden" name="items[${idx}][nama_barang]" id="${prefix}-nama-${idx}">
            </td>
            <td style="padding:7px 10px;">
                <select name="items[${idx}][unit]" class="row-select" required>
                    ${buildUnitOptions(unit || '')}
                </select>
            </td>
            <td style="padding:7px 10px;">
                <div style="position:relative;">
                    <input type="number"
                        name="items[${idx}][jumlah]"
                        id="${prefix}-jumlah-${idx}"
                        class="row-input jumlah-input"
                        placeholder="0"
                        min="1"
                        required>

                    <small id="${prefix}-stok-info-${idx}"
                        style="
                                position:absolute;
                                right:10px;
                                bottom:-18px;
                                font-size:11px;
                                color:#64748b;
                                white-space:nowrap;">
                    </small>
                </div>
            </td>
            <td style="padding:7px 10px; text-align:center;">
                <button type="button" onclick="hapusRow('${tbodyId}', '${prefix}', ${idx})"
                        style="background:none;border:none;cursor:pointer;color:#ef4444;padding:2px;line-height:1;">
                    <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2"
                         stroke-linecap="round" stroke-linejoin="round">
                        <polyline points="3 6 5 6 21 6"/>
                        <path d="M19 6l-1 14a2 2 0 0 1-2 2H8a2 2 0 0 1-2-2L5 6"/>
                        <path d="M10 11v6M14 11v6"/>
                        <path d="M9 6V4a1 1 0 0 1 1-1h4a1 1 0 0 1 1 1v2"/>
                    </svg>
                </button>
            </td>`;
        tbody.appendChild(tr);
        updateRowNumbers(tbodyId);

        if (idBarang) {
            const sel = tr.querySelector(`select[name="items[${idx}][id_barang]"]`);
            if (sel) onRowChange(sel, prefix, idx);
        }
    }

    function hapusRow(tbodyId, prefix, idx) {
        const rows = document.getElementById(tbodyId).querySelectorAll('tr');
        if (rows.length <= 1) { alert('Minimal satu barang harus diisi.'); return; }
        const el = document.getElementById(prefix + '-row-' + idx);
        if (el) el.remove();
        updateRowNumbers(tbodyId);
    }

    function updateRowNumbers(tbodyId) {
        document.getElementById(tbodyId).querySelectorAll('tr').forEach((tr, i) => {
            const c = tr.querySelector('.row-num');
            if (c) c.textContent = i + 1;
        });
    }

    function onRowChange(select, prefix, idx) {

        const opt  = select.options[select.selectedIndex];
        const nama = opt ? (opt.getAttribute('data-nama') || '') : '';

        document.getElementById(prefix + '-nama-' + idx).value = nama;

        const idBarang = select.value;

        const jumlahInput =
            document.getElementById(prefix + '-jumlah-' + idx);

        const stok =
            parseInt(stokData[idBarang] ?? 0);

        jumlahInput.max = stok;

        jumlahInput.dataset.stok = stok;

        jumlahInput.placeholder =
            stok > 0
                ? 'Stok: ' + stok
                : 'Stok Habis';

        if (stok <= 0) {
            jumlahInput.value = '';
        }

    }

    // Validasi stok semua baris sebelum submit — returns false + tampilkan popup jika melebihi stok
    // originalStok: { idBarang: jumlahAsli } — stok yang akan dikembalikan karena sedang diedit
    function validateStok(tbodyId, prefix, originalStok) {
        originalStok = originalStok || {};
        const rows       = document.getElementById(tbodyId).querySelectorAll('tr');
        const violations = [];
        rows.forEach(function(row) {
            const idSel    = row.querySelector('select[id^="' + prefix + '-id-"]');
            const jumlahEl = row.querySelector('input[id^="' + prefix + '-jumlah-"]');
            if (!idSel || !jumlahEl) return;
            const idBarang = idSel.value;
            const jumlah   = parseInt(jumlahEl.value);
            if (!idBarang || !stokData.hasOwnProperty(idBarang) || isNaN(jumlah)) return;
            // Stok efektif = stok saat ini + jumlah asli yg akan dikembalikan saat edit
            const stokEfektif = parseInt(stokData[idBarang]) + (originalStok[idBarang] || 0);
            if (jumlah > stokEfektif) {
                const nama = idSel.options[idSel.selectedIndex] ? idSel.options[idSel.selectedIndex].text : idBarang;
                violations.push({ idBarang, nama, jumlah, stok: stokEfektif });
            }
        });
        if (violations.length > 0) {
            const list = document.getElementById('popup-stok-list');
            list.innerHTML = violations.map((v, i) =>
                `<div style="display:grid;grid-template-columns:1fr auto;align-items:center;padding:8px 12px;${
                    i < violations.length - 1 ? 'border-bottom:1px solid #fee2e2;' : ''}">` +
                    `<div>` +
                        `<div style="font-size:13px;font-weight:600;color:#1e2a45;">${v.nama}</div>` +
                        `<div style="font-size:11px;color:#9aa5bb;margin-top:1px;">Diinput: ${v.jumlah.toLocaleString('id-ID')} pcs</div>` +
                    `</div>` +
                    `<div style="font-size:12px;font-weight:600;color:#ef4444;white-space:nowrap;">` +
                        `Stok: ${v.stok.toLocaleString('id-ID')} pcs` +
                    `</div>` +
                `</div>`
            ).join('');
            document.getElementById('modalStokKurang').style.display = 'flex';
            return false;
        }
        return true;
    }

    function closeStokPopup() {
        document.getElementById('modalStokKurang').style.display = 'none';
    }

    document.addEventListener('input', function(e){

        if(!e.target.classList.contains('jumlah-input'))
            return;

        const stok =
            parseInt(e.target.dataset.stok || 0);

        const value =
            parseInt(e.target.value || 0);

        if(value > stok)
        {
            e.target.value = stok;

            alert(
                'Jumlah tidak boleh melebihi stok tersedia (' +
                stok +
                ')'
            );
        }
    });

    /* ===================================================
       TAMBAH MODAL
    =================================================== */
    let rowCounter = 0;

    function tambahBarangRow(idBarang, unit, jumlah) {
        rowCounter++;
        buildRow('keluar-rows', 'add', rowCounter, idBarang, unit, jumlah);
    }

    function openTambahModal() {
        document.getElementById('keluar-rows').innerHTML = '';
        rowCounter = 0;
        tambahBarangRow();
        document.getElementById('modalTambahBarangKeluar').style.display = 'flex';
        document.body.style.overflow = 'hidden';
    }

    function closeTambahModal() {
        document.getElementById('modalTambahBarangKeluar').style.display = 'none';
        document.body.style.overflow = '';
    }

    document.getElementById('modalTambahBarangKeluar').addEventListener('click', function(e) {
        if (e.target === this) closeTambahModal();
    });

    /* ===================================================
       EDIT MODAL
    =================================================== */
    let editRowCounter = 0;

    function tambahEditRow(idBarang, unit, jumlah) {
        editRowCounter++;
        buildRow('edit-keluar-rows', 'edit', editRowCounter, idBarang, unit, jumlah);
    }

    let editOriginalStok = {}; // map idBarang -> jumlah asli saat edit dibuka

    function openEditModal(link) {
        const id       = link.dataset.id;
        const faktur   = link.dataset.faktur;
        const tanggal  = link.dataset.tanggal;
        const filePath = link.dataset.filePath;
        const fileName = link.dataset.fileName;
        const items    = JSON.parse(link.dataset.items);

        const baseUrl = '{{ url('/barang-keluar') }}';
        document.getElementById('formEditBarangKeluar').action = baseUrl + '/' + id;

        document.getElementById('edit_nomor_faktur').value   = faktur || '';
        document.getElementById('edit_tanggal_keluar').value = tanggal || '';

        const fileInfo = document.getElementById('edit_current_file');
        fileInfo.textContent = fileName ? 'File saat ini: ' + fileName : '';

        // Bukti file wajib hanya jika belum ada file sebelumnya
        const editBuktiInput    = document.getElementById('edit_bukti_file');
        const editBuktiRequired = document.getElementById('edit_bukti_required');
        if (fileName) {
            editBuktiInput.removeAttribute('required');
            editBuktiRequired.style.display = 'none';
        } else {
            editBuktiInput.setAttribute('required', 'required');
            editBuktiRequired.style.display = '';
        }

        // Populate item rows & simpan stok asli
        document.getElementById('edit-keluar-rows').innerHTML = '';
        editRowCounter   = 0;
        editOriginalStok = {};
        items.forEach(function(item) {
            tambahEditRow(item.id_barang, item.unit, item.jumlah);
            // Akumulasi jumlah asli per ID barang (bisa ada ID sama lebih dari 1 baris)
            editOriginalStok[item.id_barang] = (editOriginalStok[item.id_barang] || 0) + parseInt(item.jumlah);
        });

        document.getElementById('modalEditBarangKeluar').style.display = 'flex';
        document.body.style.overflow = 'hidden';
    }

    function closeEditModal() {
        document.getElementById('modalEditBarangKeluar').style.display = 'none';
        document.body.style.overflow = '';
    }

    document.getElementById('modalEditBarangKeluar').addEventListener('click', function(e) {
        if (e.target === this) closeEditModal();
    });

    /* ===================================================
       DETAIL MODAL
    =================================================== */
    function openDetailModal(btn) {
        const faktur   = btn.dataset.faktur;
        const tanggal  = btn.dataset.tanggal;
        const jumlah   = btn.dataset.jumlah;
        const fileUrl  = btn.dataset.file;
        const fileName = btn.dataset.fileName;
        const items    = JSON.parse(btn.dataset.items);

        document.getElementById('detail_faktur').textContent  = faktur || '-';
        document.getElementById('detail_tanggal').textContent = tanggal;
        document.getElementById('detail_jumlah').textContent  = jumlah + ' item';

        const fileWrap = document.getElementById('detail_file_wrap');
        if (fileUrl) {
            document.getElementById('detail_file_link').href        = fileUrl;
            document.getElementById('detail_file_name').textContent = fileName || 'Lihat File';
            fileWrap.style.display = 'block';
        } else {
            fileWrap.style.display = 'none';
        }

        const tbody = document.getElementById('detail_tbody');
        tbody.innerHTML = '';
        items.forEach(function(item, i) {
            const tr = document.createElement('tr');
            tr.style.borderBottom = '1px solid #f1f5f9';
            tr.innerHTML = `
                <td style="padding:8px 12px;text-align:center;color:#9aa5bb;">${i + 1}</td>
                <td style="padding:8px 12px;color:#1e2a45;font-weight:500;">${item.id_barang}</td>
                <td style="padding:8px 12px;color:#374151;">${item.nama_barang}</td>
                <td style="padding:8px 12px;color:#374151;">${item.unit}</td>
                <td style="padding:8px 12px;text-align:center;color:#1e2a45;font-weight:600;">${parseInt(item.jumlah).toLocaleString('id-ID')}</td>`;
            tbody.appendChild(tr);
        });

        document.getElementById('modalDetailBarangKeluar').style.display = 'flex';
        document.body.style.overflow = 'hidden';
    }

    function closeDetailModal() {
        document.getElementById('modalDetailBarangKeluar').style.display = 'none';
        document.body.style.overflow = '';
    }

    document.getElementById('modalDetailBarangKeluar').addEventListener('click', function(e) {
        if (e.target === this) closeDetailModal();
    });

    /* ===================================================
       HAPUS MODAL
    =================================================== */
    function openHapusModal(id, faktur) {
        const baseUrl = '{{ url('/barang-keluar') }}';
        document.getElementById('formHapusBarangKeluar').action = baseUrl + '/' + id;
        document.getElementById('hapus_faktur_keluar').textContent = faktur;
        document.getElementById('modalHapusBarangKeluar').style.display = 'flex';
        document.body.style.overflow = 'hidden';
    }

    function closeHapusModal() {
        document.getElementById('modalHapusBarangKeluar').style.display = 'none';
        document.body.style.overflow = '';
    }

    document.getElementById('modalHapusBarangKeluar').addEventListener('click', function(e) {
        if (e.target === this) closeHapusModal();
    });

    /* ===================================================
       TABLE FILTER / PAGER
    =================================================== */
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

    // Intercept submit: cek stok sebelum kirim
    document.getElementById('formTambahBarangKeluar').addEventListener('submit', function(e) {
        if (!validateStok('keluar-rows', 'add')) e.preventDefault();
    });

    document.getElementById('formEditBarangKeluar').addEventListener('submit', function(e) {
        if (!validateStok('edit-keluar-rows', 'edit', editOriginalStok)) e.preventDefault();
    });

    function validateFileSize(input) {
        const file = input.files[0];

        const error =
            document.getElementById('fileError');

        error.style.display = 'none';
        error.innerHTML = '';

        if (!file) return;

        const maxSize = 5 * 1024 * 1024; // 5 MB

        if (file.size > maxSize)
        {
            error.innerHTML =
                'Ukuran file melebihi batas maksimum 5 MB.';

            error.style.display = 'block';

            input.value = '';
        }
    }
</script>
@endpush
