<div class="row">
    <div class="col-md-12">
        <table>
            <tr>
                <td>Nomor Dokumen</td>
                <td>&nbsp; : &nbsp;</td>
                <td><?php echo $payment->row()->verification_code; ?></td>
            </tr>
        </table>
    </div>
    <div class="col-md-12">
        <table class="table table-bordered">
            <tr>
                <th width="auto">Tanggal Pembayaran</th>
                <th width="140px">Tunai</th>
                <th width="140px">Kredit</th>
                <th width="140px">Debet</th>
                <th width="140px">Transfer</th>
                <th width="140px">Total</th>
            </tr>
            <tr>
                <td>
                    <?php echo $payment->row()->txt_confirm_payment_date; ?>
                </td>
                <td align="right">
                    <?php echo "Rp. " . az_thousand_separator($payment->row()->total_cash) . ",-"; ?>
                </td>
                <td align="right">
                    <?php echo "Rp. " . az_thousand_separator($payment->row()->total_credit) . ",-"; ?>
                </td>
                <td align="right">
                    <?php echo "Rp. " . az_thousand_separator($payment->row()->total_debet) . ",-"; ?>
                </td>
                <td align="right">
                    <?php echo "Rp. " . az_thousand_separator($payment->row()->total_transfer) . ",-"; ?>
                </td>
                <td align="right">
                    <?php echo "Rp. " . az_thousand_separator($payment->row()->total_pay) . ",-"; ?>
                </td>
            </tr>
        </table>
    </div>
</div>