<?php
/*
	Carrega os dados da ação/índice a partir da api/url para o banco.
	// Renner: LREN3.SAO
	// Ibovespa: IBOV.SAO
*/

// GET symbol
if (!isset($_GET['symbol'])) {
	echo json_encode("Get symbol required.");
    exit;
} else {
	$symbol = $_GET['symbol'];
}

// URL do json a ser tratado
$url = "https://www.alphavantage.co/query?function=TIME_SERIES_DAILY&datatype=json&symbol=" . $symbol . "&apikey=...";

// Get json
$array = file_get_contents($url);

// Converte json para array php
$array = json_decode($array, true);

// Pula para série de dias no array multi-nível
$array = $array["Time Series (Daily)"];

// Get X primeiros
$array = array_slice($array, 0, 5);

// Require conexão banco
require_once "../db/db-general.php";
require_once "../db/db-connect.php";

// Loop no array
foreach ($array as $key => $value) {

	// Valida se já tem registro, para ser INSERT ou UPDATE
	$Banco = $dbConnect->prepare("SELECT date FROM indicadores_mercado
		WHERE date = :date AND symbol = :symbol");
	$Banco->bindParam(':date', $key);
	$Banco->bindParam(':symbol', $symbol);
	$Banco->execute();
	$Banco = $Banco->rowCount();
	
	if ($Banco == 0) {
		// INSERT
		$Banco = $dbConnect->prepare("INSERT INTO indicadores_mercado (date, symbol, value)
			VALUES (:date, :symbol, :value)");
		$Banco->bindParam(':date', $key);
		$Banco->bindParam(':symbol', $symbol);
		$Banco->bindParam(':value', $array[$key]["4. close"]);
		$Banco->execute();
	} else {
		// UPDATE
		$Banco = $dbConnect->prepare("UPDATE indicadores_mercado SET value = :value
			WHERE date = :date AND symbol = :symbol");
		$Banco->bindParam(':date', $key);
		$Banco->bindParam(':symbol', $symbol);
		$Banco->bindParam(':value', $array[$key]["4. close"]);
		$Banco->execute();
	}
}