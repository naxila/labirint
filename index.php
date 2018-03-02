<?php

$file = file_get_contents("1.txt");
$file = explode("\n", $file);
unset($file[0]);
$ln = 0;
foreach ($file as $s) {
  $ln++;
  echo "<h2>Лабиринт $ln</h2><br>";
  $s = explode(" ", $s);
  $from = $s[0];
  $to = $s[1];

  $res = [];
  $ways = ['yug', 'vostok', 'sever', 'zapad'];
  $vector = 0;

  $h = 0;
  $h_max = 0;
  $w = 0;
  $w_max = 0;
  $w_min = 0;

  for ($i=0; $i < strlen($from); $i++) { 
    $vector = vector($from[$i], $vector);
    if ($from[$i] == "W") {
      if ($vector == 0) {
        $h++;
        if ($h > $h_max) $h_max = $h;
      }
      elseif ($vector == 2) {
        $h--;
      }
      elseif ($vector == 3) {
        $w--;
        if ($w < $w_min) $w_min = $w;
      }
      elseif ($vector == 1) {
        $w++;
        if ($w > $w_max) $w_max = $w;
      }

    }
  }

  $h = $h_max;
  $w = (abs($w_min)+$w_max);
  $arr = [];

  for ($i=0; $i < $h; $i++) {
    for ($j=0; $j < $w; $j++) { 
      $arr[$i][$j] = "";
    }
  }

  $i = 0;
  $j = abs($w_min)-1;
  $vector = 0;
  $st = 0;
  for ($m=0; $m < strlen($from); $m++) { 
    $vector = vector($from[$m], $vector);
    if ($from[$m] == "R") {
      $arr[$i][$j] .= vector("B", $vector).vector("L",$vector);
    }
    elseif ($m > 1 && $from[$m-1] == "W") {
      $arr[$i][$j] .= vector("L",$vector);
    }
    if ($from[$m] == "W" && $m > 0 && $m != strlen($from)-1) {
      // $arr[$i][$j] = $arr[$i][$j]."$st;";
      $st++;
      if ($vector == 0) $i++;
      elseif ($vector == 1) $j++;
      elseif ($vector == 2) $i--;
      elseif ($vector == 3) $j--; 
    }
  }

  $vector = vector("B", $vector);
  // echo $vector." ".$i." ".$j;
  for ($m=0; $m < strlen($to); $m++) { 
    $vector = vector($to[$m], $vector);
    if ($to[$m] == "R") {
      $arr[$i][$j] .= vector("B", $vector).vector("L",$vector);
    }
    elseif ($m > 1 && $to[$m-1] == "W") {
      $arr[$i][$j] .= vector("L",$vector);
    }
    if ($to[$m] == "W" && $m > 0) {
      if ($vector == 0) $i++;
      elseif ($vector == 1) $j++;
      elseif ($vector == 2) $i--;
      elseif ($vector == 3) $j--; 
    }
  }

  foreach ($arr as $ar) {
    foreach ($ar as $a) {
      if ($a != "") echo " $a";
      else echo " _";
    }
    echo "<br>";
  }

}

function vector($current_vector, $v){
  if($current_vector=='L') {
    $v += 1;
  }
  else if($current_vector=='R') {
    $v -= 1;
  }
  else if($current_vector=='B') {
    $v -= 2;
  }
  if($v<0){
    $v = $v + 4;
  }
  else if($v>3){
    $v = 0;
  }
  return $v;
}

