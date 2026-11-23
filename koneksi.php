<?php
session_start();
mysqli_report(MYSQLI_REPORT_OFF);

$db = new mysqli('127.0.0.1', 'root', 'root', 'db_klinik');
if ($db->connect_error) {
    die('Koneksi database gagal.');
}

function e($teks) {
    return htmlspecialchars($teks, ENT_QUOTES, 'UTF-8');
}

function rp($angka) {
    return 'Rp ' . number_format($angka, 0, ',', '.');
}

function wajib_login($role = null) {
    if (!isset($_SESSION['user'])) {
        header('Location: index.php');
        exit;
    }
    if ($role && $_SESSION['user']['role'] !== $role) {
        header('Location: dashboard.php');
        exit;
    }
}
