<?php
/*
	Retorna json com os dados, filtrados por data e symbol
	// Renner: LREN3.SAO
	// Ibovespa: IBOV.SAO
	// Bitcoin: BTC
*/

// GET symbol
if (!isset($_GET['symbol'])) {
	echo json_encode("Get symbol required.");
    exit;
} else {
	$symbol = $_GET['symbol'];
}

// GET date-start
if (!isset($_GET['date-start'])) {
	echo json_encode("Get date-start required.");
    exit;
} else {
	$dateStart = $_GET['date-start'];
}

// GET date-end
if (!isset($_GET['date-end'])) {
	echo json_encode("Get date-end required.");
    exit;
} else {
	$dateEnd = $_GET['date-end'];
}

// Require conexão banco
require_once "../db/db-general.php";
require_once "../db/db-connect.php";

// Retorna consulta
$Banco = $dbConnect->prepare("SELECT date, symbol, value FROM indicadores_mercado
	WHERE symbol = :symbol AND date >= :dateStart AND date <= :dateEnd");
$Banco->bindParam(':symbol', $symbol);
$Banco->bindParam(':dateStart', $dateStart);
$Banco->bindParam(':dateEnd', $dateEnd);
$Banco->execute();
$Banco = $Banco->fetchAll(PDO::FETCH_ASSOC);

header('Content-Type: application/json');
echo json_encode($Banco);