

$(document).ready(function() {

  $('#invoiceTable').DataTable({
  "paging":   false,
  "info":     false,
  "order": [[ 0, "asc" ]],
  "columnDefs": [{ "orderable": false, "targets": [5,6,7,8,9,10,11,12] }]
});

  $('#productsTable').DataTable({
    "paging":   false,
    "info":     false,
    "order": [[ 0, "asc" ]],
    "columnDefs": [{ "orderable": false, "targets": [ 5,6,7,8,9,10,11,12] }]
  });

  $('#productInventoryTable').DataTable({
    "paging":   false,
    "info":     false,
    "order": [[ 0, "asc" ]],
    "columnDefs": [{ "orderable": false, "targets": [ 5,6,7,8,9,10] }]
  });

$('#stockEntryTable').DataTable({
    "paging":   false,
    "info":     false,
    "order": [[ 0, "desc" ]],
    "columnDefs": [{ "orderable": false, "targets": [2] }]
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
    "order": [[ 1, "desc" ]],
    "columnDefs": [{ "orderable": false, "targets": [3,4,5,6,7,8,9] }]
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

});
