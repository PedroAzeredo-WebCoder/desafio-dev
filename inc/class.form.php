<?php

/**
 * @package fornm
 * @subpackage class_base
 * @author pedro-azeredo <pedro.azeredo93@gmail.com>
 */

class Form
{

    private $form;
    private $fields;
    private $upload;

    /**
     * Form function
     * Função responsavel por agrupar dados básicos de criação de uma tag FORM
     *
     * @param string $action
     * @param string $name
     * @param string $id
     * @param string $method
     */
    public function __construct(string $action, string $name = NULL, string $id = NULL, string $method = "POST")
    {
        $this->form["action"] = $action;
        $this->form["name"] = $name == NULL ? "formSubmit" : $name;
        $this->form["id"] = $id == NULL ? "formSubmit" : $id;
        $this->form["method"] = $method;
    }

    public function setUpload()
    {
        $this->upload = true;
    }

    /**
     * addField function
     * Função responsável por incluir novo campo no formulário
     *
     * @param string $field
     */
    public function addField(string $field)
    {
        $this->fields[] = $field;
    }

    /**
     * writeHtml
     * Imprimir HTML montado após conclusão das definições do template
     *
     * 
     */
    public function writeHtml()
    {
        if ($this->upload == true) {
            $upload = "enctype='multipart/form-data'";
        }
        $outHtml = "
                <form class='auth-login-form mt-2' name='" . $this->form["name"] . "' id='" . $this->form["id"] . "' action='" . $this->form["action"] . "' method='" . $this->form["method"] . "' " . $upload . ">
                    " . implode("", $this->fields) . "
                </form>
            ";
        return $outHtml;
    }
}

// funcoes responsaveis pelos campos dos formulários

/**
 * fieldSet function
 *
 * @param string $type
 * @param string $label
 * @param string $placeholder
 * @param string $name
 * @param string $value
 * @param bool   $required
 * @param string $pattern
 * @param bool   $disabled
 * @param bool   $autocomplete
 * @param string $attr
 * @param string $id
 * @param string $class
 * @param string $js
 * @return string
 */
function fieldSet(string $type, string $label = NULL, string $placeholder = NULL, string $name, $value, bool $required = false, string $pattern = NULL, bool $disabled = false, string $autocomplete = NULL, string $attr = NULL, string $id = NULL, string $class = NULL, string $js = NULL): string
{
    $slug = slug($label);
    $_name  = $name == NULL ? "f_" . $slug : "f_" . $name;
    $_id    = $id == NULL ? "id_" . $slug : $id;

    if ($required && $label) {
        $label = "<label for='" . $_id . "' class='form-label'>" . $label . " <span class='text-danger' data-bs-toggle='tooltip' data-bs-title='Este campo é obrigatório!'>*</span></label>";
        $required = 'required';
    } else {
        $label = "<label for='" . $_id . "' class='form-label d-none'>" . $name . "</label>";
    }

    if ($disabled != false) {
        $disabled = 'disabled';
    }

    if ($pattern != NULL) {
        $pattern = 'pattern="' . $pattern . '"';
    }

    if ($type == 'select') {

        if (is_array($value)) {
            $value[] = $value;

            $optionsBuscarPor = array();
            for ($z = 0; $z < COUNT($value); $z++) {
                // print_r($value);
                // die();
                $selected = "";
                if (in_array($value[$z]["id"], $value)) {
                    $selected = "selected";
                }
                $optionsBuscarPor[] = "<option " . $selected . " value='" . $value[$z]["id"] . "'>" . $value[$z]["name"] . "</option>";
            }
        }

        $input = "
                <select " . $attr . " name='" . $_name . "[]' id='" . $_id . "' class='form-select" . $class . "' " . $js . " " . $required . ">
                    " . implode("", $optionsBuscarPor) . "
                </select>
        ";
    } else if ($type == "checkbox") {
        $input = "
            <div class='mb-1 form-check form-switch mb-3' id='js_" . $slug . "'>
                <input type='" . $type . "' class='form-check-input' role='switch'  id='" . $_id . "' name='" . $_name . "' " . $value . ">
                <label for='" . $_id . "' class='custom-control-label'>" . $label . "</label>
            </div>
        ";
    } else {
        $input = "<input type='" . $type . "' class='form-control " . $class . "' name='" . $_name . "' id='" . $_id . "' placeholder='" . $placeholder . "' value='" . $value . "' " . $js . " " . $required . " " . $pattern . " autocomplete='" . $autocomplete . "' " . $attr . "/>";
    }

    $out = "
            <fieldset class='mb-1 " . $class . "' id='js_" . $slug . "' " . $disabled . ">
                " . $label . "
                <div class='field position-relative'>
                " . $input . "
                </div>
            </fieldset>
        ";

    return $out;
}

