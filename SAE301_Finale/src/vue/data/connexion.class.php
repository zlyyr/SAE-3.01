<?php

//Classe qui gère la connexion à la base de données
class Connexion
{

    private PDO $db;
    public function __construct()
    {
        //Configuration de la base de données
        $db_config = [
            'SGBD' => 'mysql',
            'host' => 'devbdd.iutmetz.univ-lorraine.fr',
            'port' => '3306',
            'dbname' => 'e98735u_SAE301',
            'user' => 'e98735u_appli',
            'pass' => '32402754'
        ];

        try {
            //Création de la connexion PDO à MySQL avec encodage UTF-8
            $this->db = new PDO("mysql:host={$db_config['host']};port={$db_config['port']};dbname={$db_config['dbname']};charset=utf8", $db_config['user'], $db_config['pass']);
            $this->db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            //En cas d’erreur de connexion, on arrete et on affiche l’erreur
            die("Erreur de connexion : " . $e->getMessage());
        }
    }

    public function execSQL(string $req, array $valeurs = []): array
    {
        //Prépare la requête (protection contre les injections SQL)
        $query = $this->db->prepare($req);
        $query->execute($valeurs);
        //Retourne tous les résultats sous forme de tableau associatif
        return $query->fetchAll();
    }
}
?>