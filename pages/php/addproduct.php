<?php
require '_connect.php';
require '_core.php';

$CDATA['PAGE_NAME'] = 'ADDPRD';
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
      <div>Add or update Product</div>
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
    if(@$_POST['UKey'] == '2') {
      ChromePhp::log("AKEY" . $_POST['akey']);
      if(@$_POST['akey'] == $_SESSION['AUTH_KEY']) {
        if( isset($_POST['BrandID']) && !empty($_POST['BrandID']) &&
            isset($_POST['SupplierID']) && !empty($_POST['SupplierID']) &&
            isset($_POST['ProductTypeID']) && !empty($_POST['ProductTypeID']) &&
            isset($_POST['ProductName']) && !empty($_POST['ProductName']) &&
            isset($_POST['CostPrice']) && !empty($_POST['CostPrice']) &&
            isset($_POST['SellingPrice']) && !empty($_POST['SellingPrice']) )
          {
            $ProductInventory = new ProductInventory();
            
            $ProductInventory->dateOfEntry = date("y-m-d H:i:s");
            $ProductInventory->brandID = mysql_real_escape_string(trim($_POST['BrandID']));
            $ProductInventory->supplierID = mysql_real_escape_string(trim($_POST['SupplierID']));
            $ProductInventory->productTypeID = mysql_real_escape_string(trim($_POST['ProductTypeID']));
            $ProductInventory->productName = mysql_real_escape_string(trim($_POST['ProductName']));
            $ProductInventory->costPrice = mysql_real_escape_string(trim($_POST['CostPrice']));
            $ProductInventory->sellingPrice = mysql_real_escape_string(trim($_POST['SellingPrice']));
            $Qty = 0;
            
            if(isset($_POST['minStockAlert']) && !empty($_POST['minStockAlert']) )
               $ProductInventory->minStockAlert = 1;

               ChromePhp::log("min stock alert" . $ProductInventory->minStockAlert);

            if(isset($_POST['ProductNotes']) && !empty($_POST['ProductNotes']) )
               $ProductInventory->productNotes = mysql_real_escape_string(trim($_POST['ProductNotes']));

            if(isset($_POST['Qty']) && !empty($_POST['Qty']) )
              $Qty = (int)$_POST['Qty'];
            
            $result = false;
            $msg = '';
            if(isset($_POST['ProductID']) && !empty($_POST['ProductID'])) {
                  $ProductInventory->productID = mysql_real_escape_string(trim($_POST['ProductID']));
                  $result = UpdateProductInventory($ProductInventory);
                  $msg = 'Product updated successfully!';

            } else {
                $result = AddProductInventory($ProductInventory);
                $msg = 'Product added successfully!';
            }

            if($result) {
               
               if($Qty>0) {
                 $stock = new Stock();
                 $stock->ProductID= GetMaxProductInventoryID();
                 $stock->Qty=$Qty;
                 $stock->TansactionTypeID = 3;  //3=>purchase
                 if(AddStockEntry($stock))
                  $msg .= " And Stock entry created with type PURCHASE";
                 }
                  echo '<div class="alert alert-block alert-success">
                          <button type="button" class="close" data-dismiss="alert">
                            <i class="ace-icon fa fa-times"></i>
                          </button>
                          <i class="ace-icon fa fa-check green"></i>&nbsp;&nbsp;'.$msg .'&nbsp;&nbsp;</div>';
            } else {
              echo '<div class="alert alert-block alert-danger">
                      <button type="button" class="close" data-dismiss="alert">
                        <i class="ace-icon fa fa-times"></i>
                      </button>
                      <i class="ace-icon fa fa-ban red"></i>&nbsp;&nbsp;'.$msg .'&nbsp;&nbsp;</div>';
            }
          } else {
            echo '<div class="alert alert-block alert-danger">
                    <button type="button" class="close" data-dismiss="alert">
                      <i class="ace-icon fa fa-times"></i>
                    </button>
                    <i class="ace-icon fa fa-ban red"></i>
                    Please enter all the details.
                  </div>';
          }
          /* codefellas Security Robot for re-submission of form */
          $_SESSION['AUTH_KEY'] = mt_rand(100000000,999999999);
          ChromePhp::log($_SESSION['AUTH_KEY']);
          /* END */
        } else {

          echo '<div class="alert alert-block alert-danger">
                  <button type="button" class="close" data-dismiss="alert">
                    <i class="ace-icon fa fa-times"></i>
                  </button>
                  <i class="ace-icon fa fa-android red"></i>
                  Pikes ACE security Robot has detected re-submission of same data or hack attempt. Please try later.
                </div>';
        }
    }

      ?>
  </div>
    <!-- Default box -->
    <div class="box">
      <div class="box-header with-border">
        <h3 class="box-title">

        <?php
          $title="Add";
          if($GetProductInventory = @GetProductInventoryByID($_GET['id'])) {
            ChromePhp::log("got product id ");
            $Product  = mysql_fetch_assoc($GetProductInventory);
            ChromePhp::log($Product);
            $title="Modify";
          }
        ?>
        <h3 class="box-title"><?php echo $title; ?></h3>
       <!--  <div class="box-tools pull-right">
          <button type="button" class="btn btn-box-tool" data-widget="collapse" data-toggle="tooltip" title="Collapse">
            <i class="fa fa-minus"></i></button>
          <button type="button" class="btn btn-box-tool" data-widget="remove" data-toggle="tooltip" title="Remove">
            <i class="fa fa-times"></i></button>
        </div> -->
      </div>
      <div class="box-body">

      <div>
          <form class="form-horizontal" id="AddorUpdateProduct" name="AddorUpdateProduct" action="addProduct.php?_auth=<?php echo $_SESSION['AUTH_KEY']; ?>" method="post">
            <input type="hidden" value="<?php echo $_SESSION['AUTH_KEY']; ?>" name="akey" id="ID_akey" >
            <input type="hidden" value="14.5" name="VatPer">

            <div class="form-group">
                <label for="BrandID" class="control-label col-sm-3 lables">Brand<span class="mandatoryLabel">*</span></label>
                <div class="col-sm-4">
                    <select class="form-control" name="BrandID" >
                    <option selected="true" disabled="disabled" style="display: none" value="default" >Select Brand</option>
                    <?php
                        $brands = GetBrands();
                        if(mysql_num_rows($brands)!=0) {
                            while($brand = mysql_fetch_assoc($brands)) {
                                if( isset($Product['BrandID']) &&  $brand['BrandID'] == $Product['BrandID'] )
                                    echo '<option value="' . $brand['BrandID'] . '" selected>' . $brand['BrandName'] . '</option>';
                                else
                                    echo '<option value="' . $brand['BrandID'] . '">' . $brand['BrandName'] . '</option>';
                            }
                        }
                    ?>
                    </select>
                </div>
            </div>

            <div class="form-group">
                <label for="SupplierID" class="control-label col-sm-3 lables">Supplier<span class="mandatoryLabel">*</span></label>
                <div class="col-sm-4">
                    <select class="form-control"  name="SupplierID" >
                    <option selected="true" disabled="disabled" style="display: none" value="default" >Select Supplier</option>
                    <?php
                        $suppliers = GetSuppliers();
                        if(mysql_num_rows($suppliers)!=0) {
                            while($supplier = mysql_fetch_assoc($suppliers)) {
                                if(isset($Product['SupplierID']) && $supplier['SupplierID'] == $Product['SupplierID'] )
                                    echo '<option value="' . $supplier['SupplierID'] . '" selected >' . $supplier['SupplierName'] . '</option>';
                                else
                                    echo '<option value="' . $supplier['SupplierID'] . '" >' . $supplier['SupplierName'] . '</option>';
                            }
                        }
                    ?>
                    </select>
                </div>
              </div>

            <div class="form-group">
                <label for="ProductTypeID" class="control-label col-sm-3 lables">Product Type<span class="mandatoryLabel">*</span></label>
                <div class="col-sm-4">
                    <select class="form-control" name="ProductTypeID" >
                    <option selected="true" disabled="disabled" style="display: none" value="default" >Select Product Type</option>
                    <?php
                        $prodcutTypes = GetProdcutTypes();
                        if(mysql_num_rows($prodcutTypes)!=0) {
                            while($prodcutType = mysql_fetch_assoc($prodcutTypes)) {
                                if(isset($Product['ProductTypeID']) && $prodcutType['ProductTypeID'] == $Product['ProductTypeID'] )
                                    echo '<option value="' . $prodcutType['ProductTypeID'] . '" selected >' . $prodcutType['ProductTypeName'] . '</option>';
                                else
                                    echo '<option value="' . $prodcutType['ProductTypeID'] . '" >' . $prodcutType['ProductTypeName'] . '</option>';
                            }
                        }
                    ?>
                    </select>
                </div>
              </div>              

              <div class="form-group">
                <label for="ProductName" class="control-label col-sm-3 lables">Product Name<span class="mandatoryLabel">*</span></label>
                <div class="col-sm-4">
                  <input type="text" class="form-control" name="ProductName" placeholder="Product Name" value="<?php if(isset($Product['ProductName'])) echo $Product['ProductName']; ?>" >
                </div>
              </div>

              <div class="form-group">
                <label for="CostPrice" class="control-label col-sm-3 lables">Cost Price<span class="mandatoryLabel">*</span></label>
                <div class="col-sm-4">
                  <div class='input-group'>
                    <span class="input-group-addon">
                        <span class="fa fa-inr"></span>
                    </span>
                    <input type="text" class="form-control rate currency" name="CostPrice" maskedFormat="10,2" placeholder="0.00" value="<?php if(isset($Product['CostPrice'])) echo $Product['CostPrice']; ?>" >
                  </div>
                </div>
              </div>

              <div class="form-group">
                <label for="SellingPrice" class="control-label col-sm-3 lables">Selling Price<span class="mandatoryLabel">*</span></label>
                <div class="col-sm-4">
                  <div class='input-group'>
                    <span class="input-group-addon">
                        <span class="fa fa-inr"></span>
                    </span>
                    <input type="text" class="form-control rate currency" name="SellingPrice" maskedFormat="10,2" placeholder="0.00" value="<?php if(isset($Product['SellingPrice'])) echo $Product['SellingPrice']; ?>" >
                  </div>
                </div>
              </div>

            <div class="form-group">
                <label for="ProductNotes" class="control-label col-sm-3 lables">Notes</label>
                <div class="col-sm-4">
                <textarea  class="form-control" name="ProductNotes" placeholder="Notes"><?php if(isset($Product['ProductNotes'])) echo $Product['ProductNotes']; ?></textarea>
                </div>
            </div>

            <div class="form-group">
                <label class="control-label col-sm-3 lables"></label>
                <div class="col-sm-4">
                    <div class="checkbox">
                        <label>
                        <input name="minStockAlert" type="checkbox" <?php if(isset($Product['MinStockAlert']) && $Product['MinStockAlert'] == '1' ) echo 'checked'; else if(isset($Product['MinStockAlert']) && $Product['MinStockAlert'] == '0')  echo ''; else echo 'checked';?> > show me stock notification for this prodcut
                        </label>
                    </div>    
                </div>
            </div>     

          <div class="form-group">        
            <hr class="col-md-offset-2 col-sm-6">
          </div>

          <div class="form-group" <?php if(isset($Product['ProductID'])) echo 'hidden'; ?> >
            <label for="Qty" class="control-label col-sm-3 lables">Qty</label>
            <div class="col-sm-4">    
              <input type="text" class="form-control" name="Qty" onkeypress='return event.charCode >= 48 && event.charCode <= 57' >
              <span class="blue">Note : Adding Quantity here will create stock entry immediately.<br/>you can leave this balnk as well if you don't want to create stock entry</span>
            </div>
          </div>

          <div class="form-group">
            <label class="col-sm-3 control-label no-padding-right" for="form-field-1"> </label>

            <div class="col-sm-9">
              <input type="submit" name="nc_submit" value="submit" id="ID_Sub" class="btn btn-sm btn-success" style="<?php if(isset($Product['ProductID'])) echo 'margin-left:90px'; else echo 'margin-left:50px'; ?>" />
              <input type="hidden" name="UKey" value="1" id="ID_UKey" />
              <input type="hidden" value="<?php if(isset($Product['ProductID'])) echo $Product['ProductID']; ?>" name="ProductID" >
              <button type="reset" class="btn btn-sm btn-default" style="visibility:<?php if(isset($Product['ProductID'])) echo 'hidden'; else 'visible'?> ">Clear</button>
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
