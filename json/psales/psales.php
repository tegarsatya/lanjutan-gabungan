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
		$tabel	= '<tr><td colspan="9">Session login anda habis...</td></tr>';
		$navi	= '';
	} else {
		$tabel	= '';
		$no		= $mulai;
		$jumlah	= $conn->query("SELECT COUNT(A.id_tfk) AS total FROM transaksi_faktur_b AS A LEFT JOIN outlet_b AS C ON A.id_out=C.id_out WHERE A.kode_tfk LIKE '%$cari%' OR C.nama_out LIKE '%$cari%'")->fetch(PDO::FETCH_ASSOC);
		$master	= $conn->prepare("SELECT A.id_tfk, A.kode_tfk, A.tgl_tfk, A.tgl_limit, A.total_tfk, A.status_tfk, SUM(B.jumlah_pfk) AS bayar, C.nama_out FROM transaksi_faktur_b AS A LEFT JOIN pembayaran_faktur_b AS B ON A.id_tfk=B.id_tfk LEFT JOIN outlet_b AS C ON A.id_out=C.id_out WHERE A.kode_tfk LIKE '%$cari%' OR C.nama_out LIKE '%$cari%' GROUP BY A.id_tfk ORDER BY A.tgl_tfk DESC LIMIT :mulai, :maxi");
		$master->bindParam(':mulai', $mulai, PDO::PARAM_INT);
		$master->bindParam(':maxi', $maxi, PDO::PARAM_INT);
		$master->execute();
		while($hasil= $master->fetch(PDO::FETCH_ASSOC)){
			$status	= ($hasil['status_tfk']=='Tagihan') ? 'Belum Bayar' : (($hasil['status_tfk']=='Bayar') ? 'Pembayaran Sebagian' : 'Lunas');
			$view	= ($data->akses($admin, $menu, 'A.read_status')==='Active') ? '<a href="#modal1" onclick="crud(\'psales\', \'detail\', \''.$hasil['id_tfk'].'\')" data-toggle="modal"><span class="badge badge-warning"><i class="fa fa-search"></i></span></a>' : '';
			$no++; 
			$tabel	.= '<tr><td><center>'.$no.'</center></td><td>'.$hasil['kode_tfk'].'</td><td>'.$hasil['nama_out'].'</td><td>'.$hasil['tgl_tfk'].'</td><td>'.$hasil['tgl_limit'].'</td><td>'.$data->angka($hasil['total_tfk']).'</td><td>'.$data->angka($hasil['total_tfk'] - $hasil['bayar']).'</td><td>'.$status.'</td><td><center>'.$view.'</center></td></tr>';
		}
		$navi	= $paging->myPaging($menu, $jumlah['total'], $maxi, $page); 
	}
	$conn	= $base->close();
	$json	= array("tabel" => $tabel, "halaman" => $page, "paginasi" => $navi);
	http_response_code(200);
	header('Access-Control-Allow-Origin: *');
	header("Content-type: application/json; charset=utf-8");
	//header('Content-type: text/html; charset=UTF-8');
	echo(json_encode($json));
?>