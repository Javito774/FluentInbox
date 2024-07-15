<?php
function eliminar_inexistencias($data, $schema)
{
  foreach ($data as $key => $value) {
    if ($key === "id")
      continue;
    if (!array_key_exists($key, $schema["attributes"]))
      unset($data[$key]);
  }
  return $data;
}

function eliminar_passwords($data, $schema)
{
  foreach ($data as $key => $value) {
    if ($schema["attributes"][$key]["type"] == "password")
      unset($data[$key]);
  }
  return $data;
}

function eliminar_privados($data, $schema)
{
  foreach ($data as $key => $value) {
    if ($schema["attributes"][$key]["private"])
      unset($data[$key]);
  }
  return $data;
}

function eliminar_relaciones($data, $schema)
{
  foreach ($data as $key => $value) {
    if ($schema["attributes"][$key]["type"] == "relation")
      unset($data[$key]);
  }
  return $data;
}

function checkRequiredFields($data, $schema)
{
  $errors = [];
  foreach ($schema["attributes"] as $key => $value) {
    if ($schema["attributes"][$key]["required"] && !isset($data[$key]))
      $errors[] = [
        "path" => [
          "$key"
        ],
        "message" => "$key is a required field",
        "name" => "ValidationError"
      ];
  }
  if (count($errors) > 0) {
    $message = count($errors) . " errors occurred";
    error(400, "ValidationError", $message, ["errors" => $errors]);
  }
}

function sanitize_input($data, $schema)
{
  $data = eliminar_inexistencias($data, $schema);
  $data = eliminar_passwords($data, $schema);
  $data = eliminar_privados($data, $schema);
  return $data;
}

function sanitize_output($data, $schema)
{
  $data = eliminar_inexistencias($data, $schema);
  $data = eliminar_passwords($data, $schema);
  $data = eliminar_privados($data, $schema);
  return $data;
}

function sanitize_query($query, $schema)
{
}

function sanitize_filters($filters, $schema)
{
}

function satinitize_sort($sort, $schema)
{
}

function sanitize_fields($fields, $schema)
{
}

function sanitize_populate($fields, $schema)
{
}
