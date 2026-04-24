<?php
header('Content-Type: application/json');

$pdo = new PDO("mysql:host=localhost;dbname=hardware_db;charset=utf8mb4","root","");

$sql = "SELECT idUtilisateur, pseudoUtilisateur FROM utilisateur";

echo json_encode($pdo->query($sql)->fetchAll(PDO::FETCH_ASSOC));