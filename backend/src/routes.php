<?php

function findRoutes($uid)
{
  if (file_exists(__DIR__ . "/content-types/{$uid}/routes.php")) {
    require_once __DIR__ . "/content-types/{$uid}/routes.php";
    $routeFunction = "{$uid}_getRoutes";
    $routes = call_user_func($routeFunction);
  } else {
    $routes = getCommonRoutes($uid);
  }
  return $routes;
}

function getCommonRoutes($uid)
{
  $routes = [
    "find" => [
      "method" => "GET",
      "path" => "/{$uid}",
      "handler" => "{$uid}.find"
    ],
    "findOne" => [
      "method" => "GET",
      "path" => "/{$uid}/:id",
      "handler" => "{$uid}.findOne"
    ],
    "create" => [
      "method" => "POST",
      "path" => "/{$uid}",
      "handler" => "{$uid}.create"
    ],
    "update" => [
      "method" => "PUT",
      "path" => "/{$uid}/:id",
      "handler" => "{$uid}.update"
    ],
    "delete" => [
      "method" => "DELETE",
      "path" => "/{$uid}/:id",
      "handler" => "{$uid}.delete"
    ],
  ];
  return $routes;
}

function routeHasMethod($route, $method)
{
  return $route["method"] == $method;
}

function routeMatchPath($route, $path)
{
  $regex = "#:[A-Za-z0-9]+#";
  $replace = "[0-9]+";
  $regexPath = preg_replace($regex, $replace, $path);
  $regexPath = "#^" . $regexPath . "(?:/|\\?(?:.+=.+)(?:&.+=.+)*)?$#";
  return preg_match($regexPath, $route);
}

function extractParams($route, $path)
{
  $paramValues = [];

  $regex = "#:([A-Za-z0-9]+)#";
  preg_match_all($regex, $path, $paramNames);

  $paramNames = $paramNames[1];
  $regexPath = $path;

  foreach ($paramNames as $name) {
    $regexPath = str_replace(":{$name}", "([^/]+)", $regexPath);
  }

  // Remove any query parameters from the route
  $route = strtok($route, '?');

  if (preg_match("#^$regexPath$#", $route, $matches)) {
    array_shift($matches); // Remove the first element, which is the full match
    $paramValues = array_slice($matches, 0, count($paramNames));
    $paramValues = array_combine($paramNames, $paramValues);
  }

  return $paramValues;
}
