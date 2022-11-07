<?php
require_once("./inc/common.php");
checkAccess("arquivosList");

$cad_arquivos_id        = getParam("cad_arquivos_id");
$f_arquivo                 = getParam("f_arquivo");

$dados = array(
	"id"            => $cad_arquivos_id,
	"arquivo"          => $f_arquivo
);

if (!empty($cad_arquivos_id)) {

	$sql_update = "
	UPDATE cad_arquivos SET
		arquivo = :arquivo
	WHERE
		id = :id
	";

	try {
		$conn->prepare($sql_update)->execute($dados);
		$lastInsertId = $cad_arquivos_id;
		$actionText = "Alteração efetuada com sucesso";
		$tipo = 'success';
	} catch (PDOException $e) {
		$actionText = "Erro ao alterar";
		$tipo = 'error';
	}
} else {

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
}

setAlert($actionText, $tipo);
redirect("arquivosList.php");
