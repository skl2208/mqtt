<?php

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json");

include "config.php";

ini_set('display_errors', 1);
error_reporting(E_ALL);

if (isset($_GET["id"]) && $_GET["id"] != "") {
    $id = $_GET["id"];
    try {
        $sql = "SELECT pid,msg FROM mqtt WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param('i', $id);
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();
        $pid = $row["pid"];
        $msg = $row["msg"];

        $list_result["status"] = "OK";
        $list_result["data"] = $msg;

        //=== Update ว่าอ่านแล้ว ===//
        $sql = "UPDATE mqtt SET unread='F',updatetime=CURRENT_TIMESTAMP() WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param('i',  $id);
        $stmt->execute();
    } catch (Exception $e) {
        echo $e->getMessage();
        $list_result["status"] = "ERROR";
        $list_result["message"] = $e->getMessage();
        $list_result["data"] = "";
    }
}

echo json_encode($list_result);
$conn->close();
