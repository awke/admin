<?php

function Array2File( $aryArray, $sFilename ,$append=0)
{
   
   //Optimistic execution
   $retVal = true;
  
if($append==1)
{
$mode="a+";
}
else
{
$mode="w+";
} 
   //Try to open the file
   if (!$handle = fopen($sFilename, $mode)) {
         print "Cannot open file ($sFilename)";
         $retVal = false;
   }else{

       //Convert the array to a string. Each element is one line of the string.
       $sArrayAsString = "";
       foreach( $aryArray as $thisElement ){
		$trim =rtrim($thisElement);
           $sArrayAsString .= $trim . "\n";    
       }
       
       //Be good and remove the trailing newline
       $sArrayAsString = substr( $sArrayAsString, 0, strlen( $sArrayAsString ) - 1 );

       //Write the data to the file
       if (!fwrite($handle, $sArrayAsString)) {
           print "Cannot write to file ($sFilename)";
           $retVal = false;
       }else{
           //Debug:
           //print "Success, wrote ($somecontent) to file ($filename)";        
           fclose($handle);
       }            
   }
   
   return $retVal;
}
