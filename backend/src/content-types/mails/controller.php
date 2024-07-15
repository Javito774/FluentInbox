<?php
require_once __DIR__ . "/../../controllers.php";
class mailsController
{
  public function send($query): array
  {
    $id = $query["params"]["id"];

    $mail_data = $this->findOne($query);
    // Get mail data.
    // Get all the asociated destinatarios.
    $sql = "SELECT * FROM populate_destinatarios WHERE mail=$id";
    $mysqli = get_mysql_connection();
    $destinatarios = [];
    try {
      $resultado = $mysqli->query($sql);
      while ($fila = $resultado->fetch_assoc()) {
        $destinatarios[] = $fila;
      }
    } catch (Exception $e) {
      error(500, "Error fetching destinatarios", $e->getMessage());
    }

    if ($mail_data["estado"] !== "draft")
      error(409, "This mail could not be send");

    // Check the data is complete.
    if (empty($mail_data["body"]) || empty($mail_data["asunto"]) || sizeof($destinatarios) <= 0) {
      error(409, "Data incomplete");
    }

    if (!$mail_data["envio"]) {
      $this->update([
        "data" => [
          "estado" => "snoozed"
        ],
        "params" => [
          "id" => $mail_data["id"]
        ]
      ]);
    } else {
      $this->update([
        "data" => [
          "estado" => "scheduled"
        ],
        "params" => [
          "id" => $mail_data["id"]
        ]
      ]);
    }
    require_once(__DIR__ . "/../queue/controller.php");
    $queueController = new queueController();
    if (!$mail_data["envio"]) {
      foreach ($destinatarios as $dest) {
        $query = [
          "data" => [
            "mail" => $mail_data["id"],
            "destinatario" => $dest["email"]
          ]
        ];
        $queueController->create($query);
      }
    } else {
      foreach ($destinatarios as $dest) {
        $queueController->create(["data" => [
          "mail" => $mail_data["id"],
          "destinatario" => $dest["email"],
          "schedule_at" => (new DateTime($mail_data["envio"]))->format("Y-m-d H:i:s")
        ]]);
      }
    }

    $result = [
      "status" => "Enviando correo con id: " . $id
    ];
    return $result;
  }
  public function sendTest($query): array
  {
    $id = $query["params"]["id"];
    $mail_data = $this->findOne($query);
    $user_email = $query["auth"]["user"]["email"];

    $this->update([
      "data" => [
        "estado" => "scheduled"
      ],
      "params" => [
        "id" => $mail_data["id"]
      ]
    ]);

    require_once(__DIR__ . "/../queue/controller.php");

    $queueController = new queueController();
    $query = [
      "data" => [
        "mail" => $mail_data["id"],
        "destinatario" => $user_email
      ]
    ];
    $queueController->create($query);

    $result = [
      "status" => "Enviando test del correo: " . $id . " a la direccion de correo " . $user_email
    ];
    return $result;
  }
  public function create($query): array
  {
    return generalCreate($query, 'mails');
  }
  public function find($query): array
  {
    return generalFind($query, 'mails');
  }
  public function findOne($query): array
  {
    return generalFindOne($query, 'mails');
  }
  public function update($query): array
  {
    return generalUpdate($query, 'mails');
  }
  public function delete($query): array
  {
    return generalDelete($query, 'mails');
  }
}
