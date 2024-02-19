<?php

namespace App\DB;

use PDO;
use PDOException;

class DBConnect
{

    private string $host = 'localhost';
    private ?string $username = 'root';
    private ?string $password = '';
    private ?string $database = 'facturation_prog';
    private PDO $db;

    public function __construct(string $host = null, string $username = null, string $password = null, string $database = null)
    {
        $this->host = $host ?? $this->host;
        $this->username = $username ?? $this->username;
        $this->password = $password ?? $this->password;
        $this->database = $database ?? $this->database;

        try {
            $this->db = new PDO('mysql:host=' . $this->host . ';dbname=' . $this->database, $this->username, $this->password);
            $this->db->setAttribute(PDO::MYSQL_ATTR_INIT_COMMAND, 'SET NAMES UTF8');
            $this->db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            throw new PDOException("Impossible de se connecter a la base de donnee: " . $e->getMessage());
        }
    }

    public function query(string $sql, array $data = []): array
    {
        $req = $this->db->prepare($sql);
        $req->execute($data);
        return $req->fetchAll(PDO::FETCH_ASSOC);
    }

    public function queryBuild(string $sql, array $data = []): bool
    {
        $req = $this->db->prepare($sql);
        return $req->execute($data);
    }

    public function getPDO(): PDO
    {
        return $this->db;
    }
}
