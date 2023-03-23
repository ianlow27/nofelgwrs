#!/usr/bin/php
<?php
$CMwyafEiriau=1800;
//=======================================================
$DGeirfa = array();

foreach(explode("\n", file_get_contents("./geirfa23.txt")) as $ll1a){
  $d1a = explode("\t", $ll1a);
  if(!isset($d1a[2])) continue;
  $d1a[0] = preg_replace("/ /", "_", strtolower($d1a[0]) );
  $d1a[2] = preg_replace("/ /", "_", strtolower($d1a[2]) );
  if(!isset($DGeirfa[$d1a[2]])){
    $DGeirfa[$d1a[2]] = $d1a[0]."~".$d1a[1];
  }else {
    $DGeirfa[$d1a[2]] .= "/". $d1a[0]."~".$d1a[1];
  }
}//dforeach
//echo count($DGeirfa);
//---------------------
$DGeiriau = array();
$DSaesneg = array();
foreach(explode("\n", file_get_contents("./geiriau.txt")) as $ll1a){
  if( trim($ll1a) == "") continue;
  $d1a = explode("/", trim($ll1a) );
  $d1a[0] = trim($d1a[0]);
  $d1a[1] = trim($d1a[1]);
  if(!isset($d1a[1])) continue;
  $DGeiriau[$d1a[0]] = $d1a[1];
  $d1b = explode("~", $d1a[1]);
  $d1b[0] = trim($d1b[0]);

  if(isset($db1[1])) $d1b[1] = trim($d1b[1]);

  if( !isset( $DSaesneg[$d1b[0]] ) ){
    $DSaesneg[$d1b[0]] = trim($d1a[0]);
  }else {
    $DSaesneg[$d1b[0]] .= ",".trim($d1a[0]);
  }
}
//echo $DSaesneg["and"]."\n";
//die();
//---------------------
$DFfeil = explode("\n", file_get_contents("./testun"));
$LlRhestr="";
$LlNewydd="";
//print_r($DGeiriau);
//print_r($DSaesneg);
$CGeiriau=0;
$BSylweb = 0;
foreach($DFfeil as $ll1a){
  $ll1a = trim($ll1a);
  if(substr($ll1a,0,2)=="/*"){ $BSylweb = 1; }
  if(substr($ll1a,0,2)=="*/"){ $BSylweb = 0;  continue; }
  if($BSylweb) continue;
   
  if(substr($ll1a,0,10)=="|YMADAEL!!"){ break; }

  if(substr($ll1a,0,1)=="|"){
//echo $ll1a."\n";
    
    if(substr($ll1a,0,7)=="|ffeil="){
     //echo $ll1a."\n";
     //echo "_________________________________\n";
      $CGeiriau=0;
      $LlRhestr="";
      $LlNewydd="";
    }
    if(preg_match("/=/", $ll1a)) continue;

//u´´´´´´´´´´´´´´´´´´´´´´´´´´´´´´´´´´´´´´/
    $ll1a = preg_replace("/\[\"([a-z]{1,1})/", "[xxzzxx$1", $ll1a);
    $ll1a = preg_replace("/([\[\]a-zA-Z\^`´'%\-_]+)([^\[\]a-zA-Z\^`'´%\-_]+)/", "$1 $2", $ll1a);
    $ll1a = preg_replace("/([^\[\]a-zA-Z\^`´%'\-_]+)([\[\]a-zA-Z\^`'´%\-_]+)/", "$1 $2", $ll1a);

    $d1a = explode(" ", substr($ll1a,1));
    foreach($d1a as $ll1b){
//echo "__[".$ll1b;
      $ll1b = datdreiglo($ll1b);
//echo "__[".$ll1b."\n";

if(preg_match("/([a-zA-Z]+)/", $ll1b)) $CGeiriau++;
//echo "__".$CGeiriau."__".$ll1b."\n";

//echo "__\n";
      if(substr(strrev($ll1b),0,1) == "`"){
        $ll2a = strtolower(strrev(substr(strrev($ll1b),1)));
    
        if(isset($DSaesneg[$ll2a] )){
          $LlRhestr.=$ll2a."xxx(".$DSaesneg[$ll2a] .") ";
          //$LlRhestr.=$ll2a."xxx ";
          //die("**********\nGWALL 001! Mae'r gair '". $ll2a. "(". $DSaesneg[$ll2a].")' wedi'i gael ei ddefnyddio!\n**********\n");
        }else {
          $LlRhestr.=strtolower(strrev(substr(strrev($ll1b),1)))." ";
        }
      }else {
        if(!isset($DGeiriau[strtolower($ll1b)])){
          if(preg_match("/[a-zA-Z\^%´'\-_]/", strtolower($ll1b) )){
            $LlNewydd .= strtolower($ll1b). " ";
          }
        }
      }
    }//dforeach

  }//dif

}//dforeach

if($CGeiriau > $CMwyafEiriau) die("\nGWALL 002!!! Mae'r nifer o eiriau (cyfanswm o ". $CGeiriau.") yn fwy nag ".$CMwyafEiriau.". Mae'n angen i leihau y geiriau!!\n");
//-------------------------------
$DRhestr= explode(" ",$LlRhestr);
sort($DRhestr);
$DRhestr = array_unique($DRhestr);
//print_r($DRhestr);

