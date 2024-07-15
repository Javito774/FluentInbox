<?php

function validate_input($data, $schema, $auth = [])
{
  foreach ($data as $key => $value) {
    if (array_key_exists($key, $schema["attributes"]) && !is_null($value)) {
      $parser = getParser($schema["attributes"][$key]["type"]);
      if ($schema["attributes"][$key]["type"] === 'enumeration') {
        $enum = $schema["attributes"][$key]["enum"];
        $data[$key] = call_user_func($parser, $value, $enum);
      } else {
        $data[$key] = call_user_func($parser, $value);
      }
    } else {
      unset($data[$key]);
    }
  }
  return $data;
}

function validate_query($data, $schema, $auth = [])
{
  foreach ($data as $key => $value) {
    if (array_key_exists($key, $schema["attributes"])) {
      $parser = getParser($schema["attributes"][$key]["type"]);
      if ($schema["attributes"][$key]["type"] === 'enumeration') {
        $enum = $schema["attributes"][$key]["enum"];
        $data[$key] = call_user_func($parser, $value, $enum);
      } else {
        $data[$key] = call_user_func($parser, $value);
      }
    }
  }
  return $data;
}


function validate_filters($data, $schema, $auth = [])
{
  foreach ($data as $key => $operator) {
    if ($key === '$and' || $key === '$or') {
      // Es un operador lógico, llama a la función de manera recursiva para cada elemento
      foreach ($operator as $subfilter) {
        validate_filters($subfilter, $schema, $auth);
      }
    } else {
      foreach ($operator as $field => $value) {
        if (array_key_exists($field, $schema["attributes"])) {
          $fieldSchema = $schema["attributes"][$field];
          $parser = getParser($fieldSchema["type"]);

          if ($fieldSchema["type"] === 'enumeration') {
            $enum = $fieldSchema["enum"];
            call_user_func($parser, $value, $enum);
          } else {
            call_user_func($parser, $value);
          }
        } else {
          unset($data[$key][$field]);
        }
      }
    }
  }
  return $data;
}


function validate_sort($sort, $schema, $auth = [])
{
  $validSort = [];

  if (array_key_exists("field", $sort) && array_key_exists($sort["field"], $schema["attributes"])) {
    $validSort["field"] = $sort["field"];
    $validSort["order"] = strtoupper($sort["order"]) === "DESC" ? "DESC" : "ASC";
  }

  return $validSort;
}

function validate_fields($fields, $schema, $auth = [])
{
  $validFields = [];

  foreach ($fields as $field) {
    if (array_key_exists($field, $schema["attributes"]) || $field == "id") {
      $validFields[] = $field;
    }
  }

  return $validFields;
}

function validate_populate($data, $schema, $auth)
{
  // To use in the future
}
