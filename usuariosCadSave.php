<?php
require_once("./inc/common.php");
checkAccess("usuariosList");

writeLogs("==== ".__FILE__." ====", "access");
writeLogs(print_r($_POST, true), "access");

$cad_usuarios_id        = getParam("cad_usuarios_id");
$f_nome                 = getParam("f_nome");
$f_usuario              = getParam("f_usuario");
$f_email                = getParam("f_email");
$f_senha                = getParam("f_senha");
$f_acessos              = getParam("f_acessos");
$f_ativo                = getParam("f_ativo");

if ($f_ativo == "on") {
    $f_ativo = "1";
} else {
    $f_ativo = "0";
}

$dados = array(
    "id"            => $cad_usuarios_id,
    "nome"          => $f_nome,
    "usuario"       => $f_usuario,
    "email"         => $f_email,
    "status"        => $f_ativo,
);

if ($f_senha != "") {
    $dados["senha"] = md5($f_senha);
}

if (!empty($cad_usuarios_id)) {
    $sql_update = "
		UPDATE cad_usuarios SET
            nome = :nome,
            usuario = :usuario,
			email = :email,
			status = :status
		WHERE
			id = :id
		";

    if ($f_senha != "") {
        $sql_update = "
            UPDATE cad_usuarios SET
                nome = :nome,
                usuario = :usuario,
                senha = :senha,
                email = :email,
                status = :status
            WHERE
                id = :id
            ";
    }

    try {
        $conn->prepare($sql_update)->execute($dados);
        $lastInsertId = $cad_usuarios_id;
        $actionText = "Alteração efetuada com sucesso";
        $tipo = 'success';
    } catch (PDOException $e) {
        $actionText = "Erro ao alterar";
        $tipo = 'error';
        writeLogs("==== ".__FILE__." ====", "error");
		writeLogs("Action: UPDATE SQL", "error");
		writeLogs(print_r($e, true), "error");
		writeLogs(printSQL($sql_update, $dados, true), "error");
    }
} else {
    $dados["uniqid"] = uniqIdNew();

    $sql_insert = "
			INSERT INTO cad_usuarios (
				id, 
				nome, 
				usuario, 
				senha, 
				email,
                uniqid,
				status
			) VALUES (
				:id, 
				:nome, 
				:usuario, 
				:senha, 
				:email,
                :uniqid, 
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
        writeLogs("==== ".__FILE__." ====", "error");
		writeLogs("Action: Insert SQL", "error");
		writeLogs(print_r($e, true), "error");
		writeLogs(printSQL($sql_insert, $dados, true), "error");
    }
}

// acessos
$sql_delete = "DELETE FROM usuarios_has_menu WHERE cad_usuarios_id = " . $lastInsertId;
$conn->query($sql_delete);

for ($x = 0; $x < COUNT($f_acessos); $x++) {
    $dados = array(
        "cad_usuarios_id"      => $lastInsertId,
        "adm_menu_id"          => $f_acessos[$x],
    );
    $sql_insert = "
			INSERT INTO usuarios_has_menu (
				cad_usuarios_id,
                adm_menu_id
			) VALUES (
				:cad_usuarios_id, 
				:adm_menu_id
			)";
    $conn->prepare($sql_insert)->execute($dados);
}

setAlert($actionText, $tipo);
redirect("usuariosList.php");
