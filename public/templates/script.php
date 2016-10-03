<script type="text/javascript">
document.addEventListener("DOMContentLoaded", function() {
    Dropzone.autoDiscover = false;
    var el = document.querySelector('input[name=_wpcf7][value="<?php echo $form_id; ?>"]');
    var form = el.form;
    var $ = jQuery;
    var pt = document.getElementById('<?php echo $preview_template_id?>');
    var options = {};
    if (pt) {
        options.previewTemplate = pt.innerHTML;
    }

    $.extend(options,
            {   url: form.action,
                paramName: "<?php echo $param_name; ?>",
                uploadMultiple: false,
                autoProcessQueue: false,
                previewsContainer: "#dz-preview-container",
                <?php if ($message) :?>
                dictDefaultMessage: "<?php echo $message;?>",
                <?php endif;?>
                <?php if ($max_files) :?>
                maxFiles: <?php echo $max_files;?>,
                parallelUploads: <?php echo $max_files;?>,
                <?php endif;?>
                addRemoveLinks: <?php echo $add_remove_links;?>,
                dictRemoveFile: '',
                createImageThumbnails: <?php echo $create_image_thumbnails;?>,
                <?php if ($max_filesize) :?>
                maxFilesize: <?php echo $max_filesize;?>,
                <?php endif;?>
                acceptedFiles: "<?php echo $accepted_files;?>",
                init: function () {
                    var dz = this;
                    var submit = form.querySelector('[type=submit]');

                    submit.addEventListener('click', function (e) {
                        var files = dz.getQueuedFiles();

                        if (files.length > 0) {

                            $(form).attr( "enctype", "multipart/form-data" )
                                   .attr( "encoding", "multipart/form-data" );

                            var formData = new FormData(form);

                            for (var _i = 0, _len = files.length; _i < _len; _i++) {
                                var param = dz.options.paramName + (dz.options.uploadMultiple ? "[" + _i + "]" : "")
                                formData.append(param, files[_i], files[_i].name);
                            }

                            formData.append('_wpcf7_is_ajax_call', 1);

                            $(form).on('form-submit-validate', function(event, a, $form, options, veto) {
                                $.extend(options, { formData: formData });
                            });
                        }

                    }, false);

                    dz.on('addedfile', function(file) {
                        $('.dz-message').hide();
                    });

                    dz.on('removedfile', function(file) {
                        if (dz.files.length == 0) {
                            $('.dz-message').show();
                        }
                    });
                    dz.on('error', function(file) {
                        $(file.previewElement).fadeOut(2000,
                                function() {
                                    dz.removeFile(file);
                                });
                    });

                    // Clear selected files after form was successfully submited
                    $(form).on('reset', function() {
                        dz.removeAllFiles(true);
                    });
                }
            });

    new Dropzone(document.querySelector('#<?php echo $name; ?>'), options);
});
</script>
