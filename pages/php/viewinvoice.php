<?php
require '_connect.php';
require '_core.php';

$CDATA['PAGE_NAME'] = 'ADDINV';
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
      <div>Invoice</div>
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
        <h3 class="box-title">View</h3>
       <!--  <div class="box-tools pull-right">
          <button type="button" class="btn btn-box-tool" data-widget="collapse" data-toggle="tooltip" title="Collapse">
            <i class="fa fa-minus"></i></button>
          <button type="button" class="btn btn-box-tool" data-widget="remove" data-toggle="tooltip" title="Remove">
            <i class="fa fa-times"></i></button>
        </div> -->
      </div>
      <div class="box-body" ng-app="InvoicesApp" ng-controller="InvoiceCtrl">

        <div class="container col-xs-12">

            <div>
                <div class="invoice-title">
                    <h2>Purchase Invoice</h2><h3 class="pull-right">
                    <button type="button" class="btn btn-md btn-info" ng-click="DisplayPreviousInvoice();"><i class="fa fa-arrow-left" aria-hidden="true"></i></button>
                    #{{Invoice.invoiceNumber}}
                    <button type="button" class="btn btn-md btn-info" ng-click="DisplayNextInvoice();"><i class="fa fa-arrow-right" aria-hidden="true"></i></button></h3>
                </div>
                <hr>
                <div class="row">
                    <div class="col-xs-6">
                        <address>
                        <strong>Supplier: &nbsp;&nbsp;</strong>{{Invoice.supplierName}}<br>
                        <strong>TIN: &nbsp;&nbsp;</strong>{{Invoice.tinNumber}}<br>
                        </address>
                    </div>
                    <!--<div class="col-xs-6 text-right">
                        <address>
                        <strong>Shipped To:</strong><br>
                            Jane Smith<br>
                            1234 Main<br>
                            Apt. 4B<br>
                            Springfield, ST 54321
                        </address>
                    </div>-->
                </div>
                <div class="row">
                    <div class="col-xs-6">
                        <address>
                            <strong>Payment Method: &nbsp;&nbsp;</strong>
                            <span ng-if="Invoice.paymentType==1">Cash</span>
                            <span ng-if="Invoice.paymentType==2">Card</span>
                            <span ng-if="Invoice.paymentType==3">Cheque</span>
                            <br/>
                            <span ng-if="Invoice.paymentType==3">Cheque No: {{Invoice.chequeNo}} <br/> Cheque Date : {{Invoice.chequeDate}} </span>    					
                        </address>
                    </div>
                    <div class="col-xs-6 text-right">
                        <address>
                            <strong>Invoice Date:</strong><br>
                            {{Invoice.invoiceDate}}
                            
                        </address>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-xs-12">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <h3 class="panel-title"><strong>Order summary</strong></h3>
                        </div>
                        <div class="panel-body">
                            <div class="table-responsive">
                                <table class="table table-condensed">
                                    <thead>
                                        <tr>
                                            <td><strong>Type</strong></td>
                                            <td><strong>Brand</strong></td>
                                            <td><strong>Size</strong></td>
                                            <td><strong>Pattern</strong></td>
                                            <td class="text-right"><strong>Price</strong></td>
                                            <td class="text-right"><strong>Quantity</strong></td>
                                            <td class="text-right"><strong>Total</strong></td>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        
                                        <!-- foreach ($order->lineItems as $line) or some such thing here -->
                                        <tr ng-repeat="product in InvoiceProducts">
                                            <td>{{product.productTypeName}}</td>
                                            <td>{{product.brand}}</td>
                                            <td>{{product.productSize}}</td>
                                            <td>{{product.productPattern}}</td>
                                            <td class="text-right">{{product.rate}}</td>
                                            <td class="text-right">{{product.units}}</td>
                                            <td class="text-right">{{product.Amount}}</td>
                                        </tr>
                                        <tr>
                                            <td class="no-line" colspan="5"></td>
                                            <td class="no-line text-right"><strong>Sub Total</strong></td>
                                            <td class="no-line text-right">{{Invoice.subTotalAmount}}</td>
                                        </tr>
                                        <tr>
                                            <td class="no-line" colspan="5"></td>
                                            <td class="no-line text-right"><strong>Vat</strong></td>
                                            <td class="no-line text-right">{{Invoice.vatAmount}}</td>
                                        </tr>
                                        <tr>
                                            <td class="no-line" colspan="5"></td>
                                            <td class="no-line text-right"><strong>Total</strong></td>
                                            <td class="no-line text-right">{{Invoice.totalAmount}}</td>
                                        </tr>
                                        <tr>
                                            <td class="no-line text-right"><strong>Notes</strong></td>
                                            <td class="no-line">{{Invoice.invoiceNotes}}</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
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

<script type="text/javascript" >

angular.module('InvoicesApp',[])
.controller('InvoiceCtrl', function($scope,dataservice) {
    $scope.InvoiceID = '<?php if(isset($_GET['id'])) echo $_GET['id'] ?>';

    $scope.RefreshView = function() {
        dataservice.getInvoice($scope.InvoiceID, function(response) {
            $scope.Invoice = response.data;
            console.log($scope.Invoice);

            dataservice.getInvoiceProducts($scope.InvoiceID, function(response) {
                $scope.InvoiceProducts = response.data;
                console.log($scope.InvoiceProducts);            
            });
            
        });
    }
    $scope.RefreshView();

    $scope.DisplayPreviousInvoice = function() {
        $scope.InvoiceID--;
        if($scope.InvoiceID==0)
            $scope.InvoiceID = 1;        
        $scope.RefreshView();
    }

    $scope.DisplayNextInvoice = function() {
        $scope.InvoiceID++;
        $scope.RefreshView();
    }    
})
.service('dataservice',function($http) {

    this.getInvoice = function(invoiceID,callback) {
        $http({
            method: 'GET',
            url : 'GetPurcahseInvoiceData.php?action=Retrive&item=Invoice&Invoice='+ invoiceID,
        }).then(callback)
    }

    this.getInvoiceProducts = function(invoiceID,callback) {
        $http({
            method : 'GET',
            url : 'GetPurcahseInvoiceData.php?action=Retrive&item=Products&Invoice='+ invoiceID,
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
