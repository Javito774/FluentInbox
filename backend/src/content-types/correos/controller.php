<?php
require_once __DIR__ . "/../../controllers.php";
class correosController
{
  public function create($query): array
  {
    return generalCreate($query, 'correos');
  }
  public function find($query): array
  {
    return generalFind($query, 'correos');
  }
  public function findOne($query): array
  {
    return generalFindOne($query, 'correos');
  }
  public function update($query): array
  {
    return generalUpdate($query, 'correos');
  }
  public function delete($query): array
  {
    return generalDelete($query, 'correos');
  }
}
