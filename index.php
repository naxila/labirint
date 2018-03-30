<?php

$file = file_get_contents("2.txt");
$file = explode("\n", $file);

if ($file[count($file)-1] == "") unset($file[count($file)-1]);
unset($file[0]);

$ln = 0;
foreach ($file as $s) {
  $ln++;
  @printResult(solve($s, $ln));
}

function printResult($arr) {
  if (is_array($arr)) {
    foreach ($arr as $ar) {
      if (is_array($ar)) {
        foreach ($ar as $a) {
          if ($a != "") {
            $a = normalize($a);
            echo "$a";
          }
          else {
            echo "f";
          }
        }
        echo "<br/>";
      }
      else {
        if ($ar != "") {
          $ar = normalize($ar);
          echo "$ar";
        }
        else {
          echo "f";
        }
      }
    }
  }
  else echo $arr;
}

function normalize($a) {
  // return $a;
  $outs = [
    "013" =>  "1",
    "123" =>  "2",
    "13"  =>  "3",
    "012" =>  "4",
    "01"  =>  "5",
    "12"  =>  "6",
    "1"   =>  "7",
    "023" =>  "8",
    "03"  =>  "9",
    "23"  =>  "a",
    "3"   =>  "b",
    "02"  =>  "c",
    "0"   =>  "d",
    "2"   =>  "e"];
 
  $a = str_split($a);
  $a = array_unique($a);
  sort($a);
  $a = implode($a);
  return $outs[$a];
}

function vector($current_vector, $v){
  if($current_vector=='L') {
    $v += 1;
  }
  else if($current_vector=='R') {
    $v -= 1;
  }
  else if($current_vector=='B') {
    $v += 2;
  }
  if($v<0){
    $v = 3;
  }
  else if($v>3){
    $v = $v - 4;
  }
  return $v;
}

function solve($s, $ln) {
  echo "<br/>Case #$ln:<br>";
  $s = explode(" ", $s);
  $from = trim($s[0]);
  $to = trim($s[1]);

  $res = [];
  $ways = ['yug', 'vostok', 'sever', 'zapad'];
  $vector = 0;

  $h = 0;
  $h_max = 0;
  $w = 0;
  $w_max = 0;
  $w_min = 0;

  if (strlen($from) == 2 && strlen($to) == 2) {
    return "3";
  }

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

      if ($i == strlen($from)-1 && ($vector == 0 || $vector == 2)) {
        $h_max--;
        $w_max++;
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
  $lastVector = 0;
  $st = 0;
  $lastCell = [$i, $j];

  for ($m=0; $m < strlen($from); $m++) { 
    $lastVector = $vector;
    $vector = vector($from[$m], $vector);
    if ($from[$m] == "R") {
      $arr[$i][$j] .= vector("L",$vector).vector("B", $vector);
    }
    if ($m > 0 && $from[$m-1] == "W" && $from[$m] == "W") {
      $arr[$i][$j] .= vector("L", $lastVector);
    }
    if ($from[$m] == "W" && $m > 0 && $m != strlen($from)-1) {
      // $arr[$i][$j] = $arr[$i][$j]."$st;";
      $st++;
      $lastCell = [$i, $j];
      if ($vector == 0) $i++;
      elseif ($vector == 1) $j++;
      elseif ($vector == 2) $i--;
      elseif ($vector == 3) $j--; 
    }
  }

  $vector = vector("B", $vector);
  // echo $vector." ".$i." ".$j;
  for ($m=0; $m < strlen($to); $m++) { 
    $lastVector = $vector;
    $vector = vector($to[$m], $vector);
    if ($to[$m] == "R") {
      $arr[$i][$j] .= vector("B", $vector).vector("L",$vector);
    }
    if ($m > 0 && $to[$m-1] == "W" && $to[$m] == "W") {
      $arr[$i][$j] .= vector("L", $lastVector);
    }
    if ($to[$m] == "W" && $m > 0) {
      if ($vector == 0) $i++;
      elseif ($vector == 1) $j++;
      elseif ($vector == 2) $i--;
      elseif ($vector == 3) $j--; 
    }
  }
  return $arr;
}

