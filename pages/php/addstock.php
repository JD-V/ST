<?php
require '_connect.php';
require '_core.php';

$CDATA['PAGE_NAME'] = 'ADDSTOCK';
if(isLogin() && isAdmin())
{
require '_header.php'

?>
<script type = "text/javascript" >
  var patterns = new Array();
$(document).ready(function() {


  $('.product-brand').on('change', function() {
      var BrandID = this.value;
      $.ajax({
          dataType: "json",
          type: "GET",
          url: "CreateOrderForm.php?action=Retrive&BrandID="+BrandID,
          success: function(result) {
            $('.typeahead').typeahead('destroy');
            $('#productSize').val('');
            $('.product-Pattern').empty();
              var keyVal = new Array();
              patterns = [];
              for (var i = 0; i < result.length; i++) {
                keyVal[result[i].ProductID] = result[i].ProductSize;
                patterns.push({ "productSize" : result[i].ProductSize,
                                "productID" : result[i].ProductID,
                                "productPattern" : result[i].ProductPattern,
                                "Qty" : result[i].Qty });
              }
              var productSizes = new Bloodhound({
                  datumTokenizer: Bloodhound.tokenizers.whitespace,
                  queryTokenizer: Bloodhound.tokenizers.whitespace,
                  local: keyVal,
              });
              // Initializing the typeahead
              $('.typeahead').typeahead({
                  hint: true,
                  highlight: true, /* Enable substring highlighting */
                  minLength: 2,/* Specify minimum characters required for showing result */
              },
              {
                  name: 'productSizes',
                  source: productSizes
              });
          }
      });
  });


  $('#productSize').on('blur', function() {
    $('.product-Pattern').empty();
    var selectedSize = $('#productSize').val();
    for (var i = 0; i < patterns.length; i++) {
      if(patterns[i].productSize == selectedSize)
       $('.product-Pattern').append(new Option(patterns[i].productPattern, patterns[i].productID));
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
        if( isset($_POST['BrandID']) && !empty($_POST['BrandID']) &&
            isset($_POST['productSize']) && !empty($_POST['productSize']) &&
            isset($_POST['Pattern']) && !empty($_POST['Pattern']) &&
            isset($_POST['Qty']) && !empty($_POST['Qty'])  )
          {

             $qty = FilterInput($_POST['Qty']);
             $productID = FilterInput($_POST['Pattern']);
         
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
          <form class="form-horizontal"  name="addstock" id="addstock" action="addstock.php?_auth=<?php echo $_SESSION['AUTH_KEY']; ?>" method="post">
              <input type="hidden" value="<?php echo $_SESSION['AUTH_KEY']; ?>" name="akey" id="ID_akey" >

              <div class="form-group">
                  <label for="BrandID" class="control-label col-sm-3 lables">Brand<span class="mandatoryLabel">*</span></label>
                  <div class="col-sm-4">
                      <select class="form-control product-brand" name="BrandID" id="BrandID"  >
                      <option selected="true" disabled="disabled" style="display: none" value="default">Select Brand</option>
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
                <label for="productSize" class="control-label col-sm-3 lables" >product Size<span class="mandatoryLabel">*</span></label>
                <div class="col-sm-4">
                  <input type="text" id="productSize" name="productSize" class="form-control typeahead tt-query" autocomplete="on" spellcheck="false" >
                </div>
                <div id="errorMsgMN" name="errorMsgMN" style="font:10px Tahoma,sans-serif;margin-left:5px;display:inline;color:red;" role="error"></div>
              </div>
              
              <div class="form-group">
                  <label for="Pattern" class="control-label col-sm-3 lables">Pattern<span class="mandatoryLabel">*</span></label>
                  <div class="col-sm-4">
                      <select class="form-control product-Pattern" name="Pattern" id="Pattern"  >
                      <option selected="true" disabled="disabled" style="display: none" value="default">Select Pattern</option>
                      </select>
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
                  <input type="submit" name="nc_submit" value="submit" id="ID_Sub" class="btn btn-sm btn-success" style="margin-left:50px" />
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