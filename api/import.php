<?php

class DB_Entry
{
    var $id;
    var $name;
    var $category;
    var $price;
    var $stock;
    var $rating;
    var $reviews_count;
    var $created_at;
    var $image;
    function __construct(array $data)
    {
        // isnull validation
        if ($data["id"] === null) {throw new Exception("field id does not exists", 400);}
        if ($data["name"] === null) {throw new Exception("field name does not exists", 400);}
        if ($data["category"] === null) {throw new Exception("field category does not exists", 400);}
        if ($data["price"] === null) {throw new Exception("field price does not exists", 400);}
        if ($data["stock"] === null) {throw new Exception("field stock does not exists", 400);}
        if ($data["rating"] === null) {throw new Exception("field rating does not exists", 400);}
        if ($data["created_at"] === null) {throw new Exception("field created_at does not exists", 400);}
        // datatype validation
        if (!is_numeric($data["id"])) throw new Exception("field id field is not a number", 400);
        if (!is_numeric($data["price"])) throw new Exception("field price field is not a number", 400);
        if (!is_numeric($data["stock"])) throw new Exception("field stock field is not a number", 400);
        if (!is_numeric($data["rating"]) ) throw new Exception("field rating field is not a number", 400);
        if (!is_numeric($data["reviews_count"])) throw new Exception("field reviews_count field is not a number", 400);
        if (isValidISO8601Date($data["created_at"]) === false) throw new Exception("field created_at is not a valid string", 400);

        $this->id = $data["id"];
        $this->name = $data["name"];
        $this->category = $data["category"];
        $this->price = $data["price"];
        $this->stock = $data["stock"];
        $this->rating = $data["rating"];
        $this->reviews_count = $data["reviews_count"];
        $this->created_at = getISO8601Date($data["created_at"]);
        $this->image = $data["image"];
    }
}

function getISO8601Date($value){
    $dateTime = \DateTime::createFromFormat(\DateTime::ATOM, $value);
    if ($dateTime) {
        return $value;
    }
    $dateTime = \DateTime::createFromFormat("Y-m-d", $value);
    return \DateTime::createFromFormat("Y-m-d", $value)->format(\DateTime::ATOM);
}

function isValidISO8601Date($value)
{
    if (!is_string($value)) {
        return false;
    }

    $dateTime = \DateTime::createFromFormat(\DateTime::ATOM, $value);


    //ISO 8601
    if ($dateTime) {
        return $dateTime->format(\DateTime::ATOM) === $value;
    }
    $dateTime = \DateTime::createFromFormat("Y-m-d", $value);

    // loose datetime format
    if ($dateTime) {
        return $dateTime->format("Y-m-d") === $value;
    }

    return false;
}

function storeNewEntry(DB_Entry $entry)
{
    $db = new SQLite3($_SERVER['DOCUMENT_ROOT']  . '/data/data.db');

    $sql = "
        INSERT INTO products(
            id,name,category,price,stock,
            rating,reviews_count,created_at,image
        ) values (
            $entry->id,
            \"$entry->name\",
            \"$entry->category\",
            $entry->price,
            $entry->stock,
            $entry->rating,
            $entry->reviews_count,
            \"$entry->created_at\",
            \"$entry->image\"
        )";

    $ret = $db->exec($sql);
    if ($ret === false) {
        $err_msg = $db->lastErrorMsg();
        throw new Exception($err_msg, 500);
    }
}


function PostResponse()
{
    http_response_code(501);

    $newEntry = $_POST["file_content"];
    $datatype = $_POST["type"];

    if ($datatype == "application/json") {
        $data = json_decode($newEntry, true);

        if (!$data) {
            http_response_code(400);    
            return json_encode(array("msg" => "could not decode json"));
        }

        storeNewEntry(new DB_Entry($data));
        http_response_code(200);
        return json_encode(array("msg" => "saved new entry"));
    }
    if ($datatype == "text/csv") {

        $data = fgetcsv($newEntry,0,",");

        http_response_code(501);
        return json_encode(array("msg" => "WIP"));
    }

    http_response_code(405);
    return json_encode(array("msg" => "only json and csv text allowed"));

    // ---------------------------

}
