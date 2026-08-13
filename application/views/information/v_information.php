<form class="form-horizontal" name="form_information" id="form_information">
	<?php
	foreach ($information->result() as $key => $value) {
	?>
		<div class="form-group">
			<label class="control-label col-md-2"><?php echo azlang(ucfirst(str_replace('_', ' ', $value->key))); ?></label>
			<div class="col-md-6">
				<?php
					if ($value->type == 'text') {
				?>
						<input type="text" class="form-control" value="<?php echo $value->value; ?>" name="<?php echo $value->key; ?>" />
				<?php
					} 
					else if ($value->type == 'textarea') {
				?>
						<textarea rows="7" class="form-control" name="<?php echo $value->key; ?>"><?php echo $value->value; ?></textarea>
				<?php
					} 
					else if ($value->type == 'select') {
				?>
						<select class="form-control select" name="<?php echo $value->key; ?>" id="<?php echo $value->key; ?>">
							<?php
								if ($value->key == 'provinsi') {
									foreach ($provinsi as $p_key => $p_value) {
										$sel = '';
										if ($p_value['province_id'] == $value->value) {
											$sel = 'selected';
										}
										echo "<option " . $sel . " value='" . $p_value['province_id'] . "'>" . $p_value['province'] . "</option>";
									}
								} 
								else if ($value->key == 'kota') {
							?>
									<input type="hidden" id="helper_kota" value="<?php echo $value->value; ?>">
							<?php
							}
							?>
						</select>
				<?php
					} 
					else if ($value->type == 'button_apbd') {
						if ($value->value == '0') {
							// APBD murni
				?>
							<button class="btn btn-danger btn-apbd" id="btn_apbd" type="button" data-value="1" data-type="lock" name="<?php echo $value->key; ?>">
								<?php echo azlang('Kunci APBD'); ?>
							</button>

							<div>
								<p style="color:red; font-weight:bold;">Paket belanja saat ini membaca data APBD (APBD MURNI)</p>
							</div>
				<?php
						} 
						else if ($value->value == '1') {
							// PAPBD
				?>			
							<button class="btn btn-success btn-apbd" id="btn_apbd" type="button" data-value="0" data-type="unlock" name="<?php echo $value->key; ?>">
								<?php echo azlang('Buka Kunci APBD'); ?>
							</button>
							<!-- <button class="btn btn-danger btn-apbd" id="btn_apbd" type="button" data-value="2" data-type="lock" name="<?php echo $value->key; ?>">
								<?php // echo azlang('Kunci APBD PERUBAHAN'); ?>
							</button> -->

							<div>
								<p style="color:red; font-weight:bold;">Paket belanja saat ini membaca data PAPBD</p>
							</div>
				<?php
						}
						// else if ($value->value == '2') {
							// PAPBD Perubahan
				?>			
							<!-- <button class="btn btn-success btn-apbd" id="btn_apbd" type="button" data-value="1" data-type="unlock" name="<?php echo $value->key; ?>">
								<?php // echo azlang('Buka Kunci APBD PERUBAHAN'); ?>
							</button>

							<div>
								<p style="color:red; font-weight:bold;">Paket belanja saat ini membaca data PAPBD (Perubahan)</p>
							</div> -->
				<?php
						// }
				?>
						
				<?php
					}
					
					if ($value->type == 'time') {
				?>
						<div class="input-group az-datetime">
							<input type="text" class="form-control time <?php echo $value->key; ?>" id="<?php echo $value->key; ?>" name="<?php echo $value->key; ?>" value="<?php echo $value->value; ?>" />
							<span class="input-group-addon">
								<span class="glyphicon glyphicon-calendar"></span>
							</span>
						</div>
				<?php
					}
				?>
			</div>
		</div>
	<?php
	}
	?>
	<div class="form-group">
		<div class="col-md-3"></div>
		<div class="col-md-9">
			<button class="btn btn-primary" id="btn_save" type="button"><?php echo azlang('Save'); ?></button>
		</div>
	</div>
</form>