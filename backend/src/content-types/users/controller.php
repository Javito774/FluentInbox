<?php
require_once __DIR__ . "/../../controllers.php";
class usersController
{
  public function create($query): array
  {
    return generalCreate($query, 'users');
  }
  public function find($query): array
  {
    return generalFind($query, 'users');
  }
  public function findOne($query): array
  {
    return generalFindOne($query, 'users');
  }
  public function update($query): array
  {
    return generalUpdate($query, 'users');
  }
  public function delete($query): array
  {
    return generalDelete($query, 'users');
  }

  public function me($query): array
  {
    $data = [
      "id" => $query["auth"]["user"]["id"],
      "name" => $query["auth"]["user"]["username"],
      "email" => $query["auth"]["user"]["email"],
      "confirmed" => $query["auth"]["user"]["confirmed"],
      "blocked" => $query["auth"]["user"]["blocked"]
    ];

    return $data;
  }
}
