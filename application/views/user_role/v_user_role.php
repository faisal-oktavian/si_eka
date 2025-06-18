<form class="form-horizontal az-form" id="form" name="form" method="POST" action="<?php echo app_url();?>user_role/save">
	<div class="form-group">
		<label class="control-label col-md-2"><?php echo azlang('Role');?> *</label>
		<div class="col-md-5">
			<select class="form-control" name="idrole" id="idrole">
				<?php echo az_generate_role_option(az_get_role());?>
			</select>
		</div>
	</div>

	<div>
		<h3><?php echo azlang('User Role');?></h3>
		<table class="table table-bordered table-condensed">
			<tr>
				<th><?php echo azlang('Menu');?></th>
				<th width="10px"><?php echo azlang('Access');?></th>
				<th><?php echo azlang('User Role');?></th>
			</tr>
			<?php 
				echo $role;
			?>
		</table>
	</div>

	<div>
		<button class="btn btn-primary az-save-user-role" type="button"><?php echo azlang('Save');?></button>
	</div>
</form>