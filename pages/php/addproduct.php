<?php
require '_connect.php';
require '_core.php';

$CDATA['PAGE_NAME'] = 'ADSRVREC';
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

  addMultiInputNamingRules('#AddorUpdateProduct','ProductSize[]', 'input[name="ProductSize[]"]', {
      required: true,
      messages: {
          required: "Please spcify Product size",
      }
  } );

  addMultiInputNamingRules('#AddorUpdateProduct','Brand[]', 'input[name="Brand[]"]', {
      required: true,
      messages: {
          required: "Please specify Product Brand",
      }
  } );

  addMultiInputNamingRules('#AddorUpdateProduct','Quantity[]', 'input[name="Quantity[]"]', {
      required: true,
      messages: {
          required: "Please Enter Quantity",
      }
  });

  addMultiInputNamingRules('#AddorUpdateProduct','Rate[]', 'input[name="Rate[]"]', {
      required: true,
      messages: {
          required: "Please Enter Product Rate",
      }
  });

  addMultiInputNamingRules('#AddorUpdateProduct','Amount[]', 'input[name="Amount[]"]', {
      required: true,
      messages: {
          required: "Please Enter Amount",
      }
  });

  addMultiInputNamingRules('#AddorUpdateProduct','Subtotal[]', 'input[name="Subtotal[]"]', {
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

  var DiscountPer = $fieldsetCurrent.find('.discount').val();
  var DiscountRs = $fieldsetCurrent.find('.discount-rs').val();

  if(Qty == '')
    Qty = 0;

  if(Rate == '')
    Rate = 0.00;

  if(DiscountPer == '')
    DiscountPer = 0.00;

  if(DiscountRs == '')
    DiscountRs = 0.00;

  if($.isNumeric(Qty) && $.isNumeric(Rate) && $.isNumeric(DiscountPer) && $.isNumeric(DiscountRs)) {
    $fieldsetCurrent.find('.amount').val((Rate*Qty).toFixed(2));
    $fieldsetCurrent.find('.subtotal').val(($fieldsetCurrent.find('.amount').val() - (Rate*Qty*DiscountPer/100.0).toFixed(2) - DiscountRs ).toFixed(2));
    UpdateFinalSubtotal();
  }
  else
    $fieldsetCurrent.find('.amount').val('');


}

function UpdateFinalSubtotal() {
  var finalSubtotal = 0.00;
  $('#AddorUpdateProduct').find('input.subtotal[type="text"]').each(function(index){
    if( $.isNumeric($(this).val()) )
      finalSubtotal += + $(this).val();
  });
    var finalDiscount = $('#AddorUpdateProduct').find('.final-discounts-amount').val();
    $('#AddorUpdateProduct').find('.final-subTotal-amount').val((finalSubtotal).toFixed(2));
}


function RemoveProductFieldset(e) {

  $(e).parent().fadeOut(300, function() {
       //remove parent element (main section)
       $(e).closest( 'fieldset' ).remove();
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
        if( isset($_POST['UserName']) && !empty($_POST['UserName']) &&
            isset($_POST['UserEmail']) && !empty($_POST['UserEmail']) &&
            isset($_POST['UserPhone']) && !empty($_POST['UserPhone']) &&
            isset($_POST['UserBday']) && !empty($_POST['UserBday']) &&
            isset($_POST['UserAddr']) && !empty($_POST['UserAddr']) &&
            isset($_POST['UserRole']) && !empty($_POST['UserRole']) &&
            isset($_POST['Status']) && !empty($_POST['Status'])  )
          {
            $UserName = mysql_real_escape_string(trim($_POST['UserName']));
            $UserEmail = mysql_real_escape_string(trim($_POST['UserEmail']));
            $UserPhone = mysql_real_escape_string(trim($_POST['UserPhone']));
            $UserBday = mysql_real_escape_string(trim($_POST['UserBday']));
            $UserAddr = mysql_real_escape_string(trim($_POST['UserAddr']));
            $UserRole = mysql_real_escape_string(trim($_POST['UserRole']));
            $Status = mysql_real_escape_string(trim($_POST['Status']));
            ChromePhp::log('role' . $UserRole);

            if($UpdateSubEvent = AddUser($UserName,$UserEmail,$UserPhone,$UserBday,$UserAddr,$UserRole,$Status) )
            {
                  echo '<div class="alert alert-block alert-success">
                          <button type="button" class="close" data-dismiss="alert">
                            <i class="ace-icon fa fa-times"></i>
                          </button>
                          <i class="ace-icon fa fa-check green"></i>
                          User Added successfully!
                        </div>';
            }
            else
            {
              echo '<div class="alert alert-block alert-danger">
                      <button type="button" class="close" data-dismiss="alert">
                        <i class="ace-icon fa fa-times"></i>
                      </button>
                      <i class="ace-icon fa fa-ban red"></i>
                      Something went wrong, try again.
                    </div>';
            }
          }
          else
          {
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
        }
        else
        {

          echo '<div class="alert alert-block alert-danger">
                  <button type="button" class="close" data-dismiss="alert">
                    <i class="ace-icon fa fa-times"></i>
                  </button>
                  <i class="ace-icon fa fa-android red"></i>
                  Our Security Robot has detected re-submission of same data or hack attempt. Please try later.
                </div>';
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
          <form class="form-horizontal" id="AddorUpdateProduct" name="AddorUpdateProduct" action="addproduct.php?_auth=<?php echo $_SESSION['AUTH_KEY']; ?>" method="post">
              <input type="hidden" value="<?php echo $_SESSION['AUTH_KEY']; ?>" name="akey" id="ID_akey" >

              <div class="form-group">
                <label for="InvoiceDate" class="control-label col-sm-3 lables">Invoice Date<span class="mandatoryLabel">*</span></label>
                <div class='col-sm-4'>
                  <input type="datetime" class="form-control" name="InvoiceDate"  />
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
                <label for="TinNumber" class="control-label col-sm-3 lables">TIN number<span class="mandatoryLabel">*</span></label>
                <div class="col-sm-4">
                  <input type="text" class="form-control" name="TinNumber" placeholder="Tin Number" >
                </div>
              </div>

          <div id="products" class="col-sm-12">
            <fieldset class="col-sm-9 col-xs-offset-1">
                <legend>Products<span class="mandatoryLabel">*</span></legend>

              <div class="form-group">
                <label for="ProductSize" class="control-label col-sm-3 lables">Product Size<span class="mandatoryLabel">*</span></label>
                <div class="col-sm-4">
                  <input type="text" class="form-control" name="ProductSize[]" placeholder="" >
                </div>
              </div>

              <div class="form-group">
                <label for="Brand" class="control-label col-sm-3 lables">Brand<span class="mandatoryLabel">*</span></label>
                <div class="col-sm-4">
                  <input type="text" class="form-control" name="Brand[]" placeholder="" >
                </div>
              </div>

              <div class="form-group">
                <label for="Quantity" class="control-label col-sm-3 lables ">Quantity<span class="mandatoryLabel">*</span></label>
                <div class="col-sm-4">
                  <input type="text" class="form-control quantity" name="Quantity[]" placeholder="0" onchange="CalculateAmount(this)">
                </div>
              </div>

              <div class="form-group">
                <label for="Rate" class="control-label col-sm-3 lables">Rate<span class="mandatoryLabel">*</span></label>
                <div class="col-sm-4">
                  <input type="text" class="form-control rate" name="Rate[]" placeholder="0.00" onchange="CalculateAmount(this)" >
                </div>
              </div>

              <div class="form-group">
                <label for="Amount" class="control-label col-sm-3 lables">Amount<span class="mandatoryLabel">*</span></label>
                <div class="col-sm-4">
                  <input type="text" class="form-control amount" name="Amount[]" placeholder="0.00" >
                </div>
              </div>

              <div class="form-group">
                <label for="Discount" class="control-label col-sm-3 lables">Discount (%)</label>
                <div class="col-sm-4">
                  <input type="text" class="form-control discount" name="Discount[]" placeholder="0.00%" value="0.00" onchange="CalculateAmount(this)" >
                </div>
              </div>

              <div class="form-group">
                <label for="Discount" class="control-label col-sm-3 lables">Discount Rs</label>
                <div class="col-sm-4">
                  <input type="text" class="form-control discount-rs" name="DiscountRs[]" placeholder="0.00" value="0.00" onchange="CalculateAmount(this)" >
                </div>
              </div>

              <div class="form-group">
                <label for="Subtotal" class="control-label col-sm-3 lables">Subtotal<span class="mandatoryLabel">*</span></label>
                <div class="col-sm-4">
                  <input type="text" class="form-control subtotal"  readonly="readonly" name="Subtotal[]" placeholder="0.00" >
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
            <label for="FinalSubTotalAmount" class="control-label col-sm-3 lables">Sub Total<span class="mandatoryLabel">*</span></label>
            <div class="col-sm-4">
              <input type="text" readonly="readonly" class="form-control final-subTotal-amount" name="FinalSubTotalAmount" placeholder="0.00" >
            </div>
          </div>

          <div class="form-group">
            <label for="FinalDiscountRate" class="control-label col-sm-3 lables">Discount (%)</label>
            <div class="col-sm-4">
              <input type="text" class="form-control final-discount-rate" name="FinalDiscountRate" placeholder="0.00 %" >
            </div>
          </div>

          <div class="form-group">
            <label for="FinalDiscountAmountRs" class="control-label col-sm-3 lables">Discount Rs</label>
            <div class="col-sm-4">
              <input type="text" class="form-control final-discounts-amount" name="FinalDiscountsAmount" placeholder="0.00" >
            </div>
          </div>

          <div class="form-group">
            <label for="VatAmount" class="control-label col-sm-3 lables">Vat<span class="mandatoryLabel">*</span></label>
            <div class="col-sm-4">
              <input type="text" class="form-control" name="VatAmount" placeholder="0.00" >
            </div>
          </div>

          <div class="form-group">
            <label for="RoundingAmount" class="control-label col-sm-3 lables">Rounding</label>
            <div class="col-sm-4">
              <input type="text" class="form-control" name="RoundingAmount" placeholder="0.00" >
            </div>
          </div>

          <div class="form-group">
            <label for="TotalAmount" class="control-label col-sm-3 lables">Total paid<span class="mandatoryLabel">*</span></label>
            <div class="col-sm-4">
              <input type="text"  readonly="readonly" class="form-control" name="TotalAmount" placeholder="0.00" >
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
              <input type="submit" name="nc_submit" value="submit" id="ID_Sub" class="btn btn-sm btn-success" style="<?php if(isset($subEventData['EventCode'])) echo 'margin-left:90px'; else echo 'margin-left:50px'; ?>" />
              <input type="hidden" name="UKey" value="1" id="ID_UKey" />
              <button type="reset" class="btn btn-sm btn-default" style="visibility:<?php if(isset($subEventData['EventCode'])) echo 'hidden'; else 'visible'?> ">Clear</button>
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
