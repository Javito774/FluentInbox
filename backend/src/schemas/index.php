<?php
function get_schema($uid)
{
  $path = __DIR__ . "/$uid.json";
  try {
    $json = file_get_contents($path);
    $jsonData = json_decode($json, true);
  } catch (Exception $e) {
    http_response_code(500);
    header("Content-Type: application/json");
    echo '{
            "data": null,
            "error": {
                "status": 500,
                "name": "Internal Server Error",
                "message": "Internal Server Error",
                "details": {}
            }
        }';
    exit;
  }
  return $jsonData;
}

