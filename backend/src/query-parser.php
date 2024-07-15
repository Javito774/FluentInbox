<?php

function parse_query($query)
{
  $filters = [];
  if (array_key_exists("filters", $query)) {
    $filters = parse_filters($query["filters"]);
  }
  $sort = [];
  if (array_key_exists("sort", $query)) {
    $sort = $query["sort"];
  }

  $fields = [];
  if (array_key_exists("fields", $query)) {
    $fields = $query["fields"];
  }

  $populate = [];
  if (array_key_exists("populate", $query)) {
    $populate = $query["populate"];
  }
  $result = [];
  if (count($filters) > 0) {
    $result["filters"] = $filters;
  }
  if (count($sort) > 0) {
    $result["sort"] = $sort;
  }
  if (count($fields) > 0) {
    $result["fields"] = $fields;
  }
  if (count($populate) > 0) {
    $result["populate"] = $populate;
  }

  return $result;
}

function parse_filters($filters)
{
  $logicalOperators = [
    '$and' => 'AND',
    '$or' => 'OR'
  ];

  $comparisonOperators = [
    '$eq' => '=',
    '$ne' => '!='
  ];

  foreach ($filters as $key => $value) {
    $operator = key($value);
    if (isset($logicalOperators[$key])) {
      // Es un operador lógico, llama a la función de manera recursiva para cada elemento
      foreach ($value as $index => $subFilter) {
        $parsedSubFilter = parse_filters($subFilter);
        if (!empty($parsedSubFilter)) {
          $parsedFilter[$key][$index] = $parsedSubFilter;
        }
      }
    } elseif (isset($comparisonOperators[$operator])) {
      // Es un operador de comparación, agrega la condición tal como está
      $parsedFilter[$key] = $value;
    }
  }

  return $parsedFilter ?? [];
}

function isValidCondition($condition, $comparisonOperators)
{
  $operator = key($condition);
  return isset($comparisonOperators[$operator]);
}

function parse_sort($sort)
{
}

function parse_populate($populate)
{
}

function parse_fields($field)
{
}

/* TODO support all those operators in 
    $eq	Equal
    $eqi	Equal (case-insensitive)
    $ne	Not equal
    $nei	Not equal (case-insensitive)
    $lt	Less than
    $lte	Less than or equal to
    $gt	Greater than
    $gte	Greater than or equal to
    $in	Included in an array
    $notIn	Not included in an array
    $contains	Contains
    $notContains	Does not contain
    $containsi	Contains (case-insensitive)
    $notContainsi	Does not contain (case-insensitive)
    $null	Is null
    $notNull	Is not null
    $between	Is between
    $startsWith	Starts with
    $startsWithi	Starts with (case-insensitive)
    $endsWith	Ends with
    $endsWithi	Ends with (case-insensitive)
    $or	Joins the filters in an "or" expression
    $and	Joins the filters in an "and" expression
    $not	Joins the filters in an "not" expression
*/
