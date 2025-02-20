<?php

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json");

include "config.php";

ini_set('display_errors', 1);
error_reporting(E_ALL);

$json = file_get_contents('php://input');
$data = json_decode($json, true);

if (json_last_error() === JSON_ERROR_NONE) {
    if (isset($data["pid"])) {
        $pid = $data["pid"];
    } else {
        $pid = "";
    }
    if (isset($data["msg"])) {
        $msg = $data["msg"];
    } else {
        $msg = "";
    }
    if (isset($data["topic"])) {
        $topic = $data["topic"];
    } else {
        $topic = "";
    }
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
            $sql = "INSERT INTO mqtt (pid,msg,topic) VALUES (?,?,?)";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param('sss', $pid, $msg, $topic);
            $message = "Insert Successfully";
        } else {
            //=== Update ===//
            $sql = "UPDATE mqtt SET unread='F',updatetime=CURRENT_TIMESTAMP() WHERE id = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param('i',   $id);
            $message = "Update READ Successfully, id = $id";
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
