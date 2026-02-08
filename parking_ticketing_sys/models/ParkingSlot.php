<?php
require_once __DIR__ . '/../config/database.php';

class ParkingSlot
{
    public static function getAll()
    {
        global $pdo;
        $stmt = $pdo->query("SELECT ps.*, pa.name as area_name FROM parking_slots ps JOIN parking_areas pa ON ps.area_id = pa.id ORDER BY pa.name, ps.slot_number");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function getAvailable()
    {
        global $pdo;
        $stmt = $pdo->query("SELECT ps.*, pa.name as area_name FROM parking_slots ps JOIN parking_areas pa ON ps.area_id = pa.id WHERE ps.status = 'available' ORDER BY pa.name, ps.slot_number");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function findById($id)
    {
        global $pdo;
        $stmt = $pdo->prepare("SELECT ps.*, pa.name as area_name FROM parking_slots ps JOIN parking_areas pa ON ps.area_id = pa.id WHERE ps.id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public static function create($areaId, $slotNumber)
    {
        global $pdo;
        $stmt = $pdo->prepare("INSERT INTO parking_slots (area_id, slot_number) VALUES (?, ?)");
        return $stmt->execute([$areaId, $slotNumber]);
    }

    public static function updateStatus($id, $status)
    {
        global $pdo;
        $stmt = $pdo->prepare("UPDATE parking_slots SET status = ? WHERE id = ?");
        return $stmt->execute([$status, $id]);
    }

    public static function delete($id)
    {
        global $pdo;
        $stmt = $pdo->prepare("DELETE FROM parking_slots WHERE id = ?");
        return $stmt->execute([$id]);
    }

    public static function getByArea($areaId)
    {
        global $pdo;
        $stmt = $pdo->prepare("SELECT * FROM parking_slots WHERE area_id = ? ORDER BY slot_number");
        $stmt->execute([$areaId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
