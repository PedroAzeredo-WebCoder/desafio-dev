<?php
error_reporting(E_ERROR);

/**
 * @package common
 * @version 1.0.0
 * @author pedro-azeredo <pedro.azeredo93@gmail.com>
 */

session_start();

/**
 * @package common
 * @subpackage third-itens
 */
require_once("./inc/config.php"); 						// configuration
require_once("./inc/class.template.php"); 				// template.class
require_once("./inc/class.table.php"); 					// table.class
require_once("./inc/class.form.php"); 					// form.class
require_once("./inc/class.sqlQuery.php"); 				// query.class
require_once("./inc/class.bootstrap.php"); 				// bootstrap functions
require_once("./inc/connection.php");					// connection mysql

// função de redirect em JS
function redirect($url)
{
	if ($url == 'volta') {
		$url = $_SERVER["HTTP_REFERER"];
	}
	echo "
			<script>
				window.location = '" . $url . "';
			</script>
		";
	die();
}

// funcao para pegar parametros
function getParam($name, $encriptado = false)
{
	if ($encriptado == false) {
		if ($_POST[$name] != "") {
			return $_POST[$name];
		} else {
			if ($_GET[$name] != "") {
				return $_GET[$name];
			}
		}
	} else {
		$e = $_GET[$name];
		$getIn = strrev(base64_decode(strrev($e)));
		$explodeGet = explode("&", $getIn);
		$out = array();
		for ($x = 0; $x < COUNT($explodeGet); $x++) {
			$explodeVal = explode("=", $explodeGet[$x]);
			$out[$explodeVal[0]] = $explodeVal[1];
		}

		return $out;
	}
}

function getFileParam($name)
{
	return $_FILES[$name];
}

// sessoes  setar e pegar
function setSession($name, $value)
{
	$_SESSION["sistema"][$name] = $value;
}

function getSession($name)
{
	return $_SESSION["sistema"][$name];
}

// funcao para testar login na página
// function checkAccess($arquivo = NULL)
// {
// 	if ($arquivo != NULL) {
// 		$test = getDbValue("SELECT id FROM cad_usuarios WHERE uniqid = '" . getSession('SYSGER') . "'");
// 		if ($test != "") {
// 			$test2 = getDbValue("SELECT COUNT(*) FROM usuarios_has_menu WHERE cad_usuarios_id = " . $test . " AND adm_menu_id = (SELECT id FROM adm_menu WHERE link = '" . $arquivo . ".php' LIMIT 1)");
// 			if ($test2 == 0) {
// 				setSession("SYSGER", "");
// 				redirect('login.php');
// 			}
// 		}
// 	} else {
// 		$test = getDbValue("SELECT id FROM cad_usuarios WHERE uniqid = '" . getSession('SYSGER') . "'");
// 		if ($test == "") {
// 			setSession("SYSGER", "");
// 			redirect('login.php');
// 		}
// 	}
// }

/*****************************************************************************************************
		retorna o valor de um campo através de expressão sql
 */
function getDbValue($sql)
{
	$conn = new PDO("mysql:host=" . DB_HOST . ";dbname=" . DB_DATABASE, DB_USER, DB_PASSWORD) or print($conn->errorInfo());
	foreach ($conn->query($sql) as $row) {
		return $row[0];
	}
}


// funcao para setar erro
function setAlert($mensagem, $tipo)
{
	setSession('alerta_mensagem',	$mensagem);
	setSession('alerta_tipo',		$tipo);
}

//funcao para mostrar alerta
function getAlert()
{
	if (getSession('alerta_mensagem') != "") {
		$out = "
			<script>
			jQuery(function($){
				swal({
					title: '" . getSession('alerta_mensagem') . "',
					icon: '" . getSession('alerta_tipo') . "',
					timer: 3000,
				});
			});
			</script>
			";
		setSession('alerta_mensagem',	'');
		setSession('alerta_tipo',		'');
		return $out;
	}
}

// funcao para arrumar data BR -> US
function ChangeDate($date)
{
	$str = explode(" ", $date);
	$data = implode("-", array_reverse(explode("/", $str[0])));
	$time = $str[1];
	return $data . " " . $time . ":00";
}

// funcao para arrumar data BR -> US
function DateToBR($date)
{
	$str = explode(" ", $date);
	$data = implode("/", array_reverse(explode("-", $str[0])));
	$time = $str[1];
	return $data . " " . $time;
}

