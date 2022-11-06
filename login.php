<?php

    require_once("./inc/common.php");

    $form = new Form("loginValidar.php");
    $form->addField(textField("Usuário"));
    $form->addField(passField("Senha"));
    $form->addField(submitBtn("Acessar"));

    $template = new Template("Login");
    $template->setTemplate("login");
    $template->addContent($form->writeHtml());
    $template->writeHtml();
