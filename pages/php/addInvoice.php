<?php
require '_connect.php';
require '_core.php';

$CDATA['PAGE_NAME'] = 'ADDINV';
if(isLogin() && isAdmin())
{
require '_header.php'

?>

<script type="text/javascript">
var VatRate = 14.5;
function AddProductFieldset(e){

  var $fieldsetmain = $(e).closest( 'fieldset' );
  var $fieldset =  $(e).closest( 'fieldset' ).clone();
  $( $fieldset ).find( "input" ).val('');   //clears out input field
  $fieldsetmain.parent().append($fieldset);

  addMultiInputNamingRules('#AddorUpdateInvoice','ProductSize[]', 'input[name="ProductSize[]"]', {
      required: true,
      messages: {
          required: "Please spcify Product size",
      }
  } );

  addMultiInputNamingRules('#AddorUpdateInvoice','Brand[]', 'input[name="Brand[]"]', {
      required: true,
      messages: {
          required: "Please specify Product Brand",
      }
  } );

  addMultiInputNamingRules('#AddorUpdateInvoice','ProductPattern[]', 'input[name="ProductPattern[]"]', {
      required: true,
      messages: {
          required: "Please specify Product Pattern",
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
  var finalSubtotal = 0.00;
  $('#AddorUpdateInvoice').find('input.amount[type="text"]').each(function(index) {
    if( $.isNumeric($(this).val()) )
      finalSubtotal += + $(this).val();
  }); 

if($.isNumeric(finalSubtotal)) {
    $('#AddorUpdateInvoice').find('.total-paid').val( finalSubtotal.toFixed(2) );
  }
  else {
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
</script>


<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
  <div id='message' style='display: none;'></div>

  <!-- Content Header (Page header) -->
  <section class="content-header" id="MainEventInfo" >
    <h1>
      <div>Add Invoice</div>
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
        if( isset($_POST['InvoiceDate']) && !empty($_POST['InvoiceDate']) &&
            isset($_POST['CompanyName']) && !empty($_POST['CompanyName']) &&
            isset($_POST['InvoiceNumber']) && !empty($_POST['InvoiceNumber']) &&
            isset($_POST['ProductSize']) && !empty($_POST['ProductSize']) &&
            isset($_POST['Brand']) && !empty($_POST['Brand']) &&
            isset($_POST['ProductPattern']) && !empty($_POST['ProductPattern']) &&
            isset($_POST['Quantity']) && !empty($_POST['Quantity']) &&
            isset($_POST['Rate']) && !empty($_POST['Rate']) &&
            isset($_POST['Amount']) && !empty($_POST['Amount']) &&
            isset($_POST['TotalAmount']) && !empty($_POST['TotalAmount'])   )
          {
            $Invoice = new Invoice();
            
            $dateStr = FilterInput($_POST['InvoiceDate']);
            $date = DateTime::createFromFormat('d-m-Y', $dateStr);
            $Invoice->invoiceDate = $date->format('Y-m-d'); // => 2013-12-24
            $Invoice->companyName = FilterInput($_POST['CompanyName']);
            $Invoice->invoiceNumber = FilterInput($_POST['InvoiceNumber']);
            $Invoice->tinNumber = FilterInput($_POST['TinNumber']);
            $Invoice->totalAmount = FilterInput($_POST['TotalAmount']);

            if(isset($_POST['InvoiceNotes']) && !empty($_POST['InvoiceNotes']) )
              $Invoice->invoiceNotes = FilterInput($_POST['InvoiceNotes']);
            
            $Invoice->invoiceID = GetMaxInvoiceID() + 1;
            
            if(AddInvoice($Invoice))
            {
              $successCount = 0;
              $productSize = $_POST['ProductSize'];
              $ProductPattern = $_POST['ProductPattern'];
              $brand = $_POST['Brand'];
              $units = $_POST['Quantity'];
              $rate = $_POST['Rate'];
              $amount = $_POST['Amount'];
              
              for ($i=0; $i < count($productSize); $i++) {
                $Product =  new Product();
                $Product->invoiceID = $Invoice->invoiceID;
                $Product->productSize = $productSize[$i];
                $Product->productPattern = $ProductPattern[$i];
                $Product->brand = $brand[$i];
                $Product->units = $units[$i];
                $Product->rate = $rate[$i];
                $Product->amount = $amount[$i];

                if(AddProduct($Product))
                  $successCount++;
              }
              echo MessageTemplate(MessageType::Success, "Invoice Added successfully!");
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
        <h3 class="box-title"><?php echo 'Add'; ?></h3>

       <!--  <div class="box-tools pull-right">
          <button type="button" class="btn btn-box-tool" data-widget="collapse" data-toggle="tooltip" title="Collapse">
            <i class="fa fa-minus"></i></button>
          <button type="button" class="btn btn-box-tool" data-widget="remove" data-toggle="tooltip" title="Remove">
            <i class="fa fa-times"></i></button>
        </div> -->
      </div>
      <div class="box-body">

      <div id="AddOrUpdateProducts">
          <form class="form-horizontal" id="AddorUpdateInvoice" name="AddorUpdateInvoice" action="addInvoice.php?_auth=<?php echo $_SESSION['AUTH_KEY']; ?>" method="post">
              <input type="hidden" value="<?php echo $_SESSION['AUTH_KEY']; ?>" name="akey" id="ID_akey" >
              <input type="hidden" value="14.5" name="VatPer">

              <div class="form-group">
                <label for="InvoiceDate" class="control-label col-sm-3 lables">Invoice Date<span class="mandatoryLabel">*</span></label>
                <div class='col-sm-4'>
                  <input type="datetime" class="form-control" name="InvoiceDate" id="InvoiceDate"  />
                </div>
              </div>

              <div class="form-group">
                <label for="CompanyName" class="control-label col-sm-3 lables">Company<span class="mandatoryLabel">*</span></label>
                <div class="col-sm-4">
                  <input type="text" class="form-control" name="CompanyName" placeholder="Company Name" >
                </div>
              </div>

              <div class="form-group">
                <label for="InvoiceNumber" class="control-label col-sm-3 lables">Invoice number<span class="mandatoryLabel">*</span></label>
                <div class="col-sm-4">
                  <input type="text" class="form-control" name="InvoiceNumber" placeholder="Invoice Number" >
                </div>
              </div>

              <div class="form-group">
                <label for="TinNumber" class="control-label col-sm-3 lables">TIN number</label>
                <div class="col-sm-4">
                  <input type="text" class="form-control" name="TinNumber" placeholder="Tin Number" >
                </div>
              </div>

          <div id="products" class="col-sm-12">
            <fieldset class="col-sm-9 col-xs-offset-1">
                <legend>Products<span class="mandatoryLabel">*</span></legend>

              <div class="form-group">
                <label for="Brand" class="control-label col-sm-3 lables">Brand<span class="mandatoryLabel">*</span></label>
                <div class="col-sm-4">
                  <input type="text" class="form-control" name="Brand[]" placeholder="" >
                </div>
              </div>

              <div class="form-group">
                <label for="ProductSize" class="control-label col-sm-3 lables">Product Size<span class="mandatoryLabel">*</span></label>
                <div class="col-sm-4">
                  <input type="text" class="form-control" name="ProductSize[]" placeholder="" >
                </div>
              </div>

              <div class="form-group">
                <label for="ProductPattern" class="control-label col-sm-3 lables">Product Pattern<span class="mandatoryLabel">*</span></label>
                <div class="col-sm-4">
                  <input type="text" class="form-control" name="ProductPattern[]" placeholder="" >
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
                <label for="Amount" class="control-label col-sm-3 lables">Amount<span class="mandatoryLabel">*</span></label>
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
            </div>
          </fieldset>

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
