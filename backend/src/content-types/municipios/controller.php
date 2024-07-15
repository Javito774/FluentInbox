<?php
require_once __DIR__ . "/../../controllers.php";
class municipiosController
{
  public function create($query): array
  {
    return generalCreate($query, 'municipios');
  }
  public function find($query): array
  {
    return generalFind($query, 'municipios');
  }
  public function findOne($query): array
  {
    return generalFindOne($query, 'municipios');
  }
  public function update($query): array
  {
    return generalUpdate($query, 'municipios');
  }
  public function delete($query): array
  {
    return generalDelete($query, 'municipios');
  }
}
