@extends('layouts.dashboard')

@section('title', 'Data Stock')

@section('icon', 'fas fa-box')

@section('page-title', 'Data Stock')

@section('content')

<div class="dashboard-content">

    {{-- =========================================
        TOP ACTION
    ========================================== --}}
    <div class="page-action">

        <div class="action-left">

            <h3>Daftar Barang Gudang</h3>

            <p>
                Monitoring seluruh data stock barang
            </p>

        </div>

        {{-- BUTTON --}}
        <button class="btn-primary">

            <i class="fas fa-plus"></i>

            Tambah Barang

        </button>

    </div>

    {{-- =========================================
        MINI CARD
    ========================================== --}}
    <div class="mini-stats">

        {{-- TOTAL --}}
        <div class="glass-card mini-card">

            <div class="mini-icon blue">
                <i class="fas fa-box"></i>
            </div>

            <div>

                <span>Total Barang</span>

                <h2>125</h2>

            </div>

        </div>

        {{-- MINIMUM --}}
        <div class="glass-card mini-card">

            <div class="mini-icon yellow">
                <i class="fas fa-triangle-exclamation"></i>
            </div>

            <div>

                <span>Stock Minimum</span>

                <h2>8</h2>

            </div>

        </div>

        {{-- KATEGORI --}}
        <div class="glass-card mini-card">

            <div class="mini-icon green">
                <i class="fas fa-layer-group"></i>
            </div>

            <div>

                <span>Kategori</span>

                <h2>12</h2>

            </div>

        </div>

    </div>

    {{-- =========================================
        TABLE
    ========================================== --}}
    <div class="glass-card stock-table-card">

        {{-- TABLE HEADER --}}
        <div class="table-header">

            {{-- LEFT --}}
            <div class="table-header-left">

                <select class="table-select">

                    <option>10</option>
                    <option>25</option>
                    <option>50</option>

                </select>

                <span>data</span>

            </div>

            {{-- RIGHT --}}
            <div class="table-header-right">

                {{-- FILTER --}}
                <select class="table-filter">

                    <option>Semua Kategori</option>
                    <option>Safety</option>
                    <option>ATK</option>
                    <option>Elektronik</option>

                </select>

                {{-- SEARCH --}}
                <div class="search-box">

                    <i class="fas fa-search"></i>

                    <input type="text"
                        placeholder="Cari barang...">

                </div>

            </div>

        </div>

        {{-- TABLE --}}
        <div class="table-responsive">

            <table class="modern-table">

                <thead>

                    <tr>

                        <th>No</th>
                        <th>Kode</th>
                        <th>Nama Barang</th>
                        <th>Kategori</th>
                        <th>Stock</th>
                        <th>Status</th>
                        <th>Action</th>

                    </tr>

                </thead>

                <tbody>

                    {{-- ROW --}}
                    <tr>

                        <td>1</td>

                        <td>A001</td>

                        <td>Helm Safety</td>

                        <td>Safety</td>

                        <td>15</td>

                        <td>

                            <span class="badge success">
                                Aman
                            </span>

                        </td>

                        <td>

                            <div class="table-action">

                                <button class="btn-action blue">

                                    <i class="fas fa-eye"></i>

                                </button>

                                <button class="btn-action yellow">

                                    <i class="fas fa-pen"></i>

                                </button>

                                <button class="btn-action red">

                                    <i class="fas fa-trash"></i>

                                </button>

                            </div>

                        </td>

                    </tr>

                    {{-- ROW --}}
                    <tr>

                        <td>2</td>

                        <td>A002</td>

                        <td>Sepatu Safety</td>

                        <td>Safety</td>

                        <td>3</td>

                        <td>

                            <span class="badge warning">
                                Minimum
                            </span>

                        </td>

                        <td>

                            <div class="table-action">

                                <button class="btn-action blue">

                                    <i class="fas fa-eye"></i>

                                </button>

                                <button class="btn-action yellow">

                                    <i class="fas fa-pen"></i>

                                </button>

                                <button class="btn-action red">

                                    <i class="fas fa-trash"></i>

                                </button>

                            </div>

                        </td>

                    </tr>

                    {{-- ROW --}}
                    <tr>

                        <td>3</td>

                        <td>A003</td>

                        <td>Kertas A4</td>

                        <td>ATK</td>

                        <td>0</td>

                        <td>

                            <span class="badge danger">
                                Habis
                            </span>

                        </td>

                        <td>

                            <div class="table-action">

                                <button class="btn-action blue">

                                    <i class="fas fa-eye"></i>

                                </button>

                                <button class="btn-action yellow">

                                    <i class="fas fa-pen"></i>

                                </button>

                                <button class="btn-action red">

                                    <i class="fas fa-trash"></i>

                                </button>

                            </div>

                        </td>

                    </tr>

                </tbody>

            </table>

        </div>

        {{-- PAGINATION --}}
        <div class="table-footer">

            <span>
                Menampilkan 1 sampai 3 dari 3 data
            </span>

            <div class="pagination">

                <button>
                    <i class="fas fa-chevron-left"></i>
                </button>

                <button class="active">
                    1
                </button>

                <button>
                    <i class="fas fa-chevron-right"></i>
                </button>

            </div>

        </div>

    </div>

</div>

@endsection