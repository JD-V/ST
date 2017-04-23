<?php
$CDATA['PAGE_NAME'] = 'ORDERS';
require '_connect.php';
require '_core.php';

if(isLogin() && isAdmin())
{
require '_header.php';
?>
<script type = "text/javascript">

function DisplayInvoice(InvoiceID){

window.open("DisplaySaleInvoice.php?id="+InvoiceID);

}
</script>
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
  <!-- Content Header (Page header) -->
  <section class="content-header" id="MainEventInfo" >
    <h1>
      <div>Order history
      <small></small><div>
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
      <?php

        if(@$_GET['act'] == 'delete' && $http_referer != 'direct_link' && !empty($_GET['del']))
        {
          $DELEventID = mysql_real_escape_string($_GET['del']);

          if($DelCase = mysql_query("DELETE FROM subevent WHERE subeventId = '$DELEventID' "))
          {
          echo '<div class="alert alert-block alert-success">
                <button type="button" class="close" data-dismiss="alert">
                  <i class="ace-icon fa fa-times"></i>
                </button>
                <i class="ace-icon fa fa-tick red"></i>
                Order deleted successfully.
              </div>';
          }
          else
          {
          echo '<div class="alert alert-block alert-danger">
                <button type="button" class="close" data-dismiss="alert">
                  <i class="ace-icon fa fa-times"></i>
                </button>
                <i class="ace-icon fa fa-ban red"></i>
                Something went wrong, try later.
              </div>';
          }
        }
      ?>
    </div>
    <!-- Default box -->


    <div class="box">
      <div class="box-header with-border">
        <h3 class="box-title">records</h3>

        <div class="box-tools pull-right">
<!--           <button type="button" class="btn btn-box-tool" data-widget="collapse" data-toggle="tooltip" title="Collapse">
            <i class="fa fa-minus"></i></button>
          <button type="button" class="btn btn-box-tool" data-widget="remove" data-toggle="tooltip" title="Remove">
            <i class="fa fa-times"></i></button> -->
        </div>
      </div>
      <div class="box-body">
      <div class="table-responsive col-sm-12" >
          <table id="serviceTable" class="table table-striped table-hover" >
            <thead>
              <tr>
                <th>#</th>
                <th>Invoice</th>
                <th>DateTime</th>

                <th>Customer</th>
                <th>Phone</th>
                <th>Vehicle No</th>
                
                <th>SubTotal</th>
                <th>Vat</th>
                <th>Discount</th>

                <th>Amount Paid</th>
                <th>Notes</th>
                <!--<th><i class="fa fa-pencil" aria-hidden="true"></i></th>-->
                <th>Invoice</i></th>
              </tr>
            </thead>
            <tbody>
              <?php
                $i = 0;
                $orders = GetOrders();
                while ($order = mysql_fetch_assoc($orders)) {
                  ?>
                  <tr>
                    <th><?php echo '<a href="addservicerecord.php?id='.$order['InvoiceNumber'].'">' . $i+=1  . '</a>'; ?></th>
                    <td><?php echo $order['InvoiceNumber']; ?></td>
                    <td><?php $date = date_create($order['InvoiceDateTime']); echo date_format($date, 'd-m-Y H:i'); ?></td>

                    <td><?php echo $order['CustomerName']; ?></td>
                    <td><?php echo $order['CustomerPhone'] ?></td>
                    <td><?php echo $order['VehicleNumber']; ?></td>

                    <td><?php echo $order['SubTotal']; ?></td>
                    <td><?php echo $order['Vat']; ?></td>
                    <td><?php echo $order['Discount']; ?></td>

                    <td><?php echo $order['AmountPaid']; ?></td>
                    <td><?php echo $order['Notes']; ?></td>
                    <td><?php echo '<input type="button" class="btn btn-sm btn-info" value="Invoice" onclick = "DisplayInvoice('.$order['InvoiceNumber'].');" />'; ?></td>
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
