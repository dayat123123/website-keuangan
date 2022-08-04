<?php 
include '../koneksi.php';
$rand = rand();
$id  = $_POST['id'];
$tanggal  = $_POST['tanggal'];
$jenis  = $_POST['jenis'];
$kategori  = $_POST['kategori'];
$nominal  = $_POST['nominal'];
$keterangan  = $_POST['keterangan'];
$bank  = $_POST['bank_id'];
$allowed =  array('jpg','jpeg','pdf');
$filename = $_FILES['trnfoto']['name'];
$ext = pathinfo($filename, PATHINFO_EXTENSION);

$transaksi = mysqli_query($koneksi,"select * from transaksi where id='$id'");
$t = mysqli_fetch_assoc($transaksi);
$bank_lama = $t['bank_id'];

$rekening = mysqli_query($koneksi,"select * from bank where bank_id='$bank_lama'");
$r = mysqli_fetch_assoc($rekening);

// Kembalikan nominal ke saldo bank lama

if($t['jenis'] == "Pemasukan"){
	$kembalikan = $r['bank_saldo'] - $t['nominal'];
	mysqli_query($koneksi,"update bank set bank_saldo='$kembalikan' where bank_id='$bank_lama'");

}else if($t['jenis'] == "Pengeluaran"){
	$kembalikan = $r['bank_saldo'] + $t['nominal'];
	mysqli_query($koneksi,"update bank set bank_saldo='$kembalikan' where bank_id='$bank_lama'");

}


if($t['jenis'] == "Pemasukan"){

	$rekening2 = mysqli_query($koneksi,"select * from bank where bank_id='$bank'");
	$rr = mysqli_fetch_assoc($rekening2);
	$saldo_sekarang = $rr['bank_saldo'];
	$total = $saldo_sekarang+$nominal;
	mysqli_query($koneksi,"update bank set bank_saldo='$total' where bank_id='$bank'");

}elseif($t['jenis'] == "Pengeluaran"){

	$rekening2 = mysqli_query($koneksi,"select * from bank where bank_id='$bank'");
	$rr = mysqli_fetch_assoc($rekening2);
	$saldo_sekarang = $rr['bank_saldo'];
	$total = $saldo_sekarang-$nominal;
	mysqli_query($koneksi,"update bank set bank_saldo='$total' where bank_id='$bank'");

}	

if($filename == ""){
	mysqli_query($koneksi, "update transaksi set tanggal ='$_POST[tanggal]',  nominal = '$_POST[nominal]', keterangan = '$_POST[keterangan]', foto = '',
	bank_id = '$_POST[bank_id]',
	kategori_id = '$_POST[kategori_id]' where id='$_POST[id]'") or die(mysqli_error($koneksi));
	header("location:transaksi.php?alert=berhasilupdate");
}else{
	$ext = pathinfo($filename, PATHINFO_EXTENSION);

	if(!in_array($ext,$allowed) ) {
		header("location:transaksi.php?alert=gagal");
	}else{
		move_uploaded_file($_FILES['trnfoto']['tmp_name'], '../gambar/bukti/'.$filename);
		$xgambar = $rand.'_'.$filename;
		mysqli_query($koneksi, "update transaksi set tanggal ='$_POST[tanggal]',  nominal = '$_POST[nominal]', keterangan = '$_POST[keterangan]', foto = '$_POST[keterangan]',
		bank_id = '$_POST[bank_id]',
		kategori_id = '$_POST[kategori_id], where id='$_POST[id]'");
		header("location:transaksi.php?alert=berhasilupdate");
	}
}

// mysqli_query($koneksi, "update transaksi set transaksi_tanggal='$tanggal', transaksi_jenis='$jenis', transaksi_kategori='$kategori', transaksi_nominal='$nominal', transaksi_keterangan='$keterangan', transaksi_bank='$bank' where transaksi_id='$id'") or die(mysqli_error($koneksi));
// header("location:transaksi.php");