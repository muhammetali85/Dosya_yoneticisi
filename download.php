<?php
include 'auth.php';
$base = 'uploads/';
$path = $_GET['path'] ?? '';
$file = basename($_GET['file']);
$fullPath = realpath($base . '/' . $path . '/' . $file);

if (file_exists($fullPath)) {
  header('Content-Description: File Transfer');
  header('Content-Type: application/octet-stream');
  header('Content-Disposition: attachment; filename="' . $file . '"');
  header('Content-Length: ' . filesize($fullPath));
  readfile($fullPath);
  exit;
} else {
  echo "Dosya bulunamadı.";
}
