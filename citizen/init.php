<?php
session_start();
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'citizen') {
    header("Location: ../login.php");
    exit();
}

// Initialize mock reports if not exists
if (!isset($_SESSION['reports'])) {
    $_SESSION['reports'] = [
        'WR-2026-1048' => [
            'id' => 'WR-2026-1048',
            'type' => 'Plastic',
            'desc' => 'Large amount of plastic waste accumulated near the park.',
            'location' => 'No 15, Park Road',
            'area' => 'Colombo 03',
            'priority' => 'Normal',
            'date' => '2026-08-29',
            'time' => '10:00 AM',
            'status' => 'Pending',
            'submitted_at' => '2026-08-28 09:15 AM'
        ],
        'WR-2026-1047' => [
            'id' => 'WR-2026-1047',
            'type' => 'Organic',
            'desc' => 'Garden waste to be collected.',
            'location' => 'No 45, Temple Road',
            'area' => 'Colombo 05',
            'priority' => 'Low',
            'date' => '2026-08-28',
            'time' => '08:00 AM',
            'status' => 'Collected',
            'submitted_at' => '2026-08-27 10:30 AM'
        ],
        'WR-2026-1046' => [
            'id' => 'WR-2026-1046',
            'type' => 'E-Waste',
            'desc' => 'Old television and broken computer parts.',
            'location' => 'No 12, Main Street',
            'area' => 'Colombo 07',
            'priority' => 'High',
            'date' => '2026-08-29',
            'time' => '02:00 PM',
            'status' => 'In Progress',
            'submitted_at' => '2026-08-28 08:00 AM'
        ],
        'WR-2026-1045' => [
            'id' => 'WR-2026-1045',
            'type' => 'Paper',
            'desc' => 'Cardboard boxes from recent delivery.',
            'location' => 'No 8, Galle Road',
            'area' => 'Colombo 04',
            'priority' => 'Normal',
            'date' => '2026-08-26',
            'time' => '11:00 AM',
            'status' => 'Resolved',
            'submitted_at' => '2026-08-25 04:20 PM'
        ],
        'WR-2026-1044' => [
            'id' => 'WR-2026-1044',
            'type' => 'Glass',
            'desc' => 'Broken glass bottles near the community center.',
            'location' => 'No 3, Lake Drive',
            'area' => 'Colombo 08',
            'priority' => 'High',
            'date' => '2026-08-29',
            'time' => '09:00 AM',
            'status' => 'Assigned',
            'submitted_at' => '2026-08-28 07:15 AM'
        ],
        'WR-2026-1043' => [
            'id' => 'WR-2026-1043',
            'type' => 'Mixed Waste',
            'desc' => 'General household waste bags.',
            'location' => 'No 22, Cross Street',
            'area' => 'Colombo 01',
            'priority' => 'Low',
            'date' => '2026-08-30',
            'time' => '08:00 AM',
            'status' => 'Pending',
            'submitted_at' => '2026-08-28 11:45 AM'
        ],
        'WR-2026-1042' => [
            'id' => 'WR-2026-1042',
            'type' => 'Hazardous',
            'desc' => 'Old paint cans and chemicals.',
            'location' => 'No 50, Baseline Road',
            'area' => 'Colombo 09',
            'priority' => 'High',
            'date' => '2026-08-27',
            'time' => '01:00 PM',
            'status' => 'Cancelled',
            'submitted_at' => '2026-08-26 02:30 PM'
        ],
        'WR-2026-1041' => [
            'id' => 'WR-2026-1041',
            'type' => 'Metal',
            'desc' => 'Rusty iron sheets and pipes.',
            'location' => 'No 5, Sea Street',
            'area' => 'Colombo 02',
            'priority' => 'Normal',
            'date' => '2026-08-27',
            'time' => '10:00 AM',
            'status' => 'Collected',
            'submitted_at' => '2026-08-26 09:10 AM'
        ]
    ];
}
?>
