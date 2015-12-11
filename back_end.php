
<?php
  header("Access-Control-Allow-Origin: *");
  $googleApiKey = "AIzaSyBWCtABzNgXaPblRlhGdLxmjc3eT_xXbMU";
  $gMapStr = urlencode("https://maps.googleapis.com/maps/api/geocode/xml?address=" . $_GET["sa"] . "," . $_GET["city"] . "," . $_GET["states"] . "&key=" . $googleApiKey);
  //echo $gMapStr;
  $xml=simplexml_load_file("$gMapStr") or die("Error: Cannot create object");
  //echo $xml->status;
  if ($xml->status === "ZERO_RESULTS") {
    exit("There is no result returned by Google Map");
  }
  //var_dump(xml);
  $lat = $xml->result->geometry->location->lat;
  //echo $lat;
  $lng = $xml->result->geometry->location->lng;
  //echo $lng;

  $apiKey = "17890a9eefa0dd3ece1f29b9e4524036";
  $fcStr = "https://api.forecast.io/forecast/" . $apiKey . "/" . $lat. "," . "$lng" . "?units=" . $_GET['unit'] . "&exclude=flags";
  //echo $fcStr;
  $json = file_get_contents($fcStr);
  $jsonDoc = json_decode($json, true);
  $l_timezone = 'America/Los_Angeles';
  //echo $l_timezone;
  date_default_timezone_set($jsonDoc["timezone"]);
  for ($i = 0; $i <= 7; $i++) {
    $jsonDoc["daily"]["data"][$i]["sunriseTime"] = strftime("%I:%M %p", $jsonDoc["daily"]["data"][$i]["sunriseTime"]);
    $jsonDoc["daily"]["data"][$i]["sunsetTime"] = strftime("%I:%M %p", $jsonDoc["daily"]["data"][$i]["sunsetTime"]);
    //echo $jsonDoc["daily"]["data"][$i]["sunriseTime"];
    //echo $jsonDoc["daily"]["data"][$i]["sunsetTime"];
  }
  for ($i = 0; $i < 24; $i++) {
    $jsonDoc["hourly"]["data"][$i]["time"] = strftime("%I:%M %p", $jsonDoc["hourly"]["data"][$i]["time"]);
  }

  for ($i = 1; $i <= 7; $i++) {
    date_default_timezone_set($jsonDoc["timezone"]);
    $time = $jsonDoc["daily"]["data"][$i]["time"];
    //echo $time . '\n';
    $timeStr = strftime("%Y-%m-%d", $time);
    //echo $timeStr;
    date_default_timezone_set($l_timezone);
    $date = new DateTime($timeStr);
    $jsonDoc["daily"]["data"][$i]["time"] = $date->getTimestamp();
    //echo $jsonDoc["daily"]["data"][$i]["time"] . '\n';
  }


  echo json_encode($jsonDoc);
?>




















