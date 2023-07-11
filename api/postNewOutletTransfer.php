<?php
	require_once('../config/connection/connection.php');
	require_once('../config/connection/security.php');
	require_once('../config/function/data.php');
	$secu	= new Security;
	$base	= new DB;
	$data	= new Data;
	$catat	= date('Y-m-d H:i:s');
    $encrypt= $secu->injection(@$_GET['encrypt']);
    $act	= $secu->injection(@$_GET['act']);
	$conn	= $base->open();
    $hasil 	= "Error";
    // checking encrypt
    $source = $data->self_apl();
    $sourceKey  = $source['key_apl'];
    $datas = json_decode(file_get_contents('php://input'), true);
    if (md5(date('Y-m-d') . "#" . $sourceKey) == $encrypt) {
        // save transfer product detail
        $msgBugs = array();
        switch($act){
            case "updateStatusApprove":
                // execute insert
                try {
                    // insert outlet data
                    $outletData = implode("','",array_slice($datas["outlet"],0,14));
                    $outletDataSave = "INSERT INTO outlet_b VALUES('".$outletData."')";
                    $outletDataSave	= $conn->prepare($outletDataSave);
                    $outletDataSave->execute();
                    // insert outlet alamat
                    $outletAlamatData = implode("','",array_slice($datas["outlet"],0,1))."','".implode("','",array_slice($datas["outlet"],15,17))."','".implode("','",array_slice($datas["outlet"],10,4));
                    $outletAlamatDataSave = "INSERT INTO outlet_alamat_b VALUES('', '".$outletAlamatData."')";
                    $outletAlamatDataSave	= $conn->prepare($outletAlamatDataSave);
                    $outletAlamatDataSave->execute();
                    // insert outlet diskon
                    foreach ($datas["discount"] as $key => $value) {
                        $outletDiskonData = implode("','",array_slice($value,1,10));
                        $outletDiskonDataSave = "INSERT INTO outlet_diskon_b VALUES('', '".$outletDiskonData."')";
                        $outletDiskonDataSave = $conn->prepare($outletDiskonDataSave);
                        $outletDiskonDataSave->execute();
                    }
                    // insert outlet legal
                    foreach ($datas["legal"] as $key => $value) {
                        $outletLegalData = implode("','",array_slice($value,1,8));
                        $outletLegalDataSave = "INSERT INTO outlet_legal_b VALUES('', '".$outletLegalData."')";
                        $outletLegalDataSave = $conn->prepare($outletLegalDataSave);
                        $outletLegalDataSave->execute();
                    }
                } catch (PDOException $e) {
                    array_push($msgBugs, $e->getMessage());
                }
                break;
            default:
                $hasil = "Error";
                http_response_code(500);
                break;
        }
    } else {
        $hasil = "Unauthorized";
        http_response_code(401);
    }
    // check error
    if (empty($msgBugs)) {
        $hasil = "Success";
        http_response_code(200);
    } else {
        $hasil = implode(", ",$msgBugs);
        http_response_code(500);
    }
	$conn	= $base->close();
	header('Access-Control-Allow-Origin: *');
	header("Content-type: application/json; charset=utf-8");
	echo json_encode(array("result" => $hasil));
?>