<table class="table" style="border-color:#efefef; margin:0px;" width="100%" border="1">
    <tr>
        <th width="25%">Nomor Kontrak Pengadaan</th>
        <th width="35%">Nama Paket Belanja</th>
        <th width="35%">Uraian</th>
        <th width="5%">Volume</th>
    </tr>
        
    <?php
        foreach ((array) $verification->result_array() as $key => $value) {
    ?>
            <tr>
                <td><?php echo $value['contract_code'];?></td>
                <td><?php echo $value['nama_paket_belanja'];?></td>
                <td><?php echo $value['nama_sub_kategori'];?></td>
                <td align="center"><?php echo az_thousand_separator($value['volume']);?></td>
            </tr>
    <?php
        }
    ?>
</table>