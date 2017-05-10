<?php
require '_connect.php';
require '_core.php';

$CDATA['PAGE_NAME'] = 'ADSRVREC';
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
    <div>Service record
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

      <div ng-app="serviceApp" ng-controller="serviceCtrl" ng-controller="ScrollController">
          <form class="form-horizontal" name="serviceForm" valid-submit="sendForm()" novalidate >
              <input type="hidden" value="<?php echo $_SESSION['AUTH_KEY']; ?>" name="akey" id="ID_akey" >

              <div class="form-group">
                <label for="InvoiceNo" class="control-label col-sm-3 lables">Invoice No<span class="mandatoryLabel">*</span></label>
                <div class="col-sm-4">
                  <input type="text" class="form-control" name="InvoiceNo" ng-model = "InvoiceNo"  required>
                </div>
                <div ng-show = "serviceForm.$submitted && serviceForm.InvoiceNo.$invalid" class="errorMessage">Invoice No is required</div>
              </div>

              <div class="form-group">
                <label for="ServiceInvoiceDate" class="control-label col-sm-3 lables ">Invoice Date<span class="mandatoryLabel">*</span></label>
                <div class='col-sm-4'>
                  <input type="text" class="form-control service-invoice-date" name="ServiceInvoiceDate" ng-model = "ServiceInvoiceDate" required/>
                </div>
                <div ng-show = "serviceForm.ServiceInvoiceDate.$dirty && serviceForm.ServiceInvoiceDate.$invalid" class="errorMessage">Date is required</div>
              </div>

              <div class="form-group">
                <label for="CustomerName" class="control-label col-sm-3 lables">Customer Name<span class="mandatoryLabel">*</span></label>
                <div class="col-sm-4">
                  <input type="text" class="form-control" name="CustomerName" ng-model = "CustomerName" placeholder="Cutomer Name" ng-model = "CustomerName" required>
                </div>
                <div  ng-show="serviceForm.$submitted && serviceForm.CustomerName.$error.required" class="errorMessage">customer name is required</div>
              </div>

              <div class="form-group">
                <label for="CustomerPhone" class="control-label col-sm-3 lables">Phone<span class="mandatoryLabel">*</span></label>
                <div class="col-sm-4">
                  <div class='input-group'>
                    <span class="input-group-addon">
                        <span class="fa fa-mobile"></span>
                    </span>
                    <input type="text" class="form-control phone" maxlength="10" ng-minlength="10" ng-maxlength="10" name="CustomerPhone" placeholder="Phone Number" ng-model="CustomerPhone" onkeypress="return isNumberKey(event)" required>
                  </div>
                </div>
                <div ng-show="serviceForm.$submitted && serviceForm.CustomerPhone.$error.required" class="errorMessage">Please enter phone numnber</div>
                <div class="errorMessage" ng-show="((serviceForm.CustomerPhone.$error.minlength || serviceForm.CustomerPhone.$error.maxlength) &&  serviceForm.CustomerPhone.$dirty) ">phone number should be 10 digits</div>
              </div>

              <div class="form-group">
                <label for="VehicleNo" class="control-label col-sm-3 lables">Vehicle Number<span class="mandatoryLabel">*</span></label>
                <div class="col-sm-4">
                  <input type="text" class="form-control" name="VehicleNo" placeholder="Vehicle Number" ng-model="VehicleNo" required >
                </div>
                <div ng-show="serviceForm.$submitted && serviceForm.VehicleNo.$error.required" class="errorMessage">Please enter Vehicle numnber</div>
              </div>

            <div class="form-group">
              <label for="VehicleMileage" class="control-label col-sm-3 lables">Vehicle Mileage<span class="mandatoryLabel">*</span></label>
              <div class="col-sm-4">
                <input type="text" class="form-control" name="VehicleMileage" placeholder="Vehicle Mileage" ng-model="VehicleMileage" required >
              </div>
              <div ng-show="serviceForm.$submitted && serviceForm.VehicleMileage.$error.required" class="errorMessage">Please enter Vehicle Mileage</div>
            </div>              

            <div class="form-group">
              <label for="Address" class="control-label col-sm-3 lables">Address<span class="mandatoryLabel">*</span></label>
              <div class="col-sm-4">
                <textarea  class="form-control" id="Address" name="Address" cols="30" rows="3" placeholder="Address" ng-model="Address" required></textarea>
              </div>
              <div ng-show="serviceForm.$submitted && serviceForm.Address.$error.required" class="errorMessage">Please provide valid address</div>
            </div>

            <div class="form-group">
            <label for="addItems" class="control-label col-sm-3 lables">Add Items<span class="mandatoryLabel">*</span></label>
              <div class="col-sm-4" >
                <div class="dropdown">
                  <button style="width:100%" class="btn btn-primary dropdown-toggle" type="button" data-toggle="dropdown">Add serviceables&nbsp;&nbsp;&nbsp;&nbsp;<span class="caret"></span></button>
                  <ul style="width:100%" class="dropdown-menu">
                    <li ng-repeat="item in serviceable"><a ng-click="AddItem(item.ItemID)">{{item.Item}}&nbsp;&nbsp;&nbsp;&nbsp;-&nbsp;&nbsp;&nbsp;{{item.Price}}</a></li>
                  </ul>
                </div>
              </div>
              <div ng-show = "serviceForm.$submitted  && (serviceItem.length==0)" class="errorMessage">Please select atleast one item</div>
            </div>   

            <fieldset class="col-sm-9 col-xs-offset-1">
              <legend>Serviceable<span class="mandatoryLabel">*</span></legend>
              <div class="box-body">
              <div class="table-responsive col-sm-12" id= "OrganizerList" >
                <table id="OrgTable" class="table table-striped table-hover" >
                  <thead>
                    <tr>
                      <th>Particulars</th>
                      <th>Price</th>
                      <th>Qty</th>
                      <th>Amount</th>

                      <th><i class="fa fa-pencil-square-o" aria-hidden="true"></i>&nbsp;&nbsp;&nbsp;<i class="fa fa-times" aria-hidden="true"></i></th>
                    </tr>
                  </thead>
                  <tr ng-repeat="item in serviceItem">
                    <td>
                    <div ng-class="{'edited': item.itemEdited, 'error' : item.itemInvalid }">
                      <label ng-hide="item.editing" >{{item.Item}}</label>
                        <input ng-change="item.itemEdited = true;" ng-click="item.editing = true" ng-blur="item.editing = false; item.itemInvalid = validateInput(item.Item); item.itemEdited = !item.itemInvalid" type="text" ng-show="item.editing" ng-model="item.Item;"  />
                    </div>
                    </td>
                    <td>
                    <div ng-class="{'edited': item.priceEdited, 'error' : item.priceInvalid}">
                      <label  ng-hide="item.editing" >{{item.Price}}</label>
                        <input ng-change="item.priceEdited = true" ng-click="item.editing = true" ng-blur=" item.editing = false; item.priceInvalid = validateInput(item.Price  ); item.priceEdited = !item.priceInvalid" onkeypress='return event.charCode >= 48 && event.charCode <= 57' type="text" ng-show="item.editing" ng-model="item.Price"  />
                    </div>
                    </td>
                    <td>
                    <div ng-class="{'edited': item.QtyEdited, 'error' : item.QtyInvalid}">
                      <label  ng-hide="item.editing" >{{item.Qty}}</label>
                      <input ng-change="item.QtyEdited = true" ng-click="item.editing = true" ng-blur=" item.editing = false; item.QtyInvalid = validateInput(item.Qty  );  item.QtyEdited = !item.QtyInvalid" onkeypress='return event.charCode >= 48 && event.charCode <= 57' type="text" ng-show="item.editing" ng-model="item.Qty"  />
                    </div>
                    </td>

                    <td>
                    <div>
                      <label  ng-model="item.Amount" >{{item.Qty*item.Price}}</label>
                    </div>
                    </td>

                    <td>
                    <a ng-click="item.editing = !item.editing;" ><i class="fa fa-pencil-square-o" aria-hidden="true"></i></a>&nbsp;&nbsp;&nbsp;
                    <a ng-click="RemoveItem(item.ItemID, $index);" ><i class="fa fa-times" aria-hidden="true"></i></a>
                    </td>
                  </tr>
                </table>
              </div>
              </div>
            </fieldset>

              <div class="form-group">
                <label for="SubTotal" class="control-label col-sm-3 lables">SubTotal<span class="mandatoryLabel">*</span></label>
                <div class="col-sm-4">
                  <div class='input-group'>
                    <span class="input-group-addon">
                        <span class="fa fa-inr"></span>
                    </span>
                    <input type="text" disabled class="form-control amount"   name="subTotal" placeholder="0.00" ng-model="SubTotal" required>
                  </div>
                </div>
                <div  ng-show="serviceForm.$submitted && serviceForm.subTotal.$error.required" class="errorMessage"></div>
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
                <label for="AmountPaid" class="control-label col-sm-3 lables">Amount paid<span class="mandatoryLabel">*</span></label>
                <div class="col-sm-4">
                  <div class='input-group'>
                    <span class="input-group-addon">
                        <span class="fa fa-inr"></span>
                    </span>
                    <input type="text" class="form-control amount" onkeypress='return event.charCode >= 48 && event.charCode <= 57' name="TotalAmountPaid" placeholder="0.00" ng-model="TotalAmountPaid" required>
                  </div>
                </div>
                <div ng-show = "serviceForm.TotalAmountPaid.$dirty && ( TotalAmountPaid == undefined || TotalAmountPaid <= 0 )" class="errorMessage">Please enter Total Amount Paid</div>
              </div>

              <div class="form-group">
                <label for="Notes" class="control-label col-sm-3 lables">Notes</label>
                <div class="col-sm-4">
                  <textarea  class="form-control" name="Notes" cols="30" rows="3" placeholder="Notes" ng-model="Notes" ></textarea>
                </div>
              </div>

              <div class="form-group">
                <label class="col-sm-3 control-label no-padding-right" for="form-field-1"> </label>

                <div class="col-sm-9" >
                  <button ng-click="gotoBottom()" type="submit" class="btn btn-sm btn-success">Submit!</button>
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

  angular.module('serviceApp', [])
  .filter('getById', function() {
  return function(input, id) {
    var i=0, len=input.length;
    for (; i<len; i++) {
      if (+input[i].ItemID == +id) {
        return input[i];
      }
    }
    return null;
  }
})
  .controller('serviceCtrl', function($scope,$filter, dataService) {
  
  // refreshing data in the table
  $scope.RefreshView = function() {
    dataService.getServiceable(function(response) {
      console.log(response.data);
      //$scope.serviceableActual = angular.copy(response.data);
      $scope.serviceable = response.data;
    });
  };
   //$scope.serviceItem = [];
  // $scope.TotalAmountPaid  = 0.0;

$scope.AddItem = function(ItemId){

       if($filter('getById')($scope.serviceItem, ItemId)== null){
       
        var found = $filter('getById')($scope.serviceable, ItemId);
        console.log(found);
        var obj = {
          'ItemID': found.ItemID,
          'Item' : found.Item,
          'Price' : found.Price,
          'Qty' :1,
          'Amount': found.Price
        };

         $scope.serviceItem.push(obj);
         $scope.CalculateAmount();
       }
      };
      
      $scope.RemoveItem = function($ItemID,$index){
        $scope.serviceItem.splice($index,1);
        $scope.CalculateAmount();
    };

  $scope.updateTotalAmountPaid = function() {
    $scope.TotalAmountPaid = $scope.SubTotal;
    if($scope.DiscountAmount != undefined && $scope.DiscountAmount != 0)
      $scope.TotalAmountPaid -=  +$scope.DiscountAmount;
  };    

  $scope.CalculateAmount = function(){
    var sum = 0;
      angular.forEach($scope.serviceItem, function(value){
          sum += +(value.Qty*value.Price);
      });
      $scope.SubTotal = sum;
      $scope.TotalAmountPaid = $scope.SubTotal;
  };

  $scope.validateInput = function($inputValue) {
    if($inputValue === "") {
      document.getElementById("messages").innerHTML = MessageTemplate(1, "can not be blank.");
      autoClosingAlert(".alert-block", 2000);
      return true;
    }
    else
    {
      $scope.CalculateAmount();
      return false;
    }
  };
  $scope.reset = function(){
      $scope.CustomerName  = "";
      $scope.ServiceInvoiceDate = '<?php echo date("d-m-Y H:i") ?>';
      dataService.getMaxServiceInvoiceNumber(function(response) {
        console.log(response.data);
        $scope.InvoiceNo = parseInt(response.data) +  +1;
      });
        $scope.serviceItem = [];
        $scope.SubTotal = 0;
        $scope.TotalAmountPaid = 0;
        $scope.VehicleNo = '';
        $scope.VehicleMileage = '';
        $scope.CustomerPhone = '';
        $scope.DiscountAmount = 0;
        $scope.Notes = '';
        $scope.Address = '';
   }

  $scope.reset();
  $scope.sendForm = function() {
    var serviceInvoiceDateVal = $('.service-invoice-date').val();

    if($scope.SubTotal == 0 || serviceInvoiceDateVal == '') {
      document.getElementById("messages").innerHTML = MessageTemplate(1, "Please correct all the errors.");
        autoClosingAlert(".alert-block", 2000);
    } else {
      var FormData = {
        Products: []
      };
      for (var i = 0; i < $scope.serviceItem.length; i++) {

        if( ($scope.serviceItem[i].hasOwnProperty('itemInvalid') && $scope.serviceItem[i].itemInvalid ) ||
            ($scope.serviceItem[i].hasOwnProperty('priceInvalid') && $scope.serviceItem[i].priceInvalid ) ||
            ($scope.serviceItem[i].hasOwnProperty('QtyInvalid') && $scope.serviceItem[i].QtyInvalid )  )
            {
              document.getElementById("messages").innerHTML =MessageTemplate(1, "Please correct the errors.");
              autoClosingAlert(".alert-block", 2000);
              return;
            } else FormData.Products.push($scope.serviceItem[i]);
      }
      FormData.InvoiceNo = $scope.InvoiceNo;
      FormData.CustomerName= $scope.CustomerName;
      $scope.ServiceInvoiceDate = serviceInvoiceDateVal;
      FormData.ServiceInvoiceDate = $scope.ServiceInvoiceDate;
      FormData.CustomerPhone = $scope.CustomerPhone;
      FormData.VehicleNo = $scope.VehicleNo;
      FormData.VehicleMileage = $scope.VehicleMileage;
      FormData.SubTotal = $scope.SubTotal;
      FormData.DiscountAmount =  $scope.DiscountAmount;
      FormData.TotalAmountPaid =  $scope.TotalAmountPaid;
      FormData.Address = $scope.Address;
      FormData.Notes = $scope.Notes;
      console.log(JSON.stringify(FormData));

      dataService.submitServiceRecord(FormData, function(response) {
          if(parseInt(response.data, 10)) {
            document.getElementById("messages").innerHTML = 
              MessageTemplate(0, "Service Invoice has been added with " + response.data +" item(s)");
              $scope.reset();
              $scope.serviceForm.$setPristine();
              $scope.serviceForm.$setUntouched();
              $scope.serviceForm.$submitted = false;
          }
          else {
              document.getElementById("messages").innerHTML =  MessageTemplate(1, response.data);
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

    //get Location Service
    this.getServiceable = function(callback) {
      $http({
        method : "GET",
        url : "AddUpdateRetriveServiceable.php?action=Retrive",
      }).then(callback)
    };

  this.getMaxServiceInvoiceNumber = function(callback) {
        
        $http({
          method : "GET",
          url : "CreateServiceRecordForm.php?action=Retrive&item=MaxServiceInvoice",
        }).then(callback)
      };
    //Save Service
    this.submitServiceRecord = function(FormData,callback) {
      
      $http({
        method : 'GET',
        url : "CreateServiceRecordForm.php?action=save",
        params:{FormData : JSON.stringify(FormData)},
      }).then(callback)
    };

  }).directive('validSubmit', ValidSubmit);
</script>


<?php
require '_footer.php';
}
else
{
  header('Location: ../../index.php');
}
?>
