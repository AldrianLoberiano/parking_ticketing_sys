<?php
require_once __DIR__ . '/../models/Vehicle.php';
require_once __DIR__ . '/../models/ParkingSlot.php';
require_once __DIR__ . '/../models/Ticket.php';
require_once __DIR__ . '/../includes/functions.php';

class GuardController
{
    public static function dashboard()
    {
        requireRole('guard');
        $GLOBALS['availableSlots'] = ParkingSlot::getAvailable();
        $GLOBALS['activeTickets'] = Ticket::getActive();
    }

    public static function checkin()
    {
        requireRole('guard');
        global $availableSlots;
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $plate = sanitize($_POST['plate_number']);
            $vehicleType = sanitize($_POST['vehicle_type']);
            $slotId = $_POST['slot_id'];

            // Validate required fields
            if (empty($plate) || empty($vehicleType) || empty($slotId)) {
                flashMessage('error', 'All fields are required');
                redirect('checkin.php');
            }

            $vehicle = Vehicle::findByPlate($plate);
            if (!$vehicle) {
                // Vehicle not found, create it automatically for the current guard user
                $guardId = $_SESSION['user_id'];
                if (Vehicle::create($plate, $guardId, $vehicleType, 'Unknown')) {
                    flashMessage('info', 'New vehicle registered automatically: ' . htmlspecialchars($plate));
                    $vehicle = Vehicle::findByPlate($plate);
                } else {
                    flashMessage('error', 'Failed to register new vehicle. Please try again.');
                    redirect('checkin.php');
                }
            }

            if (Ticket::create($vehicle['id'], $slotId)) {
                flashMessage('success', 'Check-in successful for vehicle: ' . htmlspecialchars($plate) . ' (' . htmlspecialchars($vehicleType) . ')');
            } else {
                flashMessage('error', 'Check-in failed. The parking slot may no longer be available.');
            }
            redirect('checkin.php');
        }
        $availableSlots = ParkingSlot::getAvailable();
    }

    public static function checkout()
    {
        requireRole('guard');
        global $activeTickets;
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $ticketId = $_POST['ticket_id'];

            if (Ticket::checkout($ticketId)) {
                flashMessage('success', 'Check-out successful');
            } else {
                flashMessage('error', 'Check-out failed');
            }
            redirect('dashboard.php');
        }
        $activeTickets = Ticket::getActive();
    }
}
