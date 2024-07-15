<?php
require_once __DIR__ . "/../../controllers.php";
class zonasController
{
  public function create($query): array
  {
    return generalCreate($query, 'zonas');
  }
  public function find($query): array
  {
    return generalFind($query, 'zonas');
  }
  public function findOne($query): array
  {
    return generalFindOne($query, 'zonas');
  }
  public function update($query): array
  {
    return generalUpdate($query, 'zonas');
  }
  public function delete($query): array
  {
    return generalDelete($query, 'zonas');
  }
}
