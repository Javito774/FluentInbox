<?php
require_once __DIR__ . '/validators.php';
// Función para parsear una cadena
function parse_string($value)
{
  // Validar la cadena
  if (validate_string($value)) {
    // Devolver la cadena
    return $value;
  } else {
    // Lanzar una excepción si la cadena no es válida
    throw new Exception("Invalid string");
  }
}

// Función para parsear un texto
function parse_text($value)
{
  // Validar el texto
  if (validate_text($value)) {
    // Devolver el texto
    return $value;
  } else {
    // Lanzar una excepción si el texto no es válido
    throw new Exception("Invalid text");
  }
}

// Función para parsear un correo electrónico
function parse_email($value)
{
  // Validar el correo electrónico
  if (validate_email($value)) {
    // Devolver el correo electrónico
    return $value;
  } else {
    // Lanzar una excepción si el correo electrónico no es válido
    throw new Exception("Invalid email");
  }
}

// Función para parsear una contraseña
function parse_password($value)
{
  // Validar la contraseña
  if (validate_password($value)) {
    // Devolver la contraseña
    return $value;
  } else {
    // Lanzar una excepción si la contraseña no es válida
    throw new Exception("Invalid password");
  }
}

// Función para parsear una fecha
function parse_date($value)
{
  // Validar la fecha
  if (validate_date($value)) {
    // Devolver la fecha como un objeto DateTime
    return new DateTime($value);
  } else {
    // Lanzar una excepción si la fecha no es válida
    throw new Exception("Invalid date");
  }
}

// Función para parsear una hora
function parse_time($value)
{
  // Validar la hora
  if (validate_time($value)) {
    // Devolver la hora como un objeto DateTime
    return new DateTime($value);
  } else {
    // Lanzar una excepción si la hora no es válida
    throw new Exception("Invalid time");
  }
}

// Función para parsear una fecha y hora
function parse_datetime($value)
{
  // Validar la fecha y hora
  if (validate_datetime($value)) {
    // Devolver la fecha y hora como un objeto DateTime
    $datetime = new DateTime($value);
    return $datetime;
  } else {
    // Lanzar una excepción si la fecha y hora no son válidas
    throw new Exception("Invalid datetime");
  }
}

function parse_ISOdatetime($value)
{
  // Validar la fecha y hora
  if (validate_ISOdatetime($value)) {
    // Devolver la fecha y hora como un objeto DateTime
    $datetime = new DateTime($value);
    $datetime->setTimezone(new DateTimeZone('Europe/Madrid'));
    return $datetime;
  } else {
    // Lanzar una excepción si la fecha y hora no son válidas
    throw new Exception("Invalid datetime");
  }
}

// Función para parsear un sello de tiempo
function parse_timestamp($value)
{
  // Validar el sello de tiempo
  if (validate_timestamp($value)) {
    // Devolver el sello de tiempo como un número entero
    return intval($value);
  } else {
    // Lanzar una excepción si el sello de tiempo no es válido
    throw new Exception("Invalid timestamp");
  }
}

// Función para parsear un número entero
function parse_integer($value)
{
  // Validar el número entero
  if (validate_integer($value)) {
    // Devolver el número entero como un número entero
    return intval($value);
  } else {
    // Lanzar una excepción si el número entero no es válido
    throw new Exception("Invalid integer");
  }
}

// Función para parsear un número entero grande
function parse_biginteger($value)
{
  // Validar el número entero grande
  if (validate_biginteger($value)) {
    // Devolver el número entero grande como un número de coma flotante
    return floatval($value);
  } else {
    // Lanzar una excepción si el número entero grande no es válido
    throw new Exception("Invalid biginteger");
  }
}

// Función para parsear un número de coma flotante
function parse_float($value)
{
  // Validar el número de coma flotante
  if (validate_float($value)) {
    // Devolver el número de coma flotante como un número de coma flotante
    return floatval($value);
  } else {
    // Lanzar una excepción si el número de coma flotante no es válido
    throw new Exception("Invalid float");
  }
}

// Función para parsear un número decimal
function parse_decimal($value)
{
  // Validar el número decimal
  if (validate_decimal($value)) {
    // Devolver el número decimal como un número de coma flotante
    return floatval($value);
  } else {
    // Lanzar una excepción si el número decimal no es válido
    throw new Exception("Invalid decimal");
  }
}

// Función para parsear una enumeración
function parse_enumeration($value, $enum)
{
  // Validar la enumeración
  if (validate_enumeration($value, $enum)) {
    // Devolver la enumeración como un valor de la enumeración
    return $value;
  } else {
    // Lanzar una excepción si la enumeración no es válida
    throw new Exception("Invalid enumeration");
  }
}

// Función para parsear un valor booleano
function parse_boolean($value)
{

  // Validar el valor booleano
  if (validate_boolean($value)) {
    // Devolver el valor booleano como un valor booleano
    $filteredValue = filter_var($value, FILTER_VALIDATE_BOOLEAN);
    return ($filteredValue === false) ? "0" : "1";
  } else {
    // Lanzar una excepción si el valor booleano no es válido
    throw new Exception("Invalid boolean");
  }
}

// Función para parsear un valor JSON
function parse_json($value)
{
  // Validar el valor JSON
  if (validate_json($value)) {
    // Devolver el valor JSON como un valor PHP
    return json_decode($value, true);
  } else {
    // Lanzar una excepción si el valor JSON no es válido
    throw new Exception("Invalid json");
  }
}

function parse_relation($value)
{
  $result = [];
  if (array_key_exists("connect", $value) && is_array($value["connect"])) {
    foreach ($value["connect"] as $key => $conn) {
      $result["connect"][$key] = parse_integer($conn);
    }
  }
  if (array_key_exists("disconnect", $value) && is_array($value["disconnect"])) {
    foreach ($value["disconnect"] as $key => $conn) {
      $result["disconnect"][$key] = parse_integer($conn);
    }
  }
  return $result;
}
