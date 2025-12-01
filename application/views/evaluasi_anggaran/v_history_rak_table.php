<style>
    thead > tr {
        background-color: #144e7c;
    }
    thead > tr > th {
        color: #ffffff;
        text-align: center;
        font-size: 14px;
        vertical-align: middle !important;
    }  
    .modal-lg {
        width:auto !important;
    }
    .table-responsive{
        font-size: 12px; 
        margin-top: 0px;
        overflow-x: auto !important;
        position: relative;
        width: 100%;
        /* min-height: 400px; jika perlu */
    }
    table {
        border-collapse: separate !important;
        /* Agar sticky tidak bug di beberapa browser */
    }

    /* Border kanan untuk kolom terakhir di thead dan tbody */
    th:last-child, td:last-child {
        border-right: 1px solid #dee2e6 !important;
    }

    /* Freeze kolom uraian */
    th.col-uraian, td.col-uraian {
        position: sticky !important;
        left: 0;
        background: #fff;
        z-index: 2;
        box-shadow: 2px 0 2px -1px #ccc;
    }
    th.col-uraian {
        z-index: 3; /* Supaya header di atas cell */
    }

    th.col-uraian-title {
        background: #144e7c !important;
        color: #fff !important;
    }
    th.col-month, td.col-month { 
        width: 150px; 
        min-width: 150px;
        text-align: center;
        font-size: 14px;
    }
</style>

