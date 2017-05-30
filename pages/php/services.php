<?php
$CDATA['PAGE_NAME'] = 'SERVICES';
require '_connect.php';
require '_core.php';

if(isLogin())
{
require '_header.php';
$roleID = getUserRoleID();
?>

<script type = "text/javascript">

function DisplayInvoice(InvoiceID){

window.open("DisplayServiceInvoice.php?id="+InvoiceID);

}
</script>

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
  <!-- Content Header (Page header) -->
  <section class="content-header" id="MainEventInfo" >
    <h1>
      <div>Service history
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
    </div>
    <!-- Default box -->


    <div class="box">
      <div class="box-header with-border">
        <h3 class="box-title">Records</h3>

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
                <th>Invoice</th>
                <th>DateTime</th>

                <th>Customer</th>
                <th>Phone</th>
                <th>Vehicle No</th>
                <th>Mileage</th>

                <th>Amount Paid</th>
                <th>Address</th>
                <th>Notes</th>
                
                <th>Invoice</i></th>
                <th><i class="fa fa-pencil" aria-hidden="true"></i></th>
              </tr>
            </thead>
            <tbody>
              <?php
                $i = 0;
                $services = GetServices2();
                foreach ($services as $service) {
                  ?>
                  <tr>
                    <td><?php echo $service->invoiceNumber; ?></td>
                    <td><?php echo $service->invoiceDate; ?></td>

                    <td><?php echo $service->customerName; ?></td>
                    <td><?php echo $service->customerPhone ?></td>
                    <td><?php echo $service->vehicleNumber; ?></td>
                    <td><?php echo $service->vehicleMileage; ?></td>

                    <td><?php echo $service->amountPaid; ?></td>
                    <td><?php echo $service->address; ?></td>
                    <td><?php echo $service->notes; ?></td>
                    
                    <td><?php echo '<input type="button" class="btn btn-sm btn-info" value="Invoice" onclick = "DisplayInvoice('.$service->invoiceNumber.');" />'; ?></td>
                    <th>
                    <?php 
                      if( $roleID == 1 || strtotime(date('d-m-Y H:i:s')) - strtotime($service->invoiceDate) <= 900) 
                        echo '<a href="addservicerecord.php?id='. $service->invoiceNumber.'">' . '<i class="fa fa-pencil" aria-hidden="true"></i>'. '</a>'; 
                    ?>
                    </th>
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
