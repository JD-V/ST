<?php
require '_connect.php';
require '_core.php';

if(isLogin() && isOrganizer() )
{
  if( isset($_GET['subeventid']) && isset($_GET['LocationId']) && isset($_GET['StartDtTm']) && isset($_GET['Duration'])){

    $subeventid = mysql_real_escape_string($_GET['subeventid']);
  	$Duration = mysql_real_escape_string($_GET['Duration']);
	  $LocationId = mysql_real_escape_string($_GET['LocationId']);
    $StartDtTm = mysql_real_escape_string($_GET['StartDtTm']);

		GetLocationAvailibility($subeventid,$Duration,$LocationId,$StartDtTm);
    
   }
}

function GetLocationAvailibility($subeventid,$Duration,$LocationId,$StartDtTm)
{
  if( $getLoc = mysql_query(" SELECT se.subeventname, se.StartDtTm, se.LocationId, DATE_ADD(se.StartDtTm, INTERVAL se.Duration HOUR_MINUTE) AS EndDate FROM subevent se WHERE 
        se.LocationId = '$LocationId' AND se.subeventid != '$subeventid' AND (
        TO_SECONDS('$StartDtTm') > TO_SECONDS(se.StartDtTm) AND  
        TO_SECONDS('$StartDtTm') < TO_SECONDS(DATE_ADD(se.StartDtTm, INTERVAL se.Duration HOUR_MINUTE)) 
        OR 
        TO_SECONDS(DATE_ADD('$StartDtTm', INTERVAL '$Duration' HOUR_MINUTE)) > TO_SECONDS(se.StartDtTm) AND  
        TO_SECONDS(DATE_ADD('$StartDtTm', INTERVAL '$Duration' HOUR_MINUTE)) < TO_SECONDS(DATE_ADD(se.StartDtTm, INTERVAL se.Duration HOUR_MINUTE))
        OR
        TO_SECONDS('$StartDtTm') < TO_SECONDS(se.StartDtTm) AND  
        TO_SECONDS(DATE_ADD('$StartDtTm', INTERVAL '$Duration' HOUR_MINUTE)) > TO_SECONDS(DATE_ADD(se.StartDtTm, INTERVAL se.Duration HOUR_MINUTE))
        OR
        TO_SECONDS('$StartDtTm') > TO_SECONDS(se.StartDtTm) AND  
        TO_SECONDS(DATE_ADD('$StartDtTm', INTERVAL '$Duration' HOUR_MINUTE)) < TO_SECONDS(DATE_ADD(se.StartDtTm, INTERVAL se.Duration HOUR_MINUTE)) )" ) )
      {
        if(mysql_num_rows($getLoc)>0)
        {
          $loc = mysql_fetch_assoc($getLoc);

          echo 'Event \''.$loc['subeventname'] . '\' has occupied this location from ' . date("H:i:s",strtotime($loc['StartDtTm'])) . ' to ' . date("H:i:s",strtotime($loc['EndDate']));
        }
        else
          echo '<span style="color:Green">Available</span>';
      }
}
