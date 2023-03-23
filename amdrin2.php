#!/usr/bin/php
<?php
/*
$DGeirfa = array();

foreach(explode("\n", file_get_contents("./geirfa23.txt")) as $ll1a){
  $d1a = explode("\t", $ll1a);
  if(!isset($d1a[2])) continue;
  if(!isset($DGeirfa[$d1a[2]])){
    $DGeirfa[$d1a[2]] = $d1a[0]."_".$d1a[1];
  }else {
    $DGeirfa[$d1a[2]] .= "/". $d1a[0]."_".$d1a[1];
  }
}//dforeach
//echo count($DGeirfa);
//---------------------
$DGeiriau = array();
foreach(explode(" ", file_get_contents("./geiriau.txt")) as $ll1a){
  $ll1a = trim($ll1a);
  $DGeiriau[$ll1a] = 1;
}
//---------------------
*/


$cyRhif=0;
$DFfeil = explode("\n", file_get_contents("./testun"));
$LlGeiriau="";
$BSylweb = 0;
foreach($DFfeil as $ll1a){
  $ll1a = trim($ll1a);
  if(substr($ll1a,0,2)=="/*"){ $BSylweb = 1; }
  if(substr($ll1a,0,2)=="*/"){ $BSylweb = 0;  continue; }
  if($BSylweb) continue;

  if(substr($ll1a,0,10)=="|YMADAEL!!"){ break; }

  if(substr($ll1a,0,7)=="|ffeil="){
    $LlGeiriau="";
  }

  if(substr($ll1a,0,1)=="!"){
    $d1a = preg_split("/[\)=]/", $ll1a);
    $d1b = explode("/", $d1a[1]);
    $llGair="";
    foreach($d1b as $ll1b){
      $ll1b = trim($ll1b);
      $llGair=preg_replace("/`/", "", $ll1b) ;
      if(substr(strrev($ll1b),0,1)=="`"){
        break;
      }
    }//dforeach
    if($llGair == "") die("GWAL!! Diffyg o arwyddnod '`' yn '".$ll1a."'. ");
    $cyRhif++;
    $dGair = explode("~", $llGair);
    //echo "`". $cyRhif. ") ". $dGair[0]. " = ".  $d1a[2]."~".$dGair[1]."\n";
    $LlGeiriau .= $dGair[0]. " = ".  $d1a[2]."~".$dGair[1]."\n";
  }//dif

}//dforeach

echo  '
=====================================================
`ffeil=gwersxxx
`gwers=Welsh Lesson xxxb - Beginner-1 Sylfaenol
`teitl=Vocab Stanza xxx/Reading Vocab
';

$DGeiriau = explode("\n", $LlGeiriau);
sort($DGeiriau);
$cyRhif=0;
foreach($DGeiriau as $ll1a){
  if(trim($ll1a)=="") continue;
  $cyRhif++;
  echo "`". $cyRhif. ") ". $ll1a."\n";
}//dforeach



echo '`nodwch=1)
`nodwch=2)
`nodwch=3)
`maintcy=135
`llun1=grammadeg1/170/425/45
`llun2=cenhinen/80/503/390
`llun3=gwacter/1/3/7
`llun4=gwacter/80/495/325
`===========
';
?>
