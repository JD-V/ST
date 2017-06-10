<?php
require '_connect.php';
require '_core.php';

$CDATA['PAGE_NAME'] = 'ADSLSREC';
if(isLogin())
{
require '_header.php'

?>
<style type="text/css" > 

.top {
  margin-top: -30px;
}

.right {
  float: right;
  position: relative;
  top: 6px;
}

#notebooks {
  background: whitesmoke;

  border: 1px solid rgba(0, 0, 0, 0.2);

  box-shadow: inset 1px 1px 0 white;
  max-height: 300px;
}

.ul-style {
  margin: 0 auto;
  padding: 0;
  max-height: 240px;
  overflow-y: auto;
  border: 1px solid rgba(0, 0, 0, 0.1);
  padding: 5px 5px 0 5px;
  border-left: none;
  border-right: none;
}

.li-style-yellow {
  list-style: none;
  background-color: rgba(0, 0, 0, 0.05);
  background-image: 
    linear-gradient(
      90deg,
      #FFD32E 10px,
      #EEE 10px,
      #EEE 11px,
      transparent 11px);
  padding: 10px 15px 10px 25px;
  border: 1px solid #CCC;
  box-shadow: inset 1px 1px 0 rgba(255, 255, 255, 0.5);
  margin-bottom: 5px;
  width: 100%;
  box-sizing: border-box;
  cursor: pointer;
  border-radius: 3px;
}


.li-style-red {
  list-style: none;
  background-color: rgba(0, 0, 0, 0.05);
  background-image: 
    linear-gradient(
      90deg,
      #ff6b2e 10px,
      #EEE 10px,
      #EEE 11px,
      transparent 11px);
  padding: 10px 15px 10px 25px;
  border: 1px solid #CCC;
  box-shadow: inset 1px 1px 0 rgba(255, 255, 255, 0.5);
  margin-bottom: 5px;
  width: 100%;
  box-sizing: border-box;
  cursor: pointer;
  border-radius: 3px;
}


.li-style-green {
  list-style: none;
  background-color: rgba(0, 0, 0, 0.05);
  background-image: 
    linear-gradient(
      90deg,
      #2eff6b 10px,
      #EEE 10px,
      #EEE 11px,
      transparent 11px);
  padding: 10px 15px 10px 25px;
  border: 1px solid #CCC;
  box-shadow: inset 1px 1px 0 rgba(255, 255, 255, 0.5);
  margin-bottom: 5px;
  width: 100%;
  box-sizing: border-box;
  cursor: pointer;
  border-radius: 3px;
}


#query {
  width: 100%;
  box-sizing: border-box;
  font-size: 19px;
  padding: 5px;
  font-family: calibri light;
  margin-bottom: 10px;
  border: 1px solid rgba(0, 0, 0, 0.2);

  box-shadow: inset 1px 1px 0 rgba(0, 0, 0, 0.1);
}

#notebooks select {
  width: 120px;
  margin-left: 230px;
  margin-top: -45px;
  border-radius: 0 3px 3px 0;
  border: 1px solid rgba(0, 0, 0, 0.2);
  border-left: 1px solid rgba(0, 0, 0, 0.1);
  position: absolute;
  padding: 7.5px;
  box-shadow: inset 0 1px 0 rgba(0, 0, 0, 0.1);
}