/**
 * emailField function
 *
 * @param string $placeholder
 * @param string $value
 * @param string $name
 * @param string $id
 * @param string $css
 * @param string $js
 * @return string
 */
function emailField(string $placeholder = NULL, string $value = NULL, string $name = NULL, string $id = NULL, string $css = NULL, string $js = NULL): string
{
    $slug = slug($placeholder);
    $_name  = $name == NULL ? "f_" . $slug : $name;
    $_id    = $id == NULL ? "id_" . $slug : $id;

    if ($placeholder) {
        $label = "<label for='" . $_id . "' class='form-label'>" . $placeholder . "</label>";
    }

    $out = "
            <div class='mb-1' id='js_" . $slug . "'>
                " . $label . "
                <input type='email' class='form-control " . $css . "' name='" . $_name . "' id='" . $_id . "' placeholder='" . $placeholder . "' value='" . $value . "' " . $js . "/>
            </div>
        ";

    return $out;
}

/**
 * textField function
 *
 * @param string $placeholder
 * @param string $value
 * @param string $name
 * @param string $id
 * @param string $css
 * @param string $js
 * @return string
 */
function textField(string $placeholder = NULL, string $value = NULL, string $name = NULL, bool $required = NULL, string $id = NULL, string $css = NULL, string $js = NULL): string
{
    $slug = slug($placeholder);
    $_name  = $name == NULL ? "f_" . $slug : $name;
    $_id    = $id == NULL ? "id_" . $slug : $id;

    $simb = '';
    $required = '';
    if ($required) {
        $simb = '<span class="text-danger">*</span>';
        $required = 'required';
    }

    if ($placeholder) {
        $label = "<label for='" . $_id . "' class='form-label'>" . $placeholder . $simb . "</label>";
    }

    $out = "
            <div class='mb-1' id='js_" . $slug . "'>
                " . $label . "
                <input type='text' class='form-control " . $css . "' name='" . $_name . "' id='" . $_id . "' placeholder='" . $placeholder . "' value='" . $value . "' " . $js . " " . $required . "/>
            </div>
        ";

    return $out;
}


/**
 * tel function
 *
 * @param string $placeholder
 * @param string $value
 * @param string $name
 * @param string $id
 * @param string $css
 * @param string $js
 * @return string
 */
function telField(string $placeholder = NULL, string $value = NULL, string $name = NULL, string $id = NULL, string $css = NULL, string $js = NULL): string
{
    $slug = slug($placeholder);
    $_name  = $name == NULL ? "f_" . $slug : $name;
    $_id    = $id == NULL ? "id_" . $slug : $id;

    if ($placeholder) {
        $label = "<label for='" . $_id . "' class='form-label'>" . $placeholder . "</label>";
    }

    $out = "
            <div class='mb-1' id='js_" . $slug . "'>
                " . $label . "
                <input type='tel' class='form-control " . $css . "' name='" . $_name . "' id='" . $_id . "' placeholder='" . $placeholder . "' value='" . $value . "' " . $js . "/>
            </div>
        ";

    return $out;
}

/**
 * urlField function
 *
 * @param string $placeholder
 * @param string $value
 * @param string $name
 * @param string $id
 * @param string $css
 * @param string $js
 * @return string
 */
