

$(document).ready(function() {

  $('#invoiceTable').DataTable({
  "paging":   true,
  "info":     false,
  "order": [[ 2, "desc" ]],
  "pagingType": "full_numbers",
  "columnDefs": [{ "orderable": false, "targets": [ 4,5,6,7,8,9,11] }]
});

  $('#productsTable').DataTable({
    "paging":   true,
    "info":     false,
    "order": [[ 0, "asc" ]],
    "pagingType": "full_numbers",
    "columnDefs": [{ "orderable": false, "targets": [ 5,6,7,8] }]
  });

  $('#productInventoryTable').DataTable({
    "paging":   true,
    "info":     false,
    "order": [[7, "desc" ]],
    "pagingType": "full_numbers",
    "columnDefs": [{ "orderable": false, "targets": [ 5,6,8,9 ] }]
  });

$('#stockEntryTable').DataTable({
    "paging":   true,
    "info":     false,
    "order": [[ 0, "desc" ]],
    "pagingType": "full_numbers",    
    "columnDefs": [{ "orderable": false, "targets": [5] }]
  });

$('#salesTable').DataTable({
    "paging":   false,
    "info":     false,
    "order": [[ 1, "desc" ]],
    "columnDefs": [{ "orderable": false, "targets": [3,4,5,6,7,8,9,10,11,12] }]
  });

  $('#serviceTable').DataTable({
    "paging":   false,
    "info":     false,
    "order": [[ 0, "desc" ]],
    "columnDefs": [{ "orderable": false, "targets": [3,4,5,6,7,8,9,10] }]
  });

    $('#supplierTable').DataTable({
    "paging":   false,
    "info":     false,
    "order": [[ 1, "desc" ]],
    "columnDefs": [{ "orderable": false, "targets": [5,6,7] }]
  });

    $('#nonbillableTable').DataTable({
    "paging":   false,
    "info":     false,
    "order": [[ 1, "desc" ]],
    "columnDefs": [{ "orderable": false, "targets": [2,3,4,5] }]
  });
  

  $('*[name=RecDate]').appendDtpicker({"dateFormat":'DD-MM-YYYY hh:mm'});
  $('*[name=InvoiceDate]').appendDtpicker({"dateOnly": true, "dateFormat":'DD-MM-YYYY' });
  $('*[name=ServiceInvoiceDate]').appendDtpicker({"dateFormat":'DD-MM-YYYY hh:mm' });
  $('*[name=SalesInvoiceDate]').appendDtpicker({"dateFormat":'DD-MM-YYYY hh:mm' });
  $('*[name=ChequeDate]').appendDtpicker({"dateOnly": true, "dateFormat":'DD-MM-YYYY'});
  $('*[name=FromDate]').appendDtpicker({"dateOnly": true, "dateFormat":'DD-MM-YYYY' });
  $('*[name=ToDate]').appendDtpicker({"dateOnly": true, "dateFormat":'DD-MM-YYYY' });
});
