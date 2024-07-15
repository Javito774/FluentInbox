<?php
error_reporting(E_ERROR | E_PARSE);
// Cargar las dependencias y configuraciones necesarias
require_once __DIR__ . '/vendor/autoload.php';
require_once __DIR__ . "/erros.php";

// Allow from any origin
header('Access-Control-Allow-Origin: *');
header("Access-Control-Allow-Headers: Content-Type, Authorization, X-API-KEY, Origin, X-Requested-With, Accept, Access-Control-Request-Method");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE");
header("Allow: GET, POST, OPTIONS, PUT, DELETE");
header('Content-Type: application/json; charset=utf-8');
header('Access-Control-Max-Age: 86400');

if($_SERVER['REQUEST_METHOD'] === "OPTIONS") {
    http_response_code(200);
    exit;
}
/*
$_SERVER['JWT_KEY'] = '37e70e62552050344572965eb683b06a244f29569b79729b8634f19710438ea3';
$_SERVER['DB_HOST'] = 'db5014948307.hosting-data.io';
$_SERVER['DB_USER'] = 'dbu1432289';
$_SERVER['DB_PASSWORD'] = 'h!C9fFiKJ8&Sq9_';
$_SERVER['DB_NAME'] = 'dbs12424815';
*/

$_SERVER['JWT_KEY'] = '37e70e62552050344572965eb683b06a244f29569b79729b8634f19710438ea3';
$_SERVER['DB_HOST'] = 'db';
$_SERVER['DB_USER'] = 'root';
$_SERVER['DB_PASSWORD'] = 'root';
$_SERVER['DB_NAME'] = 'fluentinbox';


// Obtener la solicitud actual
$REALrequestUri = $_SERVER['REQUEST_URI'];
$requestMethod = $_SERVER['REQUEST_METHOD'];
$preRoute = $_SERVER["PRE_ROUTE"] ?? "";
$_SERVER["requestUri"] = str_replace($preRoute, "", $REALrequestUri);
list($requestUriwoQuery, $QUERY) = explode('?', $_SERVER["requestUri"]);
$requestUriArray = explode('/', $requestUriwoQuery);
$uid = $requestUriArray[1];
$uidPath = __DIR__ . "/content-types/{$uid}";

$allowedMethods = [
  'POST',
  'GET',
  'PUT',
  'DELETE'
];


if (!in_array($requestMethod, $allowedMethods))
  error(405, "Method Not Allowed");


if (!file_exists($uidPath))
  error(404, "Not Found");

require_once __DIR__ . '/routes.php';

$routes = findRoutes($uid);

$routesMatchedMethod = array_filter($routes, function ($route) {
  return routeHasMethod($route, $_SERVER['REQUEST_METHOD']);
});

if (empty($routesMatchedMethod))
  error(404, "Not Found");

$routesMatchedPath = array_filter($routesMatchedMethod, function ($route) {
  return routeMatchPath($_SERVER["requestUri"], $route["path"]);
});

if (empty($routesMatchedPath))
  error(404, "Not Found");

$route = $routesMatchedPath[array_key_first($routesMatchedPath)];

$params = extractParams($_SERVER["requestUri"], $route["path"]);

$uid_action = $route["handler"];

$token = null;
$headers = getallheaders(); // get all the HTTP headers

if(isset($_SERVER['REDIRECT_HTTP_AUTHORIZATION'])) {
    $headers['Authorization'] = $_SERVER['REDIRECT_HTTP_AUTHORIZATION'];
}

if (isset($headers['Authorization'])) {
  $auth = $headers['Authorization'];
} elseif (isset($headers['authorization'])) {
  $auth = $headers['authorization'];
} elseif (isset($headers['AUTHORIZATION'])) {
  $auth = $headers['AUTHORIZATION'];
}else {
  $auth = null;
}

