jQuery(document).ready(function($) {

	setTimeout(function(){     
		$( "#type" ).select2( { "placeholder" : 'Select KPI Items' } );
		$("#fromDate").datepicker({
			dateFormat: "MM yy",
			changeMonth: true,
			changeYear: true,
			showButtonPanel: true,

			beforeShow: function(input, inst) {
				var value = $(this).val();

				if (value.length > 0) {
					var parts = value.split(" ");
					var month = $.datepicker.regional[''].monthNames.indexOf(parts[0]);
					var year = parts[1];

					$(this).datepicker("option", "defaultDate", new Date(year, month, 1));
					$(this).datepicker("setDate", new Date(year, month, 1));
				}

				setTimeout(function () {
					$(".ui-datepicker-calendar").hide(); 
				}, 0);
			},

			onClose: function(dateText, inst) {
				var month = $("#ui-datepicker-div .ui-datepicker-month :selected").val();
				var year = $("#ui-datepicker-div .ui-datepicker-year :selected").val();

				$(this).val($.datepicker.formatDate('MM yy', new Date(year, month, 1)));
			}
		});
	},100);

	// Hide calendar days
        $(document).on("focus", ".ui-datepicker-calendar", function () {
            $(this).hide();
        });


        $('#add-kpi-records-form').validate({
        	ignore: ":hidden",
        	rules: {
        		fromDate: {
        			required: true,
        		},
        		type: {
        			required: true,
        		},
        		amount: {
        			required: true,
        		}    
        	},

        	invalidHandler: function(event, validator) {

        		if( jQuery('#type').val() == 0  ){
        			jQuery('#type').parent().append('<label id="type-error" class="error" for="type">This field is required.</label>');
        		}

        		swal.fire({
        			"title": "",
        			'icon': "error",
        			"text": "There are some errors in your submission. Please correct them.",
        			"type": "error",
        			"buttonStyling": false,
        			"confirmButtonClass": "btn btn-brand btn-sm btn-bold"
        		}).then(function () {
        			if (validator.errorList.length) {
        				const firstErrorElement = $(validator.errorList[0].element);
        				if (firstErrorElement.is(":visible")) {
        					$('html, body').animate({
        						scrollTop: firstErrorElement.offset().top - 100 
        					}, 500);
        					firstErrorElement.focus();
        				}
        			}
        		});
        	},

        	submitHandler: function (form) {

        		var valid_flag = 0;
            jQuery('label.error').remove();
            if( jQuery('#type').val() == 0  ){
                jQuery('#type').parent().append('<label id="type-error" class="error" for="type">This field is required.</label>');
                valid_flag = 1;
            }

            if( valid_flag == 1 ){
                swal.fire({
                "title": "",
                 'icon': "error",
                "text": "There are some errors in your submission. Please correct them.",
                "type": "error",
                "buttonStyling": false,
                "confirmButtonClass": "btn btn-brand btn-sm btn-bold"
            }).then(function () {
               $('html, body').animate({
                    scrollTop: jQuery('label.error').offset().top - 200 // adjust offset as needed
                }, 500);
            });
                
                return false;
            }
jQuery('#add-kpi-records-form button[type="submit"] .spinner-border').show();

        		$('#add-kpi-records-form').ajaxSubmit({
        			url: site_url+'/api/storeQuickKPIRecord',
        			success: function(res) {
        				jQuery('#add-kpi-records-form button[type="submit"] .spinner-border').hide();
        				if( res.status == 'success' ){
        					window.location.href = site_url+"/kpi-records";
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
        				jQuery('#add-kpi-records-form button[type="submit"] .spinner-border').hide();
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