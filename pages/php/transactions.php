<?php
require '_connect.php';
require '_core.php';

$CDATA['PAGE_NAME'] = 'MNSTOCK';
if(isLogin())
{
require '_header.php';
?>

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
  <!-- Content Header (Page header) -->
  <section class="content-header">
    <h1>
      <div>Stock Activity
      <small></small><div>
    </h1>
  </section>

  <!-- Main content -->
  <section class="content">
    <div id="messages">
    </div>
    <!-- Default box -->
    <div class="box" ng-app="stockTxnApp" ng-controller="stockTxnCtrl" data-ng-init="RefreshView()">
      <div class="box-header with-border">
        <p class="box-title">Transactions</p>
        <div class="box-tools pull-right">
          <span class=" total-count" > {{stockTxn.total()}}</span> Record(s) matching
        </div>          
      </div>
      <div class="box-body">
      <div class="table-responsive col-sm-12" >
        <table id="StockTable" class="table table-striped table-hover" ng-table="stockTxn"  show-filter="true" >
          <tr ng-repeat="item in $data">
            <td align="center" title="'Date/Time'" filter="{ transactionDate: 'text'}" sortable="'transactionDate'">{{item.transactionDate}}</td>
            <td align="center" title="'Type'" filter="{ productType: 'select'}" filter-data="productTypes" sortable="'productType'">{{item.productType}}</td>
            <td align="center" title="'Brand'" filter="{ brand: 'text'}" sortable="'brand'">{{item.brand}}</td>
            <td align="center" title="'Size'" filter="{ productSize: 'text'}" sortable="'productSize'">{{item.productSize}}</td>
            <td align="center" title="'Pattern'" filter="{ productPattern: 'text'}" sortable="'productPattern'">{{item.productPattern}}</td>
            <td align="center" title="'Qty'" filter="{ qty: 'text'}" sortable="'qty'">{{item.qty}}</td>
            <td align="center" title="'Transaction Type'" filter="{ transactionType: 'select'}" filter-data="transactionTypes" sortable="'transactionType'"
                ng-class="{'green': item.transactionTypeID == 1, 
                        'red': item.transactionTypeID == 2, 
                        'blue': item.transactionTypeID == 3,
                        'purple': item.transactionTypeID == 4}" >{{item.transactionType}}
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

<script type="text/javascript">
  angular.module('stockTxnApp', ['ngTable'])
  .controller('stockTxnCtrl', function($scope, dataService, $q, NgTableParams) {
    // refreshing data in the table
    $scope.RefreshView = function() {
      
      $scope.productTypes = [{id:'', title:'All'}];
      $scope.transactionTypes = [{id:'', title:'All'},
                                 {id:'PURCHASE', title:'PURCHASE'},
                                 {id:'SELL', title:'SELL'},
                                 {id:'ADD', title:'ADD'},
                                 {id:'REMOVE', title:'REMOVE'}];
      dataService.getProductTypes(function(response) {
        response.data.forEach(function(item,i) {
          $scope.productTypes.push({id:item.productTypeName, title:item.productTypeName });
        });
      });
      dataService.getStockTransactions(function(response) {
        $scope.stockTxn = new NgTableParams({page: 1, count: 10}, { dataset: response.data});
      });
    };
 })
  .service('dataService', function($http) {

    this.getStockTransactions = function(callback) {
      $http({
        method : "GET",
        url : "AddUpdateRetriveStokcs.php?action=Retrive&item=stocktxns",
      }).then(callback)
    };

    this.getProductTypes = function(callback) {
      $http({
        method : "GET",
        url : "getinventorydetails.php?action=retrive&item=producttypes",
      }).then(callback)
    };

  })
</script>

<?php
require '_footer.php';
}
else
{
  header('Location: ../../index.php');
}
?>
