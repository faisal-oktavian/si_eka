<script>
jQuery(document).ready(function() {
    jQuery("#btn_save_password").on("click", function() {
        show_loading();
        jQuery.ajax({
            url: app_url+"account/change_password_process",
            type: "POST",
            data: jQuery("#form").serialize(),
            dataType: "JSON",
            success: function(response){
                hide_loading();
                if (response.sMessage == "") {
                    jQuery("#old_password").val("");
                    jQuery("#new_password").val("");
                    jQuery("#confirm_password").val("");
                    bootbox.alert({
                        title: "Berhasil",
                        message: "<?php echo azlang('Success save data');?>"
                    });
                }
                else {
                    bootbox.alert({
                        title: "Error",
                        message: response.sMessage
                    });
                }
            },
            error: function(response) {
                console.log(response);
            }
        });
    });
});