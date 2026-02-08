<?php
require_once __DIR__ . '/../models/User.php';
require_once __DIR__ . '/../models/Vehicle.php';
require_once __DIR__ . '/../models/ParkingArea.php';
require_once __DIR__ . '/../models/ParkingSlot.php';
require_once __DIR__ . '/../models/Ticket.php';
require_once __DIR__ . '/../includes/functions.php';

class AdminController
{
    public static function dashboard()
    {
        requireRole('admin');
        $GLOBALS['users'] = User::getAll();
        $GLOBALS['vehicles'] = Vehicle::getAll();
        $GLOBALS['areas'] = ParkingArea::getAll();
        $GLOBALS['slots'] = ParkingSlot::getAll();
        $GLOBALS['tickets'] = Ticket::getAll();
        $GLOBALS['analytics'] = Ticket::getAnalytics();
    }

    public static function manageUsers()
    {
        requireRole('admin');
        global $users;
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (isset($_POST['create'])) {
                $name = sanitize($_POST['name']);
                $email = sanitize($_POST['email']);
                $password = $_POST['password'];
                $role = sanitize($_POST['role']);
                User::create($name, $email, $password, $role);
                flashMessage('success', 'User created');
            } elseif (isset($_POST['update'])) {
                $id = $_POST['id'];
                $name = sanitize($_POST['name']);
                $email = sanitize($_POST['email']);
                $role = sanitize($_POST['role']);
                User::update($id, $name, $email, $role);
                flashMessage('success', 'User updated');
            } elseif (isset($_POST['delete'])) {
                $id = $_POST['id'];
                User::delete($id);
                flashMessage('success', 'User deleted');
            }
            redirect('manage_users.php');
        }
        $users = User::getAll();
    }

    public static function manageAreas()
    {
        requireRole('admin');
        global $areas;
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (isset($_POST['create'])) {
                $name = sanitize($_POST['name']);
                $location = sanitize($_POST['location']);
                ParkingArea::create($name, $location);
                flashMessage('success', 'Area created');
            } elseif (isset($_POST['update'])) {
                $id = $_POST['id'];
                $name = sanitize($_POST['name']);
                $location = sanitize($_POST['location']);
                ParkingArea::update($id, $name, $location);
                flashMessage('success', 'Area updated');
            } elseif (isset($_POST['delete'])) {
                $id = $_POST['id'];
                ParkingArea::delete($id);
                flashMessage('success', 'Area deleted');
            }
            redirect('manage_areas.php');
        }
        $areas = ParkingArea::getAll();
    }

    public static function manageSlots()
    {
        requireRole('admin');
        global $areas, $slots;
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (isset($_POST['create'])) {
                $areaId = $_POST['area_id'];
                $slotNumber = sanitize($_POST['slot_number']);
                ParkingSlot::create($areaId, $slotNumber);
                flashMessage('success', 'Slot created');
            } elseif (isset($_POST['delete'])) {
                $id = $_POST['id'];
                ParkingSlot::delete($id);
                flashMessage('success', 'Slot deleted');
            }
            redirect('manage_slots.php');
        }
        $areas = ParkingArea::getAll();
        $slots = ParkingSlot::getAll();
    }
}

