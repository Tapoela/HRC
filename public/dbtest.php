<?php
$mysqli = new mysqli('127.0.0.1', 'root', '', 'hrc_db');
if ($mysqli->connect_error) {
    die($mysqli->connect_error);
}
echo 'DB OK';
