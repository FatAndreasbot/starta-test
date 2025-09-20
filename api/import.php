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
        $this->id = $data["id"];
        $this->name = $data["name"];
        $this->category = $data["category"];
        $this->price = $data["price"];
        $this->stock = $data["stock"];
        $this->rating = $data["rating"];
        $this->reviews_count = $data["reviews_count"];
        $this->created_at = $data["created_at"];
        $this->image = $data["image"];
    }
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
        throw new Exception($err_msg);
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
