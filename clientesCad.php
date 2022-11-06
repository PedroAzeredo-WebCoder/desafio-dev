<?php
require_once("./inc/common.php");
checkAccess("clientesList");

$e = getParam("e", true);
$cad_clientes_id = $e["cad_clientes_id"];

$f_status = "checked";
if (!empty($cad_clientes_id)) {
    $query = new sqlQuery();
    $query->addTable("cad_clientes");
    $query->addcolumn("id");
    $query->addcolumn("nome");
    $query->addcolumn("documento");
    $query->addcolumn("tipo");
    $query->addcolumn("email");
    $query->addcolumn("celular");
    $query->addcolumn("telefone");
    $query->addcolumn("cep");
    $query->addcolumn("estado");
    $query->addcolumn("cidade");
    $query->addcolumn("bairro");
    $query->addcolumn("logradouro");
    $query->addcolumn("numero");
    $query->addcolumn("complemento");
    $query->addcolumn("senha");
    $query->addcolumn("status");
    $query->addWhere("id", "=", $cad_clientes_id);

    foreach ($conn->query($query->getSQL()) as $row) {
        $f_nome = $row["nome"];
        $f_documento = mascara(strlen($row["documento"]) == 14 ? "XXX.XXX.XX/XXXX-XX" : "XXX.XXX.XXX-XX", $row["documento"]);
        $f_tipo = $row["tipo"];
        $f_email = $row["email"];
        $f_celular = $row["celular"];
        $f_telefone = $row["telefone"];
        $f_cep = $row["cep"];
        $f_estado = $row["estado"];
        $f_cidade = $row["cidade"];
        $f_bairro = $row["bairro"];
        $f_logradouro = $row["logradouro"];
        $f_numero = $row["numero"];
        $f_complemento = $row["complemento"];
        $f_status = "";

        if ($row["status"] == 1) {
            $f_status = "checked";
        }
    }
}

$form = new Form("clientesCadSave.php");
$form->addField(hiddenField($cad_clientes_id, "cad_clientes_id"));
$form->addField(textField("Nome", $f_nome));
$form->addField(textField("Documento", $f_documento));
$form->addField(emailField("E-mail", $f_email));
$form->addField(telField("Celular", $f_celular));
$form->addField(telField("Telefone", $f_telefone));
$form->addField(textField("CEP", $f_cep));
$form->addField(textField("Estado", $f_estado));
$form->addField(textField("Cidade", $f_cidade));
$form->addField(textField("Bairro", $f_bairro));
$form->addField(textField("Logradouro", $f_logradouro));
$form->addField(textField("Número", $f_numero));
$form->addField(textField("Complemento", $f_complemento));
$form->addField(passField("Senha", $f_senha));
$form->addField(checkboxField("Ativo", $f_status));
$form->addField(submitBtn("Salvar"));

//pagamentos
$pagination = new Pagination();

$pagamentos = new Table();
$pagamentos->addHeader("ID",         "text-center", "col-1");
$pagamentos->addHeader("Data",      "text-center", "col-1", false);
$pagamentos->addHeader("ID do Pagamento", "text-center", "col-2", false);
$pagamentos->addHeader("Descrição",       "text-center", null, false);
$pagamentos->addHeader("Valor",      "text-center", "col-1", false);
$pagamentos->addHeader("Dt. Ref",       "text-center", "col-1", false);
$pagamentos->addHeader("Plano",       "text-center", "col-1", false);
$pagamentos->addHeader("Status",       "text-center", "col-1", false);


$pay = new sqlQuery();
$pay->addTable("asaas_cad_pagamentos");
$pay->addcolumn("id");
$pay->addcolumn("DATE_FORMAT(dateCreated, '%d/%m/%Y') as dateCreated");
$pay->addcolumn("payId");
$pay->addcolumn("description");
$pay->addcolumn("value");
$pay->addcolumn("DATE_FORMAT(dueDate, '%d/%m/%Y') as dueDate");
$pay->addcolumn("externalReference");
$pay->addcolumn("statusDesc");
$pay->addWhere("cad_clientes_id", "=", "'" . $cad_clientes_id . "'");
$pay->addOrder("id", "DESC");

$pay->setLimit(PAGITATION, $pagination->startLimit());

$pagination->setSQL($pay->getCount());

if ($conn->query($pay->getSQL()) && getDbValue($pay->getCount()) != 0) {
    foreach ($conn->query($pay->getSQL()) as $row) {

        if ($row["statusDesc"] == 'CONFIRMED') {
            $status = badge("Confirmado", "success");
        } else {
            $status = badge("Recusado", "danger");
        }

        $pagamentos->addCol($row["id"], "text-center");
        $pagamentos->addCol($row["dateCreated"], "text-center");
        $pagamentos->addCol($row["payId"]);
        $pagamentos->addCol($row["description"]);
        $pagamentos->addCol("R$ " . number_format($row["value"], 2, ",", "."), "text-end");
        $pagamentos->addCol($row["dueDate"]);
        $pagamentos->addCol($row["externalReference"], "text-center");
        $pagamentos->addCol($status);
        $pagamentos->endRow();
    }
} else {
    $pagamentos->addCol("Nenhum registro encontrado", "text-center", 7);
    $pagamentos->endRow();
}

$tabs = new Tabs();
$tabs->addTab("Dados Pessoais", $form->writeHtml(), true);
$tabs->addTab("Pagamentos", $pagamentos->writeHtml());


$template = new Template("Cadastro de Clientes");
$template->addBreadcrumb("Home", "index.php");
$template->addBreadcrumb("Listagem de Clientes", "clientesList.php");
$template->addContent($tabs->writeHtml(), true);
$template->writeHtml();