function urlField(string $placeholder = NULL, string $value = NULL, string $name = NULL, string $id = NULL, string $css = NULL, string $js = NULL): string
{
    $slug = slug($placeholder);
    $_name  = $name == NULL ? "f_" . $slug : $name;
    $_id    = $id == NULL ? "id_" . $slug : $id;

    if ($placeholder) {
        $label = "<label for='" . $_id . "' class='form-label'>" . $placeholder . "</label>";
    }

    $out = "
            <div class='mb-1' id='js_" . $slug . "'>
                " . $label . "
                <input type='url' class='form-control " . $css . "' name='" . $_name . "' id='" . $_id . "' placeholder='" . $placeholder . "' value='" . $value . "' " . $js . "/>
            </div>
        ";

    return $out;
}

/**
 * passField function
 *
 * @param string $placeholder
 * @param string $value
 * @param string $name
 * @param string $id
 * @param string $css
 * @param string $js
 * @return string
 */
function passField(string $placeholder = NULL, string $value = NULL, string $name = NULL, string $id = NULL, string $css = NULL, string $js = NULL): string
{
    $slug = slug($placeholder);
    $_name  = $name == NULL ? "f_" . $slug : $name;
    $_id    = $id == NULL ? "id_" . $slug : $id;

    if ($placeholder) {
        $label = "<label for='" . $_id . "' class='form-label'>" . $placeholder . "</label>";
    }

    $out = "
            <div class='mb-1' id='js_" . $slug . "'>
                " . $label . "
                <div class='field position-relative'>
                    <input type='password' class='form-control " . $css . "' name='" . $_name . "' id='" . $_id . "' placeholder='" . $placeholder . "' value='" . $value . "' " . $js . " autocomplete='new-password'/>
                </div>
            </div>
        ";

    return $out;
}

/**
 * submitBtn function
 *
 * @param string $placeholder
 * @param string $css
 * @param string $js
 * @return string
 */
function submitBtn(string $placeholder, string $css = "btn-primary", string $js = NULL): string
{
    return "<button type='submit' class='btn " . $css . "' " . $js . ">" . $placeholder . "</button>";
}


function listField(string $placeholder = NULL, $content, string $value = NULL, string $name = NULL, string $id = NULL, string $css = NULL, string $js = NULL): string
{
    $slug = slug($placeholder);
    $_name  = $name == NULL ? "f_" . $slug : $name;
    $_id    = $id == NULL ? "id_" . $slug : $id;

    if ($placeholder) {
        $label = "<label for='" . $_id . "' class='form-label'>" . $placeholder . "</label>";
    }

    if (is_array($content)) {
        $optionsBuscarPor = array();
        for ($z = 0; $z < COUNT($content); $z++) {
            $selected = "";
            if ($content[$z]["id"] == $value) {
                $selected = "selected";
            }
            $optionsBuscarPor[] = "<option " . $selected . " value='" . $content[$z]["id"] . "'>" . $content[$z]["name"] . "</option>";
        }
    }

    $out = "
            <div class='mb-1' id='js_" . $slug . "'>
                " . $label . "
                <select name='" . $_name . "' id='" . $_id . "' class='form-select" . $css . "' " . $js . ">
                    <option value=''>-- Escolha --</option>
                    " . implode("", $optionsBuscarPor) . "
                </select>
            </div>
        ";

    return $out;
}

function hiddenField(string $value = NULL, string $name = NULL, string $id = NULL)
{
    $slug = slug($value);
    $_name  = $name == NULL ? "f_" . $slug : $name;
    $_id    = $id == NULL ? "id_" . $name : $id;

    return "<input type='hidden' name='" . $_name . "' id='" . $_id . "' value='" . $value . "'/>";
}

