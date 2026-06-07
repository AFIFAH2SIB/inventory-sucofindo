@extends('layouts.dashboard')

@section('title', 'Manajemen User')
@section('page-title', 'Manajemen User')

@section('header-icon')
    <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="2"
         stroke-linecap="round" stroke-linejoin="round">
        <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/>
        <circle cx="12" cy="7" r="4"/>
    </svg>
@endsection

@section('header-actions')
    <button type="button" class="btn-header" onclick="openTambahModal()">
        <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"
             stroke-linecap="round" stroke-linejoin="round">
            <line x1="12" y1="5" x2="12" y2="19"/>
            <line x1="5" y1="12" x2="19" y2="12"/>
        </svg>
        Tambah Pengguna
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
            <div style="display:flex;align-items:center;gap:10px;">
                <div class="filter-status-wrap">
                    <select id="filterRole" onchange="filterTable()">
                        <option value="">Semua Hak Akses</option>
                        <option value="admin">Admin</option>
                        <option value="supervisor">Supervisor</option>
                    </select>
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
        </div>
        <table class="data-table">
            <thead>
                <tr>
                    <th>No.</th>
                    <th>Nama Pengguna</th>
                    <th>Email</th>
                    <th>No. Telepon</th>
                    <th>Hak Akses</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody id="tableBody">
                @forelse($users as $i => $user)
                <tr data-role="{{ $user->role }}">
                    <td>{{ $i + 1 }}</td>
                    <td>{{ $user->name }}</td>
                    <td>{{ $user->email }}</td>
                    <td>{{ $user->phone ?? '-' }}</td>
                    <td>
                        <span class="badge-role badge-{{ $user->role }}">
                            {{ ucfirst($user->role) }}
                        </span>
                    </td>
                    <td>
                        <button type="button" class="btn-aksi-edit" title="Edit"
                            onclick="openEditModal({{ $user->id }}, '{{ addslashes($user->name) }}', '{{ $user->email }}', '{{ $user->phone ?? '' }}', '{{ $user->role }}')">
                            <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2"
                                 stroke-linecap="round" stroke-linejoin="round">
                                <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/>
                                <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/>
                            </svg>
                        </button>
                        <button type="button" class="btn-aksi-hapus" title="Hapus"
                            onclick="openHapusModal({{ $user->id }}, '{{ addslashes($user->name) }}')">
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
                    <td colspan="6" style="text-align:center; color:#9aa5bb;">Belum ada data pengguna.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
        <div class="table-footer" id="tableFooter">Menampilkan {{ count($users) }} dari {{ count($users) }} data</div>
    </div>

    {{-- Modal Edit Pengguna --}}
    <div id="modalEditPengguna" class="modal-overlay" style="display:none;">
        <div class="modal-box">
            <div class="modal-header">
                <h3 class="modal-title">Edit Pengguna</h3>
                <button type="button" class="modal-close" onclick="closeEditModal()">&times;</button>
            </div>
            <form id="formEditPengguna" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-body">
                    <div class="form-group">
                        <label class="form-label">Nama Pengguna <span class="required">*</span></label>
                        <input type="text" id="edit_name" name="name" class="form-input" placeholder="Masukkan nama pengguna" required>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Email <span class="required">*</span></label>
                        <input type="email" id="edit_email" name="email" class="form-input" placeholder="Masukkan email" required>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Nomor Telepon</label>
                        <input type="text" id="edit_phone" name="phone" class="form-input" placeholder="Masukkan nomor telepon">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Password Baru <span style="color:#94a3b8;font-weight:400;">(kosongkan jika tidak diubah)</span></label>
                        <input type="password" name="password" class="form-input" placeholder="Masukkan password baru">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Hak Akses <span class="required">*</span></label>
                        <select id="edit_role" name="role" class="form-input" required>
                            <option value="admin">Admin</option>
                            <option value="supervisor">Supervisor</option>
                        </select>
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

    {{-- Modal Tambah Pengguna --}}
    <div id="modalTambahPengguna" class="modal-overlay" style="display:none;">
        <div class="modal-box">
            <div class="modal-header">
                <h3 class="modal-title">Tambah Pengguna</h3>
                <button type="button" class="modal-close" onclick="closeTambahModal()">&times;</button>
            </div>
            <form action="{{ route('manajemen-user.store') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="form-group">
                        <label class="form-label">Nama Pengguna <span class="required">*</span></label>
                        <input type="text" name="name" class="form-input" placeholder="Masukkan nama pengguna" required>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Email <span class="required">*</span></label>
                        <input type="email" name="email" class="form-input" placeholder="Masukkan email" required>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Nomor Telepon</label>
                        <input type="text" name="phone" class="form-input" placeholder="Masukkan nomor telepon">
                    </div>
                    <div class="form-group">
                        <label class="form-label">
                            Password <span class="required">*</span>
                        </label>

                        <div class="password-wrapper">

                            <input
                                type="password"
                                id="passwordInput"
                                name="password"
                                class="form-input password-input"
                                placeholder="Masukkan password"
                                required>

                            <button
                                type="button"
                                class="toggle-password"
                                onclick="togglePassword()">

                                <svg id="eyeIcon"
                                    width="18"
                                    height="18"
                                    viewBox="0 0 24 24"
                                    fill="none"
                                    stroke="currentColor"
                                    stroke-width="2">

                                    <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8S1 12 1 12z"></path>
                                    <circle cx="12" cy="12" r="3"></circle>

                                </svg>

                            </button>

                        </div>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Hak Akses <span class="required">*</span></label>
                        <select name="role" class="form-input" required>
                            <option value="" disabled selected>Pilih hak akses</option>
                            <option value="admin">Admin</option>
                            <option value="supervisor">Supervisor</option>
                        </select>
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

    {{-- Modal Konfirmasi Hapus Pengguna --}}
    <div id="modalHapusPengguna" class="modal-overlay" style="display:none;">
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
                <p style="font-size:15px; color:#1e2a45; font-weight:600; margin:0 0 6px;">Hapus Pengguna?</p>
                <p style="font-size:13px; color:#6b7a99; margin:0;">Pengguna <strong id="hapus_nama_pengguna"></strong> akan dihapus secara permanen.</p>
            </div>
            <div class="modal-footer" style="justify-content:center; gap:10px;">
                <button type="button" class="btn-cancel" onclick="closeHapusModal()">Batal</button>
                <form id="formHapusPengguna" method="POST" style="display:inline;">
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

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/manajemen-user.css') }}">
@endpush

