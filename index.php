<?php
$request = $_SERVER['REQUEST_URI'];

// rudementory routing
switch ($request) {
    case '/':
        require __DIR__ . '/view/index.html';
        break;
    case '/admin/import':
        require __DIR__ . '/view/admin/import.html';
        break;
    default:
        require __DIR__ . '/view/404.html';
        break;
}