#notebooks select:focus, #query:focus {
  border: 1px solid #FFD32E;
  box-shadow: 0 0 10px rgba(255, 255, 0, 0.1);
  outline: none;
}
</style>

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
  <div id='message' style='display: none;'></div>

  <!-- Content Header (Page header) -->
  <section class="content-header" id="MainEventInfo" >
    <h1>
    <div>Sales record
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
    </div>
    <!-- Default box -->
    <div class="box">
      <div class="box-header with-border">
      <?php
          $title= "Add";
          if($GetServiceRecord = @GetServiceRecord($_GET['id'])) {
            ChromePhp::log("got record id ");
            $serviceRecord  = mysql_fetch_assoc($GetServiceRecord);
            ChromePhp::log($serviceRecord);
            $title= "Update";
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

      <div id="AddOrUpdateServiceRecord" ng-app="salesApp" ng-controller="salesCtrl">
          <form name = "salesForm" class="form-horizontal" valid-submit="sendForm()"  novalidate >
              <input type="hidden" value="<?php echo $_SESSION['AUTH_KEY']; ?>" name="akey" id="ID_akey" >

              <div class="form-group">
                <label for="InvoiceNo" class="control-label col-sm-3 lables">Invoice No<span class="mandatoryLabel">*</span></label>
                <div class="col-sm-4">
                  <input type="text" class="form-control" name="InvoiceNo" ng-model = "InvoiceNo"  required >
                </div>
                <div ng-show = "salesForm.InvoiceNo.$dirty && salesForm.InvoiceNo.$invalid" class="errorMessage">Invoice No. is required</div>                
              </div>

              <div class="form-group">
                <label for="ServiceInvoiceDate" class="control-label col-sm-3 lables">Invoice Date<span class="mandatoryLabel">*</span></label>
                <div class='col-sm-4'>
                  <input type="text" date-fix class="form-control sales-invoice-date" name="SalesInvoiceDate" ng-model = "SalesInvoiceDate" required/>
                </div>
                <div ng-show = "salesForm.SalesInvoiceDate.$dirty && salesForm.SalesInvoiceDate.$invalid" class="errorMessage">Date is required</div>
              </div>

              <div class="form-group">
                <label for="VehicleNo" class="control-label col-sm-3 lables">Vehicle Number<span class="mandatoryLabel">*</span></label>
                <div class="col-sm-4">
                  <input type="text" class="form-control" name="VehicleNo" placeholder="Vehicle Number" ng-model="VehicleNo" required ng-blur="RetriveCustomerData()" >
                </div>
                <div ng-show="salesForm.$submitted && salesForm.VehicleNo.$error.required" class="errorMessage">Please enter Vehicle numnber</div>
              </div>

              <div class="form-group">
                <label for="CustomerName" class="control-label col-sm-3 lables">Customer Name<span class="mandatoryLabel">*</span></label>
                <div class="col-sm-4">
                  <input type="text" class="form-control" name="CustomerName" placeholder="Cutomer Name" ng-model = "CustomerName" required >
                </div>
                <div  ng-show="salesForm.$submitted && salesForm.CustomerName.$error.required" class="errorMessage">customer name is required</div>
              </div>

              <div class="form-group">
                <label for="CustomerPhone" class="control-label col-sm-3 lables">Phone<span class="mandatoryLabel">*</span></label>
                <div class="col-sm-4">
                  <div class='input-group'>
                    <span class="input-group-addon">
                        <span class="fa fa-mobile"></span>
                    </span>
                    <input type="text" class="form-control phone" maxlength="10" ng-minlength="10" ng-maxlength="10" name="CustomerPhone" placeholder="Phone Number" ng-model="CustomerPhone" onkeypress="return isNumberKey(event)" onkeypress='return event.charCode >= 48 && event.charCode <= 57' required>
                  </div>
                </div>
                <div ng-show="salesForm.$submitted && salesForm.CustomerPhone.$error.required" class="errorMessage">Please enter phone numnber</div>
                <div class="errorMessage" ng-show="((salesForm.CustomerPhone.$error.minlength || salesForm.CustomerPhone.$error.maxlength) &&  salesForm.CustomerPhone.$dirty) ">phone number should be 10 digits</div>
              </div>

            <div class="form-group">
              <label for="VehicleMileage" class="control-label col-sm-3 lables">Vehicle Mileage<span class="mandatoryLabel">*</span></label>
              <div class="col-sm-4">
                <input type="text" class="form-control" name="VehicleMileage" placeholder="Vehicle Mileage" ng-model="VehicleMileage" required >
              </div>
              <div ng-show="salesForm.$submitted  && salesForm.VehicleMileage.$error.required" class="errorMessage">Please enter Vehicle Mileage</div>
            </div>

            <div class="form-group">
              <label for="Address" class="control-label col-sm-3 lables">Address<span class="mandatoryLabel">*</span></label>
              <div class="col-sm-4">
                <textarea  class="form-control" name="Address" placeholder="Address" ng-model="Address" required></textarea>
              </div>
              <div ng-show = "salesForm.$submitted && salesForm.Address.$error.required" class="errorMessage">Please provide customer Address</div>
            </div>

            <div class="form-group">
                <label for="BrandID" class="control-label col-sm-3 lables">Brand<span class="mandatoryLabel">*</span></label>
                <div class="col-sm-4">
                    <select class="form-control" name="BrandID" id="BrandID" ng-model="Brand" ng-change="GetProducts()" >
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
              <label for="addItems" class="control-label col-sm-3 lables">Add Products<span class="mandatoryLabel">*</span></label>
              <div class="col-sm-4" >
                  <div id="notebooks">
                    <input type="text" id="query" ng-model="query" placeholer="Serch for size or pattern" />
                    <ul class="ul-style" id="notebook_ul">
                      <li ng-class="{'li-style-red': (product.Qty == undefined || product.Qty == 0), 'li-style-green': (product.Qty >= 9), 'li-style-yellow': (product.Qty < 9 && product.Qty>0)}"  ng-repeat="product in products | filter:query | orderBy: product.ProductName" ng-click="AddProduct(product.ProductID)">
                      Size: {{product.ProductDisplay}}<br/>
                      Type: {{product.ProductTypeName}}<br/>
                      <div class="right top">{{product.MaxSellPrice}}</div>
                      </li>
                    </ul>
                  </div>
              </div>
              <div ng-show = "salesForm.$submitted  && (addedProducts.length==0)" class="errorMessage">Please select atleast one product</div>
            </div>
              
            <fieldset class="col-sm-9 col-xs-offset-1">
            <legend>Products<span class="mandatoryLabel">*</span></legend>
            <div class="table-responsive col-sm-12" id= "OrganizerList" >
              <table id="OrgTable" class="table table-striped table-hover" >
                <thead>
                  <tr>
                    <th>Brand</th>
                    <th>Size</th>
                    <th>Pattern</th>
                    <th>Type</th>
                    <th>Sell Price</th>
                    <th>Qty</th>
                    <th>Amount</th>

                    <th><i class="fa fa-pencil-square-o" aria-hidden="true"></i>&nbsp;&nbsp;&nbsp;<i class="fa fa-times" aria-hidden="true"></i></th>
                  </tr>
                </thead>
                <tr ng-repeat="item in addedProducts">
                  <td>{{item.BrandName}}</td>
                  <td>{{item.ProductSize}}</td>
                  <td>{{item.ProductPattern}}</td>
                  <td>{{item.ProductTypeName}}</td>
                  <td>
                  <div ng-class="{'edited': item.priceEdited, 'error' : item.priceInvalid}">
                    <label  ng-hide="item.editingPrice" >{{item.SellPrice}}</label>
                      <input ng-change="item.priceEdited = true" ng-click="item.editing = true" 
                      ng-blur=" item.editingPrice = false; item.priceInvalid = validateInput(item.Price); item.priceEdited = !item.priceInvalid" 
                      onkeypress='return event.charCode >= 48 && event.charCode <= 57' type="text" ng-show="item.editingPrice" ng-model="item.SellPrice"  />
                  </div>
                  </td>
                  <td>
                  <div ng-class="{'edited': item.QtyEdited, 'error' : item.QtyInvalid}">
                    <label  ng-hide="item.editingQty" >{{item.Qty}}</label>
                      <input ng-change="item.QtyEdited = true" ng-click="item.editing = true" 
                      ng-blur=" item.editingQty = false; item.QtyInvalid = validateInput(item.Qty  ); item.QtyEdited = !item.QtyInvalid" 
                      onkeypress='return event.charCode >= 48 && event.charCode <= 57' type="text" ng-show="item.editingQty" ng-model="item.Qty"  />
                  </div>
                  </td>

                  <td>
                  <div>
                    <label  ng-model="item.Amount" >{{item.Qty*item.SellPrice}}</label>
                  </div>
                  </td>

                  <td>
                  <a ng-click="item.editingQty = !item.editingQty; item.editingPrice = !item.editingPrice; " ><i class="fa fa-pencil-square-o" aria-hidden="true"></i></a>&nbsp;&nbsp;&nbsp;
                  <a ng-click="RemoveItem(item.ItemID, $index);" ><i class="fa fa-times" aria-hidden="true"></i></a>
                  </td>
                </tr>
              </table>
            </div>
          </fieldset>    

            <div class="form-group">
                <label for="BasicAmount" class="control-label col-sm-3 lables">Basic<span class="mandatoryLabel">*</span></label>
                <div class="col-sm-4">
                  <div class='input-group'>
                    <span class="input-group-addon">
                        <span class="fa fa-inr"></span>
                    </span>
                    <input type="text" readonly="readonly" class="form-control amount" name="BasicAmount" placeholder="0.00" ng-model="BasicAmount" required>
                  </div>
                </div>
                <div  ng-show="salesForm.$submitted && salesForm.BasicAmount.$error.required" class="errorMessage"></div>
              </div>

               <div class="form-group">
                <label for="VatAmount" class="control-label col-sm-3 lables">Vat (<?php echo $_SESSION['VatFactor']; ?>%)</label>
                <div class="col-sm-4">
                  <div class='input-group'>
                    <span class="input-group-addon">
                        <span class="fa fa-inr"></span>
                    </span>
                    <input type="text" readonly="readonly" class="form-control amount" name="VatAmount" placeholder="0.00" ng-model="VatAmount" ng-blur="updateTotalAmountPaid()" >
                  </div>
                </div>
              </div>

              <div class="form-group">
                <label for="DiscountAmount" class="control-label col-sm-3 lables">Discount</label>
                <div class="col-sm-4">
                  <div class='input-group'>
                    <span class="input-group-addon">
                        <span class="fa fa-inr"></span>
                    </span>
                    <input type="text" class="form-control amount" name="DiscountAmount" placeholder="0.00" onkeypress='return event.charCode >= 48 && event.charCode <= 57' ng-model="DiscountAmount" ng-blur="updateTotalAmountPaid()" >
                  </div>
                </div>
              </div>

              <div class="form-group">
                <label for="AmountPaid" class="control-label col-sm-3 lables">Total<span class="mandatoryLabel">*</span></label>
                <div class="col-sm-4">
                  <div class='input-group'>
                    <span class="input-group-addon">
                        <span class="fa fa-inr"></span>
                    </span>
                    <input type="text" class="form-control amount" onkeypress='return event.charCode >= 48 && event.charCode <= 57' name="TotalAmountPaid" placeholder="0.00" ng-model="TotalAmountPaid" required>
                  </div>
                </div>
                <div ng-show="salesForm.TotalAmountPaid.$dirty && ( TotalAmountPaid == undefined || TotalAmountPaid <= 0 )" class="errorMessage">Please enter Total Amount Paid</div>
              </div>

              <div class="form-group">
                <label class="control-label col-sm-3 lables">Payment Type<span class="mandatoryLabel">*</span></label>
                <div class="col-sm-4">                  
                  <label class="radio-inline"> <input type="radio" name="paymentType" id="cash" value="1" ng-model="PaymentMethod"> Cash </label>
                  <label class="radio-inline"> <input type="radio" name="paymentType" id="card" value="2" ng-model="PaymentMethod" > Card </label>
                  <label class="radio-inline"> <input type="radio" name="paymentType" id="cheque" value="3" ng-model="PaymentMethod" ng-change="AddDatePlugin()" > Cheque </label>
                </div>
              </div>

              <div class="form-group" ng-show="PaymentMethod == 3">
                <label for="ChequeNo" class="control-label col-sm-3 lables">cheque No<span class="mandatoryLabel">*</span></label>
                <div class="col-sm-4">
                    <input type="text" ng-required="PaymentMethod == 3" class="form-control cheque-no" onkeypress='return event.charCode >= 48 && event.charCode <= 57' 
                      name="ChequeNo" ng-model="ChequeNo">
                </div>
                <div ng-show="PaymentMethod == 3 && salesForm.$submitted  && salesForm.ChequeNo.$error.required" class="errorMessage">Please enter cheque no.</div>
              </div>

              <div class="form-group"  ng-show="PaymentMethod == 3">
                <label for="ChequeDate"  class="control-label col-sm-3 lables">cheque Date<span class="mandatoryLabel">*</span></label>
                <div class="col-sm-4">
                    <input type="text" ng-required="PaymentMethod == 3" class="form-control cheque-date" ng-model="ChequeDate" name="ChequeDate">
                </div>
                <div ng-show="PaymentMethod == 3 && salesForm.$submitted  && salesForm.ChequeDate.$error.required" class="errorMessage">Please select cheque date</div>
              </div>

              <div class="form-group">
                <label for="CustomerTIN" class="control-label col-sm-3 lables">Customer TIN</label>
                <div class="col-sm-4">
                  <input type="text" class="form-control" name="CustomerTIN" placeholder="Customer TIN" ng-model="CustomerTIN" >
                </div>
              </div>

              <div class="form-group">
                <label for="CustomerPAN" class="control-label col-sm-3 lables">Customer PAN</label>
                <div class="col-sm-4">
                  <input type="text" class="form-control" name="CustomerPAN" placeholder="Customer PAN" ng-model="CustomerPAN" >
                </div>
              </div>

              <div class="form-group">
                <label for="Notes" class="control-label col-sm-3 lables">Notes</label>
                <div class="col-sm-4">
                  <textarea class="form-control" name="Notes" placeholder="Notes" ng-model="Notes" ></textarea>
                </div>
              </div>

              <div class="form-group">
                <label class="col-sm-3 control-label no-padding-right" for="form-field-1"> </label>

                <div class="col-sm-9">
                  <button type="submit" class="btn btn-sm btn-success">Submit!</button>
                  <input type="hidden" name="UKey" value="1" id="ID_UKey"  />
                  <button class="btn btn-sm btn-default" ng-click = "reset()" >Clear</button>
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

<script type="text/javascript">

var ValidSubmit = ['$parse', function ($parse) {
        return {
            compile: function compile(tElement, tAttrs, transclude) {
                return {
                    post: function postLink(scope, element, iAttrs, controller) {
                        var form = element.controller('form');
                        form.$submitted = false;
                        var fn = $parse(iAttrs.validSubmit);
                        element.on('submit', function(event) {
                            scope.$apply(function() {
                                element.addClass('ng-submitted');
                                form.$submitted = true;
                                if(form.$valid) {
                                    fn(scope, {$event:event});
                                }
                            });
                        });
                        scope.$watch(function() { return form.$valid}, function(isValid) {
                            if(form.$submitted == false) return;
                            if(isValid) {
                                element.removeClass('has-error').addClass('has-success');
                            } else {
                                element.removeClass('has-success');
                                element.addClass('has-error');
                            }
                        });
                    }
                }
            }
        }
    }];

  angular.module('salesApp', [])
  .filter('getById', function() {
  return function(input, id) {
    var i=0, len=input.length;
    for (; i<len; i++) {
      if (+input[i].ProductID == +id) {
        return input[i];
      }
    }
    return null;
  }
})
  .controller('salesCtrl', function($scope,$filter, dataService) {
  
  // refreshing data in the table
  $scope.RefreshView = function() {
    $scope.VatFactor = <?php echo $_SESSION['VatFactor']; ?>;
    $scope.PaymentMethod = '1';
  };

  $scope.GetProducts = function() {
    var BrandID = $('#BrandID').val();
    console.log("BrandID " + BrandID);
    dataService.getProducts(BrandID, function(response) {
      console.log(response.data);
      $scope.productsActual = angular.copy(response.data);
      $scope.products = response.data;
    });
  };

  $scope.RetriveCustomerData = function() {
     dataService.getcustomerData($scope.VehicleNo, function(response) {
      var customerData = response.data;
      if(customerData.data == 1) {
        $scope.CustomerName = customerData.CustomerName
        $scope.VehicleMileage= customerData.VehicleMileage;
        $scope.CustomerPhone = customerData.CustomerPhone;
        $scope.CustomerTIN = customerData.CustomerTIN;
        $scope.CustomerPAN = customerData.CustomerPAN;
        $scope.Address = customerData.Address;
      }
    });
  }
  // $scope.updateSalesDate = function(){
  //   $scope.SalesInvoiceDate = $('.sales-invoice-date').val();
  // }

  // $scope.AddDatePlugin = function() {
    
  //   $("input").prop('required',true);
  // }

  // $scope.$watch('PaymentMethod', function(newValue, oldValue) {
  //   if( $scope.PaymentMethod == '3') {
  //     $(".cheque-date").prop('required',true);
  //     $(".cheque-no").prop('required',true);
  //   } else {
  //     $(".cheque-date").prop('required',false);
  //     $(".cheque-no").prop('required',false);
  //   } 
  // });
  
  $scope.AddProduct = function(ProductID){

    if($filter('getById')($scope.addedProducts, ProductID) == null) {
    
      var found = $filter('getById')($scope.productsActual, ProductID);

      if(found.Qty == undefined || found.Qty == 0) {
        document.getElementById("messages").innerHTML = 
        MessageTemplate(1, "Product not available in stock");
        scrollTo(0,0);
        autoClosingAlert(".alert-block", 2000);
        return;
      }
      var obj = {
        'ProductID': found.ProductID,
        'BrandName' : found.BrandName,        
        'ProductSize' : found.ProductSize,
        'ProductPattern' : found.ProductPattern,
        'ProductTypeName' : found.ProductTypeName,
        'SellPrice': found.MaxSellPrice,
        'CostPrice': found.CostPrice,
        'Qty' : 1
      };
      $scope.addedProducts.push(obj);
      $scope.CalculateAmount();
    }
  };
      
 $scope.RemoveItem = function($ItemID,$index){
      $scope.addedProducts.splice($index,1);
      $scope.CalculateAmount();
 };

 $scope.updateTotalAmountPaid = function() {
  $scope.TotalAmountPaid = $scope.BasicAmount + +$scope.VatAmount;
  if($scope.DiscountAmount != undefined && $scope.DiscountAmount != 0)
    $scope.TotalAmountPaid -=  +$scope.DiscountAmount;
 };

  $scope.CalculateAmount = function(){
    var sum = 0;
      angular.forEach($scope.addedProducts, function(value){
          sum += +(value.Qty*value.SellPrice);
      });
      $scope.VatAmount = Math.ceil(sum * $scope.VatFactor) / 100;
      $scope.BasicAmount = sum-$scope.VatAmount;
      
      $scope.TotalAmountPaid = sum;
  };

  $scope.validateInput = function($inputValue) {
      if($inputValue === "") {
        document.getElementById("messages").innerHTML =
        MessageTemplate(1, "can not be blank");
        scrollTo(0,0);
        autoClosingAlert(".alert-block", 2000);
        return true;
      } else{
        $scope.CalculateAmount();
        return false;
      }
    };

    $scope.reset = function(){

      $scope.CustomerName  = "";
      $scope.SalesInvoiceDate = '<?php echo date("d-m-Y H:i") ?>';
      dataService.getMaxSalesInvoiceNumber(function(response) {
        console.log(response.data);
        $scope.InvoiceNo = parseInt(response.data) +  +1;
      });
        $scope.Brand = 0;
        $scope.addedProducts = [];
        $scope.BasicAmount = 0;
        $scope.VatAmount = 0;
        $scope.TotalAmountPaid = 0;
        $scope.VehicleNo = '';
        $scope.VehicleMileage= '';
        $scope.CustomerPhone = '';
        $scope.DiscountAmount = 0;
        $scope.CustomerTIN = '';
        $scope.CustomerPAN = '';
        $scope.Notes = '';
        $scope.Address = '';
        $scope.PaymentMethod = 1;
        $scope.ChequeNo = '';
        $scope.ChequeDate = '<?php echo date("d-m-Y") ?>';
   }
  
  $scope.reset();

  $scope.sendForm = function() {
    var salesInvoiceDateVal = $('.sales-invoice-date').val();
    var chequeDateVal = $('.cheque-date').val();
    if($scope.BasicAmount == 0 || salesInvoiceDateVal == ''  || chequeDateVal == '' ) {
      document.getElementById("messages").innerHTML = MessageTemplate(1, "Please correct all the errors.");
      autoClosingAlert(".alert-block", 4000);
    } else {
      var FormData = {
        Products: []
      };      
      for (var i = 0; i < $scope.addedProducts.length; i++) {

        if( ($scope.addedProducts[i].hasOwnProperty('itemInvalid') && $scope.addedProducts[i].itemInvalid ) ||
            ($scope.addedProducts[i].hasOwnProperty('priceInvalid') && $scope.addedProducts[i].priceInvalid ) ||
            ($scope.addedProducts[i].hasOwnProperty('QtyInvalid') && $scope.addedProducts[i].QtyInvalid )  )
            {
              document.getElementById("messages").innerHTML = MessageTemplate(1, "Please correct the errors.");
              autoClosingAlert(".alert-block", 4000);
              return;
            } else FormData.Products.push($scope.addedProducts[i]);
      }
      FormData.InvoiceNo = $scope.InvoiceNo;
      FormData.CustomerName= $scope.CustomerName;
      $scope.SalesInvoiceDate = salesInvoiceDateVal;
      FormData.SalesInvoiceDate = $scope.SalesInvoiceDate
      FormData.SalesInvoiceDate = $scope.SalesInvoiceDate;
      FormData.CustomerPhone = $scope.CustomerPhone;
      FormData.VehicleNo = $scope.VehicleNo;
      FormData.VehicleMileage = $scope.VehicleMileage;
      FormData.CustomerPAN = $scope.CustomerPAN;
      FormData.CustomerTIN = $scope.CustomerTIN;
      FormData.BasicAmount = $scope.BasicAmount;
      FormData.VatAmount = $scope.VatAmount;
      FormData.DiscountAmount =  $scope.DiscountAmount;
      FormData.TotalAmountPaid =  $scope.TotalAmountPaid;
      FormData.Notes = $scope.Notes;
      FormData.Address = $scope.Address;
      FormData.PaymentMethod = $scope.PaymentMethod;

      if($scope.PaymentMethod == '3') {
        FormData.ChequeNo = $scope.ChequeNo;
        $scope.ChequeDate = chequeDateVal;
        FormData.ChequeDate = $scope.ChequeDate;
        if($scope.ChequeNo == '') {
          document.getElementById("messages").innerHTML = MessageTemplate(1, "Please proviude the cheque no.");
          autoClosingAlert(".alert-block", 4000);
        }
      }
      console.log(JSON.stringify(FormData));

      dataService.submitOrder(FormData, function(response) {
        if(parseInt(response.data, 10)) {
          document.getElementById("messages").innerHTML = 
            MessageTemplate(0, "Sales Invoice has been added with " + response.data +" product(s)");
            $scope.reset();              
            $scope.salesForm.$setPristine();
            $scope.salesForm.$setUntouched();
            $scope.salesForm.$submitted = false;
        } else {
            document.getElementById("messages").innerHTML = MessageTemplate(1, response.data);
        }
        autoClosingAlert(".alert-block", 4000);
        scrollTo(0,0);
      });
    }
  };
  $scope.getClass = function(b) {
    return b.toString();
  }

  // initial call
  $scope.RefreshView();
  })  
  .service('dataService', function($http) {

    this.getProducts = function(BrandID,callback) {
      
      $http({
        method : "GET",
        url : "CreateOrderForm.php?action=Retrive&BrandID="+BrandID,
      }).then(callback)
    };

  this.getMaxSalesInvoiceNumber = function(callback) {
        
        $http({
          method : "GET",
          url : "CreateOrderForm.php?action=Retrive&item=MaxSalesInvoice",
        }).then(callback)
      };

  // Get customerData
  this.getcustomerData = function(CustomerData,callback) {
        
        $http({
          method : "GET",
          url : "CreateOrderForm.php?action=Retrive&item=CutomerDetails&vehicleNo="+CustomerData,
        }).then(callback)
      };

    //Save orders
    this.submitOrder = function(FormData,callback) {
      
      console.log("Save All Locations");
      $http({
        method : 'GET',
        url : "CreateOrderForm.php?action=save",
        params:{FormData : JSON.stringify(FormData)},
      }).then(callback)
    };

  })
  .directive('validSubmit', ValidSubmit);

   
</script>


<?php
require '_footer.php';
}
else
{
  header('Location: ../../index.php');
}
?>