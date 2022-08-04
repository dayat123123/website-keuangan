<?php 
include '../koneksi.php';
$rand = rand();
$allowed =  array('jpg','jpeg','pdf');
$filename = $_FILES['trnfoto']['name'];
$rekening = mysqli_query($koneksi,"select * from bank where bank_id = '$_POST[bank_id]'");
$r = mysqli_fetch_assoc($rekening);

if($_POST['jenis'] == "Pemasukan"){

	$saldo_sekarang = $r['bank_saldo'];
	$total = $saldo_sekarang+$_POST['nominal'];
	mysqli_query($koneksi,"update bank set bank_saldo='$total' where bank_id = '$_POST[bank_id]'");

}elseif($_POST['jenis'] == "Pengeluaran"){

	$saldo_sekarang = $r['bank_saldo'];
	$total = $saldo_sekarang-$_POST['nominal'];
	mysqli_query($koneksi,"update bank set bank_saldo='$total' where bank_id = '$_POST[bank_id]'");

}

if($filename == ""){
	mysqli_query($koneksi, "insert into transaksi set tanggal ='$_POST[tanggal]', jenis = '$_POST[jenis]', nominal = '$_POST[nominal]', keterangan = '$_POST[keterangan]', foto = '',
bank_id = '$_POST[bank_id]',
kategori_id = '$_POST[kategori_id]'");
	header("location:transaksi.php?alert=berhasil");
}else{
	$ext = pathinfo($filename, PATHINFO_EXTENSION);

	if(!in_array($ext,$allowed) ) {
		header("location:transaksi.php?alert=gagal");
	}else{
		move_uploaded_file($_FILES['trnfoto']['tmp_name'], '../gambar/bukti/'.$filename);
		$file_gambar = $rand.'_'.$filename;
		mysqli_query($koneksi, "insert into transaksi set tanggal ='$_POST[tanggal]', jenis = '$_POST[jenis]', nominal = '$_POST[nominal]', keterangan = '$_POST[keterangan]', foto = '$filename',
bank_id = '$_POST[bank_id]',
kategori_id = '$_POST[kategori_id]'");
		header("location:transaksi.php?alert=berhasil");
	}
}

// mysqli_query($koneksi, "insert into transaksi values (NULL,'$tanggal','$jenis','$kategori','$nominal','$keterangan','$bank')")or die(mysqli_error($koneksi));
// header("location:transaksi.php");