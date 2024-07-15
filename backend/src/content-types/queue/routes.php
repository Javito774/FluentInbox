<?php

function queue_getRoutes()
{
  $routes = getCommonRoutes('queue');
  $routes["stats"] = [
    "method" => "GET",
    "path" => "/queue/stats",
    "handler" => "queue.stats"
  ];
  return $routes;
}
