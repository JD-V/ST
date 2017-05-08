
=<?php
$CDATA['PAGE_NAME'] = 'INVPRDS';
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
      <div>Inventory Products
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
                Event deleted successfully.
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
        <h3 class="box-title">Products</h3>

        <div class="box-tools pull-right">
           <button type="button" class="btn btn-box-tool" data-widget="collapse" data-toggle="tooltip" title="Collapse">
            <i class="fa fa-minus"></i></button>
          <button type="button" class="btn btn-box-tool" data-widget="remove" data-toggle="tooltip" title="Remove">
            <i class="fa fa-times"></i></button> 
        </div>
      </div>
      <div class="box-body">      
        <div class="table-responsive col-sm-12" >
          <table id="productInventoryTable" class="table table-striped table-hover" >
            <thead>
              <tr>
                <th>#</th>
                <th>supplier</th>
                <th>Brand</th>
                <th>Size</th>
                <th>Pattern</th>
                <th>Type</th>
                <td align="right" style="font-weight:bold">Cost Price</td>
                <td align="right" style="font-weight:bold">Min Sell Price</td>
                <td align="right" style="font-weight:bold">Max Sell Price</td>
                <th>Stock Alert</th>
                <th>Notes</th>
                <th>Last Modified</th>                
                <th><i class="fa fa-pencil" aria-hidden="true"></i></th>
              </tr>
            </thead>
            <tbody>
              <?php
                $i = 0;
                $productInventory = GetProductInventory2();
                foreach ($productInventory as $product) {
                  ?>
                  <tr>
                    <td><?php echo '<a href="addproduct.php?id='. $product->productID.'">' . $i+=1  . '</a>'; ?></td>
                    <td><?php echo $product->supplierID ?></td>
                    <td><?php echo $product->brandName; ?></td>                    
                    <td><?php echo $product->productSize ?></td>
                    <td><?php echo $product->productPattern ?></td>
                    <td><?php echo $product->productTypeName ?></td>
                    <td align="right"><?php echo $product->costPrice ?></td>
                    <td align="right"><?php echo $product->minSellingPrice; ?></td>
                    <td align="right"><?php echo $product->maxSellingPrice; ?></td>
                    <td align="center"><?php if($product->minStockAlert) echo '<i class="fa fa-check green" aria-hidden="true"></i>'; else echo '<i class="fa fa-times red" aria-hidden="true" ></i>'; ?></td>
                    <td><?php echo $product->productNotes; ?></td>
                    <td><?php echo $product->lastModified; ?></td>
                    <td><?php echo '<a href="addproduct.php?id='.$product->productID.'"  class=""><i class="fa fa-pencil" aria-hidden="true"></i></a>'; ?></td>
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
