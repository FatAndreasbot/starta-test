<?php
header("Content-Type: application/json");

require $_SERVER['DOCUMENT_ROOT'] . '/api/import.php';

$http_verb = $_SERVER["REQUEST_METHOD"];
$request = $_SERVER['REQUEST_URI'];
$subrequest = explode("/", string: $request)[2];

switch ($subrequest) {
    case "import":
        if ($http_verb != "POST") {
            http_response_code(405);
            $jsonData = array(
                "msg" => "only post requests allowed"
            );
            echo json_encode($jsonData);
            break;
        }
        try {
            echo PostResponse();
        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode(array("msg" => $e->getMessage()));
        }
        break;
    default:
        header("Content-Type: application/json");
        http_response_code(404);
        echo json_encode(array('msg' => 'not a valid endpoint'));
}
