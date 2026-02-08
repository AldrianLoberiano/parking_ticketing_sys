<?php
require_once __DIR__ . '/../config/database.php';

class ParkingArea
{
    public static function getAll()
    {
        global $pdo;
        $stmt = $pdo->query("SELECT * FROM parking_areas ORDER BY name");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function findById($id)
    {
        global $pdo;
        $stmt = $pdo->prepare("SELECT * FROM parking_areas WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public static function create($name, $location)
    {
        global $pdo;
        $stmt = $pdo->prepare("INSERT INTO parking_areas (name, location) VALUES (?, ?)");
        return $stmt->execute([$name, $location]);
    }

    public static function update($id, $name, $location)
    {
        global $pdo;
        $stmt = $pdo->prepare("UPDATE parking_areas SET name = ?, location = ? WHERE id = ?");
        return $stmt->execute([$name, $location, $id]);
    }

    public static function delete($id)
    {
        global $pdo;
        $stmt = $pdo->prepare("DELETE FROM parking_areas WHERE id = ?");
        return $stmt->execute([$id]);
    }
}

