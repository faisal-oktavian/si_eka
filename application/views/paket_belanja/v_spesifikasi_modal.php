<form class="form-horizontal" id="form_add_spesifikasi">
	<input type="hidden" id="hd_idpb_detail_sub" name="hd_idpb_detail_sub" class="x-hidden">
	
    <div class="form-group">
		<label class="control-label col-md-3">Spesifikasi <red>*</red></label>
		<div class="col-md-8">
			<textarea class="form-control" name="specification" id="specification" rows="5"></textarea>
		</div>
	</div>
    <div class="form-group">
        <label class="control-label col-md-3">Link </label>
        <div class="col-md-8">
            <input type="text" class="form-control" id="link_url" name="link_url"/>
        </div>
    </div>
</form>

<script src="<?php echo base_url('assets/plugins/ckeditor/ckeditor.js'); ?>"></script>

<script>
    CKEDITOR.replace('specification', {
        height: 250,
        removePlugins: 'elementspath',
        extraPlugins: 'justify,colorbutton,font,table,link,liststyle',
        autoGrow_minHeight: 200,
        autoGrow_maxHeight: 400,
        toolbarCanCollapse: true,
        toolbar: [
            { name: 'clipboard', items: [ 'Cut', 'Copy', 'Paste', 'PasteText', '-', 'Undo', 'Redo' ] },
            { name: 'basicstyles', items: [ 'Bold', 'Italic', 'Underline', 'Strike', '-', 'Subscript', 'Superscript', '-', 'RemoveFormat' ] },
            '/',
            { name: 'paragraph', items: [ 'NumberedList', 'BulletedList', '-', 'Outdent', 'Indent', '-', 'Blockquote', '-', 'JustifyLeft', 'JustifyCenter', 'JustifyRight', 'JustifyBlock' ] },
            { name: 'insert', items: [ 'Table', 'HorizontalRule' ] },
            '/',
            { name: 'styles', items: [ 'Format', 'Font', 'FontSize' ] },
            { name: 'colors', items: [ 'TextColor', 'BGColor' ] },
            { name: 'tools', items: [ 'Maximize' ] }
        ],
        contentsCss: [
            CKEDITOR.basePath + 'contents.css',
            'https://fonts.googleapis.com/css2?family=Roboto:wght@400;500&display=swap'
        ],
        bodyClass: 'document-editor',
        font_names: 'Arial;Roboto;Times New Roman;Verdana;Courier New;Comic Sans MS'
    });
</script>