<?php

require_once __DIR__ . "/utils.php";

function error(int $status, string $name, string $message = null, $details = null): void
{
  if ($message === null) {
    $message = $name;
  }
  if ($details === null)
    $details = "";
  $error = [
    "data" => null,
    "error" => [
      "status" => $status,
      "name" => $name,
      "message" => $message,
      "details" => $details
    ]
  ];
  sendResponse($error, $status);
}
