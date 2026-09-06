<?php
session_start();
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'collector') {
    header("Location: ../login.php");
    exit();
}

// Initialize mock collections if not exists
if (!isset($_SESSION['collections'])) {
    $_SESSION['collections'] = [
        'COL-1048' => [
            'id' => 'COL-1048', 'citizen' => 'Nimal Perera', 'phone' => '071 234 5678',
            'location' => 'Green Street', 'area' => 'Colombo 03', 'coords' => '6.9271, 79.8612',
            'type' => 'Plastic', 'est_qty' => '8 kg', 'act_qty' => '',
            'priority' => 'Normal', 'date' => date('Y-m-d'), 'time' => '10:30 AM',
            'status' => 'Upcoming', 'notes' => 'Near the main gate'
        ],
        'COL-1047' => [
            'id' => 'COL-1047', 'citizen' => 'Amal Silva', 'phone' => '077 123 4567',
            'location' => 'Lake Road', 'area' => 'Colombo 03', 'coords' => '6.9250, 79.8600',
            'type' => 'Organic', 'est_qty' => '15 kg', 'act_qty' => '',
            'priority' => 'High', 'date' => date('Y-m-d'), 'time' => '09:30 AM',
            'status' => 'In Progress', 'notes' => 'Two large bags'
        ],
        'COL-1046' => [
            'id' => 'COL-1046', 'citizen' => 'Saman Perera', 'phone' => '076 987 6543',
            'location' => 'Park Avenue', 'area' => 'Colombo 03', 'coords' => '6.9280, 79.8650',
            'type' => 'Paper', 'est_qty' => '5 kg', 'act_qty' => '6 kg',
            'priority' => 'Low', 'date' => date('Y-m-d'), 'time' => '08:30 AM',
            'status' => 'Completed', 'notes' => ''
        ],
        'COL-1045' => [
            'id' => 'COL-1045', 'citizen' => 'Dilshan Kumar', 'phone' => '075 555 1234',
            'location' => 'Temple Road', 'area' => 'Colombo 03', 'coords' => '6.9200, 79.8700',
            'type' => 'Glass', 'est_qty' => '10 kg', 'act_qty' => '',
            'priority' => 'Normal', 'date' => date('Y-m-d'), 'time' => '11:30 AM',
            'status' => 'Upcoming', 'notes' => 'Fragile items'
        ],
        'COL-1044' => [
            'id' => 'COL-1044', 'citizen' => 'Kamal Fernando', 'phone' => '072 111 2222',
            'location' => 'Station Road', 'area' => 'Colombo 03', 'coords' => '6.9300, 79.8550',
            'type' => 'E-Waste', 'est_qty' => '20 kg', 'act_qty' => '',
            'priority' => 'Emergency', 'date' => date('Y-m-d'), 'time' => '01:00 PM',
            'status' => 'Upcoming', 'notes' => 'Old refrigerator'
        ],
        'COL-1043' => [
            'id' => 'COL-1043', 'citizen' => 'Sunil Perera', 'phone' => '078 333 4444',
            'location' => 'Galle Road', 'area' => 'Colombo 04', 'coords' => '6.9100, 79.8500',
            'type' => 'Mixed Waste', 'est_qty' => '12 kg', 'act_qty' => '12 kg',
            'priority' => 'Normal', 'date' => date('Y-m-d', strtotime('-1 days')), 'time' => '10:00 AM',
            'status' => 'Completed', 'notes' => ''
        ],
        'COL-1042' => [
            'id' => 'COL-1042', 'citizen' => 'Nalin Silva', 'phone' => '074 777 8888',
            'location' => 'Duplication Road', 'area' => 'Colombo 04', 'coords' => '6.9050, 79.8550',
            'type' => 'Plastic', 'est_qty' => '5 kg', 'act_qty' => '5 kg',
            'priority' => 'Normal', 'date' => date('Y-m-d', strtotime('-1 days')), 'time' => '11:00 AM',
            'status' => 'Completed', 'notes' => ''
        ],
        'COL-1041' => [
            'id' => 'COL-1041', 'citizen' => 'Ruwan Kumara', 'phone' => '070 123 1234',
            'location' => 'Havelock Road', 'area' => 'Colombo 05', 'coords' => '6.8900, 79.8600',
            'type' => 'Organic', 'est_qty' => '25 kg', 'act_qty' => '',
            'priority' => 'High', 'date' => date('Y-m-d', strtotime('-2 days')), 'time' => '09:00 AM',
            'status' => 'Issue Reported', 'notes' => 'Customer unavailable'
        ]
    ];
}

if (!isset($_SESSION['route_status'])) {
    $_SESSION['route_status'] = 'Not Started';
}
?>
