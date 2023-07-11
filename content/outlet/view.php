<div class="content-header">
    <div>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="#">Menu</a></li>
                <li class="breadcrumb-item"><a href="#">Mitra</a></li>
                <li class="breadcrumb-item active" aria-current="page">Outlet</li>
            </ol>
        </nav>
        <h4 class="content-title">Detail Data - Outlet</h4>
    </div>
</div>
<?php
	$kode	= $secu->injection($_GET['keycode']);
	$read	= $conn->prepare("SELECT A.kode_out, A.nama_out, A.resmi_out, A.npwp_out, A.ofcode_out, A.status_out, B.telp_ola, B.hp_ola, B.fax_ola, B.email_ola, B.picp_ola, B.picpk_ola, B.jatuk_ola, C.parameter_odi, C.diskon1_odi, C.diskon2_odi FROM outlet_b AS A LEFT JOIN outlet_alamat_b AS B ON A.id_out=B.id_out LEFT JOIN outlet_diskon_b AS C ON A.id_out=C.id_out WHERE A.id_out=:kode");
	$read->bindParam(':kode', $kode, PDO::PARAM_STR);
	$read->execute();
	$view	= $read->fetch(PDO::PARAM_STR);
?>
<div class="content-body">
    <?php if (in_array($view['status_out'],['Inactive', 'Revised']) ) { ?>
    <div>
        <div class="row row-sm">
            <div class="col-sm-12 mg-b-25">
                <a href="#modal1" id="btnApprove" onclick="crud('outlet','updateStatusApprove','<?php echo $kode; ?>')" class="btn btn-success btn-xs" data-toggle="modal"><i class="fa fa-check"></i> APPROVE</a>
                <a href="#modal1" id="btnReject" onclick="crud('outlet','updateStatusReject','<?php echo $kode; ?>')" class="btn btn-danger btn-xs" data-toggle="modal"><i class="fa fa-times"></i> REJECT</a>
                <a href="#modal1" id="btnNeedRevision" onclick="crud('outlet','updateStatusRevision','<?php echo $kode; ?>')" class="btn btn-warning btn-xs" data-toggle="modal"><i class="fa fa-edit"></i> NEED REVISION</a>
            </div>
        </div>
    </div>
    <?php } ?>
    <div class="table-responsive">
        <table class="table table-hover mg-b-0">
            <tr>
                <td width="30%">ID</td>
				<td width="5%"><center>:</center></td>
                <td width="65%"><?php echo($view['kode_out']); ?></td>
            </tr>
            <tr>
                <td>Nama</td>
				<td><center>:</center></td>
                <td><?php echo($view['nama_out']); ?></td>
            </tr>
            <tr>
                <td>Nama Resmi</td>
				<td><center>:</center></td>
                <td><?php echo($view['resmi_out']); ?></td>
            </tr>
            <tr>
                <td>Officer Code</td>
				<td><center>:</center></td>
                <td><?php echo($view['ofcode_out']); ?></td>
            </tr>
            <tr>
                <td>NPWP</td>
				<td><center>:</center></td>
                <td><?php echo($view['npwp_out']); ?></td>
            </tr>
            <tr>
                <td>Telp.</td>
				<td><center>:</center></td>
                <td><?php echo($view['telp_ola']); ?></td>
            </tr>
            <tr>
                <td>Mobile / WA</td>
				<td><center>:</center></td>
                <td><?php echo($view['hp_ola']); ?></td>
            </tr>
            <tr>
                <td>Fax</td>
				<td><center>:</center></td>
                <td><?php echo($view['fax_ola']); ?></td>
            </tr>
            <tr>
                <td>Email</td>
				<td><center>:</center></td>
                <td><?php echo($view['email_ola']); ?></td>
            </tr>
            <tr>
                <td>PIC Procurement</td>
				<td><center>:</center></td>
                <td><?php echo($view['picp_ola']); ?></td>
            </tr>
            <tr>
                <td>Telp. PIC Procurement</td>
				<td><center>:</center></td>
                <td><?php echo($view['picpk_ola']); ?></td>
            </tr>
            <tr>
                <td>Jadwal Tukar Faktur</td>
				<td><center>:</center></td>
                <td><?php echo($view['jatuk_ola']); ?></td>
            </tr>
            <tr>
                <td rowspan="2">Info Diskon</td>
				<td rowspan="2"><center>:</center></td>
                <td><?php echo("Pembelian di bawah $view[parameter_odi] diskon $view[diskon1_odi]%"); ?></td>
            </tr>
            <tr>
                <td><?php echo("Pembelian di atas $view[parameter_odi] diskon $view[diskon2_odi]%"); ?></td>
            </tr>
        </table>
    </div>
    <div class="clearfix mg-t-25 mg-b-25"></div>
    <div class="row row-sm">
        <div class="col-sm-12">
            <a href="<?php echo("$sistem/outlet"); ?>" title="Kembai"><button type="button" class="btn btn-secondary btn-xs"><i class="fa fa-chevron-circle-left"></i> Kembali</button></a>
        </div>
    </div>
    
</div>