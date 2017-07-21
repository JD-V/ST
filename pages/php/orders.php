<?php
$CDATA['PAGE_NAME'] = 'ORDERS';
require '_connect.php';
require '_core.php';

if(isLogin())
{
require '_header.php';
$roleID = getUserRoleID();
?>
<script type = "text/javascript">


</script>
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper" ng-app="OrdersApp" ng-controller="OrdersCtrl" >
  <!-- Content Header (Page header) -->
  <section class="content-header" id="MainEventInfo" >
    <h1>
      <div>Order history
      <small></small><div>
    </h1>
<!--     <ol class="breadcrumb">
      <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
      <li><a href="#">Layout</a></li>
      <li class="active">Fixed</li>
    </ol> -->
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
        {{orders.total()}} Record(s) matching
        </div>
      </div>
      <div class="box-body">
      <div class="table-responsive col-sm-12" >
        <table class="table table-striped table-hover" ng-table="orders" show-filter="true" >
          <tr ng-repeat='order in $data'>
            <td align="center" title="'Invoice'" sortable="'invoiceNumber'" filter="{invoiceNumber: 'text'}" >{{order.invoiceNumber }}</td>
            <td align="center" title="'Date'"  sortable="'invoiceDate'" filter="{invoiceDate: 'text'}" >{{order.invoiceDate | date : "dd-MM-y"}}</td>
            <td align="center" title="'Customer'"  sortable="'customerName'" filter="{customerName: 'text'}" >{{order.customerName}}</td>
            <td align="center" title="'Phone'" sortable="'customerPhone'" filter="{customerPhone: 'text'}" >{{order.customerPhone}}</td>
            <td align="center" title="'Vehicle'" sortable="'vehicleNumber'" filter="{vehicleNumber: 'text'}" >{{order.vehicleNumber}}</td>
            <td align="center" title="'Mileage'" sortable="'vehicleMileage'" filter="{vehicleMileage: 'text'}" >{{order.vehicleMileage}}</td>
            <td align="center" title="'basic'" sortable="'basic'" filter="{basic: 'text'}" >{{order.basic | number:2}}</td>
            <td align="center" title="'Vat'" sortable="'vatAmount'" filter="{vatAmount: 'text'}" >{{order.vatAmount | number:2}}</td>
            <td align="center" title="'Discount'" sortable="'discount'" filter="{discount: 'text'}" >{{order.discount | number:2}}</td>
            <td align="center" title="'Amount Paid'" sortable="'amountPaid'" filter="{amountPaid: 'text'}" >{{order.amountPaid | number:2}}</td>
            <!--<td align="center" title="'Address'" sortable="'address'" filter="{address: 'text'}" >{{order.address}}</td>-->
            <td align="center" title="'Notes'" sortable="'notes'" filter="{notes: 'text'}" >{{order.notes}}</td>
            <td align="center" title="'Invoice'"> <input type="button" class="btn btn-sm btn-info" value="Invoice" ng-click = "DisplayInvoice(order.invoiceNumber);" /></td>
            <td ng-show="order.showEdit == 1">
              <a href="addneworder.php?id=order.invoiceNumber"><i class="fa fa-pencil" aria-hidden="true"></i></a>
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
  angular.module('OrdersApp',['ngTable'])
  
  .filter('customUserDateFilter', function($filter) {
      return function(values, dateString) {
      var filtered = [];
    
        if(typeof values != 'undefined' && typeof dateString != 'undefined') {
          angular.forEach(values, function(value) {
              if($filter('date')(value.Date).indexOf(dateString) >= 0) {
                filtered.push(value);
              }
            });
        }
        
        return filtered;
      }
  })

 .controller('OrdersCtrl', function($scope,dataService,NgTableParams) {
   $scope.refresh = function() { 
    $scope.orders = [];
    dataService.getOrders(function(response) {
      $scope.RoleID = <?php echo $roleID ?>;
      $scope.dateTime = new Date();
      var orders = response.data;
      orders.forEach(function(item,i) {
        item.invoiceDate = new Date(item.invoiceDate.replace(/-/g, "-"));
        item.timeStamp = new Date(item.timeStamp.replace(/-/g, "-"));

        console.log($scope.dateTime.getTime()-item.timeStamp.getTime());

        if($scope.RoleID == 1 || $scope.dateTime.getTime()-item.timeStamp.getTime() <= 900000)
          item.showEdit = 1;
        else 
          item.showEdit = 0;
      });
      $scope.orders = new NgTableParams( { },{ dataset: orders } );
    });
   }
  
  $scope.refresh();

  $scope.DisplayInvoice = function(InvoiceID){
    window.open("DisplaySaleInvoice.php?id="+InvoiceID);
  }

 })
 .service('dataService', function($http) {
    this.getOrders = function(callback) {
      $http({
        method : "GET",
        url: "CreateOrderForm.php?action=Retrive&item=orders"
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
