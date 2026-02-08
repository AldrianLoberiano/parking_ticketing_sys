<?php
require_once __DIR__ . '/../config/database.php';

class Vehicle
{
    public static function findByPlate($plate)
    {
        global $pdo;
        $stmt = $pdo->prepare("SELECT * FROM vehicles WHERE plate_number = ?");
        $stmt->execute([$plate]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public static function findByOwner($ownerId)
    {
        global $pdo;
        $stmt = $pdo->prepare("SELECT * FROM vehicles WHERE owner_id = ?");
        $stmt->execute([$ownerId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function create($plate, $ownerId, $model, $color)
    {
        global $pdo;
        $stmt = $pdo->prepare("INSERT INTO vehicles (plate_number, owner_id, model, color) VALUES (?, ?, ?, ?)");
        return $stmt->execute([$plate, $ownerId, $model, $color]);
    }

    public static function getAll()
    {
        global $pdo;
        $stmt = $pdo->query("SELECT v.*, u.name as owner_name FROM vehicles v JOIN users u ON v.owner_id = u.id ORDER BY v.created_at DESC");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function update($id, $plate, $model, $color)
    {
        global $pdo;
        $stmt = $pdo->prepare("UPDATE vehicles SET plate_number = ?, model = ?, color = ? WHERE id = ?");
        return $stmt->execute([$plate, $model, $color, $id]);
    }

    public static function delete($id)
    {
        global $pdo;
        $stmt = $pdo->prepare("DELETE FROM vehicles WHERE id = ?");
        return $stmt->execute([$id]);
    }
}
