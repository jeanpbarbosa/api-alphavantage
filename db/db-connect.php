<?php

try {
$dbConnect = new PDO("mysql:host=$GENERAL_PDO_host_banco;dbname=$GENERAL_PDO_nome_banco;charset=utf8", "$GENERAL_PDO_user_banco", "$GENERAL_PDO_pass_banco");
$dbConnect->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch(PDOException $e) {
echo json_encode(array(
  "status_tipo" => "NOK",
  "status_msg" => "<b>Não foi possível conectar ao banco de dados!</b><br/>Tente novamente ou contate o administrador."
));
exit;
}