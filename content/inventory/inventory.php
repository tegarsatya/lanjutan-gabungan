<div class="content-header">
    <div>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="#">Menu</a></li>
                <li class="breadcrumb-item active" aria-current="page">Inventory</li>
            </ol>
        </nav>
        <h4 class="content-title">Inventory</h4>
    </div>
</div>
<?php $cari = $secu->injection(@$_GET['cari']); ?>
<input type="hidden" name="caridata" id="caridata" value="<?php echo($cari); ?>" readonly="readonly" />
<input type="hidden" name="cariitem" id="halaman" value="1" readonly="readonly" />
<input type="hidden" name="maximal" id="maximal" value="15" readonly="readonly" />

<div class="content-body">
    <div class="row mg-b-10">
        <div class="col-sm-6">
            <a href="#modal1" onclick="<?php echo("caridata('caridata', 'inventory', '$cari')"); ?>" data-toggle="modal"><button class="btn btn-warning btn-pill btn-xs"><i class="fa fa-search"></i> Cari Data</button></a>
            
            <a href="<?php echo("$sistem/inventory"); ?>"><button class="btn btn-info btn-pill btn-xs"><i class="fa fa-spinner"></i> Refresh</button></a>
            <?php echo(($data->akses($admin, $menu, 'A.read_status')==='Active') ? '<a target="_blank" href="'.$sistem.'/laporan/xls/inventory/inventory.php?key='.$cari.'" title="XLS"><button class="btn btn-success btn-pill btn-xs"><i class="fa fa-print"></i> XLS</button></a>' : ''); ?>
            <?php echo(($data->akses($admin, $menu, 'A.read_status')==='Active') ? '<a target="_blank" href="'.$sistem.'/laporan/xls/inventory/inventorya.php?key='.$cari.'" title="Analisa Stok"><button class="btn btn-success btn-pill btn-xs"><i class="fa fa-print"></i> Analisa Stok</button></a>' : ''); ?>
            <?php echo(($data->akses($admin, $menu, 'A.create_status')==='Active') ? '<a href="'.$sistem.'/transferstok/i"><button class="btn btn-primary btn-pill btn-xs"><i class="fa fa-plus-circle"></i> Transfer Stok</button></a>' : ''); ?>
        </div>
        <div class="col-sm-6">
            <span class="badge badge-pill badge-danger"><i class="fa fa-search"></i> Search : <?php echo($cari); ?></span>
        </div>
    </div>
    <div class="table-responsive">
        <table class="table table-hover mg-b-0">
            <thead>
                <tr>
                    <th><center>#</center></th>
                    <th>Nama Produk</th>
                    <th>Gudang</th>
                    <th>Ukuran</th>
                    <th>No. Batch</th>
                    <th>ED</th>
                    <th><div align="right">Rentang Waktu</div></th>
                    <th><div align="right">Harga</div></th>
                    <th><div align="right">Harga + PPN</div></th>
                    <th><div align="right">Kuantitas</div></th>
                    <th><center>Minimal Stok Produk</center></th>
                    <th>Keterangan</th>
                </tr>
            </thead>
            <tbody id="isitabel"></tbody>
        </table>
        <!--
        <div class="mg-t-10">
            <nav aria-label="Page navigation example">
                <ul class="pagination pagination-circle mg-b-0" id="paginasi"></ul>
            </nav>
        </div>
        -->
    </div>
</div>