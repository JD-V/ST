

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

  $('*[name=RecDate]').appendDtpicker();
  $('*[name=InvoiceDate]').appendDtpicker({"dateOnly": true, "dateFormat":'DD-MM-YYYY' });

});
