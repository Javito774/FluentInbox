<?php
// Función para validar una cadena
function validate_string($value)
{
  // Comprobar si el valor es una cadena
  if (is_string($value)) {
    // Devolver verdadero si la cadena no está vacía
    return true;
  } else {
    // Devolver falso si el valor no es una cadena
    return false;
  }
}

// Función para validar un texto
function validate_text($value)
{
  // Comprobar si el valor es una cadena
  if (is_string($value)) {
    // Devolver verdadero si la cadena tiene al menos 10 caracteres
    return true;
  } else {
    // Devolver falso si el valor no eWs una cadena
    return false;
  }
}

// Función para validar un correo electrónico
function validate_email($value)
{
  // Usar la función filter_var con el filtro FILTER_VALIDATE_EMAIL
  return filter_var($value, FILTER_VALIDATE_EMAIL);
}

// Función para validar una contraseña
function validate_password($value)
{
  // Comprobar si el valor es una cadena
  if (is_string($value)) {
    // Devolver verdadero si la cadena tiene al menos 8 caracteres y contiene al menos una letra mayúscula, una letra minúscula, un número y un símbolo especial
    return preg_match("/^(?=.*[A-Z])(?=.*[a-z])(?=.*[0-9])(?=.*[!@#$%^&*]).{8,}$/", $value);
  } else {
    // Devolver falso si el valor no es una cadena
    return false;
  }
}

// Función para validar una fecha
function validate_date($value)
{
  // Usar la función filter_var con el filtro FILTER_VALIDATE_REGEXP y una expresión regular para el formato YYYY-MM-DD
  return filter_var($value, FILTER_VALIDATE_REGEXP, array("options" => array("regexp" => "/^\d{4}-\d{2}-\d{2}$/")));
}

// Función para validar una hora
function validate_time($value)
{
  // Usar la función filter_var con el filtro FILTER_VALIDATE_REGEXP y una expresión regular para el formato HH:MM:SS
  return filter_var($value, FILTER_VALIDATE_REGEXP, array("options" => array("regexp" => "/^\d{2}:\d{2}:\d{2}$/")));
}

// Función para validar una fecha y hora
function validate_datetime($value)
{
  // Usar la función filter_var con el filtro FILTER_VALIDATE_REGEXP y una expresión regular para el formato YYYY-MM-DD HH:MM:SS
  return filter_var($value, FILTER_VALIDATE_REGEXP, array("options" => array("regexp" => "/^\d{4}-\d{2}-\d{2} \d{2}:\d{2}:\d{2}$/")));
}

function validate_ISOdatetime($value)
{
  // Usar la función filter_var con el filtro FILTER_VALIDATE_REGEXP y una expresión regular para el formato YYYY-MM-DD HH:MM:SS
  return filter_var($value, FILTER_VALIDATE_REGEXP, array("options" => array("regexp" => "/^(\d{4}-\d{2}-\d{2}T\d{2}:\d{2}:\d{2}\.\d{3}Z)$/")));
}


// Función para validar un sello de tiempo
function validate_timestamp($value)
{
  // Comprobar si el valor es un número entero
  if (is_int($value)) {
    // Devolver verdadero si el número entero es positivo y menor que el máximo valor posible para un sello de tiempo (2147483647)
    return $value > 0 && $value < 2147483647;
  } else {
    // Devolver falso si el valor no es un número entero
    return false;
  }
}

// Función para validar un número entero
function validate_integer($value)
{
  // Usar la función filter_var con el filtro FILTER_VALIDATE_INT
  return filter_var($value, FILTER_VALIDATE_INT);
}

// Función para validar un número entero grande
function validate_biginteger($value)
{
  // Comprobar si el valor es una cadena numérica
  if (is_numeric($value)) {
    // Convertir la cadena numérica a un número de coma flotante
    $float_value = floatval($value);
    // Devolver verdadero si el número de coma flotante es igual al número entero redondeado y es mayor que el máximo valor posible para un número entero (2147483647)
    return $float_value == round($float_value);
  } else {
    // Devolver falso si el valor no es una cadena numérica
    return false;
  }
}

// Función para validar un número de coma flotante
function validate_float($value)
{
  // Usar la función filter_var con el filtro FILTER_VALIDATE_FLOAT
  return filter_var($value, FILTER_VALIDATE_FLOAT);
}

// Función para validar un número decimal
function validate_decimal($value)
{
  // Comprobar si el valor es una cadena numérica
  if (is_numeric($value)) {
    // Convertir la cadena numérica a un número de coma flotante
    $float_value = floatval($value);
    // Devolver verdadero si el número de coma flotante tiene al menos un dígito después del punto decimal
    return $float_value == round($float_value, 1);
  } else {
    // Devolver falso si el valor no es una cadena numérica
    return false;
  }
}

// Función para validar una enumeración
function validate_enumeration($value, $enum)
{
  // Comprobar si el valor es una cadena
  if (is_string($value)) {
    // Devolver verdadero si la cadena está entre los valores posibles de la enumeración
    return in_array($value, $enum);
  } else {
    // Devolver falso si el valor no es una cadena
    return false;
  }
}

// Función para validar un valor booleano
function validate_boolean($value)
{
  // Usar la función filter_var con el filtro FILTER_VALIDATE_BOOLEAN
  return filter_var($value, FILTER_VALIDATE_BOOLEAN, FILTER_NULL_ON_FAILURE) !== null;
}

// Función para validar un valor JSON
function validate_json($value)
{
  // Comprobar si el valor es una cadena
  if (is_string($value)) {
    // Intentar decodificar la cadena como un valor JSON
    $json_value = json_decode($value);
    // Devolver verdadero si la decodificación fue exitosa y no hubo errores
    return $json_value !== null && json_last_error() == JSON_ERROR_NONE;
  } else {
    // Devolver falso si el valor no es una cadena
    return false;
  }
}
