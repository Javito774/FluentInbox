<?php
class authController
{
  public function changePassword($query): array
  {
    $result = [
      "status" => "To be implemented"
    ];
    return $result;
  }
  public function mailConfirmation($query): array
  {
    $result = [
      "status" => "To be implemented"
    ];
    return $result;
  }
  public function forgotPassword($query): array
  {
    $result = [
      "status" => "To be implemented"
    ];
    return $result;
  }
  public function login($query): array
  {
    $schema = [
      "uid" => "Login schema",
      "attributes" => [
        "identifier" => [
          "type" => "email",
          "required" => true
        ],
        "password" => [
          "type" => "string",
          "required" => true
        ]
      ]
    ];
    $data = validate_input($query["data"], $schema);
    $data = sanitize_input($data, $schema);
    checkRequiredFields($data, $schema);

    $identifier = $data["identifier"];
    $password = $query["data"]["password"];
    $mysqli = get_mysql_connection();

    $parsedIdentifier = parse_email($identifier);

    $sql = "SELECT * FROM users WHERE email=?";
    $stmt = $mysqli->prepare($sql);
    if ($stmt) {
      $stmt->bind_param("s", $parsedIdentifier);
      $stmt->execute();
      $queryResult = [];
      $stmt->bind_result($queryResult["id"], $queryResult["username"], $queryResult["email"], $queryResult["provider"], $queryResult["password"], $queryResult["reset_password_token"], $queryResult["confirmation_token"], $queryResult["confirmed"], $queryResult["blocked"], $queryResult["created_at"], $queryResult["updated_at"], $queryResult["created_by"], $queryResult["updated_by"]);
      if ($stmt->fetch()) {
        $userData = $queryResult;
      }
      $stmt->close();
    } else {
      error(500, "Internal Server Error");
    }
    if (!$userData || !password_verify($password, $userData["password"])) {
      error(400, "ValidationError", "Invalid identifier or password");
    }

    $userSchema = get_schema('users');

    $sanitizedData = sanitize_output($userData, $userSchema);

    $currentTime = time();

    $payload = [
      "id" => $userData["id"],
      "iat" => $currentTime,
      "exp" => $currentTime + 60 * 60 // one hour later in seconds
    ];

    $jwt = encode_jwt($payload);

    $result = [
      "jwt" => $jwt,
      "user" => $sanitizedData
    ];

    return $result;
  }
  public function register($query): array
{
    $schema = [
        "uid" => "Registration schema",
        "attributes" => [
            "username" => [
                "type" => "string",
                "required" => true
            ],
            "email" => [
                "type" => "email",
                "required" => true
            ],
            "password" => [
                "type" => "string",
                "required" => true
            ]
            // Add other registration fields as needed
        ]
    ];

    $data = validate_input($query["data"], $schema);
    $data = sanitize_input($data, $schema);
    checkRequiredFields($data, $schema);

    // Additional registration logic
    $username = $data["username"];
    $email = $data["email"];
    $password = password_hash($data["password"], PASSWORD_DEFAULT); // Hash the password

    $mysqli = get_mysql_connection();

    // Check if the email is already registered
    $sqlCheckEmail = "SELECT id FROM users WHERE email=?";
    $stmtCheckEmail = $mysqli->prepare($sqlCheckEmail);
    if ($stmtCheckEmail) {
        $stmtCheckEmail->bind_param("s", $email);
        $stmtCheckEmail->execute();
        $stmtCheckEmail->store_result();

        if ($stmtCheckEmail->num_rows > 0) {
            $stmtCheckEmail->close();
            error(400, "ValidationError", "Email already registered");
        }

        $stmtCheckEmail->close();
    } else {
        error(500, "Internal Server Error");
    }

    // Insert new user into the database
    $sqlInsertUser = "INSERT INTO users (username, email, password, created_at) VALUES (?, ?, ?, NOW())";
    $stmtInsertUser = $mysqli->prepare($sqlInsertUser);

    if ($stmtInsertUser) {
        $stmtInsertUser->bind_param("sss", $username, $email, $password);
        $stmtInsertUser->execute();
        $insertedUserId = $stmtInsertUser->insert_id;
        $stmtInsertUser->close();

        // You may want to perform additional actions here, like sending a confirmation email

        $result = [
            "status" => "success",
            "message" => "User registered successfully",
            "userId" => $insertedUserId
        ];
    } else {
        error(500, "Internal Server Error");
    }

    return $result;
}
  public function resetPassword($query)
  {
    $result = [
      "status" => "To be implemented"
    ];
    return $result;
  }
}
