<?php
require_once __DIR__ . "/../../controllers.php";
class rolesController
{
  public function create($query): array
  {
    return generalCreate($query, 'roles');
  }
  public function find($query): array
  {
    return generalFind($query, 'roles');
  }
  public function findOne($query): array
  {
    return generalFindOne($query, 'roles');
  }
  public function update($query): array
  {
    return generalUpdate($query, 'roles');
  }
  public function delete($query): array
  {
    return generalDelete($query, 'roles');
  }
}
