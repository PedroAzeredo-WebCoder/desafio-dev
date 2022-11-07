<?php
require_once("./inc/common.php");
checkAccess("arquivosList");

$e = getParam("e", true);
$cad_arquivos_id = $e["cad_arquivos_id"];

if (!empty($cad_arquivos_id)) {
    $query = new sqlQuery();
    $query->addTable("cad_arquivos");
    $query->addcolumn("id");
    $query->addcolumn("arquivo");
    $query->addWhere("id", "=", $cad_arquivos_id);

    foreach ($conn->query($query->getSQL()) as $row) {
        $f_arquivo = $row["arquivo"];
    }
}

$form = new Form("arquivosCadSave.php");
$form->addField(hiddenField($cad_arquivos_id, "cad_arquivos_id"));
$form->setUpload(true);
$form->addField(fileField("Arquivo", $f_arquivo));
$form->addField(submitBtn("Salvar"));

$template = new Template("Cadastro de Arquivos");
$template->addBreadcrumb("Home", "index.php");
$template->addBreadcrumb("Listagem de Arquivos", "arquivosList.php");
$template->addContent($form->writeHtml(), true);
$template->writeHtml();
