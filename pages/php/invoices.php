<?php
$CDATA['PAGE_NAME'] = 'INVOICE';
require '_connect.php';
require '_core.php';

if(isLogin() && isAdmin())
{
require '_header.php';
?>

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
  <!-- Content Header (Page header) -->
  <section class="content-header" id="MainEventInfo" >
    <h1>
      <div>Products and Invoices
      <small></small></div>
    </h1>
<!--     <ol class="breadcrumb">
      <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
      <li><a href="#">Layout</a></li>
      <li class="active">Fixed</li>
    </ol> -->
  </section>

  <!-- Main content -->
  <section class="content">
    <!-- <div class="callout callout-info">
      <h4>Tip!</h4>

      <p>Add the fixed class to the body tag to get this layout. The fixed layout is your best option if your sidebar
        is bigger than your content because it prevents extra unwanted scrolling.</p>
    </div> -->
    <div id="messages">
    </div>
    <!-- Default box -->


    <div class="box">
      <div class="box-header with-border">
        <h3 class="box-title">Purchase Invoices</h3>

        <div class="box-tools pull-right">
           <button type="button" class="btn btn-box-tool" data-widget="collapse" data-toggle="tooltip" title="Collapse">
            <i class="fa fa-minus"></i></button>
          <button type="button" class="btn btn-box-tool" data-widget="remove" data-toggle="tooltip" title="Remove">
            <i class="fa fa-times"></i></button> 
        </div>
      </div>
      <div class="box-body">      
        <div class="table-responsive col-sm-12" >
          <table id="invoiceTable" class="table table-striped table-hover" >
            <thead>
              <tr>
                <th>#</th>
                <th>Company</th>
                <th>Invoice Date</th>
                <th>Invoice No</th>
                <th>TIN No</th>
                <td align="right" style="font-weight:bold">Subtotal</td>
                <td align="right" style="font-weight:bold">Vat</td>                
                <td align="right" style="font-weight:bold">Total Paid</td>
                 <td align="center" style="font-weight:bold">Payemnt Type</td>
                <th>Cheque No</th>
                <th>Cheque Date</th>
                <th>Notes</th>
              </tr>
            </thead>
            <tbody>
              <?php
                $i = 0;

                $Invoices = GetInvoices();
                while ($Invoice = mysql_fetch_assoc($Invoices)) {
                  ?>
                  <tr>
                    <th><?php echo $i+=1; ?></th>
                    <td><?php echo $Invoice['Company']; ?></td>
                    <td><?php $date = date_create($Invoice['InvoiceDate']); echo date_format($date, 'd-m-Y'); ?></td>
                    <td><?php echo '<a href="viewinvoice.php?id='. $Invoice['InvoiceID']. ' ">' . $Invoice['InvoiceNumber'] . '</a>'; ?></td>
                    <td><?php echo $Invoice['TinNumber'] ?></td>
                    <td align="right"><?php echo $Invoice['SubTotal'] ?></td>
                    <td align="right"><?php echo $Invoice['VatAmount'] ?></td>
                    <td align="right"><?php echo $Invoice['TotalPaid']; ?></td>
                    <td align="center">
                      <?php
                        if($Invoice['PaymentType'] == 3)
                          echo 'Cheque';  
                        else if ($Invoice['PaymentType'] == 2)
                          echo 'Card';
                        else
                          echo 'Cash';
                      ?>
                    </td>
                    <td ><?php echo $Invoice['ChequeNo']; ?></td>
                    <td >
                      <?php 
                        $chequeDate = $Invoice['ChequeDate'];
                        if ($chequeDate != '0000-00-00') {
                          $chequeDate = date_create($chequeDate); 
                          echo date_format($chequeDate, 'm-d-Y');
                        }
                      ?>
                    </td>
                    <td><?php echo $Invoice['Notes']; ?></td>
                  </tr>
                  <?php
                  }
                  ?>
            </tbody>
          </table>
      </div>
      </div>
      <!-- /.box-body -->
    </div>

    
    <div class="box">
      <div class="box-header with-border">
        <h3 class="box-title">Purchase Products</h3>

        <div class="box-tools pull-right">
           <button type="button" class="btn btn-box-tool" data-widget="collapse" data-toggle="tooltip" title="Collapse">
            <i class="fa fa-minus"></i></button>
          <button type="button" class="btn btn-box-tool" data-widget="remove" data-toggle="tooltip" title="Remove">
            <i class="fa fa-times"></i></button> 
        </div>
      </div>
      <div class="box-body">      
        <div class="table-responsive col-sm-12" >
          <table id="productsTable" class="table table-striped table-hover" >
            <thead>
              <tr>
                <th>#</th>
                <th>Invoice No</th>
                <th>Type</th>
                <th>Brand</th>
                <th>Size</th>
                <th>Pattern</th>
                <th>Qty</th>
                <td align="right" style="font-weight:bold">Unit price</td>
                <td align="right" style="font-weight:bold">Amount</td>
              </tr>
            </thead>
            <tbody>
              <?php
                $i = 0;
                $Products = GetProducts();
                while ($Product = mysql_fetch_assoc($Products)) {
                  ?>
                  <tr>
                    <th><?php echo '<a href="addproduct.php?id='. $Product['ProductID'].'">' . $i+=1  . '</a>'; ?></th>
                    <td><?php echo $Product['InvoiceNumber']; ?></td>
                    <td><?php echo $Product['ProductTypeName']; ?></td>
                    <td><?php echo $Product['ProductBrand']; ?></td>
                    <td><?php echo $Product['ProductSize'] ?></td>
                    <td><?php echo $Product['Pattern'] ?></td>
                    <td><?php echo $Product['ProductQty']; ?></td>
                    <td align="right"><?php echo $Product['UnitPrice']; ?></td>
                    <td align="right"><?php echo $Product['Amount']; ?></td>
                  </tr>
                  <?php
                }
              ?>
            </tbody>
          </table>
      </div>
      </div>
      <!-- /.box-body -->
    </div>
    <!-- /.box -->

  </section>
  <!-- /.content -->
</div>
<!-- /.content-wrapper -->
<?php
require '_footer.php';
}
else
{
  header('Location: ../../index.php');
}
?>
