<?php

function auth_getRoutes()
{
  $routes = [
    "login" => [
      "method" => "POST",
      "path" => "/auth/login",
      "handler" => "auth.login"
    ],
    "register" => [
      "method" => "POST",
      "path" => "/auth/register",
      "handler" => "auth.register"
     ]
  ];
  return $routes;
}
