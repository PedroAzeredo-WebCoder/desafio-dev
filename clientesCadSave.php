<?php
require_once("./inc/common.php");
checkAccess("clientesList");

writeLogs("==== " . __FILE__ . " ====", "access");
writeLogs(print_r($_POST, true), "access");

$cad_clientes_id        = getParam("cad_clientes_id");
$f_nome                 = getParam("f_nome");
$f_documento            = str_replace(array('.', '-', '/'), array('', '', ''), getParam("f_documento"));
$f_tipo                 = getParam("f_tipo");
$f_email                = getParam("f_email");
$f_celular             	= getParam("f_celular");
$f_telefone             = getParam("f_telefone");
$f_cep                  = getParam("f_cep");
$f_estado               = getParam("f_estado");
$f_cidade               = getParam("f_cidade");
$f_bairro               = getParam("f_bairro");
$f_logradouro           = getParam("f_logradouro");
$f_numero               = getParam("f_numero");
$f_complemento          = getParam("f_complemento");
$f_senha             	= getParam("f_senha");
$f_ativo                = getParam("f_ativo");

if ($f_ativo == "on") {
	$f_ativo = "1";
} else {
	$f_ativo = "0";
}

$dados = array(
	"id"            => $cad_clientes_id,
	"nome"          => $f_nome,
	"documento"     => $f_documento,
	"tipo"          => $f_tipo,
	"email"         => $f_email,
	"celular"       => $f_celular,
	"telefone"      => $f_telefone,
	"cep"           => $f_cep,
	"estado"        => $f_estado,
	"cidade"        => $f_cidade,
	"bairro"        => $f_bairro,
	"logradouro"    => $f_logradouro,
	"numero"        => $f_numero,
	"complemento"   => $f_complemento,
	"status"        => $f_ativo,
);

if (!empty($cad_clientes_id)) {

	if (!empty($f_senha)) {
		$dados["senha"] = md5($f_senha);

		$sql_update = "
		UPDATE cad_clientes SET
			nome = :nome,
			documento = :documento,
			tipo = :tipo,
			email = :email,
			celular = :celular,
			telefone = :telefone,
			cep = :cep,
			estado = :estado,
			cidade = :cidade,
			bairro = :bairro,
			logradouro = :logradouro,
			numero = :numero,
			complemento = :complemento,
			senha = :senha,
			dt_update = NOW(),
			status = :status
		WHERE
			id = :id
		";

	} else {

		$sql_update = "
		UPDATE cad_clientes SET
			nome = :nome,
			documento = :documento,
			tipo = :tipo,
			email = :email,
			celular = :celular,
			telefone = :telefone,
			cep = :cep,
			estado = :estado,
			cidade = :cidade,
			bairro = :bairro,
			logradouro = :logradouro,
			numero = :numero,
			complemento = :complemento,
			dt_update = NOW(),
			status = :status
		WHERE
			id = :id
		";
		
	}
	try {
		$conn->prepare($sql_update)->execute($dados);
		$lastInsertId = $cad_clientes_id;
		$actionText = "Alteração efetuada com sucesso";
		$tipo = 'success';
	} catch (PDOException $e) {
		$actionText = "Erro ao alterar";
		$tipo = 'error';
		writeLogs("==== " . __FILE__ . " ====", "error");
		writeLogs("Action: UPDATE SQL", "error");
		writeLogs(print_r($e, true), "error");
		writeLogs(printSQL($sql_update, $dados, true), "error");
	}
} else {
	$dados["uid"] = uniqIdNew();
	$dados["senha"] = md5($f_senha);


	$sql_insert = "
			INSERT INTO cad_clientes (
				id,
				uid, 
				nome,
				documento,
				tipo,
				email,
				celular,
				telefone,
				cep,
				estado,
				cidade,
				bairro,
				logradouro,
				numero,
				complemento,
				senha,
				dt_create,
				status
			) VALUES (
				:id,
				:uid,
				:nome,
				:documento,
				:tipo,
				:email,
				:celular,
				:telefone,
				:cep,
				:estado,
				:cidade,
				:bairro,
				:logradouro,
				:numero,
				:complemento,
				:senha,
				NOW(),
				:status
			)";

	try {
		$conn->prepare($sql_insert)->execute($dados);
		$lastInsertId = $conn->lastInsertId();
		$actionText = "Cadastro efetuado com sucesso";
		$tipo = 'success';
	} catch (PDOException $e) {
		$actionText = "Erro ao cadastrar";
		$tipo = 'error';
		writeLogs("==== " . __FILE__ . " ====", "error");
		writeLogs("Action: Insert SQL", "error");
		writeLogs(print_r($e, true), "error");
		writeLogs(printSQL($sql_insert, $dados, true), "error");
	}
}

setAlert($actionText, $tipo);
redirect("clientesList.php");
