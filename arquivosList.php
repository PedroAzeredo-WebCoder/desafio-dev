<?php
require_once("./inc/common.php");
checkAccess("arquivosList");

$table = new Table();
$table->cardHeader(btn("Novo", "arquivosCad.php"));
$table->addHeader("ID",                "text-center", "col-1");
$table->addHeader("Arquivo");

$query = new sqlQuery();
$query->addTable("cad_arquivos");
$query->addcolumn("id");
$query->addcolumn("arquivo");

if ($conn->query($query->getSQL()) && getDbValue($query->getCount()) != 0) {
    foreach ($conn->query($query->getSQL()) as $row) {
        $table->addCol($row["id"], "text-center");
        $table->addCol($row["arquivo"]);
        $table->endRow();
    }
} else {
    $table->addCol("Nenhum registro encontrado", "text-center", 2);
    $table->endRow();
}

$template = new Template("Listagem de arquivos");
$template->addBreadcrumb("Home", "index.php");
$template->addContent($table->writeHtml());

$template->writeHtml();
