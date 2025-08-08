<style type="text/css">
	#total_transaction {
		text-align: right;
	    font-size: 70px;
	    padding: 48px 10px;
	    background-color: #7ac4ff;
	    color: #000;
	    text-align: right;
	}
	#myModal .label-bold {
		font-size: 20px;
		padding-top: 2px;
	}
	#myModal label {
		padding-top: 13px;
	}
	#total_cash, #lack, #total_debet, #total_credit, #debet_number, #credit_number, #total_pay, #no_transfer, #total_transfer, #total_marketplace, #invoice_marketplace , #total_dp, #total_cicilan, #total_debt{
		font-size: 30px;
		padding: 23px 10px;
		text-align: right;
	}
	.txt-bank {
		font-weight: bold;
		font-size: 14px;
	}
	#debet_number, #credit_number, #no_transfer, #invoice_marketplace {
		text-align: left;
		width: 288px;
	}
	#idedc, #idedc_credit, #idtransfer, #marketplace_name {
		height: 48px;
    	width: 133px;
    	font-size: 16px;
	}
	#idaccount_payment {
		height: 48px;
    	font-size: 16px;
	}
	#idtransfer {
		width: 100%;
	}
	#total_pay {
		background-color: #2095F2;
		color: #fff;
	}
	#lack {
		background-color: #ffc107;
    	color: #fff;
	}
		#lack.pas {
			background-color: #9fef00;
    		color: #000;
		}
	#invoice_marketplace {
		font-size: 18px;
	}

	.form-payment .control-label {
		padding-top: 14px;
	}
	.utang-component{
		display: none;
	}
</style>
<?php 
	$is_comma = 'format-number';
?>
<form class="form-horizontal form-payment">
	<input type="hidden" class="form-control" name="idverification" id="idverification">
	<div class="form-group">
		<label class="control-label col-sm-2 label-bold" style="padding-top:40px;">
			TOTAL
		</label>
		<div class="col-sm-10">
			<input type="text" class="form-control" readonly="" id="total_transaction" name="total_transaction">
		</div>
	</div>
	<!-- <div class="utang-component">
		<input type="hidden" name="total_return" class="form-control" readonly="" id="total_return" style="text-align: right;">
		<div class="form-group">
			<label class="control-label col-sm-2 label-bold">
				TOTAL CICILAN
			</label>
			<div class="col-sm-10">
				<input type="text" name="total_cicilan" class="form-control" readonly="" id="total_cicilan" style="text-align: right;">
			</div>
		</div>
		<div class="form-group">
			<label class="control-label col-sm-2 label-bold">
				TOTAL HUTANG
			</label>
			<div class="col-sm-10">
				<input type="text" name="total_debt" class="form-control" readonly="" id="total_debt" style="text-align: right;">
			</div>
		</div>
	</div> -->
	<!-- <div class="form-group">
		<label class="control-label col-sm-2">
			TUNAI
		</label>
		<div class="col-sm-10">
            <div class="input-group">
                <span class="input-group-addon">Rp</span>
                <input type="text" class="form-control format-number not-marketplace calc" id="total_cash" name="total_cash">
            </div>
		</div>
	</div> -->
	<!-- <div class="form-group">
		<label class="control-label col-sm-2">
			KARTU DEBIT
		</label>
		<div class="col-sm-10">
			<table width="100%">
				<tbody>
					<tr>
						<td>
							<div class="input-group">
								<span class="input-group-addon">Rp</span>
								<input type="text" class="form-control not-marketplace format-number calc" id="total_debet" name="total_debet">
							</div>
						</td>
						<td>&nbsp;</td>
						<td><input type="text" class="form-control not-marketplace" id="debet_number" name="debet_number" placeholder="Nomor Kartu"></td>
					</tr>
				</tbody>
			</table>
		</div>
	</div> -->
	<!-- <div class="form-group">
		<label class="control-label col-sm-2">
			KARTU KREDIT
		</label>
		<div class="col-sm-10">
			<table width="100%">
				<tbody>
					<tr>
						<td>
							<div class="input-group">
								<span class="input-group-addon">Rp</span>
								<input type="text" class="form-control not-marketplace format-number calc" id="total_credit" name="total_credit">
							</div>
						</td>
						<td>&nbsp;</td>
						<td><input type="text" class="form-control not-marketplace" id="credit_number" name="credit_number" placeholder="Nomor Kartu"></td>
					</tr>
				</tbody>
			</table>
		</div>
	</div> -->
	<div class="form-group">
		<label class="control-label col-sm-2">
			TRANSFER
		</label>
		<div class="col-sm-10">
			<table width="100%">
				<tbody>
					<tr>
						<td>
							<div class="input-group">
								<span class="input-group-addon">Rp</span>
								<input type="text" class="form-control not-marketplace format-number calc" id="total_transfer" name="total_transfer">
							</div>
						</td>
						<td>&nbsp;</td>
						<td><input type="text" class="form-control not-marketplace" id="no_transfer" name="no_transfer" placeholder="Nomor Kartu"></td>
					</tr>
				</tbody>
			</table>
		</div>
	</div>
	<div class="form-group">
		<label class="control-label col-sm-2">
			TOTAL BAYAR
		</label>
		<div class="col-sm-10">
			<input type="text" class="form-control format-number" readonly="" id="total_pay" name="total_pay">
		</div>
	</div>
	<div class="form-group">
		<label class="control-label col-sm-2">
			<span id="txt_lack">KEKURANGAN</span>
		</label>
		<div class="col-sm-10">
			<input type="text" class="form-control format-number" readonly="" id="lack" name="lack">
		</div>
	</div>
	<div class="form-group">
		<label class="control-label col-sm-2">
			<span id="description">KETERANGAN</span>
		</label>
		<div class="col-sm-10">
			<textarea class="form-control" id="payment_description" name="payment_description"></textarea>
		</div>
	</div>
	<div class="form-group">
		<label class="control-label col-sm-2">
			<span>TANGGAL BAYAR</span>
		</label>
		<div class="col-sm-10">
			<?php echo $debt_payment_date; ?>
		</div>
	</div>
</form>