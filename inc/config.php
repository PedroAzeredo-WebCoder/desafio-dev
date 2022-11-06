<?php

/**
 * @package configuration
 * @version 1.0.0
 * @author pedro-azeredo <pedro.azeredo93@gmail.com>
 */

/**
 * @package configuration
 * @subpackage html
 */
define("TITTLE", "Desafio Dev");
define("META", [
    "description" => "",
    "author" => "Pedro Azeredo",
    "icon" => "./app-assets/img/logo.ico",
]);

/**
 * @package configuration
 * @subpackage database
 */

if ($_SERVER["HTTP_HOST"] == "localhost") {
    define("DB_DATABASE", "pazeredo_desafio");
    define("DB_HOST", "localhost");
    define("DB_USER", "root");
    define("DB_PASSWORD", "");
}

/**
 * @package condiguration
 * @subpackage files
 */
define("PATH_UPLOADS", "uploads/");
define("PATH_LOGS", "logs/");
