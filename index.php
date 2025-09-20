<?php
$request = $_SERVER['REQUEST_URI'];

require $_SERVER['DOCUMENT_ROOT']  .'/view/common/header.php';

$subrequest = explode("/",$request)[1];
// rudementory routing
switch ($subrequest) {
    case '':
        require $_SERVER['DOCUMENT_ROOT'] . '/view/catalog/main.php';
        break;
    case 'admin':
        require $_SERVER['DOCUMENT_ROOT'] . '/view/admin/routes.php';
        break;
    default:
        require $_SERVER['DOCUMENT_ROOT'] . '/view/404.html';
        break;
}

require $_SERVER['DOCUMENT_ROOT']  .'/view/common/footer.php';

