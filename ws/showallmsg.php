<?php

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json");

include "config.php";

ini_set('display_errors', 1);
error_reporting(E_ALL);

if (isset($_GET["pid"]) && $_GET["pid"] != "") {
    $pid = $_GET["pid"];

    $info = array(
        "id" => "",
        "topic" => "",
        "msg" => "",
        "unread" => "",
        "updatetime" => "",
    );

    $user = array("pid"=>$pid,);

    $sql = "SELECT id,topic,msg,unread,updatetime FROM mqtt WHERE pid = ? ORDER BY updatetime DESC";

    try {
        
        $stmt = $conn->prepare($sql);
        $stmt->bind_param('s', $pid);
        $stmt->execute();
        $result = $stmt->get_result();

        $list_result["status"] = "OK";
        $list_result["totalmessage"] = $result->num_rows;
        $list_result["message"] = $user;
        $list_result["data"] = [];


        while ($row = $result->fetch_assoc()) {
            $info["id"] = $row["id"];
            $info["topic"] = $row["topic"];
            $info["msg"] = $row["msg"];
            $info["unread"] = $row["unread"];
            $info["updatetime"] = $row["updatetime"];
            array_push($list_result["data"], $info);
        }




    } catch (Exception $e) {
        echo $e->getMessage();
        $list_result["status"] = "ERROR";
        $list_result["totalrec"] = 0;
        $list_result["message"] = $e->getMessage();
        $list_result["data"] = "";
    }
}

echo json_encode($list_result);
$conn->close();
