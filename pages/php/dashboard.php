<?php
$CDATA['PAGE_NAME'] = 'DASHBOARD';
require '_connect.php';
require '_core.php';
if(isLogin())
{
require '_header.php';

?>

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
  <!-- Content Header (Page header) -->
<!--   <section class="content-header">
    <h1>
      <small>DASHBOARD</small>
    </h1>
  </section> -->

  <!-- Main content -->
  <section class="content">
    <div class="alert bg-gray" id="welcomeMsg">
      <h4>Welcome <?php getUserName() ?></h4>
    </div>

      <div class="row">
        <div class="col-lg-3 col-xs-6">
          <!-- small box -->
          <div class="small-box bg-aqua">
            <div class="inner">
              <h3><?php echo TodaysSale() ?></h3>
              <p>Today's sale</p>
            </div>
            <div class="icon">
              <i class="ion ion-stats-bars"></i>
            </div>
            <a href="orders.php?date=<?php echo date('d-m-Y');?>" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
          </div>
        </div>
        <!-- ./col -->
        <div class="col-lg-3 col-xs-6">
          <!-- small box -->
          <div class="small-box bg-green">
            <div class="inner">
              <h3><?php echo TodaysService(); ?></h3>
              <p>Today's Service</p>
            </div>
            <div class="icon">
              <i class="fa fa-car"></i>
            </div>
            <a href="services.php?date=<?php echo date('d-m-Y');?>" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
          </div>
        </div>
        <!-- ./col -->
        <div class="col-lg-3 col-xs-6">
          <!-- small box -->
          <div class="small-box bg-yellow">
            <div class="inner">
              <h3><?php echo TodaysNonBillable() ?></h3>
              <p>Today's Non billables</p>
            </div>
            <div class="icon">
              <i class="fa fa-thumb-tack"></i>
            </div>
            <a href="nonbillable.php?date=<?php echo date('d-m-Y');?>" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
          </div>
        </div>
        <!-- ./col -->
        <div class="col-lg-3 col-xs-6">
          <!-- small box -->
          <div class="small-box bg-red">
            <div class="inner">
              <h3>0</h3>

              <p>upcoming Cheques</p>
            </div>
            <div class="icon">
              <i class="fa fa-money"></i>
            </div>
            <a href="#" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
          </div>
        </div>
        <!-- ./col -->
      </div>
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
