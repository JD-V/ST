<?php
require '_connect.php';
require '_core.php';

$CDATA['PAGE_NAME'] = 'ADDPRD';
if(isLogin() && isAdmin())
{
require '_header.php'

?>
<script type="text/javascript">

  function ProductTypechanged(e) {
    var type = $(e).val();
    
    if(type <=4) {
      // $(e).closest('fieldset').find('.product-brand-lable').html('Brand');
      $('.product-size-lable').html('Size');
      $('.product-pattern-lable').html('Pattern');

      $(".product-pattern").rules("add", {
         required: true,
      });
    } else {
      // $(e).closest('fieldset').find('.product-brand-lable').html('Description Line 1');
      $('.product-size-lable').html('Description Line 1');
      $('.product-pattern-lable').html('Description Line 2');
      $('.product-pattern').rules( "remove", "required" );
    }

    if(type == 1) {
        $("[data-mask]").inputmask();
        $("[data-mask]").rules("add", {
          productSizeFormat: true,
        });
    } else {
      $("[data-mask]").inputmask('remove');    
      $("[data-mask]").rules( "remove", "productSizeFormat" );
    }
  }

  function CheckAvailibility() {
    var productType = $("[name=ProductTypeID]").val();
    var productSize = $("[name=ProductSize]").val().trim();
    var productBrand = $("[name=BrandID]").val();
    var productPattern = $("[name=ProductPattern]").val().trim();

    if(productType == null) {
      $(".message").html('');
      return;
    } else if(productBrand == null) {
      $(".message").html('');
      return;
    } else if(productSize == '') {
       $(".message").html('');
       return;
    }

    $.ajax({
	    dataType: "json",
	    type: "GET",
	    url: "retriveproductdetails.php?action=Retrive&item=InventoryProduct&BrandID="+productBrand+"&TypeID="+productType+"&Size="+productSize+"&Pattern="+productPattern,
	    success: function(result) {
        if(result.productID !='' && result.productID != undefined)
  	      $(".message").html("<i class=\"fa fa-check\"></i> Product Available");
        else
          $(".message").html("<i class=\"fa fa-check\"></i> New Product");
	    },
	    error: function(XMLHttpRequest, textStatus, errorThrown) {
        $(".message").html("<i class=\"fa fa-check\"></i> error");
	        console.log("Status: " + textStatus);
          console.log("Error: " + errorThrown);
	    }
	  });
  }
</script>


<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
  <div id='message' style='display: none;'></div>

  <!-- Content Header (Page header) -->
  <section class="content-header" id="MainEventInfo" >
    <h1>
      <div>Add Product / Stock</div>
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
        if( 
            //isset($_POST['SupplierID']) && !empty($_POST['SupplierID']) &&
            isset($_POST['BrandID']) && !empty($_POST['BrandID']) &&
            isset($_POST['ProductSize']) && !empty($_POST['ProductSize']) &&
            // isset($_POST['ProductPattern']) && !empty($_POST['ProductPattern']) &&
            isset($_POST['ProductTypeID']) && !empty($_POST['ProductTypeID'])
            // isset($_POST['CostPrice']) && !empty($_POST['CostPrice']) &&
            // isset($_POST['MaxSellingPrice']) && !empty($_POST['MaxSellingPrice']) &&
            // isset($_POST['MinSellingPrice']) && !empty($_POST['MinSellingPrice'])
             )
          {
            $ProductInventory = new ProductInventory();
            $ProductInventory->productID  = GetMaxProductInventoryID();

            if($ProductInventory->productID != NULL)
              $ProductInventory->productID++;
            // $ProductInventory->supplierID = FilterInput($_POST['SupplierID']);
            $ProductInventory->brandID = FilterInput($_POST['BrandID']);
            $ProductInventory->productSize = FilterInput($_POST['ProductSize']);

            $ProductInventory->productPattern ='';
            if(isset($_POST['ProductPattern'])){
              $ProductInventory->productPattern = FilterInput($_POST['ProductPattern']);
            } 
            
            $ProductInventory->productTypeID = FilterInput($_POST['ProductTypeID']);
            // $ProductInventory->costPrice = FilterInput($_POST['CostPrice']);
            // $ProductInventory->minSellingPrice = FilterInput($_POST['MinSellingPrice']);
            // $ProductInventory->maxSellingPrice = FilterInput($_POST['MaxSellingPrice']);
            $Qty = 0;
            
            if(isset($_POST['minStockAlert']) && !empty($_POST['minStockAlert']) )
               $ProductInventory->minStockAlert = 1;

               ChromePhp::log("min stock alert" . $ProductInventory->minStockAlert);

            if(isset($_POST['ProductNotes']) && !empty($_POST['ProductNotes']) )
               $ProductInventory->productNotes = FilterInput($_POST['ProductNotes']);

            if(isset($_POST['Qty']) && !empty($_POST['Qty']))
              $ProductInventory->qty = (int)$_POST['Qty'];
            
            $result = false;
            $msg = '';
            
            $resultObject = AddProductInventory($ProductInventory);
            if($resultObject->isProductAdded == 1)
              $msg = 'Product added successfully!';
            
            if($resultObject->isProductAdded == 1 && $resultObject->isStockAdded == 1)
              $msg .= " and ";

            if($resultObject->isStockAdded == 1)
              $msg .= 'Stock added successfully!';
                            
            if($resultObject->isProductAdded == 0 && $resultObject->isStockAdded == 0) 
              echo MessageTemplate(MessageType::Failure, "Something went wrong, please contact system admin");
            else
              echo MessageTemplate(MessageType::Success, $msg);

            
            
          } else {
            echo MessageTemplate(MessageType::Failure, "Please enter all the details.");
          }
          /* codefellas Security Robot for re-submission of form */
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
        <h3 class="box-title">

        <?php
          $isValidProduct = false;
          $title="Add";
          $Product = NULL;
          if(isset($_GET['id'])) {
            $Product = GetProductInventoryByID2($_GET['id']);
            if($Product == NULL) {
              $title="Product Not Found";
            }
            else {
              $title="Update";
              $isValidProduct = true;
            }
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
          <form class="form-horizontal" id="AddorUpdateProduct" name="AddorUpdateProduct" action="addproduct.php?_auth=<?php echo $_SESSION['AUTH_KEY']; ?>" method="post">
            <input type="hidden" value="<?php echo $_SESSION['AUTH_KEY']; ?>" name="akey" id="ID_akey" >
            <input type="hidden" value="14.5" name="VatPer">

            <div class="form-group">
                <label for="ProductTypeID" class="control-label col-sm-3 lables">Product Type<span class="mandatoryLabel">*</span></label>
                <div class="col-sm-4">
                    <select class="form-control" name="ProductTypeID" onchange="ProductTypechanged(this)" >
                    <option selected="true" disabled="disabled" style="display: none" value="default" >Select Product Type</option>
                    <?php
                        $prodcutTypes = GetProdcutTypes();
                        if(mysql_num_rows($prodcutTypes)!=0) {
                            while($prodcutType = mysql_fetch_assoc($prodcutTypes)) {
                              if($isValidProduct && $prodcutType['ProductTypeID'] == $Product->productTypeID )
                                  echo '<option value="' . $prodcutType['ProductTypeID'] . '" selected >' . $prodcutType['ProductTypeName'] . '</option>';
                              else
                                  echo '<option value="' . $prodcutType['ProductTypeID'] . '" >' . $prodcutType['ProductTypeName'] . '</option>';
                            }
                        }
                    ?>
                    </select>
                </div>
              </div>

            <!--<div class="form-group">
                <label for="SupplierID" class="control-label col-sm-3 lables">Supplier<span class="mandatoryLabel">*</span></label>
                <div class="col-sm-4">
                  <select class="form-control"  name="SupplierID" >
                  <option selected="true" disabled="disabled" style="display: none" value="default" >Select Supplier</option>
                    <?php
                        $suppliers = GetSuppliers();
                        if(mysql_num_rows($suppliers)!=0) {
                            while($supplier = mysql_fetch_assoc($suppliers)) {
                              if($isValidProduct && $supplier['SupplierID'] == $Product->supplierID )
                                  echo '<option value="' . $supplier['SupplierID'] . '" selected >' . $supplier['SupplierName'] . '</option>';
                              else
                                  echo '<option value="' . $supplier['SupplierID'] . '" >' . $supplier['SupplierName'] . '</option>';
                            }
                        }
                    ?>
                  </select>
              </div>
            </div>-->

            <div class="form-group">
                <label for="BrandID" class="control-label col-sm-3 lables product-brand-lable">Brand<span class="mandatoryLabel">*</span></label>
                <div class="col-sm-4">
                    <select class="form-control product-brand" name="BrandID" >
                    <option selected="true" disabled="disabled" style="display: none" value="default" >Select Brand</option>
                    <?php
                        $brands = GetBrands();
                        if(mysql_num_rows($brands)!=0) {
                            while($brand = mysql_fetch_assoc($brands)) {
                                if( $isValidProduct &&  $brand['BrandID'] == $Product->brandID )
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
              <label for="ProductSize" class="control-label col-sm-3 lables"><span class="product-size-lable">Size</span><span class="mandatoryLabel">*</span></label>
              <div class="col-sm-4">
                <input type="text" class="form-control product-size" name="ProductSize" data-inputmask='"mask": "999/99 R99"' data-mask value="<?php if($isValidProduct) echo $Product->productSize ?>" >
              </div>
            </div>

            <div class="form-group">
              <label for="ProductPattern" class="control-label col-sm-3 lables"><span class="product-pattern-lable">Pattern</span><span class="mandatoryLabel">*</span></label>
              <div class="col-sm-4">
                <input type="text" class="form-control product-pattern" name="ProductPattern" value="<?php if($isValidProduct) echo $Product->productPattern ?>" >
              </div>
            </div>

              <!--<div class="form-group">
                <label for="CostPrice" class="control-label col-sm-3 lables">Cost Price<span class="mandatoryLabel">*</span></label>
                <div class="col-sm-4">
                  <div class='input-group'>
                    <span class="input-group-addon">
                        <span class="fa fa-inr"></span>
                    </span>
                    <input type="text" class="form-control rate currency" name="CostPrice" maskedFormat="10,2" placeholder="0.00" value="<?php if($isValidProduct) echo $Product->costPrice ?>" >
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
                    <input type="text" class="form-control rate currency" name="MinSellingPrice" maskedFormat="10,2" placeholder="0.00" value="<?php if($isValidProduct) echo $Product->minSellingPrice ?>" >
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
                    <input type="text" class="form-control rate currency" name="MaxSellingPrice" maskedFormat="10,2" placeholder="0.00" value="<?php if($isValidProduct) echo $Product->maxSellingPrice ?>" >
                  </div>
                </div>
              </div>-->

            <div class="form-group">
                <label for="ProductNotes" class="control-label col-sm-3 lables">Notes</label>
                <div class="col-sm-4">
                <textarea  class="form-control" name="ProductNotes" placeholder="Notes"><?php if($isValidProduct) echo $Product->productNotes ?></textarea>
                </div>
            </div>

            <div class="form-group">
                <label class="control-label col-sm-3 lables"></label>
                <div class="col-sm-4">
                    <div class="checkbox">
                        <label>
                        <input name="minStockAlert" type="checkbox" <?php if($isValidProduct && $Product->minStockAlert == '1' ) echo 'checked'; else if($isValidProduct && $Product->minStockAlert == '0')  echo ''; else echo 'checked';?> > show me stock notification for this prodcut
                        </label>
                    </div>    
                </div>
            </div>     

          <div class="form-group">        
            <hr class="col-md-offset-2 col-sm-6">
          </div>

          <div class="form-group" <?php if($isValidProduct) echo 'hidden'; ?> >
            <label for="Qty" class="control-label col-sm-3 lables">Qty</label>
            <div class="col-sm-4">    
              <input type="text" class="form-control" onfocus="CheckAvailibility()" name="Qty" onkeypress='return event.charCode >= 48 && event.charCode <= 57' >
              <span class="blue">Note : Adding Quantity here will create stock entry immediately.<br/>you can leave this balnk as well, if you don't want to create stock entry</span>
            </div>
            <div class="message green"></div>
          </div>

          <div class="form-group">
            <label class="col-sm-3 control-label no-padding-right" for="form-field-1"> </label>

            <div class="col-sm-9">
              <input type="submit" name="nc_submit" value="submit" id="ID_Sub" class="btn btn-sm btn-success" style="<?php if($isValidProduct) echo 'margin-left:90px'; else echo 'margin-left:50px'; ?>" />
              <input type="hidden" name="UKey" value="1" id="ID_UKey" />
              <input type="hidden" value="<?php if($isValidProduct) echo $Product->productID; ?>" name="ProductID" >
              <button type="reset" class="btn btn-sm btn-default" style="visibility:<?php if($isValidProduct) echo 'hidden'; else 'visible'?> ">Clear</button>
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
