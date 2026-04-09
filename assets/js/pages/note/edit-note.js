jQuery(document).ready(function($) {
     setTimeout(function(){
        $( "#target_date" ).datepicker( { "dateFormat" : 'dd-mm-yy' } );
        $( "#t_month" ).datepicker( { "dateFormat" : 'dd-mm-yy' } );
    },100);
    $('#edit-note-form').validate({
        // Validate only visible fields
        ignore: ":hidden",
        // Validation rules
        rules: {
            t_month: {
                required: true,
            },
            target: {
                required: true,
            },
            action_steps: {
                required: true,
            },
            person_responsible: {
                required: true,
            },
            target_date: {
                required: true,
            },          
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
            jQuery('#edit-note-form button[type="submit"]').siblings('.spinner-border').show();
            $('#edit-note-form').ajaxSubmit({
                url: site_url+'/api/updateNote',
                success: function(res) {
                    jQuery('#edit-note-form button[type="submit"]').siblings('.spinner-border').hide();
                    if( res.status == 'success' ){
                        window.location.href = site_url+"/note";
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
                error: function(res,data) {
                      
                    jQuery('#edit-note-form button[type="submit"]').siblings('.spinner-border').hide();
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