<?php

function d($mixed = '')
{
    echo"<pre>";
    var_dump($mixed);
    echo"</pre>";
}

function dp($mixed = '')
{
    echo"<pre>";
    print_r($mixed);
    echo"</pre>";
}

function dd($mixed = '')
{
    echo"<pre>";
    var_dump($mixed);
    echo"</pre>";
    exit;
}

function old($name)
{
    $input = explode('.', $name);
    if (count($input) > 1) {
        return $_REQUEST[$input[0]][$input[1]] ?? '';
    } else {
        return $_REQUEST[$input[0]] ?? '';
    }
}

function ufs()
{
    $ufs = [];

    $ufs['AC'] = 'AC';
    $ufs['AL'] = 'AL';
    $ufs['AP'] = 'AP';
    $ufs['AM'] = 'AM';
    $ufs['BA'] = 'BA';
    $ufs['CE'] = 'CE';
    $ufs['DF'] = 'DF';
    $ufs['ES'] = 'ES';
    $ufs['GO'] = 'GO';
    $ufs['MA'] = 'MA';
    $ufs['MT'] = 'MT';
    $ufs['MS'] = 'MS';
    $ufs['MG'] = 'MG';
    $ufs['PA'] = 'PA';
    $ufs['PB'] = 'PB';
    $ufs['PR'] = 'PR';
    $ufs['PE'] = 'PE';
    $ufs['PI'] = 'PI';
    $ufs['RJ'] = 'RJ';
    $ufs['RN'] = 'RN';
    $ufs['RS'] = 'RS';
    $ufs['RO'] = 'RO';
    $ufs['RR'] = 'RR';
    $ufs['SC'] = 'SC';
    $ufs['SP'] = 'SP';
    $ufs['SE'] = 'SE';
    $ufs['TO'] = 'TO';
    
    return $ufs;
}

function optionsSelect($arrInfo, $label, $value, $selected = '')
{
    $options = '';

    foreach ($arrInfo as $info) {
        $options .= '<option value="'.$info[$value].'">'.$info[$label].'</option>';
    }

    return $options;
}

function paginator($pag = 1, $total)
{
    $html = '<ul class="pagination pagination-sm no-margin pull-right">';
    if ($pag > 1) {
        $html .= '<li><a href="?'.$_SERVER['QUERY_STRING'].'&page=1">Primeira</a></li>';
        $html .= '<li><a href="?'.$_SERVER['QUERY_STRING'].'&page='.($pag - 1).'">Anterior</a></li>';
    }
    
    $html .= '<li class="active"><a href="javascript:void();">'.$pag.'</a></li>';

    if ($pag < $total) {
        $html .= '<li><a href="?'.$_SERVER['QUERY_STRING'].'&page='.($pag + 1).'">Próxima</a></li>';
        $html .= '<li><a href="?'.$_SERVER['QUERY_STRING'].'&page='.$total.'">Última ('.$total.')</a></li>';
    }
    
    $html .= '</ul>';
   

    return $html;
}
