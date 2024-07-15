<?php

require_once __DIR__ . "/types/index.php";

function sendResponse($data, $status = 200)
{
  http_response_code($status);
  $response = json_encode($data);
  header("Content-Type: application/json");
  echo $response;
  exit;
}
