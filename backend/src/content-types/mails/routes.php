<?php

function mails_getRoutes()
{
  $routes = getCommonRoutes('mails');
  $routes["send"] = [
    "method" => "POST",
    "path" => "/mails/send/:id",
    "handler" => "mails.send"
  ];
  $routes["sendTest"] = [
    "method" => "POST",
    "path" => "/mails/sendTest/:id",
    "handler" => "mails.sendTest"
  ];
  return $routes;
}
