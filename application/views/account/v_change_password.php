<form class="form-horizontal" id="form" name="form" method="post">
    <div class="form-group">
        <label for="" class="col-sm-2 control-label"><?php echo azlang('Old password');?> *</label>
        <div class="col-sm-3">
            <input class="form-control" type="password" name="old_password" id="old_password"/>
        </div>
    </div>
    <div class="form-group">
        <label for="" class="col-sm-2 control-label"><?php echo azlang('New password');?> *</label>
        <div class="col-sm-3">
            <input class="form-control" type="password" name="new_password" id="new_password"/>
        </div>
    </div>
    <div class="form-group">
        <label for="" class="col-sm-2 control-label"><?php echo azlang('Confirm Password');?> *</label>
        <div class="col-sm-3">
            <input class="form-control" type="password" name="confirm_password" id="confirm_password"/>
        </div>
    </div>
    <div class="form-group">
        <div class="col-sm-5 txt-right">
            <button type="button" class="btn btn-primary" id="btn_save_password"><?php echo azlang('Save');?></button>
        </div>
    </div>
</form>