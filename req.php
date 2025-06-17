<?php

include ("list.php");

$url = "https://rmsprodapi.nyrr.org/api/v2/runners/finishers-filter";

foreach ($races as $event)
{
  if (file_exists($event .".json")) continue;
  echo "fetching event $event\n";
  $output = [];

  for ($place = 1; ; )
  {
    $nplace = $place + 400 - 1;

    $sz=0;
    for ($page = 1; ; $page ++)
    {
      $data = '{"eventCode":"'.$event.'","searchString":null,"handicap":"","sortColumn":"overallTime","sortDescending":false,"pageIndex":'.$page.',"pageSize":100,"overallPlaceFrom":'.$place.',"overallPlaceTo":'.$nplace.'}';

      $options = [
        "http" => [
            "method"  => "POST",
            "header"  => "Content-Type: application/json;charset=utf-8\r\n" .
                         "Content-Length: " . strlen($data) . "\r\n",
            "content" => $data,
        ]
      ];

      $context = stream_context_create($options);
      $response = file_get_contents($url, false, $context);

      if ($response === false) {
        echo "failed on page $page\n";
        break;
      }

      $res = json_decode($response,true);
      if (!isset($res["totalItems"]) || $res["totalItems"] == 0)
      {
        echo "$place - $nplace totalItems = 0\n";
        break;
      }
      if (!isset($res["items"]) || count($res["items"])==0)
      {
        echo "$place - $nplace got empty items!\n";
        break;
      }
      $thissz = count($res["items"]);
      $totalsz = $res["totalItems"];
      $sz += $thissz;

      for ($x=0;$x<count($res["items"]); $x++)
        $output[] = $res["items"][$x];
      echo "got range $place - $nplace page $page (sz=$thissz -> $sz - " . count($output) . "\n";
      sleep(2);
      if ($sz>=$totalsz) break;
    }

    $place = $nplace + 1;
    if (!$sz) break;
  }
  file_put_contents($event . ".json", json_encode($output));
}

?>
