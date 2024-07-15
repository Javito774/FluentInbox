<?php

use Firebase\JWT\JWT;
use Firebase\JWT\Key;

function encode_jwt($payload)
{
  $JWT_key = $_SERVER["JWT_KEY"];
  $alg = 'HS256';
  return JWT::encode($payload, $JWT_key, $alg);
}

function decode_jwt($jwt)
{
  $JWT_key = $_SERVER["JWT_KEY"];
  $alg = 'HS256';
  return (array) JWT::decode($jwt, new Key($JWT_key, $alg));
}

function verify_jwt($jwt)
{
  $JWT_key = $_SERVER["JWT_KEY"];
  $alg = 'HS256';
  $payload = JWT::decode($jwt, new Key($JWT_key, $alg));
  if (isset($payload->id) && isset($payload->iat) && isset($payload->exp)) {
    return true;
  } else {
    return false;
  }
}
