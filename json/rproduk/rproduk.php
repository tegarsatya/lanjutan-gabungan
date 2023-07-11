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
		$tgl1	= empty(@$pecah[1]) ? "" : "AND B.tgl_tfk>='$pecah[1]'";
		$tgl2	= empty(@$pecah[2]) ? "" : "AND B.tgl_tfk<='$pecah[2]'";
		if(empty(@$pecah[1]) or empty(@$pecah[2])){
			$tabel	= '<tr><td colspan="8">Pilih periode tanggal...</td></tr>';
			$navi	= '';
		} else {
			$tabel	= '';
			$no		= $mulai;
			$jumlah	= $conn->query("SELECT COUNT(A.id_pro) AS total FROM transaksi_fakturdetail_b AS A LEFT JOIN transaksi_faktur_b AS B ON A.id_tfk=B.id_tfk LEFT JOIN produk_b AS C ON A.id_pro=C.id_pro LEFT JOIN satuan_produk AS D ON C.id_spr=D.id_spr LEFT JOIN kategori_produk_b AS E ON C.id_kpr=E.id_kpr WHERE (C.nama_pro LIKE '%$search%' OR C.kode_pro LIKE '%$search%') $tgl1 $tgl2 GROUP BY C.id_pro")->fetch(PDO::FETCH_ASSOC);
			$master		= $conn->prepare("SELECT A.id_pro, C.nama_pro, C.kode_pro, SUM(A.jumlah_tfd) AS total, D.nama_spr, E.nama_kpr FROM transaksi_fakturdetail_b AS A LEFT JOIN transaksi_faktur_b AS B ON A.id_tfk=B.id_tfk LEFT JOIN produk_b AS C ON A.id_pro=C.id_pro LEFT JOIN satuan_produk AS D ON C.id_spr=D.id_spr LEFT JOIN kategori_produk_b AS E ON C.id_kpr=E.id_kpr WHERE (C.nama_pro LIKE '%$search%' OR C.kode_pro LIKE '%$search%') $tgl1 $tgl2 GROUP BY C.id_pro ORDER BY SUM(A.jumlah_tfd) DESC LIMIT :mulai, :maxi");
			$master->bindParam(':mulai', $mulai, PDO::PARAM_INT);
			$master->bindParam(':maxi', $maxi, PDO::PARAM_INT);
			$master->execute();
			while($hasil= $master->fetch(PDO::FETCH_ASSOC)){
				$no++;
				$tabel	.= '<tr><td><center>'.$no.'</center></td><td>'.$hasil['nama_pro'].'</td><td>'.$hasil['kode_pro'].'</td><td>'.$hasil['nama_kpr'].'</td><td>'.$hasil['nama_spr'].'</td><td><div align="right">'.$data->angka($hasil['total']).'</div></td></tr>';
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