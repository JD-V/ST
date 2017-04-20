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

    <?php
    //$_SESSION['AUTH_KEY'] = mt_rand(100000000,999999999);
    ChromePhp::log("form");
    ChromePhp::log("UKEY" . @$_POST['UKey']);
    if(@$_POST['UKey'] == '2')
    {
      ChromePhp::log("AKEY" . $_POST['akey']);
      if(@$_POST['akey'] == $_SESSION['AUTH_KEY'])
      {
        if( isset($_POST['InvoiceNo']) && !empty($_POST['InvoiceNo']) &&
            isset($_POST['ServiceInvoiceDate']) && !empty($_POST['ServiceInvoiceDate']) &&
            isset($_POST['CustomerName']) && !empty($_POST['CustomerName']) &&
            isset($_POST['CustomerPhone']) && !empty($_POST['CustomerPhone']) &&
            isset($_POST['VehicleNo']) && !empty($_POST['VehicleNo']) &&
            isset($_POST['AmountPaid']) && !empty($_POST['AmountPaid']) )
          {

            $ServiceRecord = new ServiceRecord();

            $ServiceRecord->invoiceNumber = mysql_real_escape_string(trim($_POST['InvoiceNo']));

            $dateStr = mysql_real_escape_string(trim($_POST['ServiceInvoiceDate']));
            $date = DateTime::createFromFormat('d-m-Y H:i', $dateStr);
            $ServiceRecord->invoiceDate = $date->format('Y-m-d H:i:s');

            $ServiceRecord->customerName = mysql_real_escape_string(trim($_POST['CustomerName']));
            $ServiceRecord->customerPhone = mysql_real_escape_string(trim($_POST['CustomerPhone']));
            $ServiceRecord->vehicleNumber = mysql_real_escape_string(trim($_POST['VehicleNo']));
            $ServiceRecord->amountPaid = mysql_real_escape_string(trim($_POST['AmountPaid']));
            
            if(isset($_POST['Notes']))
              $ServiceRecord->notes = mysql_real_escape_string(trim($_POST['Notes']));
            
            $Msg = "";
            if(isset($_POST['InvoiceNumberForUpdate']) && !empty($_POST['InvoiceNumberForUpdate'])) {
              $Result = UpdateServicerecord($ServiceRecord);
              $Msg = "Service Record has been saved successfully!";
            } else {
              $Result = Addservicerecord($ServiceRecord);
              $Msg = "Service Record has been updated successfully!";
            }

            if($Result)
            {
                  echo '<div class="alert alert-block alert-success">
                          <button type="button" class="close" data-dismiss="alert">
                            <i class="ace-icon fa fa-times"></i>
                          </button>
                          <i class="ace-icon fa fa-check green"></i>'. $Msg .'</div>';
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

      <div id="AddOrUpdateServiceRecord" ng-app="serviceApp" ng-controller="serviceCtrl">
          <form class="form-horizontal"  name="Service" id="Service" action="addservicerecord.php?_auth=<?php echo $_SESSION['AUTH_KEY']; ?>" method="post">
              <input type="hidden" value="<?php echo $_SESSION['AUTH_KEY']; ?>" name="akey" id="ID_akey" >

              <div class="form-group">
                <label for="InvoiceNo" class="control-label col-sm-3 lables">Invoice No<span class="mandatoryLabel">*</span></label>
                <div class="col-sm-4">
                  <input type="text" class="form-control" readonly="readonly" name="InvoiceNo" value="<?php  if(isset($serviceRecord['InvoiceNumber'])) echo  $serviceRecord['InvoiceNumber'];  else echo GetMaxServiceInoviceNumber()+1;?>" >
                </div>
              </div>

              <div class="form-group">
                <label for="ServiceInvoiceDate" class="control-label col-sm-3 lables">Invoice Date<span class="mandatoryLabel">*</span></label>
                <div class='col-sm-4'>
                <?php
                    $dateVal = "";
                    if(isset($serviceRecord['InvoiceDateTime'])) {
                      $dateStr = $serviceRecord['InvoiceDateTime']; 
                      $date = DateTime::createFromFormat('Y-m-d H:i:s', $dateStr); 
                      $dateVal =   $date->format('d-m-Y H:i'); 
                    }
                  ?>
                  <input type="text" class="form-control" name="ServiceInvoiceDate" value = "<?php echo $dateVal ?>"/>
                </div>
              </div>

              <div class="form-group">
                <label for="CustomerName" class="control-label col-sm-3 lables">Customer Name<span class="mandatoryLabel">*</span></label>
                <div class="col-sm-4">
                  <input type="text" class="form-control" name="CustomerName" placeholder="Cutomer Name"  value="<?php  if(isset($serviceRecord['CustomerName'])) echo  $serviceRecord['CustomerName']; ?>" >
                </div>
              </div>

              <div class="form-group">
                <label for="CustomerPhone" class="control-label col-sm-3 lables">Phone<span class="mandatoryLabel">*</span></label>
                <div class="col-sm-4">
                  <div class='input-group'>
                    <span class="input-group-addon">
                        <span class="fa fa-mobile"></span>
                    </span>
                    <input type="text" class="form-control phone" maxlength="10" name="CustomerPhone" placeholder="Phone Number" onkeypress="return isNumberKey(event)"  value="<?php  if(isset($serviceRecord['CustomerPhone'])) echo  $serviceRecord['CustomerPhone']; ?>" >
                  </div>
                </div>
              </div>

              <div class="form-group">
                <label for="VehicleNo" class="control-label col-sm-3 lables">Vehicle Number<span class="mandatoryLabel">*</span></label>
                <div class="col-sm-4">
                  <input type="text" class="form-control" name="VehicleNo" placeholder="Vehicle Number"  value="<?php  if(isset($serviceRecord['VehicleNumber'])) echo  $serviceRecord['VehicleNumber']; ?>" >
                </div>
              </div>

            <div class="form-group">
            <label for="addItems" class="control-label col-sm-3 lables">Add Items<span class="mandatoryLabel">*</span></label>
              <div class="col-sm-4" >
                <div class="dropdown">
                  <button class="btn btn-primary dropdown-toggle" type="button" data-toggle="dropdown">Add serviceables&nbsp;&nbsp;&nbsp;&nbsp;<span class="caret"></span></button>
                  <ul class="dropdown-menu">
                    <li ng-repeat="item in serviceable"><a href="#" ng-click="AddItem(item.ItemID)">{{item.Item}}&nbsp;&nbsp;&nbsp;&nbsp;-&nbsp;&nbsp;&nbsp;{{item.Price}}</a></li>
                  </ul>
                </div>
              </div>
            </div>     

            <fieldset class="col-sm-9 col-xs-offset-1">
                <legend>Serviceable<span class="mandatoryLabel">*</span></legend>
                <div class="box-body">
      <div class="table-responsive col-sm-12" id= "OrganizerList" >
        <table id="OrgTable" class="table table-striped table-hover" >
          <thead>
            <tr>
              <th>Item</th>
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
                <input ng-change="item.QtyEdited = true" ng-click="item.editing = true" ng-blur=" item.editing = false; item.QtyInvalid = validateInput(item.Qty  ); item.QtyEdited = !item.QtyInvalid" onkeypress='return event.charCode >= 48 && event.charCode <= 57' type="text" ng-show="item.editing" ng-model="item.Qty"  />
            </div>
            </td>

            <td>
            <div>
              <label  ng-model="item.Amount" >{{item.Qty*item.Price}}</label>
            </div>
            </td>

            <td>
            <a href="#" ng-click="item.editing = !item.editing;" ><i class="fa fa-pencil-square-o" aria-hidden="true"></i></a>&nbsp;&nbsp;&nbsp;
            <a href="#" ng-click="RemoveItem(item.ItemID, $index);" ><i class="fa fa-times" aria-hidden="true"></i></a>
            </td>
          </tr>
        </table>
      </div>
      </div>
           </fieldset>    

              <div class="form-group">
                <label for="AmountPaid" class="control-label col-sm-3 lables">Amount paid<span class="mandatoryLabel">*</span></label>
                <div class="col-sm-4">
                  <div class='input-group'>
                    <span class="input-group-addon">
                        <span class="fa fa-inr"></span>
                    </span>
                    <input type="text" class="form-control amount currency" maskedFormat="10,2" name="AmountPaid" placeholder="0.00" value="<?php  if(isset($serviceRecord['AmountPaid'])) echo  $serviceRecord['AmountPaid']; ?>" >
                  </div>
                </div>
              </div>

              <div class="form-group">
                <label for="Notes" class="control-label col-sm-3 lables">Notes</label>
                <div class="col-sm-4">
                  <textarea  class="form-control" name="Notes" placeholder="Notes"><?php  if(isset($serviceRecord['Note'])) echo  $serviceRecord['Note']; ?></textarea>
                </div>
              </div>

              <div class="form-group">
                <label class="col-sm-3 control-label no-padding-right" for="form-field-1"> </label>

                <div class="col-sm-9">
                  <input type="submit" name="nc_submit" value="submit" id="ID_Sub" class="btn btn-sm btn-success" style="<?php if(isset($serviceRecord['InvoiceNumber'])) echo 'margin-left:90px'; else echo 'margin-left:50px'; ?>" />
                  <input type="hidden" name="UKey" value="1" id="ID_UKey" />
                  <input type="hidden" name="InvoiceNumberForUpdate" value="<?php if(isset($serviceRecord['InvoiceNumber'])) echo $serviceRecord['InvoiceNumber'] ?>" /> 
                  <button type="reset" class="btn btn-sm btn-default" style="visibility:<?php if(isset($serviceRecord['InvoiceNumber'])) echo 'hidden'; else 'visible'?> ">Clear</button>
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
      $scope.serviceable = response.data;
    });
  };
  $scope.serviceItem = [];
  
  
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
         
       }
      };
      
      $scope.RemoveItem = function($ItemID,$index){
    console.log('itemid' +$ItemID);
    console.log('index' +$index);
   // if($ItemID == null) {
      console.log("Removing at index : " + $index)
      $scope.serviceItem.splice($index,1);
    //}
  //  });
    
    };

  // initial call
  $scope.RefreshView();
  })
  
  
  .service('dataService', function($http) {

    //get Location Service
    this.getServiceable = function(callback) {
      console.log("Get Locations"); 
      $http({
        method : "GET",
        url : "AddUpdateRetriveServiceable.php?action=Retrive",
      }).then(callback)
    };

    //Delete Location Service
    this.deleteServiceable = function(ItemId,callback) {
      console.log("delete Location");
      $http({
        method : "GET",
        url : "AddUpdateRetriveServiceable.php?action=Remove&ItemID=" + ItemId,
      }).then(callback)
    };

    //Save Location Service
    this.saveAll = function(ItemArray,callback) {
      
      console.log("Save All Locations");
      $http({
        method : 'GET',
        url : "AddUpdateRetriveServiceable.php?action=save",
        params:{ItemArr : JSON.stringify(ItemArray)},
      }).then(callback)
    };

  });
</script>


<?php
require '_footer.php';
}
else
{
  header('Location: ../../index.php');
}
?>
