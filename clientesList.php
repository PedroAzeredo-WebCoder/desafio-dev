<?php
require_once("./inc/common.php");
checkAccess("clientesList");

$pagination = new Pagination();

$table = new Table();
$table->cardHeader(btn("Novo", "clientesCad.php"));
$table->addHeader("ID",                "text-center", "col-1");
$table->addHeader("Nome");
$table->addHeader("E-mail");
$table->addHeader("Celular",           "text-center", "col-3");
$table->addHeader("Status",     "text-center", "col-1", false);
$table->addHeader("Ação",       "text-center", "col-1", false);

$query = new sqlQuery();
$query->addTable("cad_clientes");
$query->addcolumn("id");
$query->addcolumn("nome");
$query->addcolumn("email");
$query->addcolumn("celular");
$query->addcolumn("status");

$f_searchTableStatus = getParam("f_searchTableStatus");
if ($f_searchTableStatus || $f_searchTableStatus == "0") {
    $query->addWhere("status", "=", "'0'");
} else {
    $query->addWhere("status", "=", "'1'");
}

$query->setLimit(PAGITATION, $pagination->startLimit());

$pagination->setSQL($query->getCount());

if ($conn->query($query->getSQL()) && getDbValue($query->getCount()) != 0) {
    foreach ($conn->query($query->getSQL()) as $row) {

        if ($row["status"] == 1) {
            $status = badge("Ativo", "success");
        } else {
            $status = badge("Inativo", "danger");
        }

        $celular = "";
        $email = "";
        if (!empty($row["celular"])) {
            $celular = "<a href='https://wa.me/" . str_replace(array("(", ")", "-", "+", " "), "", $row["celular"]) . "' target='_blank'>" . $row["celular"] . "</a>";
        }
        if (!empty($row["email"])) {
            $email = "<a href='mailto:" . $row["email"] . "' target='_blank'>" . $row["email"] . "</a>";
        }

        $table->addCol($row["id"], "text-center");
        $table->addCol($row["nome"]);
        $table->addCol($email);
        $table->addCol($celular, "text-center");
        $table->addCol($status, "text-center");
        $table->addCol(btn("<i class='fa-solid fa-gear'></i>", ["clientesCad.php", ["cad_clientes_id" => $row["id"]]], NULL, "btn-sm"), "text-center");
        $table->endRow();
    }
} else {
    $table->addCol("Nenhum registro encontrado", "text-center", 5);
    $table->endRow();
}

$template = new Template("Listagem de Clientes");
$template->addBreadcrumb("Home", "index.php");
$template->addContent($table->writeHtml());
$template->addContent($pagination->writeHtml());
$template->writeHtml();
