<?php
// Función para añadir el validador de longitud mínima
function addMinLengthValidator($validator, $attr, $isDraft) {
  return $attr["minLength"] && is_int($attr["minLength"]) && !$isDraft
    ? $validator->min($attr["minLength"])
    : $validator;
}

// Función para añadir el validador de longitud máxima
function addMaxLengthValidator($validator, $attr) {
  return $attr["maxLength"] && is_int($attr["maxLength"]) ? $validator->max($attr["maxLength"]) : $validator;
}

// Función para añadir el validador de entero mínimo
function addMinIntegerValidator($validator, $attr) {
  return is_numeric($attr["min"]) ? $validator->min(intval($attr["min"])) : $validator;
}

// Función para añadir el validador de entero máximo
function addMaxIntegerValidator($validator, $attr) {
  return is_numeric($attr["max"]) ? $validator->max(intval($attr["max"])) : $validator;
}

// Función para añadir el validador de flotante/decimal mínimo
function addMinFloatValidator($validator, $attr) {
  return is_numeric($attr["min"]) ? $validator->min($attr["min"]) : $validator;
}

// Función para añadir el validador de flotante/decimal máximo
function addMaxFloatValidator($validator, $attr) {
  return is_numeric($attr["max"]) ? $validator->max($attr["max"]) : $validator;
}

// Función para añadir el validador de expresión regular
function addStringRegexValidator($validator, $attr) {
  return array_key_exists("regex", $attr) && !is_null($attr["regex"])
    ? $validator->matches(new RegExp($attr["regex"]), array("excludeEmptyString" => !$attr["required"]))
    : $validator;
}

// Función para añadir el validador de unicidad
function addUniqueValidator($validator, $attr, $model, $updatedAttribute, $entity) {
  if ($attr["type"] !== "uid" && !$attr["unique"]) {
    return $validator;
  }
  return $validator->test("unique", "Este atributo debe ser único", async function ($value) {
    // Si el valor del atributo es nulo, queremos saltarnos la validación de unicidad.
    // De lo contrario, solo aceptará una entrada nula en la base de datos.
    if (is_null($value)) {
      return true;
    }
    // Aquí va la lógica para comprobar la unicidad del valor en la base de datos
  });
}
// Función para validar una cadena de texto
function stringValidator($metas, $options) {
    $schema = yup_string()->transform(function ($val, $originalVal) {
      return $originalVal;
    });
    $schema = addMinLengthValidator($schema, $metas, $options);
    $schema = addMaxLengthValidator($schema, $metas);
    $schema = addStringRegexValidator($schema, $metas);
    $schema = addUniqueValidator($schema, $metas);
    return $schema;
  }
  
  // Función para validar un correo electrónico
  function emailValidator($metas, $options) {
    $schema = stringValidator($metas, $options);
    return $schema->email()->min(1, '${path} no puede estar vacío');
  }
  
  // Función para validar un identificador único
  function uidValidator($metas, $options) {
    $schema = stringValidator($metas, $options);
    return $schema->matches(new RegExp('/^[A-Za-z0-9-_.~]*$/'));
  }
  
  // Función para validar una enumeración
  function enumerationValidator($attr) {
    return yup_string()
      ->oneOf((is_array($attr["enum"]) ? $attr["enum"] : [$attr["enum"]])->concat(null));
  }
  
  // Función para validar un entero
  function integerValidator($metas) {
    $schema = yup_number()->integer();
    $schema = addMinIntegerValidator($schema, $metas);
    $schema = addMaxIntegerValidator($schema, $metas);
    $schema = addUniqueValidator($schema, $metas);
    return $schema;
  }
  
  // Función para validar un flotante o decimal
  function floatValidator($metas) {
    $schema = yup_number();
    $schema = addMinFloatValidator($schema, $metas);
    $schema = addMaxFloatValidator($schema, $metas);
    $schema = addUniqueValidator($schema, $metas);
    return $schema;
  }
  
  // Función para validar un entero grande
  function bigintegerValidator($metas) {
    $schema = yup_mixed();
    return addUniqueValidator($schema, $metas);
  }
  
  // Función para validar una fecha
  function datesValidator($metas) {
    $schema = yup_mixed();
    return addUniqueValidator($schema, $metas);
  }
  
?>
