jQuery(document).ready(function($) {


    jQuery(document).ready(function($){
        // Month-Year Picker
        $("#fromDate, #toDate").datepicker({
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

// Hide calendar days
        $(document).on("focus", ".ui-datepicker-calendar", function () {
            $(this).hide();
        });


        var blpl_items = jQuery('.add-kpi-records-form').attr('data-blpl-items');
        var blpl_item_arr = [];
        if( blpl_items !== undefined && blpl_items !== null && blpl_items !== ''  ){
            blpl_item_arr = jQuery.parseJSON(blpl_items);
        }
// Fields
        var fields = blpl_item_arr;


        // Get Months
        function getMonths(start, end) {
            let s = new Date(start);
            let e = new Date(end);
            let months = [];

            while (s <= e) {
                let year = s.getFullYear();
                let month = (s.getMonth() + 1).toString().padStart(2, '0');
                let m = `${year}-${month}-01`;
                let m_label = s.toLocaleString('default', { month:'long'});

                months.push({value: m, label: '01 '+m_label+', '+year});
                s.setMonth(s.getMonth()+1);
            }
            return months;
        }

        $(document).on("click","#loadCSVFIle", function(e){

            let fileInput = $("#csvFile")[0];
            let file = fileInput.files[0];

            if (!file) {
                alert("Please select a CSV file first");
                return;
            }

            jQuery('#loadCSVFIle .spinner-border').show();   
            $('.load-kpi-records-form').ajaxSubmit({
                url: site_url+'/api/loadKPIRecord',
                success: function(res) {
                    jQuery('#loadCSVFIle .spinner-border').hide(); 
                    if( res.status == 'success' && res.sheetData.length > 0 ){
                        let headers = res.sheetData[0].map(h => h.trim());
                        var error_flag = 0;
                        var rows = res.sheetData;
                        for(let i = 1; i < rows.length; i++){
                            let cols = rows[i];
                            let fieldName = cols[0];
                            fieldName = fieldName.replaceAll('"','');
                            for(let j = 1; j < headers.length; j++){

                                let m_date = headers[j]; 
                                let value = cols[j] || 0;

                                var keyName = '';
                                m_date = m_date.replaceAll('"','');
                                if( value ){                                    
                                    value = value.replaceAll(',','');
                                }

                                var m_date_frm = new Date(m_date);

                                var year = m_date_frm.getFullYear();
                                var month = String(m_date_frm.getMonth() + 1).padStart(2, '0'); 
                                var day = String(m_date_frm.getDate()).padStart(2, '0');

                                var date_result = `${year}-${month}-${day}`;

                                Object.entries(fields).forEach(([key, dvalue]) => {
                                    if( dvalue == fieldName ){
                                        keyName = key;
                                    }
                                });
                                let inputName = `kpi_item[${date_result}][${keyName}]`;


                                try {
                                    let input = $(`input[name="${inputName}"]`);               
                                    if(input.length){
                                        input.val(value);
                                    }
                                } catch (error) {
                                    error_flag = 1;
                                    alert("Something Went Wrong, Please Check CSV File Column Values");
                                }
                            }
                        }
                        if( error_flag == 0 ){

                            swal.fire({
                                "title": "",
                                'icon': "success",
                                "text": "CSV Data load successfully",
                                "type": "success",
                                "buttonStyling": false,
                                "confirmButtonClass": "btn btn-brand btn-sm btn-bold"
                            });

                        }
                    }else{
                        alert("Please put data a CSV file first");
                        return;
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
                    jQuery('#loadCSVFIle .spinner-border').hide(); 
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

        });
$(document).on('click',".reset-btn",function(){
jQuery('.balance-sheet-table input').val(0);
});
$(document).on('click',"#downloadSample",function(){

    let csv = "";

    // ✅ Get headers (months)
    let headers = [];
    $(".balance-sheet-table table thead th").each(function(index){
        if(index === 0){
            headers.push("Particulars");
        } else {
            var column_val = $(this).text().trim();
            column_val = column_val.toString().replace(/"/g, '""'); // escape quotes
        headers.push(`"${column_val}"`);
        }
    });

    csv += headers.join(",") + "\n";

    // ✅ Loop rows
   $(".balance-sheet-table table tbody tr").each(function(){

    let row = [];

    // ✅ First column (escape commas & quotes)
    let fieldName = $(this).find("td:first").text().trim();

    fieldName = fieldName.replace(/"/g, '""'); // escape quotes
    row.push(`"${fieldName}"`); // wrap in quotes

    // ✅ Inputs (also safe)
    $(this).find("input").each(function(){

        let value = $(this).val() || 0;

        value = value.toString().replace(/"/g, '""'); // escape quotes
        row.push(`"${value}"`);
    });

    csv += row.join(",") + "\n";
});

    // ✅ Create file
    let blob = new Blob([csv], { type: "text/csv;charset=utf-8;" });
    let url = URL.createObjectURL(blob);

    let link = document.createElement("a");
    link.href = url;
    link.download = "kpi-record-sample.csv";
    document.body.appendChild(link);
    link.click();
    document.body.removeChild(link);
});

// Generate Table
        $(document).on( 'click', '#generate-kpi', function () {

            let from = $("#fromDate").val();
            let to = $("#toDate").val();


            if (!from || !to) {
                alert("Select both dates");
                return;
            }

            jQuery('.save-box,.add-kpi-records-form').removeClass('d-none');

            let months = getMonths(new Date(from), new Date(to));

            let table = "<div class='table-responsive balance-sheet-table'><table class='table bordered-table mb-0'><thead><tr><th>Particulars</th>";

            months.forEach(m => table += `<th>${m.label}</th>`);
            table += "</tr></thead><tbody>";

           Object.entries(fields).forEach(([key, value]) => {
            table += `<tr><td>${value}</td>`;

            months.forEach(m => {
                table += `<td>
            <input type="number" 
            name="kpi_item[${m.value}][${key}]" 
            class="form-control" value="0">
            </td>`;
        });
        });
            table += "</tbody></table></div>";

            $("#kpi-tableArea").html(table);
            jQuery('.csv-load').removeClass('d-none');
        });

    });

    jQuery(document).on('click','.save-kpi-record',function () {   
        jQuery('.save-kpi-record .spinner-border').show();   
        $('#add-kpi-records-form').ajaxSubmit({
            url: site_url+'/api/storeKPIRecord',
            success: function(res) {
                jQuery('.save-kpi-record .spinner-border').hide(); 
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
                jQuery('.save-kpi-record .spinner-border').hide(); 
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
    });

});