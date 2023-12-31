<?php
	require_once('../../config/connection/connection.php');
	require_once('../../config/connection/security.php');
	require_once('../../config/function/data.php');
	require_once('../../config/function/paging.php');
	$base	= new DB;
	$secu	= new Security;
	$data	= new Data;
	$paging	= new Paging;
	$conn	= $base->open();
	//ACCESS DATA
	$admin	= $secu->injection(@$_COOKIE['adminkuy']);
	$kunci	= $secu->injection(@$_COOKIE['kuncikuy']);
	$level	= $secu->injection(@$_COOKIE['jeniskuy']);
	$valid	= $secu->validadmin($admin, $kunci);
	//POST DATA
	$cari	= $secu->injection(@$_GET['caridata']);
	$page	= $secu->injection(@$_GET['halaman']);
	$maxi	= $secu->injection(@$_GET['maximal']);
	$menu	= $secu->injection(@$_GET['menudata']);
	$mulai	= ($page>1) ? (($page * $maxi) - $maxi) : 0;
	//READ DATA
	if($valid==false){
		$tabel	= '<tr><td colspan="6">Session login anda habis...</td></tr>';
		$navi	= '';
	} else {
		$pecah	= explode('_', $cari);
		$search	= @$pecah[0];
		$tgl1	= @$pecah[1];
		$tgl2	= @$pecah[2];
		if(empty($tgl1) || empty($tgl2)){
			$jumlah	= 0;
			$tabel	= '<tr><td colspan="6">Pilih periode tanggal...</td></tr>';
			$navi	= '';
		} else {
			$tabel	= '';
			$no		= $mulai;
			$jumlah	= $conn->query("SELECT COUNT(id_pro) AS total FROM produk_b WHERE nama_pro LIKE '%$search%'")->fetch(PDO::FETCH_ASSOC);
			$master	= $conn->prepare("SELECT id_pro, nama_pro FROM produk_b WHERE nama_pro LIKE '%$search%' ORDER BY nama_pro ASC LIMIT :mulai, :maxi");
			$master->bindParam(':mulai', $mulai, PDO::PARAM_INT);
			$master->bindParam(':maxi', $maxi, PDO::PARAM_INT);
			$master->execute();
			while($hasil= $master->fetch(PDO::FETCH_ASSOC)){
				$awal	= $data->stokawal($hasil['id_pro'], $tgl1);
				$in		= $data->stokin($hasil['id_pro'], $tgl1, $tgl2);
				$out	= $data->stokout($hasil['id_pro'], $tgl1, $tgl2);
				$akhir	= ($awal + $in) - $out;
			
				$no++; 
				$tabel	.= '<tr><td><center>'.$no.'</center></td><td>'.$hasil['nama_pro'].'</td><td>'.$awal.'</td><td>'.$in.'</td><td>'.$out.'</td><td>'.$akhir.'</td></tr>';
			}
			$navi	= $paging->myPaging($menu, $jumlah['total'], $maxi, $page); 
		}
	}
	$conn	= $base->close();
	$json	= array("tabel" => $tabel, "halaman" => $page, "paginasi" => $navi);
	http_response_code(200);
	header('Access-Control-Allow-Origin: *');
	header("Content-type: application/json; charset=utf-8");
	//header('Content-type: text/html; charset=UTF-8');
	echo(json_encode($json));
?>