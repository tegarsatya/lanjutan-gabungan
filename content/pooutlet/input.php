<div class="content-header">
    <div>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="#">Home</a></li>
                <li class="breadcrumb-item">Mitra</li>
                <li class="breadcrumb-item active" aria-current="page">Input Portal Pesanan Produk</li>
            </ol>
        </nav>
        <h4 class="content-title">Input Data - Portal Pesanan Produk</h4>
    </div>
</div>
<!--
<input type="hidden" name="caridata" id="caridata" value="-" readonly="readonly" />
<input type="hidden" name="halaman" id="halaman" value="1" readonly="readonly" />
<input type="hidden" name="maximal" id="maximal" value="15" readonly="readonly" />
-->
<form id="formoutlet" action="#" method="post" autocomplete="off">
<input type="hidden" name="nmenu" id="nmenu" value="pooutlet" readonly="readonly" />
<div class="content-body">
    <div class="component-section no-code">
        <div class="row">
             <div class="form-group col-sm-6">
                <label>Nama Outlet <span class="tx-danger">*</span></label>
                <select name="id_outlet" id="id_outlet" class="form-control select2" onchange="" required="required">
                <option value="">-- Pilih  Outlet--</option>
                <?php
                $master	= $conn->prepare("SELECT id_out, nama_out FROM outlet_b ORDER BY nama_out ASC");                 
                $master->execute();
                  while($hasil= $master->fetch(PDO::FETCH_ASSOC)){
                ?>
                 <option value="<?php echo($hasil['id_out']); ?>"><?php echo($hasil['nama_out'] ); ?></option>
                <?php } ?>
                </select>
            </div>
            <div class="form-group col-md-6">
                    <label>Upload Gambar Po<span class="tx-danger"></span></label>
					<div>
                        <button type="button" class="btn btn-primary" id="tombol1" onclick="namafile('tombol1', 'foto1')"><i class="fa fa-cloud-upload"></i> Upload</button>
                        <input type="file" id="foto1" name="foto1" hidden="hidden" />
					</div>
            </div>
        </div>
        <div class="clearfix mg-t-25 mg-b-25"></div>
        <div class="row">
            <div class="col-sm-12">
                <a href="" title="Batal"><button type="button" class="btn btn-secondary btn-xs">Batal</button></a>
                <button type="submit" id="bsave" class="btn btn-dark btn-xs">Simpan</button>
                <div id="imgloading"></div>
            </div>
		</div>
		</form>
    </div>
</div>