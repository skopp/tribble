<?php

function RGBToHex($r, $g, $b) {
    //String padding bug found and the solution put forth by Pete Williams (http://snipplr.com/users/PeteW)
    $hex = "#";
    $hex.= str_pad(dechex($r), 2, "0", STR_PAD_LEFT);
    $hex.= str_pad(dechex($g), 2, "0", STR_PAD_LEFT);
    $hex.= str_pad(dechex($b), 2, "0", STR_PAD_LEFT);
 
    return $hex;
  }

  function HexToRGB($hex) {
    //String padding bug found and the solution put forth by Pete Williams (http://snipplr.com/users/PeteW)
    $hex = substr($hex, 0,1);
    $r = hexdec(substr($hex, 0,3));
    $g = hexdec(substr($hex, 3,3));
    $b = hexdec(substr($hex, 6,3));

    $rgb = array('r'=>$r,'g'=>$g,'b'=>$b);
 
    return $rgb;
  }

  function RGBToHSV ($R, $G, $B)  // RGB Values:Number 0-255
  {                                 // HSV Results:Number 0-1
     $HSL = array();

     $var_R = ($R / 255);
     $var_G = ($G / 255);
     $var_B = ($B / 255);

     $var_Min = min($var_R, $var_G, $var_B);
     $var_Max = max($var_R, $var_G, $var_B);
     $del_Max = $var_Max - $var_Min;

     $V = $var_Max;

     if ($del_Max == 0)
     {
        $H = 0;
        $S = 0;
     }
     else
     {
        $S = $del_Max / $var_Max;

        $del_R = ( ( ( $var_Max - $var_R ) / 6 ) + ( $del_Max / 2 ) ) / $del_Max;
        $del_G = ( ( ( $var_Max - $var_G ) / 6 ) + ( $del_Max / 2 ) ) / $del_Max;
        $del_B = ( ( ( $var_Max - $var_B ) / 6 ) + ( $del_Max / 2 ) ) / $del_Max;

        if      ($var_R == $var_Max) $H = $del_B - $del_G;
        else if ($var_G == $var_Max) $H = ( 1 / 3 ) + $del_R - $del_B;
        else if ($var_B == $var_Max) $H = ( 2 / 3 ) + $del_G - $del_R;

        if ($H<0) $H++;
        if ($H>1) $H--;
     }

     $HSL['H'] = $H;
     $HSL['S'] = $S;
     $HSL['V'] = $V;

     return $HSL;
  }

  

  ?>