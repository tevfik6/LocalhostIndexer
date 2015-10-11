<?php
    $config = include "config.php";
    $data = array("success" => true);
    $data['post'] = $_POST;
    file_put_contents($_POST['file_path'], $_POST['content']);
    $data['info'] = pathinfo($_POST['file_path']);
    $data['info']['mtime'] = filemtime($_POST['file_path']);
    $data['info']['modified_date'] = date($config['date_format'], filemtime($_POST['file_path']));
    echo json_encode($data);
?>
