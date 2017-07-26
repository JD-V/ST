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
      <div>Available Stock
      <small></small><div>
    </h1>
  </section>

  <!-- Main content -->
  <section class="content">
    <div id="messages">
    </div>
    <!-- Default box -->
    <div class="box" ng-app="stockApp" ng-controller="stockCtrl" data-ng-init="RefreshView()">
      <div class="box-header with-border">
        <p class="box-title">Stock</p>
        <div class="box-tools pull-right">
          <span class=" total-count" > {{stocks.total()}}</span> Record(s) matching
        </div>          
      </div>
      <div class="box-body">
      <div class="table-responsive col-sm-12" >
        <table id="StockTable" class="table table-striped table-hover" ng-table="stocks"  show-filter="true" >
          <tr ng-repeat="item in $data">
            <td align="center" title="'ProductType'" filter="{ ProductType: 'select'}" filter-data="productTypes" sortable="'ProductType'">{{item.ProductType}}</td>
            <td align="center" title="'ProductBrand'" filter="{ ProductBrand: 'text'}" sortable="'ProductBrand'">{{item.ProductBrand}}</td>
            <td align="center" title="'ProductSize'" filter="{ ProductSize: 'text'}" sortable="'ProductSize'">{{item.ProductSize}}</td>
            <td align="center" title="'ProductPattern'" filter="{ ProductPattern: 'text'}" sortable="'ProductPattern'">{{item.ProductPattern}}</td>
            <td align="center" title="'Qty'" sortable="'Qty'">{{item.Qty}}</td>
            <td>
              <a href="addstock.php?id={{item.ProductID}}" class="btn btn-sm btn-info">ADD</a>
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
  angular.module('stockApp', ['ngTable'])
  .controller('stockCtrl', function($scope, dataService, $q, NgTableParams) {
    // refreshing data in the table
    $scope.RefreshView = function() {
      $scope.productTypes = [{id:'', title:'All'}];
      dataService.getProductTypes(function(response) {
        response.data.forEach(function(item,i) {
            console.log(item);
            $scope.productTypes.push({id:item.productTypeName, title:item.productTypeName });
        });
      });

      dataService.getStocks(function(response) {
        $scope.stocks = new NgTableParams({page: 1, count: 10}, { dataset: response.data});
      });
    };
 })
  .service('dataService', function($http) {

    // get Stocks Service
    this.getStocks = function(callback) {
      $http({
        method : "GET",
        url : "AddUpdateRetriveStokcs.php?action=Retrive",
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
