<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 * http://localhost:8080/Match_your_own_Tutors/checkUser.php?jsonDoc=%7B%0A%20%20%22phone%22%3A%2090091190%2C%0A%20%20%22password%22%3A%20123456%0A%7D
 */

header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');


//$link = mysqli_connect("localhost", "root", "", "p10");
$jsonDoc = '';
if (isset($_REQUEST['jsonDoc']))
    $jsonDoc = $_REQUEST['jsonDoc'];

$output['result'] = false;
include "DBConnect.php";
if ($jsonDoc != '') {
    $obj = json_decode($jsonDoc, true);
    if ($obj) {
        $phone = $obj['phone'];
        $password = md5($obj['password']);
        $new = md5($obj['npw']);
        $login = $obj['LoginID'];
        $Username = $obj['Username'];
        $check= $obj['Check'];
        //$output['whereiam']="here1";
        if ($phone != '' && $password != '') {
            //$link = mysqli_connect("localhost", "root", "", "tutors");
            //mysqli_query($link,"SET NAMES 'UTF8'");
           // $output['whereiam']="here2";
            if ($link) {
                $result = mysqli_query($link, "SELECT Autoid, Username, Phone, Password, Email FROM users WHERE phone='$phone' AND password= '$password'");
                if ($result) {
                    $row = mysqli_fetch_assoc($result);
                    if ($row) {
                        $output['user'] = $row;
                        $output['result'] = TRUE;
                    }
                }
            } else
                $output['errorCode'] = 'e4';
        }elseif ( $login!=''&&$Username !=''&&$check==11) {
         //   $output['whereiam']="here4";
            //$link = mysqli_connect("localhost", "root", "", "tutors");
            //mysqli_query($link,"SET NAMES 'UTF8'");
            if ($link) {
                $result = mysqli_query($link, "Select * from users where Autoid='$login'");
                
                if ($result) {
                    $row = mysqli_fetch_assoc($result);
                    if ($row) {
                        $output['user'] = $row;
                        $output['result'] = TRUE;
                    }
                }
            } else
        $output['errorCode'] = 'e4.1';}
        elseif ($password != '' && $new != '' && $login != '') {

            //$link = mysqli_connect("localhost", "root", "", "tutors");
            //mysqli_query($link,"SET NAMES 'UTF8'");
            if ($link) {
                $result = mysqli_query($link, "SELECT Autoid  FROM users WHERE Autoid='$login' AND password= '$password'");
                if ($result) {
                    $row = mysqli_fetch_assoc($result);
                    if ($row) {
                        $result = mysqli_query($link, "UPDATE users SET password='$new' WHERE Autoid='$login'");
                        if ($result) {

                            $output['result'] = TRUE;
                        }
                    }
                }
            } else
                $output['errorCode'] = 'e4.1';
        
        } else
            $output['errorCode'] = 'e3';
    }
} else
    $output['errorCode'] = 'e1';

echo json_encode($output);
