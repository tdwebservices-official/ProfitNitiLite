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