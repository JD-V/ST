
=<?php
$CDATA['PAGE_NAME'] = 'STOCKENT';
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
    <h1>Stock Activity Details</h1>
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
        <h3 class="box-title">Entries</h3>

        <div class="box-tools pull-right">
           <button type="button" class="btn btn-box-tool" data-widget="collapse" data-toggle="tooltip" title="Collapse">
            <i class="fa fa-minus"></i></button>
          <button type="button" class="btn btn-box-tool" data-widget="remove" data-toggle="tooltip" title="Remove">
            <i class="fa fa-times"></i></button> 
        </div>
      </div>
      <div class="box-body">      
        <div class="table-responsive col-sm-12" >
          <table id="stockEntryTable" class="table table-striped table-hover" >
            <thead>
              <tr>
                <th>Date/Time</th>
                <th>Brand</th>
                <th>Size</th>
                <th>Pattern</th>
                <td align="right" style="font-weight:bold">Qty</td>
                <td align="center" style="font-weight:bold" >Txn Type</td
              </tr>
            </thead>
            <tbody>
              <?php
                $i = 0;

                $stockTransactionHistory = GetStockTransactionHistory();
                while ($entry = mysql_fetch_assoc($stockTransactionHistory)) {
                  ?>
                  <tr>
                    <td><span style="display:none"><?php echo $entry['TimeStamp']?></span><?php $date= date_create($entry['TimeStamp']); echo date_format($date, 'd-m-Y H:i'); ?></td>
                    <td><?php echo $entry['BrandName']; ?></td>
                    <td><?php echo $entry['ProductSize']; ?></td>
                    <td><?php echo $entry['ProductPattern']; ?></td>
                    <td align="right"><?php echo $entry['Qty']; ?></td>
                    <td align="center" class="<?php if($entry['TansactionTypeID']=='1') echo 'green'; else if($entry['TansactionTypeID']=='2') echo 'red'; else if($entry['TansactionTypeID']=='3') echo 'blue'; else echo 'purple'?>"><?php echo $entry['TranasactionTypeName'] ?></td>
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
