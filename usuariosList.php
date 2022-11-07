<?php
require_once("./inc/common.php");
checkAccess("usuariosList");



$table = new Table();
$table->cardHeader(btn("Novo", "usuariosCad.php"));
$table->addHeader("ID",         "text-center", "col-1");
$table->addHeader("Nome");
$table->addHeader("Usuário",    "text-center", "col-2");
$table->addHeader("E-mail",     "text-center", "col-2");
$table->addHeader("Status",     "text-center", "col-1", false);
$table->addHeader("Ação",       "text-center", "col-1", false);

$query = new sqlQuery();
$query->addTable("cad_usuarios");
$query->addcolumn("id");
$query->addcolumn("nome");
$query->addcolumn("email");
$query->addcolumn("usuario");
$query->addcolumn("status");

$f_searchTableStatus = getParam("f_searchTableStatus");
if ($f_searchTableStatus || $f_searchTableStatus == "0") {
    $query->addWhere("status", "=", "'0'");
} else {
    $query->addWhere("status", "=", "'1'");
}





if ($conn->query($query->getSQL())  && getDbValue($query->getCount()) != 0) {
    foreach ($conn->query($query->getSQL()) as $row) {
        if ($row["status"] == 1) {
            $status = badge("Ativo", "success");
        } else {
            $status = badge("Inativo", "danger");
        }

        if (!empty($row["email"])) {
            $email = "<a href='mailto:" . $row["email"] . "' target='_blank'>" . $row["email"] . "</a>";
        }

        $table->addCol($row["id"], "text-center");
        $table->addCol($row["nome"]);
        $table->addCol($row["usuario"], "text-center");
        $table->addCol($email);
        $table->addCol($status, "text-center");
        $table->addCol(btn("<i class='fa-solid fa-gear'></i>", ["usuariosCad.php", ["cad_usuarios_id" => $row["id"]]], NULL, "btn-sm"), "text-center");
        $table->endRow();
    }
} else {
    $table->addCol("Nenhum registro encontrado", "text-center", 7);
    $table->endRow();
}

$template = new Template("Listagem de Usuários");
$template->addBreadcrumb("Home", "index.php");
$template->addContent($table->writeHtml());

$template->writeHtml();
