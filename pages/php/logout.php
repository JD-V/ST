<?php
require '_connect.php';
require '_core.php';

if(!(@$http_referer == 'direct_link') && isLogin())
  {
    session_destroy();
    header('Location: ../../index.php');
  }
else
  {
    header('Location: dashboard.php');
  }
?>