// funcao para saber qual script esta sendo executado
function QuemSou($url = null)
{
	if ($url == NULL) {
		$url = $_SERVER["SCRIPT_FILENAME"];
	}
	$st = array_reverse(explode("/", $url));
	$st = explode("_", $st[0]);
	return $st[0];
}

// funcao para criar senha randomica
function criaSenha($tamanho = 8, $maiusculas = true, $numeros = true, $simbolos = false)
{
	$lmin 		= 'abcdefghijklmnopqrstuvwxyz';
	$lmai 		= 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
	$num 		= '1234567890';
	$simb 		= '!@#$%*-';
	$retorno 	= '';
	$caracteres = '';

	$caracteres .= $lmin;
	if ($maiusculas) $caracteres .= $lmai;
	if ($numeros) $caracteres .= $num;
	if ($simbolos) $caracteres .= $simb;

	$len = strlen($caracteres);
	for ($n = 1; $n <= $tamanho; $n++) {
		$rand = mt_rand(1, $len);
		$retorno .= $caracteres[$rand - 1];
	}
	return $retorno;
}

/***************************************************************************
		Função para formatar qualquer valor
 */
function mascara($format, $value)
{
	if ($format == "") {
		$out = $value;
	} else {
		$out = "";
		$j = 0;
		for ($i = 0; $i < strlen($format); $i++) {
			if ($format[$i] == "9" or $format[$i] == "X") {
				$out .= $value[$j];
				$j++;
			} else {
				$out .= $format[$i];
			}
		}
	}
	return $out;
}

// funcao de uniqueID
function uniqIdNew()
{
	return md5(uniqid(rand(), true));
}

// funcao para criar slugs
function slug($string)
{
	$baseCaracters = array(
		'Š' => 'S', 'š' => 's', 'Ð' => 'Dj', '' => 'Z', '' => 'z', 'À' => 'A', 'Á' => 'A', 'Â' => 'A', 'Ã' => 'A', 'Ä' => 'A',
		'Å' => 'A', 'Æ' => 'A', 'Ç' => 'C', 'È' => 'E', 'É' => 'E',  'Ê' => 'E', 'Ë' => 'E', 'Ì' => 'I', 'Í' => 'I', 'Î' => 'I',
		'Ï' => 'I', 'Ñ' => 'N', 'Ń' => 'N', 'Ò' => 'O', 'Ó' => 'O',  'Ô' => 'O', 'Õ' => 'O', 'Ö' => 'O', 'Ø' => 'O', 'Ù' => 'U', 'Ú' => 'U',
		'Û' => 'U', 'Ü' => 'U', 'Ý' => 'Y', 'Þ' => 'B', 'ß' => 'Ss', 'à' => 'a', 'á' => 'a', 'â' => 'a', 'ã' => 'a', 'ä' => 'a',
		'å' => 'a', 'æ' => 'a', 'ç' => 'c', 'è' => 'e', 'é' => 'e',  'ê' => 'e', 'ë' => 'e', 'ì' => 'i', 'í' => 'i', 'î' => 'i',
		'ï' => 'i', 'ð' => 'o', 'ñ' => 'n', 'ń' => 'n', 'ò' => 'o',  'ó' => 'o', 'ô' => 'o', 'õ' => 'o', 'ö' => 'o', 'ø' => 'o', 'ù' => 'u',
		'ú' => 'u', 'û' => 'u', 'ü' => 'u', 'ý' => 'y', 'ý' => 'y',  'þ' => 'b', 'ÿ' => 'y', 'ƒ' => 'f',
		'ă' => 'a', 'î' => 'i', 'â' => 'a', 'ș' => 's', 'ț' => 't',  'Ă' => 'A', 'Î' => 'I', 'Â' => 'A', 'Ș' => 'S', 'Ț' => 'T',
	);
	$string = strtr(trim($string), $baseCaracters);
	$string = str_replace("-", "", $string);
	$string = str_replace(" ", "_", $string);

	return strtolower($string);
}

function br_DiaSemana($w)
{
	$dia = array(
		"1" => "DOM",
		"2" => "SEG",
		"3" => "TER",
		"4" => "QUA",
		"5" => "QUI",
		"6" => "SEX",
		"7" => "SÁB",
	);
	return $dia[$w];
}