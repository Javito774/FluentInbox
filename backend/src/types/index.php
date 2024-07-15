<?php
require_once __DIR__ . '/validators.php';
require_once __DIR__ . '/parsers.php';

function getValidator(string $type)
{
  $VALIDATORS = [
    "string" => "validate_string",
    "text" => "validate_text",
    "email" => "validate_email",
    "password" => "validate_password",
    "date" => "validate_date",
    "time" => "validate_time",
    "datetime" => "validate_datetime",
    "ISOdatetime" => "validate_ISOdatetime",
    "timestamp" => "validate_timestamp",
    "integer" => "validate_integer",
    "biginteger" => "validate_bigInteger",
    "float" => "validate_float",
    "decimal" => "validate_decimal",
    "enumeration" => "validate_enumeration",
    "boolean" => "validate_boolean",
    "json" => "validate_json",
    "relation" => "validate_relation"
  ];
  return $VALIDATORS[$type];
}

function getParser(string $type)
{
  $PARSERS = [
    "string" => "parse_string",
    "text" => "parse_text",
    "email" => "parse_email",
    "password" => "parse_password",
    "date" => "parse_date",
    "time" => "parse_time",
    "datetime" => "parse_datetime",
    "ISOdatetime" => "parse_ISOdatetime",
    "timestamp" => "parse_timestamp",
    "integer" => "parse_integer",
    "biginteger" => "parse_bigInteger",
    "float" => "parse_float",
    "decimal" => "parse_decimal",
    "enumeration" => "parse_enumeration",
    "boolean" => "parse_boolean",
    "json" => "parse_json",
    "relation" => "parse_relation"
  ];
  return $PARSERS[$type];
}
