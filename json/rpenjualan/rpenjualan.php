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
	$tanggal= date('Y-m-d');
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
		$tabel	= '<tr><td colspan="3">Session login anda habis...</td></tr>';
		$navi	= '';
	} else {
		$pecah	= explode('_', $cari);
		$outlet	= empty($pecah[0]) ? "" : "AND B.id_out='$pecah[0]'"; 
		$produk	= empty($pecah[1]) ? "" : "AND A.id_pro='$pecah[1]'"; 
		$tgl1	= empty($pecah[2]) ? "" : "AND B.tgl_tfk>='$pecah[2]'"; 
		$tgl2	= empty($pecah[3]) ? "" : "AND B.tgl_tfk<='$pecah[3]'"; 
		$tabel	= '';
		$no		= $mulai;
		$jumlah	= $conn->query("SELECT COUNT(A.id_tfd) AS total FROM transaksi_fakturdetail_b AS A LEFT JOIN transaksi_faktur_b AS B ON A.id_tfk=B.id_tfk WHERE A.id_tfd!='' $outlet $produk $tgl1 $tgl2")->fetch(PDO::FETCH_ASSOC);
		$master	= $conn->prepare("SELECT A.jumlah_tfd, A.harga_tfd, A.diskon_tfd, A.total_tfd, B.kode_tfk, B.tgl_tfk, B.po_tfk, B.tglpo_tfk, B.tgl_limit, B.status_tfk, TIMESTAMPDIFF(DAY, B.tgl_limit, :tanggal) AS jarak, C.nama_pro, C.kode_pro, D.nama_out, D.ofcode_out, E.no_bcode, E.tgl_expired FROM transaksi_fakturdetail_b AS A LEFT JOIN transaksi_faktur_b AS B ON A.id_tfk=B.id_tfk LEFT JOIN produk_b AS C ON A.id_pro=C.id_pro LEFT JOIN outlet_b AS D ON B.id_out=D.id_out LEFT JOIN produk_stokdetail_b AS E ON A.id_psd=E.id_psd WHERE A.id_tfd!='' $outlet $produk $tgl1 $tgl2 ORDER BY B.tgl_tfk DESC, B.kode_tfk DESC LIMIT :mulai, :maxi");
		$master->bindParam(':mulai', $mulai, PDO::PARAM_INT);
		$master->bindParam(':maxi', $maxi, PDO::PARAM_INT);
		$master->bindParam(':tanggal', $tanggal, PDO::PARAM_STR);
		$master->execute();
		while($hasil= $master->fetch(PDO::FETCH_ASSOC)){
			$no++; 
			$status	= ($hasil['status_tfk']=='Tagihan') ? 'Belum Bayar' : (($hasil['status_tfk']=='Bayar') ? 'Pembayaran Sebagian' : 'Lunas');
			$tabel	.= '<tr><td><center>'.$no.'</center></td><td><center>'.$hasil['tglpo_tfk'].'</center></td><td>'.$hasil['po_tfk'].'</td><td><center>'.$hasil['tgl_tfk'].'</center></td><td>'.$hasil['kode_tfk'].'</td><td>'.$hasil['nama_out'].'</td><td>'.$hasil['ofcode_out'].'</td><td>'.$hasil['nama_pro'].'</td><td>'.$hasil['kode_pro'].'</td><td>'.$hasil['no_bcode'].'</td><td>'.$hasil['tgl_expired'].'</td><td>'.$data->angka($hasil['jumlah_tfd']).'</td><td>'.$data->angka($hasil['harga_tfd']).'</td><td>'.$hasil['diskon_tfd'].'</td><td>'.$data->angka($hasil['total_tfd']).'</td><td><center>'.$hasil['tgl_limit'].'</center></td><td><center>'.$hasil['jarak'].'</center></td><td><center>'.$status.'</center></td></tr>';
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