<?php

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json");

include "config.php";

ini_set('display_errors', 1);
error_reporting(E_ALL);

$json = file_get_contents('php://input');
$data = json_decode($json, true);

if (json_last_error() === JSON_ERROR_NONE) {
    $pid = $data["pid"];
    $msg = $data["msg"];
    if (isset($data["unread"])) {
        $unread = $data["unread"];
    } else {
        $unread = "";
    }
    if (isset($data["id"])) {
        $id = $data["id"];
    } else {
        $id = "";
    }

    try {
        if ($id == "" || $id == null) {
            //=== Insert ===//
            $sql = "INSERT INTO mqtt (pid,msg) VALUES (?,?)";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param('ss', $pid, $msg);
            $message = "Insert Successfully";
        } else {
            //=== Update ===//
            $sql = "UPDATE mqtt SET pid = ?, msg = ?,unread=?,updatetime=CURRENT_TIMESTAMP() WHERE id = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param('sssi', $pid, $msg, $unread, $id);
            $message = "Update Successfully, id = $id, pid = $pid, unread = $unread";
        }

        if ($stmt->execute()) {
            $list_result["status"] = "OK";
            $list_result["message"] = $message;
        } else {
            $list_result["status"] = "ERROR";
        }
    } catch (Exception $e) {
        echo $e->getMessage();
        $list_result["status"] = "ERROR";
        $list_result["message"] = $e->getMessage();
    }
}

echo json_encode($list_result);
$conn->close();
