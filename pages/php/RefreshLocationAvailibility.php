<?php
require '_connect.php';
require '_core.php';

if(isLogin() && isAttendee())
{
  if(isset($_GET['action']) && isset($_GET['event_Id']))
  {

  	if($_GET['action'] == 'refresh')
		{
			$userid = $_SESSION['userid'];
			$eventId = mysql_real_escape_string($_GET['event_Id']);
	        RefreshLocationAvailibility($eventId,$userid);
		}
      
   }
}

function RefreshLocationAvailibility($EventId,$userid)
{
	$seatsArray = array();

	if($SeatsAvailable = mysql_query(" SELECT SE.subeventId, SE.StartDtTm,NOW() as currentDT, ER2.eventregid, (SE.AttendanceLimit - (select count(*) from eventreg ER WHERE ER.eventid = SE.subeventId)) as seatsLeft FROM subevent SE LEFT JOIN eventreg ER2 ON SE.subeventId = ER2.eventid AND ER2.userid='$userid' WHERE SE.EventId = '$EventId' ") )
	{

		if(mysql_num_rows($SeatsAvailable) >0)
		{
			while($EventSeat =  mysql_fetch_assoc($SeatsAvailable))
			{
				$seatsArray[] = $EventSeat;
			}
		}
	}
	
	echo json_encode($seatsArray);
}