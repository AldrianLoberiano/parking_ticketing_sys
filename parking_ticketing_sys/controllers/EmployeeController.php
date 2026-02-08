<?php
require_once __DIR__ . '/../models/Vehicle.php';
require_once __DIR__ . '/../models/Ticket.php';
require_once __DIR__ . '/../includes/functions.php';

class EmployeeController
{
    public static function dashboard()
    {
        requireRole('employee');
        $user = getCurrentUser();
        $vehicles = Vehicle::findByOwner($user['id']);
        $activeTickets = [];
        foreach ($vehicles as $vehicle) {
            $tickets = Ticket::getByVehicle($vehicle['id']);
            $activeTickets = array_merge($activeTickets, array_filter($tickets, function ($t) {
                return $t['status'] == 'active';
            }));
        }
        $GLOBALS['vehicles'] = $vehicles;
        $GLOBALS['activeTickets'] = $activeTickets;
    }

    public static function registerVehicle()
    {
        requireRole('employee');
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $plate = sanitize($_POST['plate_number']);
            $model = sanitize($_POST['model']);
            $color = sanitize($_POST['color']);
            $user = getCurrentUser();

            if (Vehicle::create($plate, $user['id'], $model, $color)) {
                flashMessage('success', 'Vehicle registered');
            } else {
                flashMessage('error', 'Registration failed');
            }
            redirect('dashboard.php');
        }
    }

    public static function history()
    {
        requireRole('employee');
        global $history;
        $user = getCurrentUser();
        $vehicles = Vehicle::findByOwner($user['id']);
        $history = [];
        foreach ($vehicles as $vehicle) {
            $history = array_merge($history, Ticket::getByVehicle($vehicle['id']));
        }
    }
}
