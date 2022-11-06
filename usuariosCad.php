<?php
require_once("./inc/common.php");
checkAccess("usuariosList");

$e = getParam("e", true);
$cad_usuarios_id = $e["cad_usuarios_id"];

$f_acessos = array();

if ($cad_usuarios_id) {
    $query = new sqlQuery();
    $query->addTable("cad_usuarios");
    $query->addcolumn("id");
    $query->addcolumn("nome");
    $query->addcolumn("email");
    $query->addcolumn("usuario");
    $query->addcolumn("status");
    $query->addcolumn("(SELECT GROUP_CONCAT(adm_menu_id) FROM usuarios_has_menu WHERE cad_usuarios_id = cad_usuarios.id) as acessos");
    $query->addWhere("id", "=", $cad_usuarios_id);

    foreach ($conn->query($query->getSQL()) as $row) {
        $f_nome = $row["nome"];
        $f_usuario = $row["usuario"];
        $f_email = $row["email"];
        $f_acessos = explode(",", $row["acessos"]);
        $f_status = "";

        if ($row["status"] == 1) {
            $f_status = "checked";
        }
    }
}

$queryAcessos = new sqlQuery();
$queryAcessos->addTable("adm_menu");
$queryAcessos->addcolumn("id");
$queryAcessos->addcolumn("nome");
$queryAcessos->addWhere("status", "=", 1);
$queryAcessos->addWhere("adm_menu_id", "IS NULL");

foreach ($conn->query($queryAcessos->getSQL()) as $row_acessos) {
    $options_f_tipos_veiculos[] = array("id" => $row_acessos["id"], "name" => $row_acessos["nome"]);

    $queryAcessosSubMenu = new sqlQuery();
    $queryAcessosSubMenu->addTable("adm_menu");
    $queryAcessosSubMenu->addcolumn("id");
    $queryAcessosSubMenu->addcolumn("nome");
    $queryAcessosSubMenu->addWhere("status", "=", 1);
    $queryAcessosSubMenu->addWhere("adm_menu_id", "=", $row_acessos["id"]);

    foreach ($conn->query($queryAcessosSubMenu->getSQL()) as $row_acessos) {
        $selected = "";
        if (in_array($row_acessos["id"], $f_acessos)) {
            $selected = "selected";
        }
        $options_f_tipos_veiculos[] = array("id" => $row_acessos["id"], "name" => "-- " . $row_acessos["nome"]);
    }
}

$form = new Form("usuariosCadSave.php");
$form->addField(fieldSet("hidden", NULL, NULL, "cad_usuarios_id", $cad_usuarios_id));
$form->addField(fieldSet("text", "Nome", "Pedro Azeredo", "nome", $f_nome, true, "^[a-zA-Z]{4,}(?: [a-zA-Z]+){0,2}$", false));
$form->addField(fieldSet("text", "Usuário", "pedro-azeredo", "usuario", $f_usuario, true, NULL, false));
$form->addField(fieldSet("email", "E-mail", "pedro@azeredo", "email", $f_email, true, NULL, false));
$form->addField(fieldSet("password", "Senha", NULL, "senha", $f_senha, true, NULL, false, "new-password"));
$form->addField(fieldSet("select", "Acessos", NULL, "acessos", $options_f_tipos_veiculos, true, NULL, false, NULL, "multiple"));
$form->addField(fieldSet("checkbox", "Ativo", NULL, "status", $f_status));

//$form->addField(hiddenField($cad_usuarios_id, "cad_usuarios_id"));
//$form->addField(textField("Nome", $f_nome));
//$form->addField(textField("Usuário", $f_usuario));
//$form->addField(emailField("E-mail", $f_email));
//$form->addField(passField("Senha"));
//$form->addField(listMultipleField("Acessos", $options_f_tipos_veiculos, $f_acessos));
//$form->addField(checkboxField("Ativo", $f_status));
$form->addField(submitBtn("Salvar"));

$template = new Template("Cadastro de Usuários");
$template->addBreadcrumb("Home", "index.php");
$template->addBreadcrumb("Listagem de Usuários", "usuariosList.php");
$template->addContent($form->writeHtml(), true);
$template->writeHtml();
