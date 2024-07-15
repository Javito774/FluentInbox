<?php

require_once __DIR__ . "/schemas/index.php";
require_once __DIR__ . "/sanitize/index.php";

function generalCreate($query, $contentType): array
{
  $schema = get_schema($contentType);
  $data = validate_input($query["data"], $schema);
  $data = sanitize_input($data, $schema);
  $data = eliminar_relaciones($data, $schema);
  checkRequiredFields($data, $schema);
  $currentDateTime = new DateTime();

  foreach ($schema['attributes'] as $attribute => $attributeInfo)
    if ($attributeInfo["type"] === 'datetime' && isset($data[$attribute]))
      $data[$attribute] = $data[$attribute]->format("Y-m-d H:i:s");

  $data["created_at"] = $currentDateTime->format("Y-m-d H:i:s");
  $data["updated_at"] = $currentDateTime->format("Y-m-d H:i:s");

  $keys = array_keys($data);
  $values = array_values($data);

  $mysqli = get_mysql_connection();
  $mysqli->autocommit(false); // Start a transaction
  try {
    // Using prepared statement to prevent SQL injection
    // $placeholders = array_fill(0, count($keys), '?');
    $sql = "INSERT INTO $contentType (`" . implode("`, `", $keys) . "`) VALUES ('" . implode("', '", $values) . "')";
    $stmt = $mysqli->prepare($sql);

    // Bind parameters
    // $types = ''; // Assuming all values are strings; adjust accordingly
    // foreach ($keys as $key) {
    //  $attributeInfo = $schema['attributes'][$key];
    //  $types .= get_sql_type_from_schema($attributeInfo) ?: 's'; // Default to string if type is not recognized
    // }
    // $stmt->bind_param($types, ...$values);

    // Execute the statement
    $stmt->execute();

    // Get the last inserted ID
    $lastInsertedId = $mysqli->insert_id;

    $stmt->close();

    $mysqli->commit(); // Commit the transaction
    $mysqli->autocommit(true); // Reset autocommit to true

    return ["inserted_id" => $lastInsertedId];
  } catch (Exception $e) {
    $mysqli->rollback(); // Rollback the transaction in case of an error
    $mysqli->autocommit(true); // Reset autocommit to true
    error(500, "Error creating $contentType", $e->getMessage());
  }
}

function generalFind($query, $contentType): array
{
  $sql = "SELECT * FROM $contentType";

  if (array_key_exists("fields", $query)) {
    $validatedFields = validate_fields($query["fields"], get_schema($contentType));
    if (!empty($validatedFields)) {
      $fields = implode(", ", array_map(function ($field) {
        return "`$field`";
      }, $validatedFields));
      $sql = "SELECT $fields FROM $contentType";
    }
  }

  if (array_key_exists("filters", $query)) {
    $sqlFilters = buildConditions($query["filters"]);
    $sql .= " WHERE $sqlFilters";
  }

  if (array_key_exists("sort", $query)) {
    $validatedSort = validate_sort($query["sort"], get_schema($contentType));
    if (!empty($validatedSort)) {
      $sortField = $validatedSort["field"];
      $sortOrder = $validatedSort["order"];
      $sql .= " ORDER BY `$sortField` $sortOrder";
    }
  }

  $schema = get_schema($contentType);
  $mysqli = get_mysql_connection();
  try {
    $resultado = $mysqli->query($sql);
    $result = [];
    while ($fila = $resultado->fetch_assoc()) {
      $result[] = sanitize_output($fila, $schema);
    }
    return $result;
  } catch (Exception $e) {
    error(500, "Error fetching $contentType", $e->getMessage());
  }
}

