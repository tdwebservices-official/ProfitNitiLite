jQuery(document).ready(function($){

	 var kpi_start = '';
	 var kpi_end = '';

    if( pf_readCookie('kpi_date_filter') != '' && pf_readCookie('kpi_date_filter') !== null ){
        var kpi_dates =  pf_readCookie('kpi_date_filter').split(' / ');        
        kpi_start = moment(kpi_dates[0], 'YYYY-MM-DD');
        kpi_end = moment(kpi_dates[1], 'YYYY-MM-DD');

        $("input[name='date_filter']").val(pf_readCookie('kpi_date_filter'));
    }  


    // Month-Year Picker
	$("#date_from, #date_to").datepicker({
		dateFormat: "MM yy",
		changeMonth: true,
		changeYear: true,
		showButtonPanel: true,

		beforeShow: function(input, inst) {
			var value = $(this).val();

			if (value.length > 0) {
				var parts = value.split("-");
				var month = parts[1] - 1;
				var year = parts[0];

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

			var selectedDate = new Date(year, month, 1);

        $(this).datepicker("setDate", selectedDate); // IMPORTANT
        $(this).val($.datepicker.formatDate('yy-mm', selectedDate));

        updateRange();
    }
});

	if (kpi_start && kpi_start.isValid()) {
		$("#date_from").datepicker("setDate", kpi_start.toDate());
		$("#date_from").val(kpi_start.format("YYYY-MM"));
	}

	if (kpi_end && kpi_end.isValid()) {
		$("#date_to").datepicker("setDate", kpi_end.toDate());
		$("#date_to").val(kpi_end.format("YYYY-MM"));
	}
// Hide calendar days
        $(document).on("focus", ".ui-datepicker-calendar", function () {
            $(this).hide();
        });


    function updateRange() {
        var fromDate = $("#date_from").val();
        var toDate = $("#date_to").val();
        if (fromDate && toDate) {
            $("input[name='date_filter']").val(fromDate + "-01 / " + toDate+'-01');
            jQuery('.btn-download-report,.btn-ai-report').removeAttr('disabled');
            pf_createCookie( 'kpi_date_filter', $("input[name='date_filter']").val(), 30 );
        }
    }


     setTimeout(function(){
        jQuery('.assign_by').select2({ 'placeholder' : "Choose Assign" });

        jQuery('.assign_by').on('select2:select', function (e) {
        	var selectedValue = e.params.data.id;
        	pf_createCookie( 'kpi_user_filter_id',selectedValue, 30 );
        });
        
    },100);



});

function get_report_item_list(){
	var report_table_list = {};
	jQuery('.balance-sheet-table table tbody tr').each(function () {
		var cols = $(this).find('td');

    // Skip rows that don't have enough columns

		var key = $(cols[0]).text().trim();

    // Skip empty or header rows
		if (!key || key === 'Particulars') return;

		var values = [];

    // Start from column 1 (skip first column)
		for (var i = 1; i < cols.length; i++) {
			var val = $(cols[i]).text().trim();

        // Convert to number if possible
			val = val.replace(/,/g, '');
			values.push(val === '' ? null : parseFloat(val));
		}

		report_table_list[key] = values;
	});
	return report_table_list;
}

// Function to format number with commas
function PNTFormatNumber(num) {
    return Number(num).toLocaleString('en-IN', {
        maximumFractionDigits: 2
    });
}

// Change event
$(document).on("change", "#figureType", function () {
	let divisor = parseFloat($(this).val());
	let suffixVal = $("#figureType").val(); 
	var suffix = '';
	if( suffixVal == 1000 ){
		suffix = 'K';
	}else if( suffixVal == 100000 ){
		suffix = 'L';
	}else if( suffixVal == 1000000 ){
		suffix = 'M';
	}else if( suffixVal == 10000000 ){
		suffix = 'Cr';
	}
	$(".balance-sheet-table table tbody tr td:not(:first-child)").each(function () {

		if( jQuery(this).parent().attr('data-not-check') != 'yes' ){
			let original = $(this).attr("data-original");
			if (original !== undefined) {

				let newValue = original / divisor;
				// if( original == 0 ){
				// 	$(this).text(PNTFormatNumber(newValue));
				// }else{				
				// }
					//$(this).text(PNTFormatNumber(newValue) +' '+ suffix);
				$(this).text(PNTFormatNumber(newValue));
			}
		}

	});
});

function setDataAttributeTB( labelArr = [] ){
	// Store original values
    $(".balance-sheet-table table tbody tr td:not(:first-child)").each(function () {

    		var lbl = jQuery(this).parent().find('td').first().text();    	
    		
    		if (jQuery.inArray(lbl, labelArr) != -1) {
    			jQuery(this).parent().attr('data-not-check', 'yes');
    		} else{
    			jQuery(this).parent().attr('data-not-check', 'no');
    		}

        let value = $(this).text().replace(/,/g, '');        
        if (!isNaN(value) && value !== "") {
            $(this).attr("data-original", value);
        }
    });

    
    if( jQuery(".balance-sheet-table table tbody tr").length > 0 ){
    	jQuery(".balance-sheet-table table tbody tr").each(function () {
    		const $row = $(this);
    		const label = $row.find("td:first").text().trim();
    		if (ratio_info[label] !==  undefined) {
    			$row.find("td:first").append(`<button type="button" class="view-toggle-btn" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-custom-class="tooltip-dark" data-bs-title="`+ratio_info[label]+`"><iconify-icon icon="jam:alert" class="text-primary-light text-lg mt-4"></iconify-icon> </button>`);
    		}
    	});

    	const tooltipTriggerList = document.querySelectorAll('[data-bs-toggle="tooltip"]'); 
    	const tooltipList = [...tooltipTriggerList].map(tooltipTriggerEl => new bootstrap.Tooltip(tooltipTriggerEl)); 
    }
    
}

function colorizeRowsByLabel(tableSelector, labels) {
  $(tableSelector).find("tbody tr").each(function () {
    const $row = $(this);

    // skip hidden rows
    //if ($row.is(":hidden")) return;

    const label = $row.find("td:first").text().trim();

    if (labels.includes(label)) {
      $row.find("td:not(:first)").each(function () {
        let value = $(this).text().replace(/,/g, '').trim();

        if (value === "" || isNaN(value)) return;

        value = parseFloat(value);

        if (value < 0) {
        	this.style.setProperty("color", "red", "important");
        } else if (value > 0) {
        	this.style.setProperty("color", "green", "important");
        } else {
        	this.style.setProperty("color", "black", "important");
        }
      });
    }
  });
}

function oppcolorizeRowsByLabel(tableSelector, labels) {
  $(tableSelector).find("tbody tr").each(function () {
    const $row = $(this);



    // skip hidden rows
    //if ($row.is(":hidden")) return;

    const label = $row.find("td:first").text().trim();

    if (labels.includes(label)) {
      $row.find("td:not(:first)").each(function () {
        let value = $(this).text().replace(/,/g, '').trim();


        if (value === "" || isNaN(value)) return;

        value = parseFloat(value);

        if (value < 0) {
          this.style.setProperty("color", "green", "important");
        } else if (value > 0) {
          this.style.setProperty("color", "red", "important");
        } else {
          this.style.setProperty("color", "black", "important");
        }
      });
    }
  });
}


function convertToRowsDynamic(data) {
	
    let keys = Object.keys(data); // dynamic keys
    let length = data[keys[0]].length;

    let result = [];

    for (let i = 0; i < length; i++) {
        let row = [];

        keys.forEach(key => {
            let value = data[key][i];

            // Optional: fix long decimal issue
            if (typeof value === "number") {
                value = parseFloat(value.toFixed(2));
            }

            row.push(value);
        });

        result.push(row);
    }


    return result;
}