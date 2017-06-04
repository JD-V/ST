<?php
require '_connect.php';
require '_core.php';

$CDATA['PAGE_NAME'] = 'ADDSTOCK';
if(isLogin() && isAdmin())
{
require '_header.php'

?>
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
  <div id='message' style='display: none;'></div>

  <!-- Content Header (Page header) -->
  <section class="content-header" id="MainEventInfo" >
    <h1>
    <div>Add Stock
    </div>
    </h1>
<!--     <ol class="breadcrumb">
      <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
      <li><a href="#">Layout</a></li>
      <li class="active">Fixed</li>
    </ol> -->
  </section>

  <!-- Main content -->
  <section class="content">
    <div id ="messages">

    <?php
    //$_SESSION['AUTH_KEY'] = mt_rand(100000000,999999999);
    ChromePhp::log("form");
    ChromePhp::log("UKEY" . @$_POST['UKey']);
    if(@$_POST['UKey'] == '2')
    {
      ChromePhp::log("AKEY" . $_POST['akey']);
      if(@$_POST['akey'] == $_SESSION['AUTH_KEY'])
      {
        ChromePhp::log("sbumitting ");
        if( isset($_POST['ProductID']) && !empty($_POST['ProductID']) &&
            isset($_POST['Qty']) && !empty($_POST['Qty'])  )
          {

             $qty = FilterInput($_POST['Qty']);
             $productID = FilterInput($_POST['ProductID']);
         
             $Stock = new Stock();
             $Stock->ProductID = $productID;
             $Stock->Qty = $qty;
             $Stock->TansactionTypeID = 1;

            if(AddStockEntry($Stock) == 1) {
              echo MessageTemplate(MessageType::Success, "Stock updated successfully");
            } else {
              echo MessageTemplate(MessageType::Failure, "Something went wrong, please contact your system admin.");
            }

          } else {
            echo MessageTemplate(MessageType::Failure, "Please enter all the details.");
          }
          /* pikesAce Security Robot for re-submission of form */
          $_SESSION['AUTH_KEY'] = mt_rand(100000000,999999999);
          ChromePhp::log($_SESSION['AUTH_KEY']);
          /* END */
        } else {
          echo MessageTemplate(MessageType::RoboWarning, "");
        }
    }

      ?>
  </div>
    <!-- Default box -->
    <div class="box">
      <div class="box-header with-border">
        <?php
          $isValidProduct = false;
          $title="Add stock";
          $Product = NULL;
          if(isset($_GET['id'])) {
            $Product = GetProductInventoryByID2(FilterInput($_GET['id']));
            if($Product == NULL) {
              $title="Product Not Found";
            }
            else {
              $title="Add stock";
              $isValidProduct = true;
            }
          }
        ?>      
        <h3 class="box-title"><?php echo $title ?></h3>
       <!--  <div class="box-tools pull-right">
          <button type="button" class="btn btn-box-tool" data-widget="collapse" data-toggle="tooltip" title="Collapse">
            <i class="fa fa-minus"></i></button>
          <button type="button" class="btn btn-box-tool" data-widget="remove" data-toggle="tooltip" title="Remove">
            <i class="fa fa-times"></i></button>
        </div> -->
      </div>
      <div class="box-body">

      <div>
          <form class="form-horizontal"  name="addstock" id="addstock" action="addstock.php?_auth=<?php echo $_SESSION['AUTH_KEY']; ?>" method="post">
              <input type="hidden" value="<?php echo $_SESSION['AUTH_KEY']; ?>" name="akey" id="ID_akey" >


            <div class="form-group">
              <label for="ProductType" class="control-label col-sm-3 lables">Product Type<span class="mandatoryLabel">*</span></label>
              <div class="col-sm-4">
                <input type="text" class="form-control" name="ProductType" readonly placeholder="Product Type" value="<?php if($isValidProduct) echo $Product->productTypeName ?>" >
              </div>
            </div>

            <div class="form-group">
              <label for="Supplier" class="control-label col-sm-3 lables">Supplier<span class="mandatoryLabel">*</span></label>
              <div class="col-sm-4">
                <input type="text" class="form-control" name="Supplier" readonly placeholder="Supplier" value="<?php if($isValidProduct) echo $Product->supplierName ?>" >
              </div>
            </div>

            <div class="form-group">
              <label for="Brand" class="control-label col-sm-3 lables">Brand<span class="mandatoryLabel">*</span></label>
              <div class="col-sm-4">
                <input type="text" class="form-control" name="Brand" readonly placeholder="Brand"  value="<?php if($isValidProduct) echo $Product->brandName ?>" >
              </div>
            </div>

            <div class="form-group">
              <label for="ProductSize" class="control-label col-sm-3 lables"><span class="product-size-lable" >Size</span><span class="mandatoryLabel">*</span></label>
              <div class="col-sm-4">
                <input type="text" class="form-control product-size" name="ProductSize" readonly placeholder="Product Size" data-inputmask='"mask": "999/99 R99"' data-mask value="<?php if($isValidProduct) echo $Product->productSize ?>" >
              </div>
            </div>
              
            <div class="form-group">
              <label for="ProductPattern" class="control-label col-sm-3 lables"><span class="product-pattern-lable">Pattern</span><span class="mandatoryLabel">*</span></label>
              <div class="col-sm-4">
                <input type="text" class="form-control product-pattern" name="ProductPattern" readonly placeholder="Product Pattern" value="<?php if($isValidProduct) echo $Product->productPattern ?>" >
              </div>
            </div>

              <div class="form-group">
                <label for="CostPrice" class="control-label col-sm-3 lables">Cost Price<span class="mandatoryLabel">*</span></label>
                <div class="col-sm-4">
                  <div class='input-group'>
                    <span class="input-group-addon">
                        <span class="fa fa-inr"></span>
                    </span>
                    <input type="text" class="form-control rate currency" readonly name="CostPrice" maskedFormat="10,2" placeholder="0.00" value="<?php if($isValidProduct) echo $Product->costPrice ?>" >
                  </div>
                </div>
              </div>

              <div class="form-group">
                <label for="MinSellingPrice" class="control-label col-sm-3 lables">Min Selling Price<span class="mandatoryLabel">*</span></label>
                <div class="col-sm-4">
                  <div class='input-group'>
                    <span class="input-group-addon">
                        <span class="fa fa-inr"></span>
                    </span>
                    <input type="text" class="form-control rate currency" readonly name="MinSellingPrice" maskedFormat="10,2" placeholder="0.00" value="<?php if($isValidProduct) echo $Product->minSellingPrice ?>" >
                  </div>
                </div>
              </div>

              <div class="form-group">
                <label for="MaxSellingPrice" class="control-label col-sm-3 lables">Max Selling Price<span class="mandatoryLabel">*</span></label>
                <div class="col-sm-4">
                  <div class='input-group'>
                    <span class="input-group-addon">
                        <span class="fa fa-inr"></span>
                    </span>
                    <input type="text" class="form-control rate currency" readonly name="MaxSellingPrice" maskedFormat="10,2" placeholder="0.00" value="<?php if($isValidProduct) echo $Product->maxSellingPrice ?>" >
                  </div>
                </div>
              </div>             

              <div class="form-group">
                <label for="Qty" class="control-label col-sm-3 lables">Qty</label>
                <div class="col-sm-4">
                    <input type="text" class="form-control mobile"  name="Qty" onkeypress="return isNumberKey(event)" >
                </div>
              </div>
              
              <div class="form-group">
                <label class="col-sm-3 control-label no-padding-right" for="form-field-1"> </label>
                <div class="col-sm-9">
                  <input type="hidden" name="UKey" value="1" id="ID_UKey" />
                  <input type="hidden" value="<?php if($isValidProduct) echo $Product->productID; ?>" name="ProductID" >
                  <input type="submit" name="nc_submit" value="submit" id="ID_Sub" class="btn btn-sm btn-success" style="margin-left:50px" />
                </div>
              </div>

            </form>
              <!-- /.form -->
        </div>
        <!-- /.form div -->
      </div>
      <!-- /.box body -->
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