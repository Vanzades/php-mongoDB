<?php

// Required headers
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: DELETE");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

// Include database file
include_once 'mongodb_config.php';

$dbname = 'toko';
$collection = 'barang';

// DB connection
$db = new DbManager();
$conn = $db->getConnection();

// Record to delete
$data = json_decode(file_get_contents("php://input"), true);

// _id field value
$id = $data['where'];

// Delete record
$delete = new MongoDB\Driver\BulkWrite();
$delete->delete(
    ['_id' => new MongoDB\BSON\ObjectId($id)],
    ['limit' => 0]
);

$result = $conn->executeBulkWrite("$dbname.$collection", $delete);

// Uncomment this line for debugging if needed
// print_r($result);

// Verify
if ($result->getDeletedCount() == 1) {
    echo json_encode(["message" => "Record successfully deleted"]);
} else {
    echo json_encode(["message" => "Error while deleting record"]);
}
?>