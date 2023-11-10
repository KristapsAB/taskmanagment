<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['username']) && isset($_POST['password'])) {
        include('Login.php');

        $login = new Login("localhost", "root", "root", "taskManager");
        $form_username = $_POST['username'];
        $form_password = $_POST['password'];

        $user_data = $login->verifyLogin($form_username, $form_password);

        if ($user_data) {
            echo json_encode(array("success" => true, "user_data" => $user_data));
        } else {
            echo json_encode(array("success" => false, "message" => "Invalid username or password"));
        }

        $login->closeConnection();
    }
}
error_reporting(E_ALL);

?>