function checkboxField($placeholder, string $value = NULL, string $name = NULL, string $id = NULL, string $css = NULL, string $js = NULL): string
{
    $slug = slug($placeholder);
    $_name  = $name == NULL ? "f_" . $slug : $name;
    $_id    = $id == NULL ? "id_" . $slug  : $id;

    $out = "
            <div class='mb-1 form-check form-switch mb-3' id='js_" . $slug . "'>
                <input type='checkbox' class='form-check-input' id='" . $_id . "' name='" . $_name . "' " . $value . ">
                <label for='" . $_id . "' class='custom-control-label'>" . $placeholder . "</label>
            </div>
        ";

    return $out;
}

function listMultipleField(string $placeholder = NULL, $content, $value = NULL, string $name = NULL, bool $required = NULL, string $id = NULL, string $css = NULL, string $js = NULL): string
{
    $slug = slug($placeholder);
    $_name  = $name == NULL ? "f_" . $slug : $name;
    $_id    = $id == NULL ? "id_" . $slug : $id;

    $simb = '';
    $required = '';
    if ($required) {
        $simb = '<span class="text-danger">*</span>';
        $required = 'required';
    }

    if ($placeholder) {
        $label = "<label for='" . $_id . "' class='form-label'>" . $placeholder . $simb . "</label>";
    }

    if (!is_array($value)) {
        $value[] = $value;
    }

    if (is_array($content)) {
        $optionsBuscarPor = array();
        for ($z = 0; $z < COUNT($content); $z++) {
            $selected = "";
            if (in_array($content[$z]["id"], $value)) {
                $selected = "selected";
            }
            $optionsBuscarPor[] = "<option " . $selected . " value='" . $content[$z]["id"] . "'>" . $content[$z]["name"] . "</option>";
        }
    }

    $out = "
            <div class='mb-1' id='js_" . $slug . "'>
                " . $label . "
                <select multiple name='" . $_name . "[]' id='" . $_id . "' class='form-select" . $css . "' " . $js . " " . $required . ">
                    " . implode("", $optionsBuscarPor) . "
                </select>
            </div>
        ";

    return $out;
}

function fileField(string $placeholder = NULL, string $value = NULL, string $name = NULL, string $id = NULL, string $css = NULL, string $js = NULL): string
{
    $slug = slug($placeholder);
    $_name  = $name == NULL ? "f_" . $slug : $name;
    $_id    = $id == NULL ? "id_" . $slug : $id;

    if ($placeholder) {
        $label = "<label for='" . $_id . "' class='form-label'>" . $placeholder . "</label>";
    }

    if ($value != "config/") {
        $d_value = "<a href='../uploads/" . $value . "' target='_blank'>" . $value . "</a>";
    }

    $out = "
            <div class='mb-1' id='js_" . $slug . "'>
                " . $label . "
                <input type='file' class='form-control " . $css . "' name='" . $_name . "' id='" . $_id . "' placeholder='" . $placeholder . "' " . $js . "/>
                " . $d_value . "
            </div>
        ";

    return $out;
}

function textAreaField(string $placeholder = NULL, string $value = NULL, string $name = NULL, string $id = NULL, string $css = NULL, string $js = NULL): string
{
    $slug = slug($placeholder);
    $_name  = $name == NULL ? "f_" . $slug : $name;
    $_id    = $id == NULL ? "id_" . $slug : $id;

    if ($placeholder) {
        $label = "<label for='" . $_id . "' class='form-label'>" . $placeholder . "</label>";
    }

    $out = "
            <div class='mb-1' id='js_" . $slug . "'>
                " . $label . "
                <textarea class='form-control " . $css . "' name='" . $_name . "' id='" . $_id . "' placeholder='" . $placeholder . "' " . $js . "/>" . $value . "</textarea>
            </div>
        ";

    return $out;
}

/**
 * textField function
 *
 * @param string $placeholder
 * @param string $value
 * @param string $name
 * @param string $id
 * @param string $css
 * @param string $js
 * @return string
 */