@push('scripts')
<script>
    function openTambahModal() {
        document.getElementById('modalTambahPengguna').style.display = 'flex';
        document.body.style.overflow = 'hidden';
    }

    function closeTambahModal() {
        document.getElementById('modalTambahPengguna').style.display = 'none';
        document.body.style.overflow = '';
    }

    // Close on overlay click
    document.getElementById('modalTambahPengguna').addEventListener('click', function(e) {
        if (e.target === this) closeTambahModal();
    });
    document.getElementById('modalEditPengguna').addEventListener('click', function(e) {
        if (e.target === this) closeEditModal();
    });

    function openEditModal(id, name, email, phone, role) {
        const baseUrl = "{{ url('manajemen-user') }}";
        document.getElementById('formEditPengguna').action = baseUrl + '/' + id;
        document.getElementById('edit_name').value  = name;
        document.getElementById('edit_email').value = email;
        document.getElementById('edit_phone').value = phone;
        document.getElementById('edit_role').value  = role;
        document.getElementById('modalEditPengguna').style.display = 'flex';
        document.body.style.overflow = 'hidden';
    }

    function closeEditModal() {
        document.getElementById('modalEditPengguna').style.display = 'none';
        document.body.style.overflow = '';
    }

    document.getElementById('modalHapusPengguna').addEventListener('click', function(e) {
        if (e.target === this) closeHapusModal();
    });

    function openHapusModal(id, nama) {
        const baseUrl = "{{ url('manajemen-user') }}";
        document.getElementById('formHapusPengguna').action = baseUrl + '/' + id;
        document.getElementById('hapus_nama_pengguna').textContent = nama;
        document.getElementById('modalHapusPengguna').style.display = 'flex';
        document.body.style.overflow = 'hidden';
    }

    function closeHapusModal() {
        document.getElementById('modalHapusPengguna').style.display = 'none';
        document.body.style.overflow = '';
    }

    function filterTable() {
        const q          = document.getElementById('searchInput').value.toLowerCase();
        const roleFilter = document.getElementById('filterRole').value;
        const rows = document.querySelectorAll('#tableBody tr');
        let v = 0;
        rows.forEach(r => {
            const matchSearch = r.textContent.toLowerCase().includes(q);
            const matchRole   = !roleFilter || r.dataset.role === roleFilter;
            const show = matchSearch && matchRole;
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

    function togglePassword()
    {
        const input =
            document.getElementById('passwordInput');

        if(input.type === 'password')
        {
            input.type = 'text';
        }
        else
        {
            input.type = 'password';
        }
    }
</script>
@endpush
