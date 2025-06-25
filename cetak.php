<?php
require_once __DIR__ . '/vendor/autoload.php';
include 'koneksi.php';

$bulan = $_GET['bulan'];
$tahun = $_GET['tahun'];

$query = "SELECT * FROM keuangan WHERE MONTH(waktu) = '$bulan' AND YEAR(waktu) = '$tahun' ORDER BY waktu ASC";
$result = mysqli_query($conn, $query);

$html = '
<h3 style="text-align:center;">Laporan Keuangan Bulan '.date('F', mktime(0,0,0,$bulan,1)).' '.$tahun.'</h3>
<table border="1" cellpadding="5" cellspacing="0" style="width:100%; border-collapse:collapse;">
<thead class="text-center table-primary">
<tr>
  <th>No</th>
  <th>Waktu</th>
  <th>Keterangan</th>
  <th>Pemasukkan</th>
  <th>Pengeluaran</th>
</tr>
</thead>
<tbody class="text-center">';
$no = 1;
$totalMasuk = 0;
$totalKeluar = 0;

while ($row = mysqli_fetch_assoc($result)) {
  $html .= '<tr>
    <td>' . $no++ . '</td>
    <td>' . date('d-m-Y', strtotime($row['waktu'])) . '</td>
    <td>' . htmlspecialchars($row['keterangan']) . '</td>
    <td align="right">Rp ' . number_format($row['pemasukan'], 0, ',', '.') . '</td>
    <td align="right">Rp ' . number_format($row['pengeluaran'], 0, ',', '.') . '</td>
  </tr>';

  $totalMasuk += $row['pemasukan'];
  $totalKeluar += $row['pengeluaran'];
}

$saldo = $totalMasuk - $totalKeluar;

// Total
$html .= '<tr>
  <th class="text-start ps-3" colspan="3">Total</th>
  <th>Rp ' . number_format($totalMasuk, 0, ',', '.') . '</th>
  <th>Rp ' . number_format($totalKeluar, 0, ',', '.') . '</th>
</tr>
<tr>
  <th class="text-start ps-3"colspan="4"> Jumlah Saldo</th>
  <th>Rp ' . number_format($saldo, 0, ',', '.') . '</th>
</tr>';

$html .= '</tbody></table>';

// Buat PDF
$mpdf = new \Mpdf\Mpdf(['format' => 'A4']);
$mpdf->WriteHTML($html);
$mpdf->Output("Laporan_Keuangan_{$bulan}_{$tahun}.pdf", \Mpdf\Output\Destination::INLINE);