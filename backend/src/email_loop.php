<?php
//Import PHPMailer classes into the global namespace
//These must be at the top of your script, not inside a function
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

//Load Composer's autoloader
require __DIR__ . '/vendor/autoload.php';
require_once __DIR__ . '/database.php';

$mysqli = get_mysql_connection();
$mysqli->autocommit(false); // Start a transaction
$sqlQuota = "SELECT COUNT(*) AS total_mails_sent FROM queue WHERE sent_at IS NOT NULL AND sent_at >= NOW() - INTERVAL 1 HOUR";

$maxMailsPerHour = 500;
$quota = $maxMailsPerHour;

try {
  $mainResult = $mysqli->query($sqlQuota);

  if (!$mainResult) {
    error(500, "Error fetching getting email quota", $mysqli->error);
  }

  $result = $mainResult->fetch_assoc();

  $quota = $result["total_mails_sent"];
} catch (Exception $e) {
  error(500, "Error fetching $contentType", $e->getMessage());
}

$quotaLimit = $maxMailsPerHour - $quota;

if ($quotaLimit > 0) {
  // Get data with the limit quota.
  echo "The available quota is: $quotaLimit\n";
} else {
  echo "Not quota available, waiting...\n";
}

$sql = "SELECT * FROM queue WHERE sent_at is null and schedule_at <= now() LIMIT $quotaLimit";
$mails = [];

try {
  $resultado = $mysqli->query($sql);
  while ($fila = $resultado->fetch_assoc()) {
    $mails[] = $fila;
  }
} catch (Exception $e) {
  error(500, "Error fetching mails data", $e->getMessage());
}

echo "Mails retrived\n";
print_r($mails);

$uniqueMails = [];

foreach ($mails as $item) {
  $mail = $item["mail"];

  // Use the mail value as the key in the associative array
  // This will ensure that only unique mail values are kept
  $uniqueMails[$mail] = $mail;
}

// Convert the associative array back to a regular indexed array
$uniqueMailsArray = array_values($uniqueMails);

require_once __DIR__ . "/content-types/mails/controller.php";
$mailController = new mailsController();
$finalMailData = [];
foreach ($uniqueMails as $mail) {
  $finalMailData[] = $mailController->findOne(["params" => ["id" => $mail]]);
}

echo "Body mails retrived\n";
print_r($finalMailData);

