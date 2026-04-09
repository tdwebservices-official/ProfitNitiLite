Dropzone.autoDiscover = false;
jQuery(document).ready(function($) {
    var defaultImageId   = $('#logo_media_id').val();     // existing ID from DB
    var defaultImageUrl  = $('#logo_media_url').val();    // existing thumbnail URL
    $('#kt_dropzone_3').dropzone({
        url: site_url+'/api/UploadMedia',   
         headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        paramName: "file", 
        maxFiles: 1,
        maxFilesize: 100, 
        addRemoveLinks: true,
        init: function () {
            var dz = this;

            // -----------------------------------------------------------------
            // Load default image thumbnail if exists
            // -----------------------------------------------------------------
            if (defaultImageUrl !== "" && defaultImageUrl !== undefined) {
                var mockFile = { 
                    name: "Current Logo",
                    size: 12345,
                    mockup_id: defaultImageId,
                    type: 'image/jpeg'
                };
                dz.emit("addedfile", mockFile);
                dz.emit("thumbnail", mockFile, defaultImageUrl+'?ver='+Date.now());
                dz.emit("complete", mockFile);
                dz.files.push(mockFile);
            }
        },
        accept: function (file, done) {
            if (file.name == "justinbieber.jpg") {
                done("Naha, you don't.");
            } else {
                done();
            }
        },
        success: function (file, response) {           
            if( response.media.id !== null && response.media.id !== undefined && response.media.id != '' ){            
               jQuery('#logo_media_id').val(response.media.id);              
            }
            if( response.media.media_url !== null && response.media.media_url !== undefined && response.media.media_url != '' ){
                jQuery('#logo_media_url').val(response.media.media_url);
            }
        },
        removedfile: function (file) {            
            jQuery.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                type: "POST",
                url: site_url+'/api/RemoveMedia',        
                dataType: 'json',        
                data: {
                    media_id : jQuery('#logo_media_id').val()
                },
                success: function (res) {
                    if( res.status == 'success' ){
                        file.previewElement.remove();
                        jQuery('#logo_media_id').val('');
                        jQuery('#logo_media_url').val('');    
                    }else{
                        alert('Something went wrong...');
                    }
                },
                error: function (data) {            
                    alert('Something went wrong...');
                }
            });
        }
    });

    $('#add-branding-form').validate({
        // Validate only visible fields
        ignore: ":hidden",
        // Validation rules
        rules: {
                     
        },

        // Display error
        invalidHandler: function(event, validator) {
            swal.fire({
                "title": "",
                 'icon': "error",
                "text": "There are some errors in your submission. Please correct them.",
                "type": "error",
                "buttonStyling": false,
                "confirmButtonClass": "btn btn-brand btn-sm btn-bold"
            }).then(function () {
                // Scroll to the first visible error element after modal is dismissed
                if (validator.errorList.length) {
                    const firstErrorElement = $(validator.errorList[0].element);
                    if (firstErrorElement.is(":visible")) {
                        $('html, body').animate({
                            scrollTop: firstErrorElement.offset().top - 100 // adjust offset as needed
                        }, 500);
                        firstErrorElement.focus();
                    }
                }
            });
        },

        // Submit valid form
        submitHandler: function (form) {
            jQuery('#add-branding-form button[type="submit"]').siblings('.spinner-border').show();
            $('#add-branding-form').ajaxSubmit({
                url: site_url+'/api/AddOrUpdateBranding',
                success: function(res) {
                    jQuery('#add-branding-form button[type="submit"]').siblings('.spinner-border').hide();
                    if( res.status == 'success' ){
                        window.location.href = window.location.href;
                    }
                    if( res.status == 'error' ){
                       swal.fire({
                            "title": "",
                             'icon': "error",
                            "text": res.message,
                            "type": "error",
                            "buttonStyling": false,
                            "confirmButtonClass": "btn btn-brand btn-sm btn-bold"
                        });
                    }
                },
                error: function(res) {
                    jQuery('#add-branding-form button[type="submit"]').siblings('.spinner-border').hide();
                    swal.fire({
                        "title": "",
                         'icon': "error",
                        "text": res.responseJSON.message,
                        "type": "error",
                        "buttonStyling": false,
                        "confirmButtonClass": "btn btn-brand btn-sm btn-bold"
                    });
                }
            });
        }
    });
});