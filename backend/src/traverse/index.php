<?php


function buildConditions($filters)
{
  $logicalOperators = [
    '$and' => 'AND',
    '$or' => 'OR'
  ];

  $comparisonOperators = [
    '$eq' => '=',
    '$ne' => '!='
  ];

  $sql = '';
  $isFirstCondition = true;

  foreach ($filters as $field => $value) {
    $logicalOperator = $field;
    $operator = key($value);
    $fieldValue = reset($value);

    if (isset($logicalOperators[$logicalOperator])) {
      // Es un operador lógico
      if (!$isFirstCondition) {
        $sql .= ' ' . $logicalOperators[$logicalOperator] . ' ';
      }
      $sql .= '(';

      $isFirstInnerCondition = true;
      foreach ($value as $subFilter) {
        if (!$isFirstInnerCondition) {
          $sql .= ' ' . $logicalOperators[$logicalOperator] . ' ';
        }
        $sql .= buildConditions($subFilter);
        $isFirstInnerCondition = false;
      }

      $sql .= ')';
    } elseif (isset($comparisonOperators[$operator])) {
      if (!$isFirstCondition) {
        $sql .= ' AND ';
      }

      $sql .= "$field " . $comparisonOperators[$operator] . " '$fieldValue'";
    }

    $isFirstCondition = false;
  }

  return $sql;
}
