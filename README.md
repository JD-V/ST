# GMS

You will need to install XAMPP or setup apache server and instll mysql, php, phpmyadmin

once your Apache and mysql service is running locally you need to setup database.

You will find database dump inside Database folder. go to http://localhost/phpmyadmin/ create a new database and name it as gmsdb.
Import the SQL dump from Database folder to the database you just created.

Thats it..! you are done with Database.

Below is the project structure

index.php is the login page.
use following credentials to login.
email: admin@admin.com,
password: admin123,
After login you will see dashboard page.

Now you can create a new page inside pges/php.
If you want to add any new style or script file Add inside pages/css or pages/js respectivly and name it strting with your name
eg. 'DebJS1', 'DebCss1'.


You will find a page called Registrtation you can create a copy of this page and create other forms.

to make any new page available  inside menu bar. go to '_header.php' and add following code
```
 <?php
  $userRoleID = getUserRoleID();
  if($userRoleID == 1)	// Admin role 1
  {
    $isActive = "";
    if($CDATA['PAGE_NAME'] == 'SUBEVENT'){ $isActive =  'active'; }

    echo "<li class=\"hover " . $isActive . " \">
            <a href=\"newPage.php\" >
              <i class=\"fa fa-plus-square\"></i>
              <span>Page Display name</span>
              <span class=\"pull-right-container\">
              <i class=\"fa fa-angle-right pull-right\"></i>
              </span>
            </a>
          </li>";
  }
  ?>
```
