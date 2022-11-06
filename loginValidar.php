<?php
require_once("./inc/common.php");

$f_usuario       = getParam("f_usuario");
$f_senha         = md5(getParam("f_senha"));

$query = new sqlQuery();
$query->addTable("cad_usuarios");
$query->addcolumn("uniqid");
$query->addWhere("usuario", "=", "'" . $f_usuario . "'");
$query->addWhere("senha", "=", "'" . $f_senha . "'");
$query->addWhere("status", "=", 1);
$query->setLimit(1);

foreach ($conn->query($query->getSQL()) as $row) {
    setSession('SYSGER', $row["uniqid"]);
}

if (getDbValue($query->getCount()) != 0) {
    redirect("index.php");
} else {
    $actionText = "Erro ao logar, tente novamente";
    $tipo = "error";
    setAlert($actionText, $tipo);
    redirect("login.php");
}