function generalFindOne($query, $contentType)
{
  $id = $query["params"]["id"];
  $schema = get_schema($contentType);

  $sql = "SELECT * FROM $contentType";

  if (array_key_exists("fields", $query)) {
    $validatedFields = validate_fields($query["fields"], get_schema($contentType));
    if (!empty($validatedFields)) {
      $fields = implode(", ", array_map(function ($field) {
        return "`$field`";
      }, $validatedFields));
      $sql = "SELECT $fields FROM $contentType";
    }
  }

  $sql .= " WHERE $contentType.id = $id";

  $mysqli = get_mysql_connection();
  try {
    $mainResult = $mysqli->query($sql);

    if (!$mainResult) {
      error(500, "Error fetching $contentType", $mysqli->error);
    }

    $result = $mainResult->fetch_assoc();

    if (!$result) {
      return []; // No se encontraron resultados
    }
    $result = sanitize_output($result, $schema);
  } catch (Exception $e) {
    error(500, "Error fetching $contentType", $e->getMessage());
  }


  if (array_key_exists("populate", $query)) {
    $populateFields = $query["populate"];
    foreach ($populateFields as $populateField) {
      if ($schema['attributes'][$populateField]["type"] == "relation") {
        $relationSchema = get_schema($populateField);
        if (array_key_exists('target', $schema['attributes'][$populateField])) {
          $targetTable = $schema['attributes'][$populateField]['target'];
          $originTable = $contentType;
        } else {
          $targetTable = $contentType;
          $originTable = $schema['attributes'][$populateField]['origin'];
        }

        $relatedSql = "SELECT * FROM $originTable" . "_$targetTable" . "_links AS otl_$populateField ";
        $relatedSql .= "LEFT JOIN $targetTable AS rt_$populateField ON otl_$populateField.$targetTable" . "_id = rt_$populateField.id ";
        $relatedSql .= "WHERE otl_$populateField.$originTable" . "_id = $id";

        try {
          $relatedResult = $mysqli->query($relatedSql);

          if (!$relatedResult) {
            error(500, "Error fetching $populateField data", $mysqli->error);
          }

          $relatedData = [];
          while ($row = $relatedResult->fetch_assoc()) {
            $relatedData[] = sanitize_output($row, $relationSchema);
          }

          $result[$populateField] = $relatedData;
        } catch (Exception $e) {
          error(500, "Error fetching $populateField data", $e->getMessage());
        }
      }
    }
  }

  return $result;
}


function generalUpdate($query, $contentType): array
{
  $id = $query["params"]["id"];
  $schema = get_schema($contentType);
  $data = validate_input($query["data"], $schema);
  $data = sanitize_input($data, $schema);

  $currentDateTime = new DateTime();

  $data["updated_at"] = $currentDateTime;

  $sqlFormat = [];

  $mysqli = get_mysql_connection();
  $mysqli->autocommit(false); // Start a transaction

  try {
    foreach ($schema['attributes'] as $attribute => $attributeInfo) {
      // Handle relation updates
      if ($attributeInfo['type'] === 'relation' && isset($data[$attribute])) {
        $relationTable = $attributeInfo['target'];
        $relationData = $data[$attribute];

        // Handle connection
        if (isset($relationData['connect'])) {
          foreach ($relationData['connect'] as $relatedId) {
            $sqlConnect = "INSERT INTO $contentType" . "_$relationTable" . "_links ($contentType" . "_id, $relationTable" . "_id) VALUES ($id, $relatedId)";
            $mysqli->query($sqlConnect);
          }
        }

        // Handle disconnection
        if (isset($relationData['disconnect'])) {
          foreach ($relationData['disconnect'] as $relatedId) {
            $sqlDisconnect = "DELETE FROM $contentType" . "_$relationTable" . "_links WHERE $contentType" . "_id = $id AND $relationTable" . "_id = $relatedId";
            $mysqli->query($sqlDisconnect);
          }
        }
      } elseif (isset($data[$attribute])) {
        $value = "";
        if ($attributeInfo['type'] === 'datetime' || $attributeInfo['type'] === 'ISOdatetime') {
          $value = $data[$attribute]->format("Y-m-d H:i:s");
        } else {
          $value = $data[$attribute];
        }
        $sqlFormat[] = "$attribute = '$value'";
      }
    }
    if (count($sqlFormat) > 0) {
      // Update main data
      $sql = "UPDATE " . $contentType . " SET " . implode(", ", $sqlFormat) . " WHERE id=$id";
      $mysqli->query($sql);
    }

    $mysqli->commit(); // Commit the transaction
    $mysqli->autocommit(true); // Reset autocommit to true

    return ["updated" => true];
  } catch (Exception $e) {
    $mysqli->rollback(); // Rollback the transaction in case of an error
    $mysqli->autocommit(true); // Reset autocommit to true
    error(500, "Error updating $contentType", $e->getMessage());
  }
}

function generalDelete($query, $contentType): array
{
  $id = $query["params"]["id"];
  $sql = "DELETE FROM $contentType WHERE id=$id";
  $mysqli = get_mysql_connection();
  try {
    $mysqli->query($sql);
    return ["deleted" => true];
  } catch (Exception $e) {
    error(500, "Error deleting $contentType", $e->getMessage());
  }
}

function get_sql_type_from_schema($attributeInfo)
{
  switch ($attributeInfo['type']) {
    case 'text':
    case 'string':
    case 'enumeration':
    case 'datetime':
      return 's'; // String
    case 'relation':
      return null; // Skip relations when determining types
    case 'boolean':
      return 'i'; // Integer (0 or 1) for boolean
    default:
      return 's'; // Default to string if type is not recognized
  }
}
