<?php
$dsn = 'mysql:host=localhost;dbname=lorem_ipsum_db;charset=UTF8';
$user = 'root';
$dbpassword = '';

try {
            $dbh = new PDO($dsn, $user, $dbpassword);
            //Disable Emulates pdo and thus forces PDO to use real prepared statement.
            $dbh->setAttribute(PDO::ATTR_EMULATE_PREPARES, false); 
            $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            //           echo 'Connection failed: ' . $e->getMessage();
            die('Sorry, database problem');
        }
?>

