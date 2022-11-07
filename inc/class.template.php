<?php

/**
 * @package template
 * @subpackage class_base
 * @author pedro-azeredo <pedro.azeredo93@gmail.com>
 */

class Template
{

    private $getCss;
    private $getJs;
    private $breadcrumb;
    private $setTittle;
    private $content;

    public function __construct($tittle = NULL)
    {
        $this->setTemplate();
        $this->setTittle = $tittle;
    }

    /**
     * setTemplate
     * Setar template default a ser utilizado na execução da classe
     *
     * @param string $template
     */
    public function setTemplate(string $template = "index")
    {
        if (file_exists("./inc/templates/template." . $template . ".php")) {
            $this->setTemplate = "./inc/templates/template." . $template . ".php";
            return;
        }
        $this->setTemplate = "./inc/templates/template.index.php";
    }

    private function getTemplate()
    {
        if (file_exists($this->setTemplate)) {
            $outString = implode("", file($this->setTemplate));
            return $outString;
        }
        $outString = implode("", file($this->setTemplate));
        return $outString;
    }

    /**
     * addCss
     * Responsável por registrar CSSs no template
     *
     * @param mixed $cssString
     */
    public function addCss(string $cssString)
    {
        if (file_exists($cssString)) {
            $this->getCss .= "<link href='" . $cssString . "' rel='stylesheet' type='text/css'>";
        } else {
            $this->getCss .= "<s>" . $cssString . "</style>";
        }
    }

    /**
     * addJs
     * Responsável por registrar JSs no template
     *
     * @param mixed $cssString
     */
    public function addJs(string $jsString)
    {
        if (file_exists($jsString)) {
            $this->getJs .= "<link href='" . $jsString . "' rel='stylesheet' type='text/css'>";
        } else {
            $this->getJs .= "<script>" . $jsString . "</script>";
        }
    }

    /**
     * addBreadcrumb
     * Cria código para definir breadcrump (caminho) da tela
     *
     * @param string $local
     * @param string $active
     * @param string $url
     */
    public function addBreadcrumb(string $local, string $url = NULL)
    {
        if ($url != NULL) {
            $content = "<li class='breadcrumb-item'><a href='./" . $url . "'>" . $local . "</a></li>";
        } else {
            $content = "<li class='breadcrumb-item active' aria-current='page'>" . $local . "</li>";
        }

        $this->breadcrumb .= $content;
    }

    /**
     * getBreadcrumb
     * Responsável por criar o HTML de breadcrumpb (caminho)
     *
     * @return string
     */
    private function getBreadcrumb(): string
    {

        // incluindo no breadcrumpb automaticamente a página atual
        // $this->addBreadcrumb($this->setTittle);

        $outHtml  = "<nav aria-label='breadcrumb'><ol class='breadcrumb'>";
        $outHtml .= $this->breadcrumb;
        $outHtml .= "</ol></nav>";
        return $outHtml;
    }

    /**
     * addContent
     * Inclusão de conteúdo no template utilizado
     *
     * @param string $content
     * @param string $card
     */
    public function addContent(string $content, string $card = NULL)
    {
        if ($card == true) {
            $this->content .= "
                <div class='card'>
                    <div class='card-body'>
                        <div class='card-text'>
                            " . $content . "
                        </div>
                    </div>
                </div>
                ";
        } else {
            $this->content .= $content;
        }
    }

    /**
     * writeHtml
     * Imprimir HTML montado após conclusão das definições do template
     *
     * @return void
     */
    public function writeHtml()
    {
        $outHtml = $this->__replace($this->getTemplate(),   "[%description%]",       META["description"]);
        $outHtml = $this->__replace($outHtml,               "[%author%]",            META["author"]);
        $outHtml = $this->__replace($outHtml,               "[%icon%]",              META["icon"]);
        $outHtml = $this->__replace($outHtml,               "[%title%]",             TITTLE);
        $outHtml = $this->__replace($outHtml,               "[%title_page%]",        $this->setTittle);
        $outHtml = $this->__replace($outHtml,               "[%css%]",               $this->getCss);
        $outHtml = $this->__replace($outHtml,               "[%breadcrumb%]",        $this->getBreadcrumb());
        $outHtml = $this->__replace($outHtml,               "[%include_sidebar%]",   $this->getSidebar());
        $outHtml = $this->__replace($outHtml,               "[%include_topbar%]",    $this->getTopbar());
        $outHtml = $this->__replace($outHtml,               "[%include_content%]",   $this->content);
        $outHtml = $this->__replace($outHtml,               "[%js%]",                $this->getJs);
        $outHtml = $this->__replace($outHtml,               "[%sweetalert%]",        getAlert());
        echo $outHtml;
    }

