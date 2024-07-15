<?php
require_once __DIR__ . "/../../controllers.php";
class typecentroController
{
  public function create($query): array
  {
    return generalCreate($query, 'typecentro');
  }
  public function find($query): array
  {
    return generalFind($query, 'typecentro');
  }
  public function findOne($query): array
  {
    return generalFindOne($query, 'typecentro');
  }
  public function update($query): array
  {
    return generalUpdate($query, 'typecentro');
  }
  public function delete($query): array
  {
    return generalDelete($query, 'typecentro');
  }
}