foreach ($finalMailData as $mailData) {
  $mail = new PHPMailer(true);

  try {
    $mail->CharSet = "UTF-8";
    $mail->setLanguage("es");
    //$mail->Encoding = 'base64';
    //Server settings
    $mail->SMTPDebug = SMTP::DEBUG_SERVER;                      //Enable verbose debug output
    $mail->isSMTP();                                            //Send using SMTP
    $mail->Host       = 'smtp.ionos.es';                     //Set the SMTP server to send through
    $mail->SMTPAuth   = true;                                   //Enable SMTP authentication
    $mail->Username   = 'noreply@asimadrid.es';                     //SMTP username
    $mail->Password   = 'h!C9fFiKJ8&Sq9_';                               //SMTP password
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;            //Enable implicit TLS encryption
    $mail->Port       = 587;                                    //TCP port to connect to; use 587 if you have set `SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS`

    //Recipients
    $mail->setFrom('noreply@asimadrid.es', 'ASI Madrid');
    // $mail->addReplyTo('info@example.com', 'Information');

    // For each data addThe recipient
    $realDest = [];
    foreach ($mails as $destinatario) {
      if ($destinatario["mail"] === $mailData["id"]) {
        $mail->addBCC($destinatario["destinatario"]);
        $realDest[] = $destinatario["destinatario"];
      }
    }
    echo "Añadidos todos los destinatarios\n";
    print_r($realDest);
    /*
    $mail->addAddress('joe@example.net', 'Joe User');     //Add a recipient
    $mail->addAddress('ellen@example.com');               //Name is optional
    $mail->addCC('cc@example.com');
    $mail->addBCC('bcc@example.com');

    //Attachments
    $mail->addAttachment('/var/tmp/file.tar.gz');         //Add attachments
    $mail->addAttachment('/tmp/image.jpg', 'new.jpg');    //Optional name
     */


    $htmlbody = '
      <html lang="es">
      <head>
<meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
<style>
            body {
              font-size: 16px;
            }
            #bodyContainer>*{ margin-top: 9px }
            #bodyContainer img {
              max-width: 552px;
              margin: 20px 0;
            }
@media only screen and (max-width: 600px) {
    body {
        font-size: 20px;
    }
}
        </style>
</head>
      <body>
        <table cellpadding="0" cellspacing="0" border="0" width="100%" bgcolor="#f1f5f9" style="padding:10px">
          <tbody>
            <tr>
              <td valign="top" width="100%">
                <table width="100%" role="content-container" align="center" cellpadding="0" cellspacing="0" border="0">
                  <tbody>
                    <tr>
                      <td width="100%">
                        <table width="100%" cellpadding="0" cellspacing="0" border="0">
                          <tbody>
                            <tr>
                              <td>
                                <table width="100%" cellpadding="0" cellspacing="0" border="0" style="width:100%;max-width:600px;margin: 20px auto;" align="center">
                                  <tbody>
                                    <tr>
                                      <td id="bodyContainer" role="modules-container" style="text-align:left;font-size:12pt;padding: 30px 50px 20px;border-radius: 6px;" bgcolor="#ffffff" width="100%" align="left">'
      . $mailData["bodyhtml"] . '
                                      </td>
                                    </tr>
                                  </tbody>
                                </table>
                              </td>
                            </tr>
                          </tbody>
                        </table>
                      </td>
                    </tr>
                  </tbody>
                </table>
              </td>
            </tr>
          </tbody>
        </table>
      </body>
      </html>';

    //Content
    $mail->isHTML(true);                                  //Set email format to HTML
    $mail->Subject = $mailData["asunto"];
    $mail->Body    = $htmlbody;
    $mail->AltBody = $mailData["body"];

    if ($mail->send()) {
      if (isset($mailData["id"])) {
        $mailID = $mailData["id"];
        $realDestString = implode("', '", $realDest);

        $sql = "UPDATE queue SET sent_at=CURRENT_TIMESTAMP WHERE destinatario IN ('$realDestString') AND mail = $mailID";
        try {
          $mysqli = get_mysql_connection();
          $mysqli->query($sql);
        } catch (Exception $updateError) {
          echo "Error updating the queue: {$updateError->getMessage()}";
        }

        $sql = "SELECT count(*) as restantes FROM queue WHERE sent_at is null AND mail = $mailID";
        $quota = 0;
        try {
          $mainResult = $mysqli->query($sql);

          if (!$mainResult) {
            error(500, "Error fetching getting email quota", $mysqli->error);
          }

          $result = $mainResult->fetch_assoc();

          if ($result !== null && isset($result["restantes"])) {
            $quota = $result["restantes"];
          } else {
            error(500, "Error fetching email quota data");
          }
        } catch (Exception $e) {
          error(500, "Error fetching data", $e->getMessage());
        }
        $sql = "";
        if ($quota === 0) {
          $sql = "UPDATE mails SET estado='sent' WHERE id=$mailID";
        } elseif ($mailData["estado"] !== "snoozed") {
          $sql = "UPDATE mails SET estado='snoozed' WHERE id=$mailID";
        }
        if (!empty($sql)) {
          try {
            $mainResult = $mysqli->query($sql);
            if (!$mainResult) {
              error(500, "Error fetching getting email quota", $mysqli->error);
            }
          } catch (Exception $e) {
            error(500, "Error updating mail", $e->getMessage());
          }
        }
        echo "Mensaje $mailID enviado";
      } else {
        echo "Error: Missing mail ID.";
      }
    }
  } catch (Exception $e) {
    echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
  }
}
