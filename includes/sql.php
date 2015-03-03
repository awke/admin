<?php

function sqlconn()
{
  global $dberror,$db,$dbhost,$dbname,$dbuser,$dbpasswd;
  $dberror=0;
  global $db;
  echo $dbuser;
  @mysql_close($db);
  if(!$db = @mysql_connect("$dbserver", "$dbuser", "$dbpasswd"))
  {
    $dberror=1;
  }


  $db_selected = @mysql_select_db("$dbname", $db);
  if (!$db_selected) 
  {

    $dberror=1;
  }

}

require_once('logging.php');
function gosql($sql,$verbose=0)
{
  global $db,$dberror;
  if ($result=@mysql_query($sql))
  {
    $dberror=0;
    if($verbose) 
    {                      
        echo $sql;  
        echo("<P>Sucessful - $sql</P>");
        logsys("sucessfull - $sql");
    }
}
else
{
  $dberror=1;
  if($verbose)     
  {
    $dberror=1;
    logsys("FAILED ".mysql_error(). "  in $sql");
    echo("<P>Error creating item: ".mysql_error()." in <br><b> $sql</b></P>");
  }
}

return $result;

}
?>