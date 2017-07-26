<?php
$CDATA['PAGE_NAME'] = 'SERVICES';
require '_connect.php';
require '_core.php';

if(isLogin())
{
require '_header.php';
$roleID = getUserRoleID();
?>

<!--<script type = "text/javascript">

function DisplayInvoice(InvoiceID){

window.open("DisplayServiceInvoice.php?id="+InvoiceID);

}
</script>-->

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper" ng-app="ServiceDataApp" ng-controller="ServiceDataCtrl" >
  <!-- Content Header (Page header) -->
  <section class="content-header"  >
    <h1>
      <div>Service history
      <small></small><div>
    </h1>
  </section>

  <!-- Main content -->
  <section class="content" >
    <!-- <div class="callout callout-info">
      <h4>Tip!</h4>

      <p>Add the fixed class to the body tag to get this layout. The fixed layout is your best option if your sidebar
        is bigger than your content because it prevents extra unwanted scrolling.</p>
    </div> -->
    <div id="messages">
    </div>
    <!-- Default box -->


    <div class="box">
      <div class="box-header with-border">
        <h3 class="box-title"></h3>
        <div class="box-tools pull-right">
          <span class=" total-count" > {{servicesDataNgParams.total()}}</span> Record(s) matching
        </div>
      </div>
      <div class="box-body">
        <div class="table-responsive col-sm-12" >
          <table class="table table-striped table-hover" ng-table="servicesDataNgParams" show-filter="true" >
            <tr ng-repeat='service in $data'>
              <td align="center" title="'Invoice'" sortable="'invoiceNumber'" filter="{invoiceNumber: 'text'}" >{{service.invoiceNumber }}</td>
              <td align="center" title="'Date'"  sortable="'invoiceDate'" filter="{'invoiceDate': 'text'}" >{{service.invoiceDate | date : "dd-MM-y"}}</td>
              <td align="center" title="'Customer'"  sortable="'customerName'" filter="{customerName: 'text'}" >{{service.customerName}}</td>
              <td align="center" title="'Phone'" sortable="'customerPhone'" filter="{customerPhone: 'text'}" >{{service.customerPhone}}</td>
              <td align="center" title="'Vehicle'" sortable="'vehicleNumber'" filter="{vehicleNumber: 'text'}" >{{service.vehicleNumber}}</td>
              <td align="center" title="'Mileage'" sortable="'vehicleMileage'" filter="{vehicleMileage: 'text'}" >{{service.vehicleMileage}}</td>
              <td align="center" title="'basic'" sortable="'basic'" filter="{basic: 'text'}" >{{service.subTotal | number:2}}</td>
              <td align="center" title="'Discount'" sortable="'discountAmount'" filter="{discountAmount: 'text'}" >{{service.discountAmount | number:2}}</td>
              <td align="center" title="'Paid'" sortable="'amountPaid'" filter="{amountPaid: 'text'}" >{{service.amountPaid | number:2}}</td>
              <td align="center" title="'Notes'" sortable="'notes'" filter="{notes: 'text'}" >{{service.notes}}</td>
              <td align="center" title="'Invoice'"> <input type="button" class="btn btn-sm btn-info" value="Invoice" ng-click = "DisplayInvoice(order.invoiceNumber);" /></td>
              <td ng-show="service.showEdit == 1">
                <a href="addservicerecord.php?id=service.invoiceNumber"><i class="fa fa-pencil" aria-hidden="true"></i></a>
              </td>
            </tr>
          </table>
        </div>
      </div>
      <!-- /.box-body -->
    </div>
    <!-- /.box -->

  </section>
  <!-- /.content -->
</div>
<!-- /.content-wrapper -->

<script type="text/javascript" >

var app = angular.module('ServiceDataApp',['ngTable'])
 .controller('ServiceDataCtrl', function($scope,dataService,NgTableParams) {

   $scope.refresh = function() {
    $scope.services = [];
    
    dataService.getServiceData(function(response) {
      $scope.RoleID = <?php echo $roleID ?>;
      $scope.dateTime = new Date();
      var services = response.data;
      
          
      services.forEach(function(item,i) {
        item.timeStamp = new Date(item.timeStamp.replace(/-/g, "-"));

        if($scope.RoleID == 1 || $scope.dateTime.getTime()-item.timeStamp.getTime() <= 900000)
          item.showEdit = 1;
        else 
          item.showEdit = 0;
      });

      $scope.servicesDataNgParams = new NgTableParams( { },{ dataset: services } );
    });
   }
  
  $scope.refresh();

  $scope.DisplayInvoice = function(InvoiceID){
    window.open("DisplayServiceInvoice.php?id="+InvoiceID);
  }

 })
 .service('dataService', function($http) {
    this.getServiceData = function(callback) {
      $http({
        method : "GET",
        url: "CreateServiceRecordForm.php?action=Retrive&item=servicerecords",
      }).then(callback)
    }
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