$CyRhif=0;
foreach($DRhestr as $ll1a){
  $ll1a = trim(strtolower($ll1a));
  if($ll1a == "") continue;
  $CyRhif++;
 
  if(!isset($DGeirfa[$ll1a])){
    if(preg_match("/xxx/", $ll1a)){
      //echo "**********\nGWALL 003! nid ydy'r gair Cymraeg ar gyfer '". $ll1a. "' yn bodoli yn geirfa.txt!\n**********\n";
      $d2b = explode("xxx", $ll1a);
      $ll1a = $d2b[0];
      echo "**********\nNODWCH! mae'r gair Saesneg <<< ". strtoupper($d2b[0]). " >>> wedi'i ddefnyddiwyd o'r blaen gyda'r gair Cymraeg <<< ". strtoupper($DSaesneg[$d2b[0]]). " >>>!\n**********\n";
    }else {
      die("**********\nGWALL 004! nid ydy'r gair Cymraeg ar gyfer '". $ll1a. "' yn bodoli yn geirfa.txt!\n**********\n");
    }
  }

  if(preg_match("/xxx/", $ll1a)){
    $ll1a = preg_replace("/xxx/", "", $ll1a);
    echo "!".$CyRhif.") !!!". $DSaesneg[$ll1a]. " = ". $ll1a."\n";
  }else {
    echo "!".$CyRhif.") ". $DGeirfa[$ll1a]. " = ". $ll1a."\n";
  }
}//dforeach
$dLlNewydd = explode(" ", $LlNewydd);
sort($dLlNewydd);
$dLlNewydd = array_unique($dLlNewydd);
echo "*** ";
foreach($dLlNewydd as $l1a){
  echo trim($l1a)." ";
}//dforeach
echo "*** (". $CGeiriau. " geiriau)\n";

//-------------------------------
function datdreiglo($p1a){
$p1a = strtolower($p1a);
$ll1a = substr($p1a,0,1);
$d1a = preg_split("/([\\[\\]]{1,1})/", $p1a);
  if(count($d1a) != 3){
    return $p1a;
  }else if($ll1a == "["){
    die("\nGWALL 005!!! Mae'r gair '". $p1a. "' yn diffygio  y llytheren m, a, t, c, d, neu n tu flaen i'r arwyddnod '['.\n");
  }else {
    //echo $d1a[0]."__". $d1a[1]. "___". $d1a[2]."\n";

$llDyfynnod="";
$llDychweliad=$p1a;
if(preg_match("/xxzzxx/", $d1a[1]) ){
  $d1a[1] = preg_replace("/xxzzxx/", "", $d1a[1]);
  $llDyfynnod='"';
} //die( "____".$d1a[1]."__".$p1a." \n");

    if      ($d1a[0] == "m"){
      if      ($d1a[1] == "g"){  $llDychweliad= "c".$d1a[2];
      }else if($d1a[1] == "b"){  $llDychweliad= "p".$d1a[2];
      }else if($d1a[1] == "d"){  $llDychweliad= "t".$d1a[2];
      }else if($d1a[1] == "f"){  $llDychweliad= "b".$d1a[2];
      }else if($d1a[1] == "fb"){ $llDychweliad= "b".$d1a[2];
      }else if($d1a[1] == "fm"){ $llDychweliad= "m".$d1a[2];
      }else if($d1a[1] == "dd"){ $llDychweliad= "d".$d1a[2];
      }else if($d1a[1] == "l"){  $llDychweliad= "ll".$d1a[2];
      }else if($d1a[1] == "r"){  $llDychweliad= "rh".$d1a[2];
      }else {                    $llDychweliad= "g".$d1a[1].$d1a[2];
      }
    }else if($d1a[0] == "a"){
      if      ($d1a[1] == "ch"){  $llDychweliad= "c".$d1a[2];
      }else if($d1a[1] == "ph"){  $llDychweliad= "p".$d1a[2];
      }else if($d1a[1] == "th"){  $llDychweliad= "t".$d1a[2];
      }else {                     $llDychweliad= $d1a[1].$d1a[2];
      }
    }else if($d1a[0] == "t"){
      if      ($d1a[1] == "ngh"){ $llDychweliad= "c".$d1a[2];
      }else if($d1a[1] == "mh"){  $llDychweliad= "p".$d1a[2];
      }else if($d1a[1] == "nh"){  $llDychweliad= "t".$d1a[2];
      }else if($d1a[1] == "ng"){  $llDychweliad= "g".$d1a[2];
      }else if($d1a[1] == "m"){   $llDychweliad= "b".$d1a[2];
      }else if($d1a[1] == "n"){   $llDychweliad= "d".$d1a[2];
      }else { $llDychweliad= $d1a[1].$d1a[2];
      }
    }else if($d1a[0] == "h"){
      $llDychweliad= "".$d1a[2];
    }else if($d1a[0] == "c"){
      if      ($d1a[1] == "ch"){  $llDychweliad= "c".$d1a[2];
      }else if($d1a[1] == "chg"){ $llDychweliad= "g".$d1a[2];
      }else if($d1a[1] == "ph"){  $llDychweliad= "p".$d1a[2];
      }else if($d1a[1] == "th"){  $llDychweliad= "t".$d1a[2];
      }else if($d1a[1] == "f"){  $llDychweliad= "b".$d1a[2];
      }else if($d1a[1] == "fb"){ $llDychweliad= "b".$d1a[2];
      }else if($d1a[1] == "fm"){ $llDychweliad= "m".$d1a[2];
      }else if($d1a[1] == "dd"){ $llDychweliad= "d".$d1a[2];
      }else if($d1a[1] == "l"){  $llDychweliad= "ll".$d1a[2];
      }else if($d1a[1] == "r"){  $llDychweliad= "r".$d1a[2];
      }else {                    $llDychweliad= "g".$d1a[1].$d1a[2];
      }
    }else {
      $llDychweliad= $d1a[1].$d1a[2];
    }

  }
  $llDyfynnod = "";
  return $llDyfynnod . $llDychweliad;

}//dfunc

?>
