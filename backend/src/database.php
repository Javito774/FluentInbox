<?php
function get_mysql_connection()
{
  try {
    $mysqli = new mysqli($_SERVER["DB_HOST"], $_SERVER["DB_USER"], $_SERVER["DB_PASSWORD"], $_SERVER["DB_NAME"]);
  } catch (Exception $e) {
    http_response_code(500);
    header("Content-Type: application/json");
    echo '{
            "data": "No se ha podido crear mysql",
            "error": {
            "status": 500,
            "name": "Internal Server Error",
            "message": "Internal Server Error",
            "details": {}
            }
        }';
    exit;
  }

  // check connection
  if ($mysqli->connect_error) {
    http_response_code(500);
    header("Content-Type: application/json");
    echo '{
            "data": "No se ha podido establecer conexion con la base de datos",
            "error": {
            "status": 500,
            "name": "Internal Server Error",
            "message": "Internal Server Error",
            "details": {}
            }
        }';
    exit;
  }
  return $mysqli;
}