if ($auth) { // check if the Authorization header exists
  if (substr($auth, 0, 7) == 'Bearer ') { // check if the value starts with 'Bearer '
    $token = substr($auth, 7); // get the token part after 'Bearer '
  } else {
    error(400, "UnauthorizedError", "Missing or invalid credentials");
  }
}
require_once __DIR__ . '/php-jwt/index.php';
if ($token) {
  try {
    $decodedToken = decode_jwt($token);
  } catch (Exception $e) {
    error(400, "UnauthorizedError", "Missing or invalid credentials");
  }
}

if (in_array($requestMethod, ["POST", "PUT"]))
  $_POST = @json_decode(file_get_contents('php://input'), true);

if ($requestMethod === "GET") {
  require_once __DIR__ . "/query-parser.php";
  $QUERY = $_GET;
  unset($QUERY["url"]);
  $QUERY = parse_query($QUERY);
}

require_once __DIR__ . '/database.php';

$userRoleType = "public";
$mysqli = get_mysql_connection();
if ($decodedToken) {
  $userRoleType = "authenticated";
  // TODO extract all the data for the user.
  $sql = "SELECT u.id as id, u.username as username, u.email as email, u.blocked as blocked, u.confirmed as confirmed, r.type as role_type 
    FROM (SELECT * FROM users WHERE id = ?) u 
    INNER JOIN users_roles_links ur on u.id = ur.users_id 
    INNER JOIN roles r ON ur.roles_id = r.id";
  $stmt = $mysqli->prepare($sql);
  if ($stmt) {
    $stmt->bind_param("i", $decodedToken["id"]);
    $stmt->execute();
    $stmt->bind_result($authUserId, $authUserName, $authUserEmail, $authUserBlocked, $authUserConfirmed, $roleType);
    if ($stmt->fetch()) {
      $userRoleType = $roleType;
    }
    $stmt->close();
  } else {
    error(500, "Internal Server Error", "Error getting permissions data.");
  }
}
$auth = [];
$sql = "SELECT action 
FROM permissions p
inner join permissions_roles_links pr on p.id = pr.permissions_id 
inner join (
    SELECT id 
    FROM roles 
    where type=?
) r2 on pr.roles_id = r2.id";

$stmt = $mysqli->prepare($sql);
if ($stmt) {
  $stmt->bind_param("s", $userRoleType);
  $stmt->execute();
  $stmt->bind_result($permission);
  while ($stmt->fetch()) {
    $auth[] = $permission;
  }
  $stmt->close();
} else {
  error(500, "Internal Server Error");
}

if (!in_array($uid_action, $auth)) {
  error(403, "Forbidden");
}
require_once __DIR__ . '/sanitize/index.php';
require_once __DIR__ . '/validate/index.php';
require_once __DIR__ . '/schemas/index.php';
require_once __DIR__ . '/types/index.php';
require_once __DIR__ . '/traverse/index.php';

$schema = get_schema($uid);
list($uid, $action) = explode('.', $uid_action);

$body = [];

$body["auth"]["permissions"] = $auth;
$body["auth"]["user"] = [
  "id" => $authUserId,
  "username" => $authUserName,
  "email" => $authUserEmail,
  "confirmed" => $authUserConfirmed,
  "blocked" => $authUserBlocked
];

if ($requestMethod === "GET") {

  $filters = $QUERY["filters"];
  $fields = $QUERY["fields"];
  $sort = $QUERY["sort"];
  $populate = $QUERY["populate"];


  //$filters = validate_filters($QUERY["filters"], $schema);
  if ($filters)
    $body["filters"] = $filters;

  if ($fields) {
    $body["fields"] = $QUERY["fields"];
  }

  if ($sort)
    $body["sort"] = $QUERY["sort"];

  if ($populate)
    $body["populate"] = $QUERY["populate"];
}

$body["params"] = $params;

if ($requestMethod === 'POST' || $requestMethod === 'PUT') {
  $body["data"] = $_POST;
}

$path = __DIR__ . '/content-types/' . $uid . '/controller.php';
require_once($path);
require_once(__DIR__ . '/controllers.php');

$controlleName = $uid . "Controller";
$controller_instance = new $controlleName();
$data = call_user_func([$controller_instance, $action], $body);

sendResponse($data);
