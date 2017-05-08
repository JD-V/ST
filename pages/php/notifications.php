<?php
$CDATA['PAGE_NAME'] = 'NOTIFY';
require '_connect.php';
require '_core.php';

if(isLogin() && isAdmin())
{
require '_header.php';
?>


  <script type="text/javascript">
    $.ajax({
      dataType: "json",
      type: "GET",
      url: "AddUpdateRetriveStokcs.php?action=Retrive",
      success: function(result) {
        var count =0;
        var StockArray= result;
        //console.log(StockArray);
        $.each(StockArray, function( key, value ) {
          var Qty= parseInt(value.Qty)
          if(Qty != NaN && Qty>9) {
            // console.log( value.ProductName + " : green : " + value.Qty );
          }
          else if(Qty != NaN && Qty>0) {
            count++;
            // console.log( value.ProductName + " : yellow : " + value.Qty );
            $('.notifications-table-body    ').append(
              $('<tr/>').append(
                  $('<td/>').append(
                    $('<a/>', {'href': 'manageStock.php?product='+value.ProductDisplay }).append(
                        $('<i/>', {'class': 'fa fa-warning text-yellow'})
                    ).append("&nbsp;&nbsp;&nbsp;&nbsp;" +value.ProductDisplay  +' has only ' + value.Qty + ' Nos Left')
                  )
              )
           );             
          }
          else {
            count++;
            // console.log( value.ProductName + " : red : " + value.Qty );
             $('.notifications-table-body').append(
              $('<tr/>').append(
                $('<td/>').append(
                    $('<a/>', {'href': 'manageStock.php?product='+value.ProductDisplay}).append(
                        $('<i/>', {'class': 'fa fa-ban text-red'})
                    ).append("&nbsp;&nbsp;&nbsp;&nbsp;" +value.ProductDisplay  +' is out of stock')
                )
              )
           );    
          }
        });

        // $('.notification-count-message').html('You have ' + count + ' notificaitons');
        // $('.notification-count').html(count);
      }
    });
          
  </script>

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
  <!-- Content Header (Page header) -->
  <section class="content-header" id="MainEventInfo" >
    <h1>
      <div>Notifications
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


    <div class="box">
      <div class="box-header with-border">
        <h3 class="box-title">Active</h3>

        <div class="box-tools pull-right">
<!--           <button type="button" class="btn btn-box-tool" data-widget="collapse" data-toggle="tooltip" title="Collapse">
            <i class="fa fa-minus"></i></button>
          <button type="button" class="btn btn-box-tool" data-widget="remove" data-toggle="tooltip" title="Remove">
            <i class="fa fa-times"></i></button> -->
        </div>
      </div>
      <div class="box-body">
      <div class="table-responsive col-sm-12" >
          <table class="table table-striped table-hover" >
            <thead>
              <tr>
                <th>Description</th>
              </tr>
            </thead>
            <tbody class="notifications-table-body">
            </tbody>
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
<?php
require '_footer.php';
}
else
{
  header('Location: ../../index.php');
}
?>