<table id="selectedColumn" class="table table-hover table-bordered table-sm table-condensed" cellspacing="0" width="100%" data-ordering="false">
    <thead>
        <tr>
            <th rowspan="2" class="col-uraian col-uraian-title">Uraian</th>
            <th colspan="2" class="col-month">Januari</th>
            <th colspan="2" class="col-month">Februari</th>
            <th colspan="2" class="col-month">Maret</th>
            <th colspan="2" class="col-month">April</th>
            <th colspan="2" class="col-month">Mei</th>
            <th colspan="2" class="col-month">Juni</th>
            <th colspan="2" class="col-month">Juli</th>
            <th colspan="2" class="col-month">Agustus</th>
            <th colspan="2" class="col-month">September</th>
            <th colspan="2" class="col-month">Oktober</th>
            <th colspan="2" class="col-month">November</th>
            <th colspan="2" class="col-month">Desember</th>
        </tr>
        <tr>
            <th class="col-month">RAK</th>
            <th class="col-month">Status Realisasi</th>
            <th class="col-month">RAK</th>
            <th class="col-month">Status Realisasi</th>
            <th class="col-month">RAK</th>
            <th class="col-month">Status Realisasi</th>
            <th class="col-month">RAK</th>
            <th class="col-month">Status Realisasi</th>
            <th class="col-month">RAK</th>
            <th class="col-month">Status Realisasi</th>
            <th class="col-month">RAK</th>
            <th class="col-month">Status Realisasi</th>
            <th class="col-month">RAK</th>
            <th class="col-month">Status Realisasi</th>
            <th class="col-month">RAK</th>
            <th class="col-month">Status Realisasi</th>
            <th class="col-month">RAK</th>
            <th class="col-month">Status Realisasi</th>
            <th class="col-month">RAK</th>
            <th class="col-month">Status Realisasi</th>
            <th class="col-month">RAK</th>
            <th class="col-month">Status Realisasi</th>
            <th class="col-month">RAK</th>
            <th class="col-month">Status Realisasi</th>
        </tr>
    </thead>
    <tbody>
        <?php
            foreach ($arr_data as $key => $value) {
                
                $bold = '';
                if ($value['is_bold'] == 1) {
                    $bold = 'style="font-weight:bold;" ';
                }


                // cek apakah value termasuk kategori atau subkategori
                if (strlen($value['idkategori']) > 0) {
        ?>
                    <tr>
                        <td class="col-uraian" <?php echo $bold; ?> ><?php echo $value['nama_kategori']; ?></td>

                        <td class="col-month"><?php echo $value['rak_januari']; ?></td>
                        <td class="col-month"><?php echo $value['januari']; ?></td>

                        <td class="col-month"><?php echo $value['rak_februari']; ?></td>
                        <td class="col-month"><?php echo $value['februari']; ?></td>

                        <td class="col-month"><?php echo $value['rak_maret']; ?></td>
                        <td class="col-month"><?php echo $value['maret']; ?></td>
                        
                        <td class="col-month"><?php echo $value['rak_april']; ?></td>
                        <td class="col-month"><?php echo $value['april']; ?></td>

                        <td class="col-month"><?php echo $value['rak_mei']; ?></td>
                        <td class="col-month"><?php echo $value['mei']; ?></td>

                        <td class="col-month"><?php echo $value['rak_juni']; ?></td>
                        <td class="col-month"><?php echo $value['juni']; ?></td>
                        
                        <td class="col-month"><?php echo $value['rak_juli']; ?></td>
                        <td class="col-month"><?php echo $value['juli']; ?></td>
                        
                        <td class="col-month"><?php echo $value['rak_agustus']; ?></td>
                        <td class="col-month"><?php echo $value['agustus']; ?></td>
                        
                        <td class="col-month"><?php echo $value['rak_september']; ?></td>
                        <td class="col-month"><?php echo $value['september']; ?></td>
                        
                        <td class="col-month"><?php echo $value['rak_oktober']; ?></td>
                        <td class="col-month"><?php echo $value['oktober']; ?></td>
                        
                        <td class="col-month"><?php echo $value['rak_november']; ?></td>
                        <td class="col-month"><?php echo $value['november']; ?></td>
                        
                        <td class="col-month"><?php echo $value['rak_desember']; ?></td>
                        <td class="col-month"><?php echo $value['desember']; ?></td>
                    </tr>
        <?php
                }
                else {
        ?>
                    <tr>
                        <td class="col-uraian" <?php echo $bold; ?> ><?php echo $value['nama_subkategori']; ?></td>
                        
                        <td class="col-month"><?php echo $value['rak_januari']; ?></td>
                        <td class="col-month"><?php echo $value['januari']; ?></td>

                        <td class="col-month"><?php echo $value['rak_februari']; ?></td>
                        <td class="col-month"><?php echo $value['februari']; ?></td>

                        <td class="col-month"><?php echo $value['rak_maret']; ?></td>
                        <td class="col-month"><?php echo $value['maret']; ?></td>
                        
                        <td class="col-month"><?php echo $value['rak_april']; ?></td>
                        <td class="col-month"><?php echo $value['april']; ?></td>

                        <td class="col-month"><?php echo $value['rak_mei']; ?></td>
                        <td class="col-month"><?php echo $value['mei']; ?></td>

                        <td class="col-month"><?php echo $value['rak_juni']; ?></td>
                        <td class="col-month"><?php echo $value['juni']; ?></td>
                        
                        <td class="col-month"><?php echo $value['rak_juli']; ?></td>
                        <td class="col-month"><?php echo $value['juli']; ?></td>
                        
                        <td class="col-month"><?php echo $value['rak_agustus']; ?></td>
                        <td class="col-month"><?php echo $value['agustus']; ?></td>
                        
                        <td class="col-month"><?php echo $value['rak_september']; ?></td>
                        <td class="col-month"><?php echo $value['september']; ?></td>
                        
                        <td class="col-month"><?php echo $value['rak_oktober']; ?></td>
                        <td class="col-month"><?php echo $value['oktober']; ?></td>
                        
                        <td class="col-month"><?php echo $value['rak_november']; ?></td>
                        <td class="col-month"><?php echo $value['november']; ?></td>
                        
                        <td class="col-month"><?php echo $value['rak_desember']; ?></td>
                        <td class="col-month"><?php echo $value['desember']; ?></td>
                    </tr>
        <?php
                }
            }
        ?>
    </tbody>
</table>