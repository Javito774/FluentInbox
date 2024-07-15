<?php
require_once __DIR__ . "/../../controllers.php";
class distritosController
{
  public function create($query): array
  {
    return generalCreate($query, 'distritos');
  }
  public function find($query): array
  {
    return generalFind($query, 'distritos');
  }
  public function findOne($query): array
  {
    return generalFindOne($query, 'distritos');
  }
  public function update($query): array
  {
    return generalUpdate($query, 'distritos');
  }
  public function delete($query): array
  {
    return generalDelete($query, 'distritos');
  }
}
