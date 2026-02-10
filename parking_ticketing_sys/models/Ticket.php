<?php
require_once __DIR__ . '/../config/database.php';

class Ticket
{
    public static function create($vehicleId, $slotId)
    {
        global $pdo;
        $pdo->beginTransaction();
        try {
            // Create ticket
            $stmt = $pdo->prepare("INSERT INTO tickets (vehicle_id, slot_id) VALUES (?, ?)");
            $stmt->execute([$vehicleId, $slotId]);
            $ticketId = $pdo->lastInsertId();

            // Update slot status
            ParkingSlot::updateStatus($slotId, 'occupied');

            $pdo->commit();
            return $ticketId;
        } catch (Exception $e) {
            $pdo->rollBack();
            return false;
        }
    }

    public static function checkout($ticketId)
    {
        global $pdo;
        $pdo->beginTransaction();
        try {
            // Get ticket
            $ticket = self::findById($ticketId);
            if (!$ticket) return false;

            // Update ticket
            $stmt = $pdo->prepare("UPDATE tickets SET checkout_time = CURRENT_TIMESTAMP, status = 'completed' WHERE id = ?");
            $stmt->execute([$ticketId]);

            // Update slot status
            ParkingSlot::updateStatus($ticket['slot_id'], 'available');

            $pdo->commit();
            return true;
        } catch (Exception $e) {
            $pdo->rollBack();
            return false;
        }
    }

    public static function findById($id)
    {
        global $pdo;
        $stmt = $pdo->prepare("SELECT t.*, v.plate_number, ps.slot_number, pa.name as area_name, u.name as owner_name FROM tickets t JOIN vehicles v ON t.vehicle_id = v.id JOIN parking_slots ps ON t.slot_id = ps.id JOIN parking_areas pa ON ps.area_id = pa.id JOIN users u ON v.owner_id = u.id WHERE t.id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public static function getActive()
    {
        global $pdo;
        $stmt = $pdo->query("SELECT t.*, v.plate_number, ps.slot_number, pa.name as area_name, u.name as owner_name FROM tickets t JOIN vehicles v ON t.vehicle_id = v.id JOIN parking_slots ps ON t.slot_id = ps.id JOIN parking_areas pa ON ps.area_id = pa.id JOIN users u ON v.owner_id = u.id WHERE t.status = 'active' ORDER BY t.checkin_time DESC");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function getByVehicle($vehicleId)
    {
        global $pdo;
        $stmt = $pdo->prepare("SELECT t.*, ps.slot_number, pa.name as area_name FROM tickets t JOIN parking_slots ps ON t.slot_id = ps.id JOIN parking_areas pa ON ps.area_id = pa.id WHERE t.vehicle_id = ? ORDER BY t.checkin_time DESC");
        $stmt->execute([$vehicleId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function getAll()
    {
        global $pdo;
        $stmt = $pdo->query("SELECT t.*, v.plate_number, ps.slot_number, pa.name as area_name, u.name as owner_name FROM tickets t JOIN vehicles v ON t.vehicle_id = v.id JOIN parking_slots ps ON t.slot_id = ps.id JOIN parking_areas pa ON ps.area_id = pa.id JOIN users u ON v.owner_id = u.id ORDER BY t.checkin_time DESC");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function getAnalytics()
    {
        global $pdo;
        $stmt = $pdo->query("SELECT COUNT(*) as total_tickets, COUNT(CASE WHEN status = 'active' THEN 1 END) as active_tickets, AVG(TIMESTAMPDIFF(SECOND, checkin_time, checkout_time)/3600) as avg_duration FROM tickets WHERE checkout_time IS NOT NULL");
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
}
