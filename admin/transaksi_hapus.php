<?php 
include '../koneksi.php';
$id  = $_GET['id'];

$transaksi = mysqli_query($koneksi,"select * from transaksi where id='$id'");
$t = mysqli_fetch_assoc($transaksi);
$bank_lama = $t['bank_id'];

$rekening = mysqli_query($koneksi,"select * from bank where bank_id='$bank_lama'");
$r = mysqli_fetch_assoc($rekening);

if($t['jenis'] == "Pemasukan"){
	$kembalikan = $r['bank_saldo'] - $t['nominal'];
	mysqli_query($koneksi,"update bank set bank_saldo='$kembalikan' where bank_id='$bank_lama'");

}else if($t['jenis'] == "Pengeluaran"){
	$kembalikan = $r['bank_saldo'] + $t['nominal'];
	mysqli_query($koneksi,"update bank set bank_saldo='$kembalikan' where bank_id='$bank_lama'");

}


mysqli_query($koneksi, "delete from transaksi where id='$id'");
header("location:transaksi.php");