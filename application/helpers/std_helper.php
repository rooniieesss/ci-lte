<?php

function debug($data, $exit = 0)
{
    echo "<pre>";
    print_r($data);
    echo "</pre>";
    echo "<hr>";
    !$exit ?: exit;
}

function dd($data)
{
    debug($data, 1);
}

function secho($str)
{
    echo htmlspecialchars($str);
}

function post($key = '', $clean = false)
{
    if ($key) {
        $CI = &get_instance();
        return $CI->input->post($key);
    } else {
        return $_POST;
    }
}

function is_post_request()
{
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        return true;
    } else {
        return false;
    }
}

function model($model = '', $alias = '')
{
    $CI = &get_instance();

    if ($alias) {
        $CI->load->model($model, $alias);
        return $CI->$alias;
    } else {
        $CI->load->model($model);
        $modelName = explode('/', $model);
        $modelName = $modelName[count($modelName) - 1];
        return $CI->$modelName;
    }
}

function library($lib = '')
{
    $libName = explode('/', $lib);

    $CI = &get_instance();
    $CI->load->library($lib);

    $libName = $libName[count($libName) - 1];
    return $CI->{$libName};
}

function dropdown($table, $opt = array(), $selected = array(), $extra = '')
{
    $CI = &get_instance();

    if (is_array($table)) {
        $namaform = $table[1];
        $table    = $table[0];
    }

    $alias = $table;
    if (strpos($table, ' as ') !== false) {
        $explode = explode(' as ', $table);
        $table   = $explode[0];
        $alias   = $explode[1];
    }

    $modelname = ucfirst($table) . '_model';
    model($modelname);

    $row = $CI->$modelname->get_all();

    $option = ['' => 'Pilih ' . ucwords(str_replace('_', ' ', $alias))];
    if (!empty($row)) {
        foreach ($row as $value) {
            $option[$value->{$opt[0]}] = $value->{$opt[1]};
        }
    }

    if (!empty($namaform)) {
        $opt[0] = $namaform;
    }

    echo form_dropdown($opt[0], $option, $selected, $extra);
}

function view($view_path, $newdata = null, $passing = 0)
{
    $CI = &get_instance();
    if (!$passing) {
        $newdata['data'] = $newdata;
    }
    return $CI->load->view($view_path, $newdata);

}

function activemenu($menu)
{
    if (uri_string() == $menu) {
        echo 'class="active"';
    }
}

function togglemenu($menu, $is_parent = false)
{
    if ($is_parent) {
        if (strpos(current_url(), $menu) !== false) {
            echo 'toggled';
        }
    } else {
        if (in_array(uri_string(), $menu)) {
            echo 'toggled';
        }
    }

}

function gridHeader($field, $label, $cfg)
{
    $urlAdd = ($cfg->keyword ? "/kw:$cfg->keyword" : '') . ($cfg->searchField ? "/sf:$cfg->searchField" : '');

    if ($cfg->sortField == $field) {
        if ($cfg->sortMethod == 'ASC') {
            $url = "$cfg->base/ob:$field@DESC" . $urlAdd;

            $class   = 'glyphicon glyphicon-arrow-up';
            $tooltip = 'Ascending';
        } else {
            $url     = "$cfg->base/ob:$field@ASC" . $urlAdd;
            $class   = 'glyphicon glyphicon-arrow-down';
            $tooltip = 'Descending';
        }
    } else {
        $url     = "$cfg->base/ob:$field@ASC" . $urlAdd;
        $class   = '';
        $tooltip = '';
    }
    ?>
<a href="<?php echo site_url($url) ?>" class=""><span class="<?php echo $class ?>" title="<?php echo $tooltip ?>"></span> <?php echo $label ?></a>
<?php
}

function kekata($x)
{
    $x     = abs($x);
    $angka = array("", "satu", "dua", "tiga", "empat", "lima", "enam", "tujuh", "delapan", "sembilan", "sepuluh", "sebelas");
    $temp  = "";
    if ($x < 12) {
        $temp = " " . $angka[$x];
    } else if ($x < 20) {
        $temp = kekata($x - 10) . " belas";
    } else if ($x < 100) {
        $temp = kekata($x / 10) . " puluh" . kekata($x % 10);
    } else if ($x < 200) {
        $temp = " seratus" . kekata($x - 100);
    } else if ($x < 1000) {
        $temp = kekata($x / 100) . " ratus" . kekata($x % 100);
    } else if ($x < 2000) {
        $temp = " seribu" . kekata($x - 1000);
    } else if ($x < 1000000) {
        $temp = kekata($x / 1000) . " ribu" . kekata($x % 1000);
    } else if ($x < 1000000000) {
        $temp = kekata($x / 1000000) . " juta" . kekata($x % 1000000);
    } else if ($x < 1000000000000) {
        $temp = kekata($x / 1000000000) . " milyar" . kekata(fmod($x, 1000000000));
    } else if ($x < 1000000000000000) {
        $temp = kekata($x / 1000000000000) . " trilyun" . kekata(fmod($x, 1000000000000));
    }
    return $temp;
}
function getBilangan($x, $style = 4)
{
    if ($x < 0) {
        $hasil = "minus " . trim(kekata($x));
    } else {
        $hasil = trim(kekata($x)) . " rupiah";
    }

    return $hasil;
}

function inject_post($data)
{
    foreach ($data as $key => $val) {
        $_POST[$key] = $val;
    }
}

function insert_audit_record($record, $auth)
{
    if (is_array($record)) {
        $record['__active']   = 1;
        $record['__created']  = date('Y-m-d H:i:s');
        $record['__username'] = $auth->username;
    } else {
        $record->__active   = 1;
        $record->__created  = date('Y-m-d H:i:s');
        $record->__username = $auth->username;
    }

    return $record;
}

function update_audit_record($record, $auth)
{
    if (is_array($record)) {
        $record['__updated']  = date('Y-m-d H:i:s');
        $record['__username'] = $auth->username;
    } else {
        $record->__updated  = date('Y-m-d H:i:s');
        $record->__username = $auth->username;
    }

    dd($record);

    return $record;
}

function sec2hour($seconds, $float = false)
{
    $out          = new StdClass();
    $out->hours   = floor($seconds / 3600);
    $out->minutes = floor(($seconds % 3600) / 60);

    if ($float) {
        return $out->hours + (round($out->minutes * 100 / 60) / 100);
    } else {
        return $out;
    }
}

function std_number_format($n, $decimal = 0)
{
    return number_format($n, $decimal);
}

function map($array, $key)
{
    $result = array();
    foreach ($array as $item) {
        $result[$item->{$key}] = $item;
    }

    return $result;
}