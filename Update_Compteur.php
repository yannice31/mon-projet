<?php
header('Content-Type: application/json');

// Nom du fichier JSON
$file = 'Compteur.json';

// Vérifier si le fichier existe
if (!file_exists($file)) {
    // Crée un fichier de base si inexistant
    $data = [
        "totalCommandes" => 0,
        "historique" => []
    ];
    file_put_contents($file, json_encode($data, JSON_PRETTY_PRINT));
}

// Charger le contenu actuel du fichier
$json = file_get_contents($file);
$data = json_decode($json, true);

// Récupérer les données envoyées par le formulaire
$input = json_decode(file_get_contents('php://input'), true);

if ($input) {
    // Incrémenter le compteur global
    $data['totalCommandes']++;

    // Ajouter l'entrée dans l’historique
    $data['historique'][] = [
        "date" => date("c"), // format ISO 8601 (ex: 2025-09-05T14:30:00+00:00)
        "nom" => $input['name'] ?? 'Inconnu',
        "prenom" => $input['nickname'] ?? '',
        "email" => $input['email'] ?? '',
        "numero" => $input['number'] ?? '',
        "produit" => $input['product'] ?? '',
        "quantite" => (int)($input['quantity'] ?? 1),
        "details" => $input['details'] ?? ''
    ];

    // Sauvegarder les nouvelles données dans le fichier JSON
    file_put_contents($file, json_encode($data, JSON_PRETTY_PRINT));
}

// Répondre avec les données mises à jour
echo json_encode($data);
?>