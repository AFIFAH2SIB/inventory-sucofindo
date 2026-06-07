<!DOCTYPE html>

<html>
<head>
<meta charset="utf-8">

<style>

body{
    font-family: DejaVu Sans, sans-serif;
    font-size:12px;
}

h2{
    text-align:center;
    margin-bottom:20px;
}

table{
    width:100%;
    border-collapse:collapse;
}

table th,
table td{
    border:1px solid #000;
    padding:6px;
}

table th{
    background:#f3f4f6;
}

</style>

</head>
<body>

<h2>Laporan Stok Barang</h2>

<table>

<thead>
<tr>
    <th>No</th>
    <th>ID Barang</th>
    <th>Nama Barang</th>
    <th>Stok</th>
</tr>
</thead>

<tbody>

@foreach($stokBarang as $i => $item)

<tr>
    <td>{{ $i + 1 }}</td>
    <td>{{ $item->id_barang }}</td>
    <td>{{ $item->nama_barang }}</td>
    <td>{{ $item->stok }}</td>
</tr>

@endforeach

</tbody>

</table>

</body>
</html>
