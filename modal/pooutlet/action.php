<?php
	require_once('../../config/connection/connection.php');
	require_once('../../config/connection/security.php');
	require_once('../../config/function/data.php');
    require_once('../../config/function/thumb.php');
	$secu	= new Security;
	$base	= new DB;
	$data	= new Data;
    $file	= new File;
	$catat	= date('Y-m-d H:i:s');
    $conn	= $base->open();
	$admin	= $secu->injection(@$_COOKIE['adminkuy']);
	$kunci	= $secu->injection(@$_COOKIE['kuncikuy']);
	$act	= $secu->injection(@$_GET['act']);
	$secu->validadmin($admin, $kunci);
	if ($secu->validadmin($admin, $kunci)==false) {
		header('location:'.$data->sistem('url_sis').'/signout');
	} else {
		$kode		= 'TFK'.time();
		$id_outlet	= $secu->injection($_POST['id_outlet']);
        // gambar
		$loga		= $_FILES["foto1"]['tmp_name'];
		$tiga		= $_FILES["foto1"]['type'];
		$naga		= $_FILES["foto1"]['name'];
		$fofe		= pathinfo($naga, PATHINFO_EXTENSION);
		$unik		= "pooutlet".time().".".$fofe;
		$file->uploadfoto('pooutlet', 'foto1', $unik, 400, 500);

		$statuspo	= "Belum Diprint";
		// /SAVE
		$save	= $conn->prepare("INSERT INTO po_outlet VALUES(:kode, :id_outlet, :unik, :statuspo,  :catat, :admin, :catat, :admin)");
		$save->bindParam(':kode', $kode, PDO::PARAM_STR);
		$save->bindParam(':id_outlet', $id_outlet, PDO::PARAM_STR);
		$save->bindParam(':unik', $unik, PDO::PARAM_STR);
		$save->bindParam(':statuspo', $statuspo, PDO::PARAM_STR);
		$save->bindParam(':catat', $catat, PDO::PARAM_STR);
		$save->bindParam(':admin', $admin, PDO::PARAM_STR);
		$save->execute();			
		//RIWAYAT
		$riwayat= $conn->query("INSERT INTO riwayat VALUES('', '$kode', 'Portal Pesanan Produk', 'Create', '', '$catat', '$admin')");
		$hasil	= ($save==true) ? "success" : "error";
		echo($hasil);
	// break;
	}
	$conn	= $base->close();
?>