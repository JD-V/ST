<?php
require '_connect.php';
require '_core.php';

$CDATA['PAGE_NAME'] = 'ADDINV';
if(isLogin() && isAdmin())
{
require '_header.php'

?>

<script type="text/javascript">
  function AddProductFieldset(e){

  var $fieldsetmain = $(e).closest( 'fieldset' );
  var $fieldset =  $(e).closest( 'fieldset' ).clone();
  $( $fieldset ).find( "input" ).val('');   //clears out input field
  $fieldsetmain.parent().append($fieldset);

  // if new pattern is 

  addMultiInputNamingRules('#AddorUpdateInvoice','ProductTypeID[]', 'select[name="ProductTypeID[]"]', {
      required: true,
      messages: {
          required: "Required",
      }
  } );

  addMultiInputNamingRules('#AddorUpdateInvoice','ProductSize[]', 'input[name="ProductSize[]"]', {
      required: true,
      messages: {
          required: "Required",
      }
  } );

  addMultiInputNamingRules('#AddorUpdateInvoice','Brand[]', 'input[name="Brand[]"]', {
      required: true,
      messages: {
          required: "Required",
      }
  } );

  addMultiInputNamingRules('#AddorUpdateInvoice','ProductPattern[]', 'input[name="ProductPattern[]"]', {
      required: true,
      messages: {
          required: "Required",
      }
  } );


  addMultiInputNamingRules('#AddorUpdateInvoice','NewProductPattern[]', 'input[name="NewProductPattern[]"]', {
      required: true,
      messages: {
          required: "Required",
      }
  } );  

  addMultiInputNamingRules('#AddorUpdateInvoice','Quantity[]', 'input[name="Quantity[]"]', {
      required: true,
      messages: {
          required: "Please Enter Quantity",
      }
  });

  addMultiInputNamingRules('#AddorUpdateInvoice','Rate[]', 'input[name="Rate[]"]', {
      required: true,
      messages: {
          required: "Please Enter Product Rate",
      }
  });

  addMultiInputNamingRules('#AddorUpdateInvoice','Amount[]', 'input[name="Amount[]"]', {
      required: true,
      messages: {
          required: "Please Enter Amount",
      }
  });

}

function ProductTypechanged(e) {
  var type = $(e).val();
  
  if(type == 5 || type == 6) {
    // $(e).closest('fieldset').find('.product-brand-lable').html('Description Line 1');
    $(e).closest('fieldset').find('.product-size-lable').html('Description Line 1');
    $(e).closest('fieldset').find('.product-pattern-lable').html('Description Line 2');

    // $(e).closest('fieldset').find('.product-size').rules( "remove", "required" );
    $(e).closest('fieldset').find('.product-pattern').rules( "remove", "required" );
  } else {
    // $(e).closest('fieldset').find('.product-brand-lable').html('Brand');
    $(e).closest('fieldset').find('.product-size-lable').html('Size');
    $(e).closest('fieldset').find('.product-pattern-lable').html('Pattern');


    // $(e).closest('fieldset').find(".product-size").rules("add", {
    //   required: true,
    // });
    $(e).closest('fieldset').find(".product-pattern").rules("add", {
        required: true,
        messages: {
          required: "Please Enter Product Pattern",
        }
    });    
  }

  if(type == 1) {
      $(e).closest('fieldset').find("[data-mask]").inputmask();
      $(e).closest('fieldset').find("[data-mask]").rules("add", {
        productSizeFormat: true,
      });
  } else {
    $(e).closest('fieldset').find("[data-mask]").inputmask('remove');    
    $(e).closest('fieldset').find("[data-mask]").rules( "remove", "productSizeFormat" );
  }
}

function CalculateAmount(e) {

  var $fieldsetCurrent = $(e).closest( 'fieldset' );

  var Qty = $fieldsetCurrent.find('.quantity').val();
  var Rate = $fieldsetCurrent.find('.rate').val();

  if(Qty == '')
    Qty = 0;

  if(Rate == '')
    Rate = 0.00;

  if($.isNumeric(Qty) && $.isNumeric(Rate) ) {
    $fieldsetCurrent.find('.amount').val((Rate*Qty).toFixed(2));
    UpdateFinalSubtotal();
  }
  else
    $fieldsetCurrent.find('.amount').val('');

}

function UpdateFinalSubtotal() {
  var VatRate = $('#VatPer').val();
  var finalSubtotal = 0.00;
  $('#AddorUpdateInvoice').find('input.amount[type="text"]').each(function(index) {
    if( $.isNumeric($(this).val()) )
      finalSubtotal += + $(this).val();
  }); 

  if($.isNumeric(finalSubtotal)) {
    $('#AddorUpdateInvoice').find('.subtotal').val( finalSubtotal.toFixed(2) );
    $('#AddorUpdateInvoice').find('.vat-amount').val( (finalSubtotal*VatRate/100).toFixed(2)  );
    $('#AddorUpdateInvoice').find('.total-paid').val( (finalSubtotal + finalSubtotal*VatRate/100  ).toFixed(2) );
  }
  else {
    $('#AddorUpdateInvoice').find('.total-paid').val('0.00');
    $('#AddorUpdateInvoice').find('.vat-amount').val('0.00');
    $('#AddorUpdateInvoice').find('.total-paid').val('0.00');    
  }
}

function RemoveProductFieldset(e) {

  $(e).parent().fadeOut(300, function() {
       //Remove parent element (main section)
       $(e).closest( 'fieldset' ).remove();
       UpdateFinalSubtotal();
       return false;
   });
}

function PatternSelectionChage(e)  {
  if(e.value == 0) {
    // $(e).closest( 'fieldset' ).find('.product-pattern').rules( "remove", "required" );  // remove rule from product pattern
    
    $(e).closest( 'fieldset' ).find('.new-product-pattern').removeAttr("disabled");    // enable new-product-pattern and add rule
    
    $("#AddorUpdateInvoice").validate(); //sets up the validator
    $(e).closest('fieldset').find('.new-product-pattern').rules("add", {
        required: true,
    });
  } else {
    $(e).closest( 'fieldset' ).find('.new-product-pattern').attr("disabled", "disabled"); // disable new-product-pattern and remove rule
    $(e).closest( 'fieldset' ).find('.new-product-pattern').val("-");      //clear it our

    $("#AddorUpdateInvoice").validate(); //sets up the validator
    $(e).closest( 'fieldset' ).find('.new-product-pattern').rules( "remove", "required" );

  }
}
  // $(function () {
  //     $("[data-mask]").inputmask();
  // });

$(document).ready(function () {

  $('input[name="paymentType"]').on('change', function(e) {
      var paymentType = $("input[name='paymentType']:checked").val();
      if(paymentType != 3) {
        $("#ChequeNoDiv").prop('hidden', true);
        $("#ChequeDateDiv").prop('hidden', true);

        $("#AddorUpdateInvoice").validate(); //sets up the validator
        $('input[name="ChequeNo"]').rules("remove", "required");
        $('input[name="ChequeDate"]').rules("remove", "required");

      } else {
        $("#ChequeNoDiv").prop('hidden', false);
        $("#ChequeDateDiv").prop('hidden', false);

        $("#AddorUpdateInvoice").validate(); //sets up the validator
        $('input[name="ChequeNo"]').rules("add", {
            required: true,
            messages: {
                required: "Please Enter Cheque Number",
            }
        });
        $('input[name="ChequeDate"]').rules("add", {
            required: true,
            messages: {
                required: "Please Enter Cheque Date",
            }
        });
      }
  });
});
</script>


<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
  <div id='message' style='display: none;'></div>

  <!-- Content Header (Page header) -->
  <section class="content-header" id="MainEventInfo" >
    <h1>
      <div>Invoice</div>
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
    if(@$_POST['UKey'] == '2')
    {
      if(@$_POST['akey'] == $_SESSION['AUTH_KEY'])
      {
        ChromePhp::log("NewProductPattern = ". isset($_POST['NewProductPattern']) . " count =" . count($_POST['NewProductPattern']));
        ChromePhp::log("ProductPattern = ". isset($_POST['ProductPattern']) ." count". count($_POST['ProductPattern'])  );
        ChromePhp::log("InvoiceDate = " .isset($_POST['InvoiceDate']) );
        ChromePhp::log("InvoiceDate = " .isset($_POST['InvoiceDate']) );
        ChromePhp::log("SupplierID = " .isset($_POST['SupplierID']) );
        ChromePhp::log("InvoiceNumber = " .isset($_POST['InvoiceNumber']) );
        ChromePhp::log("Brand = " .isset($_POST['Brand']) );
        ChromePhp::log("ProductPattern = " .isset($_POST['ProductPattern']) );
        ChromePhp::log("NewProductPattern = " .isset($_POST['NewProductPattern']) );
        ChromePhp::log("Quantity = " .isset($_POST['Quantity']) );
        ChromePhp::log("Rate = " .isset($_POST['Rate']) );
        ChromePhp::log("Amount = " .isset($_POST['Amount']) );
        ChromePhp::log("SubTotalAmount = " .isset($_POST['SubTotalAmount']) );
        ChromePhp::log("VatAmount = " .isset($_POST['VatAmount']) );
        ChromePhp::log("TotalAmount = " .isset($_POST['TotalAmount']) );
        ChromePhp::log("paymentType = " .isset($_POST['paymentType']) );

        if( isset($_POST['InvoiceDate']) && !empty($_POST['InvoiceDate']) &&
            isset($_POST['SupplierID']) && !empty($_POST['SupplierID']) &&
            isset($_POST['InvoiceNumber']) && !empty($_POST['InvoiceNumber']) &&
            isset($_POST['ProductSize']) && !empty($_POST['ProductSize']) &&
            isset($_POST['Brand']) && !empty($_POST['Brand']) &&
            isset($_POST['ProductPattern']) && !empty($_POST['ProductPattern']) &&
            isset($_POST['Quantity']) && !empty($_POST['Quantity']) &&
            isset($_POST['Rate']) && !empty($_POST['Rate']) &&
            isset($_POST['Amount']) && !empty($_POST['Amount']) &&
            isset($_POST['SubTotalAmount']) && !empty($_POST['SubTotalAmount']) &&
            isset($_POST['VatAmount']) && !empty($_POST['VatAmount']) &&
            isset($_POST['TotalAmount']) && !empty($_POST['TotalAmount']) &&
            isset($_POST['paymentType']) && !empty($_POST['paymentType']) )
          {
            $Invoice = new Invoice();
            
            $dateStr = FilterInput($_POST['InvoiceDate']);
            $date = DateTime::createFromFormat('d-m-Y', $dateStr);
            $Invoice->invoiceDate = $date->format('Y-m-d'); // => 2013-12-24
            $Invoice->supplierID = FilterInput($_POST['SupplierID']);
            $Invoice->invoiceNumber = FilterInput($_POST['InvoiceNumber']);
            $Invoice->totalAmount = FilterInput($_POST['TotalAmount']);
            $Invoice->vatAmount = FilterInput($_POST['VatAmount']);
            $Invoice->subTotalAmount = FilterInput($_POST['SubTotalAmount']);
            $Invoice->paymentType = FilterInput($_POST['paymentType']);

            if(isset($_POST['InvoiceNotes']) && !empty($_POST['InvoiceNotes']) )
              $Invoice->invoiceNotes = FilterInput($_POST['InvoiceNotes']);
            
            if($Invoice->paymentType == 3) {
              $Invoice->chequeNo = FilterInput($_POST['ChequeNo']);

              $dateC = date_create(FilterInput($_POST['ChequeDate'])); 
              $Invoice->chequeDate = date_format($dateC, 'Y-m-d H:i');
            }

            $Invoice->invoiceID = GetMaxInvoiceID() + 1;
            
            if(AddInvoice($Invoice))
            {
              $successCountProduct = 0;
              $successCountInventotry = 0;
              $successCountStock = 0;
              $ProductTypeID = $_POST['ProductTypeID'];
              $productSize = $_POST['ProductSize'];
              $productPattern = $_POST['ProductPattern'];

              if(isset($_POST['NewProductPattern']))
                $newProductPattern = $_POST['NewProductPattern'];

              $brand = $_POST['Brand'];
              $units = $_POST['Quantity'];
              $rate = $_POST['Rate'];
              $amount = $_POST['Amount'];
              $newPatternCount = 0;
              for ($i=0; $i < count($productSize); $i++) {
                $Product =  new Product();
                $Product->invoiceID = $Invoice->invoiceID;
                $Product->productTypeID = $ProductTypeID[$i];
                $Product->productSize = $productSize[$i];
                
                if($productPattern[$i] == '0') {
                  $Product->productPattern = $newProductPattern[$newPatternCount];
                  $newPatternCount++;
                }
                else
                  $Product->productPattern = $productPattern[$i];
                
                $Product->brandID = $brand[$i];
                $Product->units = $units[$i];
                $Product->rate = $rate[$i];
                $Product->amount = $amount[$i];

                $result = AddProduct($Product);
                if($result->isProductAdded == 1)
                  $successCountProduct++;
                if($result->isInventoryProductAdded == 1)
                  $successCountInventotry++;
                if($result->isStockAdded == 1)
                  $successCountStock++;
              }
              echo MessageTemplate(MessageType::Success, "Invoice Added successfully. with " . $successCountProduct . 
                " product(s) and  ". $successCountInventotry ." new Inventory Product. And with " . $successCountStock ." Stock Entry");
            } else {
              echo MessageTemplate(MessageType::Failure, "Something went wrong, please contact your system admin.");
            }
          } else {
            echo MessageTemplate(MessageType::Failure, "Please enter all the details.");
          }
          /* pikesAce Security Robot for re-submission of form */
          $_SESSION['AUTH_KEY'] = mt_rand(100000000,999999999);
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
        <h3 class="box-title">Add</h3>
       <!--  <div class="box-tools pull-right">
          <button type="button" class="btn btn-box-tool" data-widget="collapse" data-toggle="tooltip" title="Collapse">
            <i class="fa fa-minus"></i></button>
          <button type="button" class="btn btn-box-tool" data-widget="remove" data-toggle="tooltip" title="Remove">
            <i class="fa fa-times"></i></button>
        </div> -->
      </div>
      <div class="box-body">

      <div>
          <form class="form-horizontal" id="AddorUpdateInvoice" name="AddorUpdateInvoice" action="addInvoice.php?_auth=<?php echo $_SESSION['AUTH_KEY']; ?>" method="post">
              <input type="hidden" value="<?php echo $_SESSION['AUTH_KEY']; ?>" name="akey" id="ID_akey" >
              <input type="hidden" value="<?php echo $_SESSION['VatFactor']; ?>" name="VatPer" id="VatPer">

              <div class="form-group">
                <label for="InvoiceDate" class="control-label col-sm-3 lables">Invoice Date<span class="mandatoryLabel">*</span></label>
                <div class='col-sm-4'>
                  <input type="datetime" class="form-control" name="InvoiceDate" id="InvoiceDate"  />
                </div>
              </div>

              <div class="form-group">
                  <label for="SupplierID" class="control-label col-sm-3 lables">Supplier<span class="mandatoryLabel">*</span></label>
                  <div class="col-sm-4">
                    <select class="form-control" name="SupplierID" >
                    <option selected="true" disabled="disabled" style="display: none" value="default" >Select Supplier</option>
                      <?php
                          $suppliers = GetSuppliers();
                          if(mysql_num_rows($suppliers)!=0) {
                              while($supplier = mysql_fetch_assoc($suppliers)) {
                                    echo '<option value="' . $supplier['SupplierID'] . '" >' . $supplier['SupplierName'] . '</option>';
                              }
                          }
                      ?>
                    </select>
                </div>
              </div>

              <div class="form-group">
                <label for="InvoiceNumber" class="control-label col-sm-3 lables">Invoice number<span class="mandatoryLabel">*</span></label>
                <div class="col-sm-4">
                  <input type="text" class="form-control" name="InvoiceNumber" placeholder="Invoice Number" >
                </div>
              </div>

          <div id="products" class="col-sm-12">
          
            <fieldset class="col-sm-9 col-xs-offset-1">
                <legend>Products<span class="mandatoryLabel">*</span></legend>

              <div class="form-group">
                <label for="ProductTypeID" class="control-label col-sm-3 lables">Product Type<span class="mandatoryLabel">*</span></label>
                <div class="col-sm-4">
                    <select class="form-control" name="ProductTypeID[]" onchange="ProductTypechanged(this)" >
                    <option selected="true" disabled="disabled" style="display: none" value="default" >Select Product Type</option>
                    <?php
                        $prodcutTypes = GetProdcutTypes();
                        if(mysql_num_rows($prodcutTypes)!=0) {
                            while($prodcutType = mysql_fetch_assoc($prodcutTypes)) {
                                  echo '<option value="' . $prodcutType['ProductTypeID'] . '" >' . $prodcutType['ProductTypeName'] . '</option>';
                            }
                        }
                    ?>
                    </select>
                </div>
              </div>

              <!--<div class="form-group">
                <label for="Brand" class="control-label col-sm-3 lables"><span class="product-brand-lable">Brand</span><span class="mandatoryLabel">*</span></label>
                <div class="col-sm-4">
                  <input type="text" class="form-control product-brand" name="Brand[]" placeholder="" >
                </div>
              </div>-->

              <div class="form-group">
                  <label for="Brand" class="control-label col-sm-3 lables product-brand-lable">Brand<span class="mandatoryLabel">*</span></label>
                  <div class="col-sm-4">
                      <select class="form-control product-brand" name="Brand[]" >
                      <option selected="true" disabled="disabled" style="display: none" value="default" >Select Brand</option>
                      <?php
                          $brands = GetBrands();
                          if(mysql_num_rows($brands)!=0) {
                              while($brand = mysql_fetch_assoc($brands)) {
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
                  <input type="text" class="form-control product-size" name="ProductSize[]" data-inputmask='"mask": "999/99 R99"' data-mask>
                </div>
              </div>


              <div class="form-group" >
                  <label for="ProductPattern" class="control-label col-sm-3 lables product-brand-lable">Pattern<span class="mandatoryLabel">*</span></label>
                  <div class="col-sm-4">
                      <select class="form-control product-pattern" name="ProductPattern[]" onchange="PatternSelectionChage(this)" >
                      <option selected="true" disabled="disabled" style="display: none" value="default" >Select Pattern</option>
                      <option value="0" >None of this</option>
                      <?php
                        $patterns = GetPatterns();
                        if(mysql_num_rows($patterns)!=0) {
                            while($pattern = mysql_fetch_assoc($patterns)) {
                                echo '<option value="' . $pattern['Pattern'] . '">' . $pattern['Pattern'] . '</option>';
                            }
                        }
                      ?>
                      </select>
                  </div>
              </div>

              <div class="form-group">
                <label for="NewProductPattern" class="control-label col-sm-3 lables"><span class="new-product-pattern-lable">New Pattern</span><span class="mandatoryLabel">*</span></label>
                <div class="col-sm-4">
                  <input type="text" class="form-control new-product-pattern" value="-" name="NewProductPattern[]" placeholder="" disabled>
                </div>
              </div>

              <div class="form-group">
                <label for="Quantity" class="control-label col-sm-3 lables ">Quantity<span class="mandatoryLabel">*</span></label>
                <div class="col-sm-4">
                  <input type="text" class="form-control quantity" name="Quantity[]" placeholder="0" onchange="CalculateAmount(this)"  onkeypress="return isNumberKey(event)">
                </div>
              </div>

              <div class="form-group">
                <label for="Rate" class="control-label col-sm-3 lables">Rate<span class="mandatoryLabel">*</span></label>
                <div class="col-sm-4">
                  <div class='input-group'>
                    <span class="input-group-addon">
                        <span class="fa fa-inr"></span>
                    </span>
                    <input type="text" class="form-control rate currency" name="Rate[]" maskedFormat="10,2" placeholder="0.00" onchange="CalculateAmount(this)" >
                  </div>
                </div>
              </div>

              <div class="form-group">
                <label for="Amount" class="control-label col-sm-3 lables">Amount (Rate*Qty)<span class="mandatoryLabel">*</span></label>
                <div class="col-sm-4">
                  <div class='input-group'>
                    <span class="input-group-addon">
                        <span class="fa fa-inr"></span>
                    </span>
                    <input type="text" class="form-control amount currency" name="Amount[]" maskedFormat="10,2" placeholder="0.00" >
                  </div>
                </div>
              </div>

              <div class="form-group">
                <label class="col-sm-3 control-label no-padding-right" for="form-field-1"> </label>
                <div class="col-sm-4 col-xs-offset-1">
                  <button type="button" name="AddProduct[]" class="btn btn-sm btn-primary"  onclick="AddProductFieldset(this)">Add</button>
                  <button type="button" name="RemoveProduct[]" class="btn btn-sm btn-danger" style="margin-left:25px"  onclick="RemoveProductFieldset(this)">Remove</button>
                </div>
              </div>

          </fieldset>
          </div>

          <div class="form-group">
            <label for="SubTotal" class="control-label col-sm-3 lables">SubTotal<span class="mandatoryLabel">*</span></label>
            <div class="col-sm-4">
              <div class='input-group'>
                <span class="input-group-addon">
                    <span class="fa fa-inr"></span>
                </span>
                <input type="text" class="form-control subtotal currency" maskedFormat="10,2" name="SubTotalAmount" placeholder="0.00" >
              </div>
            </div>
          </div>

          <div class="form-group">
            <label for="VatAmount" class="control-label col-sm-3 lables">Vat (<?php echo $_SESSION['VatFactor']; ?>%) <span class="mandatoryLabel">*</span></label>
            <div class="col-sm-4">
              <div class='input-group'>
                <span class="input-group-addon">
                    <span class="fa fa-inr"></span>
                </span>
                <input type="text" class="form-control vat-amount currency" maskedFormat="10,2" name="VatAmount" placeholder="0.00" >
              </div>
            </div>
          </div>

          <!--<div class="form-group">
            <label for="DiscountAmount" class="control-label col-sm-3 lables">Discount<span class="mandatoryLabel">*</span></label>
            <div class="col-sm-4">
              <div class='input-group'>
                <span class="input-group-addon">
                    <span class="fa fa-inr"></span>
                </span>
                <input type="text" class="form-control total-paid currency" maskedFormat="10,2" name="DiscountAmount" placeholder="0.00" >
              </div>
            </div>
          </div>-->

          <div class="form-group">
            <label for="TotalAmount" class="control-label col-sm-3 lables">Total paid<span class="mandatoryLabel">*</span></label>
            <div class="col-sm-4">
              <div class='input-group'>
                <span class="input-group-addon">
                    <span class="fa fa-inr"></span>
                </span>
                <input type="text" class="form-control total-paid currency" maskedFormat="10,2" name="TotalAmount" placeholder="0.00" >
              </div>
            </div>
          </div>

              <div class="form-group">
                <label class="control-label col-sm-3 lables">Payment Type<span class="mandatoryLabel">*</span></label>
                <div class="col-sm-4">
                  <label class="radio-inline"> <input type="radio" class="paymentType" name="paymentType" id="cash" value="1" checked > Cash </label>
                  <label class="radio-inline"> <input type="radio" class="paymentType" name="paymentType" id="card" value="2" > Card </label>
                  <label class="radio-inline"> <input type="radio" class="paymentType" name="paymentType" id="cheque" value="3" > Cheque </label>
                </div>
              </div>

              <div class="form-group" id="ChequeNoDiv" hidden>
                <label for="ChequeNo" class="control-label col-sm-3 lables">cheque No<span class="mandatoryLabel">*</span></label>
                <div class="col-sm-4">
                    <input type="text" class="form-control cheque-no" onkeypress='return event.charCode >= 48 && event.charCode <= 57' name="ChequeNo" >
                </div>
                <div class="errorMessage"></div>
              </div>

              <div class="form-group" id="ChequeDateDiv" hidden>
                <label for="ChequeDate"  class="control-label col-sm-3 lables">cheque Date<span class="mandatoryLabel">*</span></label>
                <div class="col-sm-4">
                    <input type="text" class="form-control cheque-date" name="ChequeDate">
                </div>
                <div class="errorMessage"></div>
              </div>

          <div class="form-group">
            <label for="InvoiceNotes" class="control-label col-sm-3 lables">Notes</label>
            <div class="col-sm-4">
              <textarea  class="form-control" name="InvoiceNotes" placeholder="Notes"></textarea>
            </div>
          </div>

          <div class="form-group">
            <label class="col-sm-3 control-label no-padding-right" for="form-field-1"> </label>

            <div class="col-sm-9">
              <input type="submit" name="nc_submit" value="submit" id="ID_Sub" class="btn btn-sm btn-success" style="margin-left:50px;" />
              <input type="hidden" name="UKey" value="1" id="ID_UKey" />
              <button type="reset" class="btn btn-sm btn-default" style="visibility:visible">Clear</button>
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
