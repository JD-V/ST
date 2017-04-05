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
