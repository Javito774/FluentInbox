<?php
require_once __DIR__ . "/../../controllers.php";
class queueController
{
  public function create($query): array
  {
    return generalCreate($query, 'queue');
  }
  public function find($query): array
  {
    return generalFind($query, 'queue');
  }
  public function findOne($query): array
  {
    return generalFindOne($query, 'queue');
  }
  public function update($query): array
  {
    return generalUpdate($query, 'queue');
  }
  public function delete($query): array
  {
    return generalDelete($query, 'queue');
  }
  public function stats($query): array
  {
    return [
      "limit" => 200,
      "queued" => "0",
      "quota" => "0",
      "performance" => "0" 
    ];
  }
}
