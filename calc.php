<?php

include("list.php");

function t($h,$m) { return $h * 3600 + $m * 60; }
function qualtime($gender, $age)
{
  if ($age < 18) return 0;
  if ($gender == "M")
  {
    if ($age <= 34) return t(1,21);
    if ($age <= 39) return t(1,23);
    if ($age <= 44) return t(1,25);
    if ($age <= 49) return t(1,28);
    if ($age <= 54) return t(1,32);
    if ($age <= 59) return t(1,36);
    if ($age <= 64) return t(1,41);
    if ($age <= 69) return t(1,46);
    if ($age <= 74) return t(1,57);
    if ($age <= 79) return t(2,7);
    return t(2,15);
  }
  if ($gender == "W" || $gender == "X")
  {
    if ($age <= 34) return t(1,32);
    if ($age <= 39) return t(1,34);
    if ($age <= 44) return t(1,37);
    if ($age <= 49) return t(1,42);
    if ($age <= 54) return t(1,49);
    if ($age <= 59) return t(1,54);
    if ($age <= 64) return t(2,2);
    if ($age <= 69) return t(2,12);
    if ($age <= 74) return t(2,27);
    if ($age <= 79) return t(2,40);
    return t(2,50);
  }
  die("bad gender $gender\n"); 
}

foreach ($races as $event)
{
  if (!file_exists($event . ".json")) continue;
  $r = json_decode(file_get_contents($event . ".json"),true);
  $cnt = 0;
  for ($x = 0; $x < count($r); $x ++)
  {
    $u = $r[$x];
    // "age", "gender", "overallTime"
    [$h,$m,$s]=sscanf($u["overallTime"],"%d:%d:%d");
    if ($h === null || $m === null || $s === null) die("bad qualtime0 " . $u["overallTime"] . "\n");
    $s += $m*60 + $h*3600;
    if ($s<600) die("bad qualtime " . $u["overallTime"] . "\n");
    if ($s <= qualtime($u["gender"],$u["age"]))
      $cnt++;
  }
  echo "$event: $cnt qualified of " . count($r). "\n";
}
?>
