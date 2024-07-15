<?php

// Clase para representar una relación entre dos tipos de datos
class Relation
{
  // Atributos de la clase
  private $source; // Tipo de dato origen
  private $target; // Tipo de dato destino
  private $type; // Tipo de relación (opcional)

  // Constructor de la clase
  public function __construct($source, $target, $type = null)
  {
    // Asignar los valores a los atributos
    $this->source = $source;
    $this->target = $target;
    $this->type = $type;
  }

  // Métodos para obtener los valores de los atributos
  public function getSource()
  {
    return $this->source;
  }

  public function getTarget()
  {
    return $this->target;
  }

  public function getType()
  {
    return $this->type;
  }
}

// Función para validar una relación
function validate_relation($value)
{
  // Comprobar si el valor es un objeto de la clase Relación
  if ($value instanceof Relation) {
    // Obtener los valores de los atributos del objeto
    $source = $value->getSource();
    $target = $value->getTarget();
    $type = $value->getType();

    // Definir un array asociativo con los tipos de relación permitidos
    $valid_types = array(
      "has_many" => true,                     // 1:N, una entidad tiene muchas entidades relacionadas
      "belongs_to" => true,                   // N:1, una entidad pertenece a una entidad relacionada
      "has_one" => true,                      // 1:1, una entidad tiene una entidad relacionada
      "has_and_belongs_to_many" => true,      // N:M, una entidad tiene y pertenece a muchas entidades relacionadas
      "polymorphic" => true,                  // N:1, una entidad pertenece a una entidad relacionada de diferentes tipos
      "through" => true,                      // N:M, una entidad tiene y pertenece a muchas entidades relacionadas a través de una entidad intermedia
      "dependent" => true,                    // 1:N, una entidad tiene muchas entidades relacionadas que dependen de ella para su existencia
      "optional" => true                      // 0:1 o 1:0, una entidad puede o no tener una entidad relacionada
    );

    // Validar el tipo de dato origen y el tipo de dato destino usando las funciones correspondientes
    if (validate_kind($source) && validate_kind($target)) {
      // Validar el tipo de relación si existe usando el array asociativo
      if ($type == null || isset($valid_types[$type])) {
        // Devolver verdadero si todo es válido
        return true;
      } else {
        // Devolver falso si el tipo de relación no es válido
        return false;
      }
    } else {
      // Devolver falso si el tipo de dato origen o el tipo de dato destino no son válidos
      return false;
    }
  } else {
    // Devolver falso si el valor no es un objeto de la clase Relación
    return false;
  }
}

// Función para parsear una relación
function parse_relation($value)
{
  // Comprobar si el valor es una cadena
  if (is_string($value)) {
    // Usar la función explode para separar la cadena por una coma
    $parts = explode(",", $value);

    // Comprobar si la cadena tiene dos o tres partes
    if (count($parts) == 2 || count($parts) == 3) {
      // Obtener el tipo de dato origen y el tipo de dato destino de las partes
      $source = $parts[0];
      $target = $parts[1];

      // Obtener el tipo de relación de la tercera parte si existe
      $type = count($parts) == 3 ? $parts[2] : null;

      // Parsear el tipo de dato origen y el tipo de dato destino usando las funciones correspondientes
      $source = parse_kind($source);
      $target = parse_kind($target);

      // Crear el objeto de la clase Relación con los valores obtenidos
      $relation = new Relation($source, $target, $type);

      // Devolver el objeto de la clase Relación
      return $relation;
    } else {
      // Lanzar una excepción si la cadena no tiene dos o tres partes
      throw new Exception("Invalid relation format");
    }
  } else {
    // Lanzar una excepción si el valor no es una cadena
    throw new Exception("Invalid relation type");
  }
}
