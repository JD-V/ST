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
      <div>Invnetory
      <small></small><div>
    </h1>
  </section>

  <!-- Main content -->
  <section class="content">
    <div id="messages">
    </div>
    <!-- Default box -->
    <div class="box" ng-app="inventoryApp" ng-controller="inventoryCtrl" data-ng-init="RefreshView()">
      <div class="box-header with-border">
        <p class="box-title">Products</p>
      </div>
      <div class="box-body">
      <div class="table-responsive col-sm-12" >
        <table id="StockTable" class="table table-striped table-hover" ng-table="inventory"  show-filter="true" >
          <tr ng-repeat="item in $data">
            <td align="center" title="'Type'" filter="{ productTypeName: 'select'}" filter-data="productTypes" sortable="'productTypeName'">{{item.productTypeName}}</td>
            <td align="center" title="'Brand'" filter="{ brandName: 'text'}" sortable="'brandName'">{{item.brandName}}</td>
            <td align="center" title="'Size'" filter="{ productSize: 'text'}" sortable="'productSize'">{{item.productSize}}</td>
            <td align="center" title="'Pattern'" filter="{ productPattern: 'text'}" sortable="'productPattern'">{{item.productPattern}}</td>
            <td align="center" title="'Stock Alert'" sortable="'minStockAlert'"><i ng-class="{'fa fa-check green' : item.minStockAlert==1,'fa fa-times red' : item.minStockAlert==0}" aria-hidden="true"></i></td>
            <td align="center" title="'Notes'" filter="{ productNotes: 'text'}" sortable="'productNotes'">{{item.productNotes}}</td>
            <td align="center" title="'Last Modified'" filter="{ lastModified: 'text'}" sortable="'lastModified'">{{item.lastModified}}</td>
            <!--<td title="'Edit'"><a href="addproduct.php?id={{item.productID}}" ><i class="fa fa-pencil" aria-hidden="true"></i></a></td>-->
            <td title="'Add Stock'"><a href="addstock.php?id={{item.productID}}" class="btn btn-sm btn-info">ADD</a></td>
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
  angular.module('inventoryApp', ['ngTable'])
  .controller('inventoryCtrl', function($scope, dataService, $q, NgTableParams) {
    // refreshing data in the table
    $scope.RefreshView = function() {
      
      $scope.productTypes = [{id:'', title:'All'}];
      dataService.getProductTypes(function(response) {
        response.data.forEach(function(item,i) {
            console.log(item);
            $scope.productTypes.push({id:item.productTypeName, title:item.productTypeName });
        });
      });
      dataService.getInventories(function(response) {
        $scope.inventory = new NgTableParams({page: 1, count: 10}, { dataset: response.data});
      });
    };
 })
  .service('dataService', function($http) {

    this.getInventories = function(callback) {
      $http({
        method : "GET",
        url : "getinventorydetails.php?action=retrive&item=inventoryproducts",
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
