<?php
$CDATA['PAGE_NAME'] = 'REPORTS';
require '_connect.php';
require '_core.php';

if(isLogin())
{
require '_header.php';
?>

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
  <!-- Content Header (Page header) -->
  <section class="content-header" id="MainEventInfo" >
    <h1>
      <div>Reports
      <small></small><div>
    </h1>
  </section>


  <!-- Main content -->
  <section class="content">
    <div class="callout bg-gray">
      <h4>Analytics !</h4>
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
