<?php
require_once __DIR__ . "/../../controllers.php";
class listasController
{
  public function create($query): array
  {
    return generalCreate($query, 'listas');
  }
  public function find($query): array
  {
    return generalFind($query, 'listas');
  }
  public function findOne($query): array
  {
    return generalFindOne($query, 'listas');
  }
  public function update($query): array
  {
    return generalUpdate($query, 'listas');
  }
  public function delete($query): array
  {
    return generalDelete($query, 'listas');
  }
}
