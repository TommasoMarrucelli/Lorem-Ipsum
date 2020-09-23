<?php
$dsn = 'mysql:host=sql210.epizy.com;dbname=epiz_26591859_lorem_ipsum;charset=UTF8';
$user = 'epiz_26591859';
$dbpassword = 'wscWMsAoTjSH';

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

