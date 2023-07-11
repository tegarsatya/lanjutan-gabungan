<?php
	require_once('../../config/connection/connection.php');
	require_once('../../config/connection/security.php');
	require_once('../../config/function/data.php');
	$secu	= new Security;
	$base	= new DB;
	$data	= new Data;
	$conn	= $base->open();
	$menu	= $secu->injection(@$_POST['menu']);
	$cari	= $secu->injection(@$_POST['cari']);
	$pecah	= explode('_', $cari);
	$sistem	= $data->sistem('url_sis');
?>
   <div class="modal-header">
        <h6 class="modal-title" id="exampleModalLabel">Pencarian</h6>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true"><i class="fa fa-times-circle"></i></span>
        </button>
    </div>
    <div class="modal-body">
        <div class="row">
            <div class="form-group col-md-12">
                <label>Outlet <span class="tx-danger">*</span></label>
                <select type="text" name="outlet" id="outlet" class="form-control" required="required">
            		<option value="">-- Pilih Outlet --</option>
				<?php
					$master	= $conn->prepare("SELECT id_out, kode_out, nama_out FROM outlet_b ORDER BY nama_out ASC");
					$master->execute();
					while($hasil= $master->fetch(PDO::FETCH_ASSOC)){
						$pilih	= (@$pecah[0]==$hasil['id_out']) ? 'selected="selected"' : '';
				?>
                	<option value="<?php echo($hasil['id_out']); ?>" <?php echo($pilih); ?>><?php echo("$hasil[kode_out] - $hasil[nama_out]"); ?></option>
                <?php } ?>
                </select>
            </div>
        </div>
        <div class="row">
            <div class="form-group col-md-6">
                <label>Periode I <span class="tx-danger">*</span></label>
                <input type="text" name="tgl1" id="tgl1" class="form-control fortgl" value="<?php echo(@$pecah[1]); ?>" placeholder="9999-99-99" required="required" />
            </div>
            <div class="form-group col-md-6">
                <label>Priode II <span class="tx-danger">*</span></label>
                <input type="text" name="tgl2" id="tgl2" class="form-control fortgl" value="<?php echo(@$pecah[2]); ?>" placeholder="9999-99-99" required="required" />
            </div>
        </div>
    </div>
    <div class="modal-footer">
        <button type="button" class="btn btn-secondary btn-xs" data-dismiss="modal">Batal</button>
        <button type="button" id="btncari" class="btn btn-dark btn-xs">Cari</button>
        <div id="imgloading"></div>
    </div>
<?php
	$conn	= $base->close();
?>
	<script type="text/javascript">
	$(".fortgl").mask("9999-99-99");

	$("#btncari").click(function(){
		var	outlet	= $("#outlet").val();
		var tgl1	= $("#tgl1").val();
		var tgl2	= $("#tgl2").val();
		var gabung	= "cari="+outlet+"_"+tgl1+"_"+tgl2;
		window.location.href = "<?php echo($data->sistem('url_sis')."/$menu/"); ?>"+gabung;
	});
    </script>