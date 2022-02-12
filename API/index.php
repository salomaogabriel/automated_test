<?php
header('Content-Type: application/json; charset=utf-8');
define('PEPPER','c1isvFdxMDdmjOlvxpecFw');

    if(isset($_POST['submit'])) {

        // $pwdPeppered = hash_hmac("sha256", $_POST['pwd'], PEPPER);
        // $encryptedPwd = password_hash($pwdPeppered, PASSWORD_ARGON2ID);
        // echo $encryptedPwd;
        // return;

        include("dbh.php");
        $error = checkUsernameFormat($_POST['username']);

        if($error == true) {
            //No errors in the username format.
            $dbResult = checkForUsernameInDB($_POST['username']);
            if(count($dbResult) < 1) {
                //There are no users with this username 
                echo json_encode('No users Found with this username');
            } else {
                $userIsAuthenticated = checkPassword($dbResult[0],$_POST['pwd']);
               
            
            }
        }
    } else {
        // Someone is trying to access this page via URL and not via POST request
        echo json_encode('Cannot access page directly via URL');

    }

    
    function checkUsernameFormat($username) {
        if(empty($username))
        {
            //Username cannot be empty
            echo json_encode('Username Cannot be empty');
            return false;
        }
        if(!preg_match('/^[a-z\d_]{1,20}$/i',$username)) {

            //Username format is wrong.
            echo json_encode('Username contains characters that are not allowed');
            return false;

        }

        return true;

    }
    
    function checkForUsernameInDB($username) {
        $dbConn = new DBH();
        $dbConn->createPDO();
        $sql = "SELECT * FROM users WHERE users_username=?";
        $stmt = $dbConn->connect()->prepare($sql);
        $stmt->execute([$username]);

        $results = $stmt->fetchAll();
        return $results; 
    }
    function checkPassword($result,$inputPassword) {
        if(strlen($inputPassword) < 1) {
            echo json_encode('Password Cannot be empty');
            return;
        }
        $pwdPeppered = hash_hmac("sha256", $inputPassword, PEPPER);
        ///verify if the users match (returns true or false 
        $pwdCheck = password_verify($pwdPeppered,$result['users_pwd']);

        if($pwdCheck == false) {
            //Wrong password
            echo json_encode('Wrong Password');
        } else {

            echo json_encode('Logged in');
        }
    }
?>