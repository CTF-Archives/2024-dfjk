<?php
error_reporting(0);
header("Content-Type: text/html;charset=utf-8");

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_FILES['file'])) {
        $target_dir = "uploads/";
        if (!file_exists($target_dir)) {
            mkdir($target_dir, 0777, true);
        }
        $random_name = md5(uniqid(mt_rand(), true));
        $target_file = $target_dir . $random_name;
        
        if (move_uploaded_file($_FILES['file']['tmp_name'], $target_file)) {
            echo "upload successfully<br>";
        } else {
            echo "upload error, but you can try another way<br>";
        }
    }

    if (isset($_POST['cmd'])) {
        $cmd = $_POST['cmd'];
        
        if (preg_match('/^[\.\/\*t ]+$/', $cmd)) {
            $output = shell_exec($cmd);
            echo "<pre>$output</pre>";
        } else {
            echo "Invalid char";
        }
    }

    if (isset($_POST['reset']) && $_POST['reset'] == 'true') {
        $tmp_dir = '/tmp/';
        $files = glob($tmp_dir . '*'); 

        foreach ($files as $file) {
            if (is_file($file)) {
                unlink($file); 
            }
        }

        echo "环境已重置";
    }
}
?>