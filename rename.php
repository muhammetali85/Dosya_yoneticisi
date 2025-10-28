<?php
include 'auth.php';
$base = 'uploads/';
$path = $_POST['path'] ?? '';
$old = basename($_POST['old']);
$new = basename($_POST['new']);
$dir = realpath($base) . '/' . trim($path, '/');

rename("$dir/$old", "$dir/$new");
