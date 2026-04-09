<?php
session_start();
header('Content-Type: application/json');
require_once 'connect.php';

$stmt = $pdo->query("SELECT idCategorie, nom FROM categorie ORDER BY nom");
echo json_encode($stmt->fetchAll());
?>