function readField(string $placeholder = NULL, string $value = NULL): string
{

    $slug = slug($placeholder);

    if ($placeholder) {
        $label = "<label for='" . $slug . "' class='form-label'>" . $placeholder . "</label>";
    }

    $out = "
            <div class='mb-1' id='js_" . $slug . "'>
                " . $label . "
                <span class='form-control-plaintext' >" . $value . "</span>
            </div>
        ";

    return $out;
}

/**
 * timeField function
 *
 * @param string $placeholder
 * @param string $value
 * @param string $name
 * @param string $id
 * @param string $css
 * @param string $js
 * @return string
 */
function timeField(string $placeholder = NULL, string $value = NULL, string $name = NULL, string $id = NULL, string $css = NULL, string $js = NULL): string
{
    $slug = slug($placeholder);
    $_name  = $name == NULL ? "f_" . $slug : $name;
    $_id    = $id == NULL ? "id_" . $slug : $id;

    if ($placeholder) {
        $label = "<label for='" . $_id . "' class='form-label'>" . $placeholder . "</label>";
    }

    $out = "
            <div class='mb-1' id='js_" . $slug . "'>
                " . $label . "
                <input type='time' step='2' class='form-control " . $css . "' name='" . $_name . "' id='" . $_id . "' placeholder='" . $placeholder . "' value='" . $value . "' " . $js . "/>
            </div>
        ";

    return $out;
}

/**
 * hexField function
 *
 * @param string $placeholder
 * @param string $value
 * @param string $name
 * @param string $id
 * @param string $css
 * @param string $js
 * @return string
 */
function hexField(string $placeholder = NULL, string $value = NULL, string $name = NULL, string $id = NULL, string $css = NULL, string $js = NULL): string
{
    $slug = slug($placeholder);
    $_name  = $name == NULL ? "f_" . $slug : $name;
    $_id    = $id == NULL ? "id_" . $slug : $id;

    if ($placeholder) {
        $label = "<label for='" . $_id . "' class='form-label'>" . $placeholder . "</label>";
    }

    $out = "
            <div class='mb-1' id='js_" . $slug . "'>
                " . $label . "
                <input type='color' class='form-control form-control-color " . $css . "' name='" . $_name . "' id='" . $_id . "' placeholder='" . $placeholder . "' value='" . $value . "' " . $js . "/>
            </div>
        ";

    return $out;
}


function dateField(string $placeholder = NULL, string $value = NULL, string $name = NULL, string $id = NULL, string $css = NULL, string $js = NULL): string
{
    $slug = slug($placeholder);
    $_name  = $name == NULL ? "f_" . $slug : $name;
    $_id    = $id == NULL ? "id_" . $slug : $id;

    if ($placeholder) {
        $label = "<label for='" . $_id . "' class='form-label'>" . $placeholder . "</label>";
    }

    $out = "
            <div class='mb-1' id='js_" . $slug . "'>
                " . $label . "
                <input type='date' class='form-control " . $css . "' name='" . $_name . "' id='" . $_id . "' placeholder='" . $placeholder . "' value='" . $value . "' " . $js . "/>
            </div>
        ";

    return $out;
}


function editorAreaField(string $placeholder = NULL, string $value = NULL, string $name = NULL, bool $required = NULL, string $id = NULL, string $css = NULL, string $js = NULL): string
{
    $slug = slug($placeholder);
    $_name  = $name == NULL ? "f_" . $slug : $name;
    $_id    = $id == NULL ? "id_" . $slug : $id;

    $simb = '';
    $required = '';
    if ($required) {
        $simb = '<span class="text-danger">*</span>';
        $required = 'required';
    }

    if ($placeholder) {
        $label = "<label for='" . $_id . "' class='form-label'>" . $placeholder . "</label>";
    }

    $out = "
            <div class='mb-1' id='js_" . $slug . "'>
                " . $label .  $simb . "
                <div class='editor_" . $_id . " " . $css . "' style='height: 400px' id='editor_" . $_id . "' " . $js . ">
                    " . $value . "
                </div>
            </div>
            <input type='hidden' name='" . $_name . "' id='" . $_id . "' value='' " . $required . "/>
        ";

    return $out;
}
