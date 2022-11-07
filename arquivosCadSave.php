<?php
require_once("./inc/common.php");
checkAccess("arquivosList");

$cad_arquivos_id        = getParam("cad_arquivos_id");
$f_arquivo              = $_FILES['f_arquivo'];


if ($_FILES['f_arquivo']['tmp_name'] != '') {
	$f_arquivo = file_get_contents($_FILES['f_arquivo']['tmp_name']);
}

$dados = array(
	"id"            => $cad_arquivos_id,
	"arquivo"       => $f_arquivo
);

$sql_insert = "
INSERT INTO cad_arquivos (
	id,
	arquivo
) VALUES (
	:id,
	:arquivo
)";

try {
	$conn->prepare($sql_insert)->execute($dados);
	$lastInsertId = $conn->lastInsertId();
	$actionText = "Cadastro efetuado com sucesso";
	$tipo = 'success';
} catch (PDOException $e) {
	$actionText = "Erro ao cadastrar";
	$tipo = 'error';
}

setAlert($actionText, $tipo);
redirect("arquivosList.php");
