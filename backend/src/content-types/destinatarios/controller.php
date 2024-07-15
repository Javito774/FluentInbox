<?php
require_once __DIR__ . "/../../controllers.php";
class destinatariosController
{
  public function create($query): array
  {
    return generalCreate($query, 'destinatarios');
  }
  public function find($query): array
  {
    return generalFind($query, 'destinatarios');
  }
  public function findOne($query): array
  {
    return generalFindOne($query, 'destinatarios');
  }
  public function update($query): array
  {
    return generalUpdate($query, 'destinatarios');
  }
  public function delete($query): array
  {
    return generalDelete($query, 'destinatarios');
  }
}
