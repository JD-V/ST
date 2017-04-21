<?php
require '_connect.php';
require '_core.php';

$CDATA['PAGE_NAME'] = 'MNSTOCK';
if(isLogin() && isAdmin())
{
require '_header.php';
?>
<style type="text/css">

.edited label:after {
  content: " edited";
  text-transform: uppercase;
  color: #a7b9c4;
  font-size : 10px;
  padding-left: 5px;
}

.error label:after {
  content: " error";
  text-transform: uppercase;
  color: #ff0000;
  font-size : 10px;
  padding-left: 5px;
}

.normal {
  font-weight: 500;
}

</style>
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
  <!-- Content Header (Page header) -->
  <section class="content-header">
    <h1>
      <div>Manage Stokcs
      <small></small><div>
    </h1>
<!--     <ol class="breadcrumb">
      <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
      <li><a href="#">Layout</a></li>
      <li class="active">Fixed</li>
    </ol> -->
  </section>

  <!-- Main content -->
  <section class="content">
    <!-- <div class="callout callout-info">
      <h4>Tip!</h4>

      <p>Add the fixed class to the body tag to get this layout. The fixed layout is your best option if your sidebar
        is bigger than your content because it prevents extra unwanted scrolling.</p>
    </div> -->
    <div id="messages">
    </div>
    <!-- Default box -->


    <div class="box" ng-app="stockApp" ng-controller="stockCtrl">
      <div class="box-header with-border">
        <p class="box-title">Products</p>
        <div class="box-tools pull-right">
          <button type="button" class="btn btn-md btn-success" ng-click="SaveAll();">
            <i class="fa fa-floppy-o"></i>&nbsp;
            Save changes
          </button>
        </div>
      </div>
      <div class="box-body">
          <form>
    <div class="form-group">
      <div class="input-group">
        <div class="input-group-addon"><i class="fa fa-search"></i></div>
        <input type="text" class="form-control" placeholder="Search product" ng-model="serchProduct">
      </div>      
    </div>
  </form>

      <div class="table-responsive col-sm-12" >
        <table id="StockTable" class="table table-striped table-hover" datatable="ng" >
          <thead>
            <tr>
              <th>
                <a href="#" ng-click="sortType = 'ProductName'; sortReverse = !sortReverse">
                Product
                <span ng-show="sortType == 'ProductName' && !sortReverse" class="fa fa-caret-down"></span>
                <span ng-show="sortType == 'ProductName' && sortReverse" class="fa fa-caret-up"></span>
                </a>
              </th>
              <th>
                <a href="#" ng-click="sortType = 'Qty'; sortReverse = !sortReverse">
                  Quantity
                  <span ng-show="sortType == 'Qty' && !sortReverse" class="fa fa-caret-down"></span>
                  <span ng-show="sortType == 'Qty' && sortReverse" class="fa fa-caret-up"></span>
              </a>
            </th>
              <th><i class="fa fa-pencil-square-o" aria-hidden="true"></i></th>
            </tr>
          </thead>
          <tr ng-repeat="item in stocks | orderBy:sortType:sortReverse | filter:serchProduct ">
            <td>
            <div>
              <label>{{item.ProductName}}</label>
            </div>
            </td>
            <td>
            <div ng-class="{'edited': item.qtyEdited, 'error' : item.qtyInvalid}">
              <label  ng-hide="item.editing"  class='normal' >{{item.Qty}}</label>
                <input id="someid" ng-change="item.qtyEdited = true" ng-click="item.editing = true" ng-blur=" item.editing = false; item.qtyInvalid = validateInput(item.Qty); item.qtyEdited = !item.qtyInvalid" onkeypress='return event.charCode >= 48 && event.charCode <= 57' type="text" ng-show="item.editing" auto-focus="{{ item.editing }}" ng-model="item.Qty"  />
            </div>
            </td>
            <td>
            <a href="#" ng-click="item.editing = !item.editing; angular.element('#someid').focus()" ><i class="fa fa-pencil-square-o" aria-hidden="true"></i></a>&nbsp;&nbsp;&nbsp;
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

  angular.module('stockApp', [])
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
  .controller('stockCtrl', function($scope,$filter, dataService) {
  $scope.sortType     = 'ProductName'; // set the default sort type
  $scope.sortReverse  = false;  // set the default sort order
  $scope.serchProduct   = '';     // set the default search/filter term

  // refreshing data in the table
  $scope.RefreshView = function() {
    dataService.getStocks(function(response) {
      console.log(response.data);
      $scope.stocksActual = angular.copy(response.data);
      $scope.stocks = response.data;
      
    });
  };
  $scope.minlength = 3;

  // initial call
  $scope.RefreshView();

  // save Stocks Action
  $scope.SaveAll = function() {
    console.log("Save All");
    var ItemArr = []; 
    for (var i = 0; i < $scope.stocks.length; i++) {
       if(  $scope.stocks[i].hasOwnProperty('qtyEdited')  &&  $scope.stocks[i].qtyEdited ) {
          var found = $filter('getById')($scope.stocksActual, $scope.stocks[i].ProductID);
          if($scope.stocks[i].Qty != found.Qty) {
            var obj = {};
            if($scope.stocks[i].Qty < found.Qty) {
              obj = {
                'ProductID': $scope.stocks[i].ProductID,
                'Qty' : $scope.stocks[i].Qty - found.Qty,
                'TansactionTypeID'  : '2' // 2=>REMOVE
              };
            } else {
              obj = {
                'ProductID': $scope.stocks[i].ProductID,
                'Qty' :  $scope.stocks[i].Qty - found.Qty,
                'TansactionTypeID'  : '1'  // 1=>ADD
              };
            }
            ItemArr.push(obj);
          } else $scope.stocks[i].qtyEdited = false;
        
          if( $scope.stocks[i].hasOwnProperty('qtyInvalid') &&  $scope.stocks[i].qtyInvalid )
              {
                document.getElementById("messages").innerHTML = "<div class=\"alert alert-block \
                alert-warning\"><button type=\"button\" class=\"close\" data-dismiss=\"alert\"> \
                <i class=\"ace-icon fa fa-times\"></i></button><i class=\"ace-icon fa fa-hand-paper-o\"></i>&nbsp;&nbsp; Please correct the errors.</div>";
                autoClosingAlert(".alert-block", 2000);
                return;
              }
       }  
    }

    if(ItemArr.length == 0) {

      document.getElementById("messages").innerHTML = "<div class=\"alert alert-block \
          alert-warning\"><button type=\"button\" class=\"close\" data-dismiss=\"alert\"> \
          <i class=\"ace-icon fa fa-times\"></i></button><i class=\"ace-icon fa fa-ban\"></i>&nbsp;&nbsp; Nothing to update.</div>";
          autoClosingAlert(".alert-block", 2000);
          return;
    }

    dataService.saveAll(ItemArr, function(response) {
      if(parseInt(response.data, 10)) {
         document.getElementById("messages").innerHTML = "<div class=\"alert alert-block \
          alert-success\"><button type=\"button\" class=\"close\" data-dismiss=\"alert\"> \
          <i class=\"ace-icon fa fa-times\"></i></button><i class=\"ace-icon fa fa-check \
          green\"></i>&nbsp;&nbsp;" + response.data +" Item(s) saved successfully.</div>";
          $scope.RefreshView();
          autoClosingAlert(".alert-block", 2000);
      }
      else {
          document.getElementById("messages").innerHTML = "<div class=\"alert alert-block \
          alert-danger\"><button type=\"button\" class=\"close\" data-dismiss=\"alert\">  \
          <i class=\"ace-icon fa fa-times\"></i> </button><i class=\"ace-icon fa fa-ban \
          red\"></i> &nbsp; &nbsp;" + response.data + " </div>";
          autoClosingAlert(".alert-block", 4000);
      }
    });
  };
  // Over
  $scope.validateInput = function($inputValue) {
    if($inputValue === "") {
      document.getElementById("messages").innerHTML = "<div class=\"alert alert-block \
      alert-danger\"><button type=\"button\" class=\"close\" data-dismiss=\"alert\">  \
      <i class=\"ace-icon fa fa-times\"></i> </button><i class=\"ace-icon fa fa-ban \
      red\"></i> &nbsp; &nbsp;can not be blank</div>";
      autoClosingAlert(".alert-block", 2000);
      return true;
    }
    else
      return false;
  };

  })
  .service('dataService', function($http) {

    //get Location Service
    this.getStocks = function(callback) {
      console.log("Get Locations"); 
      $http({
        method : "GET",
        url : "AddUpdateRetriveStokcs.php?action=Retrive",
      }).then(callback)
    };

    //Save Location Service
    this.saveAll = function(ItemArray,callback) {
      
      console.log("Save All Locations");
      $http({
        method : 'GET',
        url : "AddUpdateRetriveStokcs.php?action=save",
        params:{ItemArr : JSON.stringify(ItemArray)},
      }).then(callback)
    };

  })
  .directive('autoFocus', function($timeout) {
    return {
        link: function (scope, element, attrs) {
            attrs.$observe("autoFocus", function(newValue){
                if (newValue === "true")
                    $timeout(function(){element.focus()});
            });
        }
    };
});

  function autoClosingAlert(selector, delay) {
   var alert = $(selector).alert();
   window.setTimeout(function() { alert.alert('close') }, delay);
  }

</script>
<?php
require '_footer.php';
}
else
{
  header('Location: ../../index.php');
}
?>
