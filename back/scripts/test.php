<?php

$host = 'db'; // nom de l'hôte
$dbname = getenv('MYSQL_DATABASE'); // nom de la base de données
$username = 'root'; // nom d'utilisateur de la base de données
$password = getenv('MYSQL_ROOT_PASSWORD');

// Connexion à la base de données avec PDO
try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
} catch (PDOException $e) {
    echo "Erreur de connexion : " . $e->getMessage();
    exit;
}

// Exécution d'une requête SQL pour récupérer les données de la table "Perimeter"
$sql = "SELECT * FROM perimeter";
$stmt = $pdo->query($sql);

// Récupération des données sous forme de tableau associatif
$data = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Parcourir les données et traiter chaque domaine
foreach ($data as $row) {
    echo "----- Working on id :  " . $row['id']  . " domain_name : " .  $row['domain_name'] . " now ...  -------\n";
    $output = shell_exec("echo '" . $row['domain_name'] . "' | nuclei -silent -j -u  -");
    if (isset($output)) {
        $output = str_replace("\n", ',',$output);
        $output = substr_replace($output, "", -1, 1);
        $output = "[" . $output . "]";

        $vulnerabilities = json_decode($output);
        foreach ($vulnerabilities as $vulnerability) {
            // Vérifier si la vulnérabilité existe déjà dans la base de données
            $template = ;
            $description =;
            $name = $vulnerability->info->name;
            $reference =;
            $severity=;
            $matched_at=;
            $timestamp=;
            $ip=;
            $query = "SELECT COUNT(*) as count FROM vulnerabilities WHERE name = :name AND perimeter_id = :perimeter_id";
            $stmt = $pdo->prepare($query);
            $stmt->execute(['name' => $name, 'perimeter_id' => $row['id']]);
            $result = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($result['count'] == 0) {
                // La vulnérabilité n'existe pas encore, donc on l'insère dans la base de données
                $query = "INSERT INTO vulnerabilities (id, perimeter_id, template, description, name, reference, 
                             severity, matched_at, timestamp, ip, is_solved, is_visible, is_new) 
                          VALUES (:id, :perimeter_id, :template, :description, :name, :reference, 
                             :severity, :matched_at, :timestamp, :ip, :is_solved, :is_visible, :is_new)";
                $stmt = $pdo->prepare($query);
                $stmt->execute([
                    'id' => guidv4(),
                    'perimeter_id' => $row['id'],
                    'template' => $template,
                    'description' => $description,
                    'name' => $name,
                    'reference' => $reference,
                    'severity' => $severity,
                    'matched_at' => $matched_at,
                    'timestamp' => $timestamp,
                    'ip' => $ip,
                    'is_solved' => true,
                    'is_visible' => true,
                    'is_new' => false,
                ]);
            }
        }
    }
}

function guidv4($data = null) {
    // Generate 16 bytes (128 bits) of random data or use the data passed into the function.
    $data = $data ?? random_bytes(16);
    assert(strlen($data) == 16);

    // Set version to 0100
    $data[6] = chr(ord($data[6]) & 0x0f | 0x40);
    // Set bits 6-7 to 10
    $data[8] = chr(ord($data[8]) & 0x3f | 0x80);

    // Output the 36 character UUID.
    return vsprintf('%s%s-%s-%s-%s-%s%s%s', str_split(bin2hex($data), 4));
}

// Fermeture de la connexion à la base de données
$pdo = null;
