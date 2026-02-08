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
            $slotId = $_POST['slot_id'];

            $vehicle = Vehicle::findByPlate($plate);
            if (!$vehicle) {
                flashMessage('error', 'Vehicle not registered');
                redirect('checkin.php');
            }

            if (Ticket::create($vehicle['id'], $slotId)) {
                flashMessage('success', 'Check-in successful');
            } else {
                flashMessage('error', 'Check-in failed');
            }
            redirect('dashboard.php');
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

