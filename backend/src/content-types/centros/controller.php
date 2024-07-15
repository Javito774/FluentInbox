<?php
require_once __DIR__ . "/../../controllers.php";
class centrosController
{
  public function create($query): array
  {
    return generalCreate($query, 'centros');
  }
  public function find($query): array
  {
    return generalFind($query, 'centros');
  }
  public function findOne($query): array
  {
    return generalFindOne($query, 'centros');
  }
  public function update($query): array
  {
    return generalUpdate($query, 'centros');
  }
  public function delete($query): array
  {
    return generalDelete($query, 'centros');
  }
}
