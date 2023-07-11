<?php
	require_once('../../config/connection/connection.php');
	require_once('../../config/connection/security.php');
	require_once('../../config/function/data.php');
	$secu	= new Security;
	$base	= new DB;
	$data	= new Data;
	$sistem	= $data->sistem('url_sis');
	$catat	= date('Y-m-d H:i:s');
	$admin	= $secu->injection(@$_COOKIE['adminkuy']);
	$kunci	= $secu->injection(@$_COOKIE['kuncikuy']);
	$kode	= $secu->injection(@$_POST['x']);
	$conn	= $base->open();
	//READ DATA
	$read	= $conn->prepare("SELECT B.kode_kot, C.top_odi, C.parameter_odi, C.diskon1_odi, C.diskon2_odi FROM outlet_b AS A LEFT JOIN kategori_outlet_b AS B ON A.id_kot=B.id_kot LEFT JOIN outlet_diskon_b AS C ON A.id_out=C.id_out WHERE A.id_out=:kode");
	$read->bindParam(':kode', $kode, PDO::PARAM_STR);
	$read->execute();
	$view	= $read->fetch(PDO::FETCH_ASSOC);
	$gabung	= 'B/SJ/'.$view['kode_kot'].'/'.$data->romawi(date('m')).'/'.date('y');
	$gobong	= 'B/FKT/'.$view['kode_kot'].'/'.$data->romawi(date('m')).'/'.date('y');
	$inv	= $data->transcode($gabung, 'sj_tfk', 'transaksi_faktur_b');
	$fak	= $data->transcode($gobong, 'kode_tfk', 'transaksi_faktur_b');
	$limit	= date("Y-m-d", strtotime("+$view[top_odi] Days", strtotime($catat)));
	$conn	= $base->close();
	$json	= array("koout" => $inv, "fkout" => $fak, "minorder" => $view['parameter_odi'], "diskon1" => $view['diskon1_odi'], "diskon2" => $view['diskon2_odi'], "jatuhtempo" => $limit);
	http_response_code(200);
	header('Access-Control-Allow-Origin: *');
	header("Content-type: application/json; charset=utf-8");
	//header('Content-type: text/html; charset=UTF-8');
	echo(json_encode($json));
?>