    /**
     * getSidebar
     * Responsável pela montagem do sideBar (menu)
     *
     * @return string
     */
    private function getSidebar(): string
    {

        $conn = new PDO("mysql:host=" . DB_HOST . ";dbname=" . DB_DATABASE, DB_USER, DB_PASSWORD) or print($conn->errorInfo());

        $sql = "
                SELECT
                    id,
                    adm_menu_id,
                    icone,
                    nome,
                    link,
                    status,
                    (SELECT COUNT(*) FROM adm_menu as AA WHERE AA.adm_menu_id = adm_menu.id AND status = 1) as subItens
                FROM
                    adm_menu
                WHERE
                    status = 1
                AND adm_menu_id IS NULL
                AND (
                    (SELECT COUNT(*) FROM adm_menu as AA WHERE AA.adm_menu_id = adm_menu.id AND status = 1 AND id IN (SELECT adm_menu_id FROM usuarios_has_menu WHERE cad_usuarios_id = (SELECT id FROM cad_usuarios WHERE uniqid = '" . getSession("SYSGER") . "'))) > 0
                OR  id IN (SELECT adm_menu_id FROM usuarios_has_menu WHERE cad_usuarios_id = (SELECT id FROM cad_usuarios WHERE uniqid = '" . getSession("SYSGER") . "'))   
                )
            ";

        $menu = array();
        if ($conn->query($sql)) {
            foreach ($conn->query($sql) as $row) {
                if ($row["subItens"] == 0) {
                    $menu[] = "
                            <li class='list-group-item nav-item'>
                                <a class='d-flex align-items-center nav-link' href='" . $row["link"] . "'>
                                    <i class='fa-solid fa-" . $row["icone"] . "'></i>
                                    <span class='text-truncate' data-i18n='" . $row["nome"] . "'>" . $row["nome"] . "</span>
                                </a>
                            </li>
                        ";
                } else {

                    $sqlSubItens = "
                            SELECT
                                id,
                                nome,
                                link
                            FROM
                                adm_menu
                            WHERE
                                status = 1
                            AND adm_menu_id = " . $row["id"] . "
                            AND id IN (SELECT adm_menu_id FROM usuarios_has_menu WHERE cad_usuarios_id = (SELECT id FROM cad_usuarios WHERE uniqid = '" . getSession("SYSGER") . "'))
                        ";
                    $menuSubItens = array();
                    if ($conn->query($sqlSubItens)) {
                        foreach ($conn->query($sqlSubItens) as $rowSubItens) {
                            $menuSubItens[] = "
                                <li class='nav-item'>
                                    <a class='d-flex align-items-center nav-link' href='" . $rowSubItens["link"] . "'>
                                        <i class='fa-solid fa-circle-dot'></i>
                                        <span class='text-truncate' data-i18n='" . $rowSubItens["nome"] . "'>" . $rowSubItens["nome"] . "</span>
                                    </a>
                                </li>";
                        }
                    }

                    $menu[] = "
                            <li class='list-group-item nav-item'>
                                <a class='d-flex align-items-center nav-link' href='#'>
                                    <i class='fa-solid fa-" . $row["icone"] . "'></i>
                                    <span class='menu-title text-truncate' data-i18n='" . $row["nome"] . "'>" . $row["nome"] . "</span>
                                </a>
                                    
                                <ul class='navbar-nav'>
                                    " . implode('', $menuSubItens) . "
                                </ul>
                            </li>
                        ";
                }
            }
        }

        $outHtml = "
                    <ul class='list-group list-group-flush navbar-nav' id='main-menu-navigation' data-menu='menu-navigation'>
                        " . implode("", $menu) . "
                    </ul>
            ";
        return $outHtml;
    }

    /**
     * gettopbar
     * Responsável por criar o topbar
     *
     * @return void
     */
    private function getTopbar()
    {
        $outHtml = "
            <nav class='fixed-top'>
                <div class='card'>
                    <div class='row justify-content-between'>
                        <div class='col'>
                            <div class='dropdown'>
                                <button class='btn btn-secondary dropdown-toggle' type='button' href='#' data-bs-toggle='dropdown' aria-expanded='false'>
                                    " . getDbValue("SELECT nome FROM cad_usuarios WHERE uniqid = '" . getSession("SYSGER") . "'") . "
                                </button>
                                <ul class='dropdown-menu'>
                                    <li><a class='dropdown-item' href='loginSair.php'><i class='me-50 fa-solid fa-power-off'></i> Sair</a></li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </nav>
            ";

        return $outHtml;
    }

    /**
     * __replace
     * Responsável por fazer o str_replace no arquivo template
     *
     * @param [type] $string
     * @param [type] $search
     * @param [type] $replace
     * @return string
     */
    private function __replace($string, $search, $replace): string
    {
        $replaced = "";
        if (!is_array($replace)) {
            $replace = array($replace);
        }
        $replaced = str_replace($search, implode("\r\n", $replace), $string);
        return $replaced;
    }
}
