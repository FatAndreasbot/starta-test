<?php
$path = explode("/", $request);
$subrequest = count($path) > 2 ? $path[2] : "";

require $_SERVER['DOCUMENT_ROOT']  . '/view/admin/common/nav.php';


switch ($subrequest) {
    case '':
        require $_SERVER['DOCUMENT_ROOT'] . '/view/admin/main.php';
        break;
    case 'import':
        require $_SERVER['DOCUMENT_ROOT'] . '/view/admin/import.php';
        break;
    default:
        require $_SERVER['DOCUMENT_ROOT'] . '/view/404.html';
        break;
}
