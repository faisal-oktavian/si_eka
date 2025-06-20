<style>
  .container-table{
    margin: 0px 10px 0px 10px;
  }
  .table-responsive{
    font-size: 12px; 
    margin-top: 0px;
  }
  .table{
    overflow-x: scroll;
  }
  thead > tr {
    background-color: #144e7c;
  }
  thead > tr > th {
    color: #ffffff;
    text-align: center;
    font-size: 14px;
  }
  .rak {
    font-weight: bold;
  }
  .provinsi {
    background-color: #a6d7ff;
    color:rgb(0, 0, 0);
  }
  .titik-dua{
    text-align: center;
    width: 20px;
  }
</style>

<!-- filter -->
<?php require_once 'vf_evaluasi_anggaran.php';?>


<!-- data -->
<div class="container-table">
  <div class="table-responsive">
    <table id="selectedColumn" class="table table-hover table-bordered table-sm table-condensed" cellspacing="0" width="100%" data-ordering="false">
      <thead>
        <tr>
          <th class="th-sm rak" colspan="8">RENCANA ANGGARAN KAS <br> SATUAN KERJA PERANGKAT DAERAH</th>
        </tr>
        <tr>
          <th class="th-sm provinsi" colspan="8">PROVINSI JAWA TIMUR <br> TAHUN ANGGARAN <?php echo $tahun_anggaran; ?></th>
        </tr>
      </thead>
      <tbody>
          <?php
            foreach ($arr_data['urusan'] as $key => $value) { // looping urusan
              foreach ($value['bidang_urusan'] as $bu_key => $bu_value) { // looping bidang urusan
                foreach ($bu_value['program'] as $p_key => $p_value) { // looping program
                  foreach ($p_value['kegiatan'] as $k_key => $k_value) { // looping kegiatan
                    foreach ($k_value['sub_kegiatan'] as $sk_key => $sk_value) { // loopping sub kegiatan
                      foreach ($sk_value['paket_belanja'] as $sk_key => $pb_value) { // loopping paket belanja
                      ?>
                        <tr>
                          <td width="150px;">Urusan</td>
                          <td class="titik-dua">:</td>
                          <td colspan="6"><?php echo $value['nama_urusan']; ?></td>
                        </tr>
                        <tr>
                          <td>Bidang Urusan</td>
                          <td class="titik-dua">:</td>
                          <td colspan="6"><?php echo $bu_value['nama_bidang_urusan']; ?></td>
                        </tr>
                        <tr>
                          <td>Program</td>
                          <td class="titik-dua">:</td>
                          <td colspan="6"><?php echo $p_value['nama_program']; ?></td>
                        </tr>
                        <tr>
                          <td>Kegiatan</td>
                          <td class="titik-dua">:</td>
                          <td colspan="6"><?php echo $k_value['nama_kegiatan']; ?></td>
                        </tr>
                        <tr>
                          <td>Sub Kegiatan</td>
                          <td class="titik-dua">:</td>
                          <td colspan="6"><?php echo $sk_value['nama_sub_kegiatan']; ?></td>
                        </tr>
                        <tr>
                          <td>Paket Belanja</td>
                          <td class="titik-dua">:</td>
                          <td colspan="6"><?php echo $pb_value['nama_paket_belanja']; ?></td>
                        </tr>
                        <tr>
                          <td>Jumlah</td>
                          <td class="titik-dua">:</td>
                          <td colspan="6">Rp. <?php echo az_thousand_separator_decimal($pb_value['nilai_anggaran']); ?></td>
                        </tr>

                        <!-- Detail -->
                        <tr>
                          <td style="text-align:center; vertical-align: middle; font-weight:bold;" colspan="2" rowspan="2">Kode Rekening</td>
                          <td style="text-align:center; vertical-align: middle; font-weight:bold; width:auto;" rowspan="2">Uraian</td>
                          <td style="text-align:center; font-weight:bold; width:auto;" colspan="3">Rincian Perhitungan</td>
                          <td style="text-align:center; vertical-align: middle; font-weight:bold; width:180px;" rowspan="2">Jumlah</td>
                          <td style="font-weight:bold; text-align:center; width:70px;"></td>
                        </tr>
                        <tr>
                          <td style="font-weight:bold; text-align:center; width:50px;">Volume</td>
                          <td style="font-weight:bold; text-align:center; width:80px;">Satuan</td>
                          <td style="font-weight:bold; text-align:center; width:130px;">Harga Satuan</td>
                          <td></td>
                        </tr>

                        <?php
                          foreach ($pb_value['akun_belanja'] as $ab_key => $ab_value) {
                        ?>
                            <tr>
                              <td style="font-weight:bold;" colspan="2"><?php echo $ab_value['no_rekening_akunbelanja']; ?></td>
                              <td style="font-weight:bold;"><?php echo $ab_value['nama_akun_belanja']; ?></td>
                              <td></td>
                              <td></td>
                              <td></td>
                              <td style="font-weight:bold;">Rp. <?php // echo ; ?></td>
                              <td></td>
                            </tr>


                            <!-- kategori, cth: hari besar, rapat, diklat, bbm -->
                            <tr>
                              <td colspan="2"><?php // echo ; ?></td>
                              <td><?php // echo ; ?></td>
                              <td><?php // echo ; ?></td>
                              <td><?php // echo ; ?></td>
                              <td><?php // echo ; ?></td>
                              <td><?php // echo ; ?></td>
                              <td style="text-align: center;">
                                <button class="btn btn-default btn-xs btn-view" data_id="488972" data_type="KAS MASUK" data_idtransaction="5245"><span class="glyphicon glyphicon-pencil"></span> Lihat</button>
                              </td>
                            </tr>
                        <?php
                          }
                        ?>

                        <!-- separator -->
                        <tr><td colspan="8" style="padding-top: 10px;"></td></tr>

                      <?php
                      }
                    }
                  }
                }
              }
            }
          ?>
      </tbody>
    </table>
  </div>
</div>