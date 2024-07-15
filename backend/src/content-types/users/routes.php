<?php

function users_getRoutes()
{
  $routes = getCommonRoutes('users');
  $routes["me"] = [
    "method" => "GET",
    "path" => "/users/me",
    "handler" => "users.me"
  ];
  return $routes;
}
