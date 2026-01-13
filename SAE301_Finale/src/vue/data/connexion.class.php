<?php

class Connexion
{

    private PDO $db;
    public function __construct()
    {
        $db_config = [
            'SGBD' => 'mysql',
            'host' => 'localhost',
            'port' => '3306',
            'dbname' => 'sae_301',
            'user' => 'sae',
            'pass' => 'password'
        ];

        try {
            $this->db = new PDO("mysql:host={$db_config['host']};port={$db_config['port']};dbname={$db_config['dbname']};charset=utf8", $db_config['user'], $db_config['pass']);
            $this->db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            die("Erreur de connexion : " . $e->getMessage());
        }
    }

    public function execSQL(string $req, array $valeurs = []): array
    {
        $query = $this->db->prepare($req);
        $query->execute($valeurs);
        return $query->fetchAll();
    }
}