<?php
// backend/get_statuts.php
session_start();
header('Content-Type: application/json');
require_once 'connect.php';

$stmt = $pdo->query("SELECT idStatut, libelle FROM statut_reference ORDER BY libelle");
echo json_encode($stmt->fetchAll());
?>