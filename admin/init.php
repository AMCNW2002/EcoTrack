<?php
session_start();
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../login.php");
    exit();
}

// 1. Initialize Citizen Reports if not exists
if (!isset($_SESSION['reports'])) {
    $reports = [];
    $statuses = ['Pending Review', 'Pending Review', 'Approved', 'Assigned', 'Scheduled', 'In Progress', 'Collected', 'Resolved', 'Rejected', 'Issue Reported'];
    $priorities = ['Low', 'Normal', 'High', 'Emergency'];
    $types = ['Organic', 'Plastic', 'Paper', 'Glass', 'Metal', 'E-Waste', 'Hazardous', 'Mixed'];
    $citizens = ['Nimal Perera', 'Amal Silva', 'Saman Perera', 'Kasun Perera', 'Dilshan Kumar', 'Tharindu Silva', 'Ravindu Perera'];
    
    for ($i=1; $i<=20; $i++) {
        $id = 'WR-2026-10' . str_pad($i + 30, 2, '0', STR_PAD_LEFT);
        $status = $statuses[array_rand($statuses)];
        $date = date('Y-m-d', strtotime('-' . rand(0, 5) . ' days'));
        
        $reports[$id] = [
            'id' => $id,
            'status' => $status,
            'type' => $types[array_rand($types)],
            'description' => 'A large amount of ' . strtolower($types[array_rand($types)]) . ' waste accumulated.',
            'priority' => $priorities[array_rand($priorities)],
            'est_qty' => rand(5, 50) . ' kg',
            'location' => 'House ' . rand(10, 99) . ', Main Street',
            'area' => 'Colombo ' . str_pad(rand(1, 10), 2, '0', STR_PAD_LEFT),
            'coords' => (6.9 + (rand(-100, 100)/10000)) . ', ' . (79.8 + (rand(-100, 100)/10000)),
            'date' => $date,
            'time' => '10:00 AM',
            'citizen' => $citizens[array_rand($citizens)],
            'phone' => '07' . rand(1, 8) . ' ' . rand(100, 999) . ' ' . rand(1000, 9999),
            'email' => 'citizen' . $i . '@example.com'
        ];
    }
    $_SESSION['reports'] = $reports;
}

// 2. Initialize Collectors if not exists
if (!isset($_SESSION['collectors'])) {
    $collectors = [];
    $names = ['Kasun Perera', 'Nimal Silva', 'Amal Fernando', 'Saman Perera', 'Dilshan Kumar', 'Tharindu Silva', 'Ravindu Perera', 'Chamod Fernando', 'Isuru Perera', 'Dinesh Silva', 'Ruwan Kumara', 'Supun Silva', 'Gayan Perera', 'Lahiru Fernando', 'Asanka Silva'];
    for ($i=1; $i<=15; $i++) {
        $id = 'COL-' . str_pad($i, 3, '0', STR_PAD_LEFT);
        $name = $names[$i-1] ?? ('Collector ' . $i);
        $collectors[$id] = [
            'id' => $id,
            'name' => $name,
            'area' => 'Colombo ' . str_pad(rand(1, 10), 2, '0', STR_PAD_LEFT),
            'vehicle' => 'WP-CAB-' . rand(1000, 9999),
            'status' => (rand(1,10) > 2) ? 'Available' : ((rand(1,2)==1) ? 'Busy' : 'Off Duty'),
            'workload' => rand(0, 15),
            'phone' => '071 222 ' . rand(1000, 9999),
            'email' => strtolower(str_replace(' ', '.', $name)) . '@ecotrack.lk',
            'performance' => rand(85, 98),
            'joined' => date('d M Y', strtotime('-'.rand(100, 1000).' days'))
        ];
    }
    $_SESSION['collectors'] = $collectors;
}

// 3. Initialize Collections if not exists
if (!isset($_SESSION['collections'])) {
    $collections = [];
    $cid_counter = 1;
    foreach ($_SESSION['reports'] as $rid => $report) {
        if (in_array($report['status'], ['Scheduled', 'In Progress', 'Collected', 'Resolved', 'Issue Reported'])) {
            $col_id = 'COL-2026-' . str_pad($cid_counter++, 3, '0', STR_PAD_LEFT);
            $collector = $_SESSION['collectors'][array_rand($_SESSION['collectors'])];
            
            $col_status = $report['status'];
            if ($col_status === 'Resolved') $col_status = 'Completed';
            if ($col_status === 'Collected') $col_status = 'Completed';
            
            $collections[$col_id] = [
                'id' => $col_id,
                'report_id' => $rid,
                'collector_id' => $collector['id'],
                'collector_name' => $collector['name'],
                'citizen' => $report['citizen'],
                'phone' => $report['phone'],
                'location' => $report['location'],
                'area' => $report['area'],
                'coords' => $report['coords'],
                'type' => $report['type'],
                'est_qty' => $report['est_qty'],
                'act_qty' => ($col_status === 'Completed') ? $report['est_qty'] : '',
                'priority' => $report['priority'],
                'date' => $report['date'],
                'time' => $report['time'],
                'status' => $col_status,
                'notes' => ''
            ];
            
            $_SESSION['reports'][$rid]['assigned_collector'] = $collector['name'];
            $_SESSION['reports'][$rid]['collector_id'] = $collector['id'];
        }
    }
    $_SESSION['collections'] = $collections;
}

// 4. Initialize Areas if not exists
if (!isset($_SESSION['areas'])) {
    $areas = [];
    for ($i = 1; $i <= 10; $i++) {
        $code = str_pad($i, 2, '0', STR_PAD_LEFT);
        $areas['CMB-'.$code] = [
            'id' => 'CMB-'.$code,
            'name' => 'Colombo ' . $code,
            'population' => rand(30000, 80000),
            'households' => rand(8000, 20000),
            'daily_waste' => rand(1000, 3000) . ' kg',
            'status' => 'Active'
        ];
    }
    $_SESSION['areas'] = $areas;
}

// 5. Initialize Vehicles if not exists
if (!isset($_SESSION['vehicles'])) {
    $vehicles = [];
    $types = ['Garbage Truck', 'Garbage Truck', 'Recycling Truck', 'Mini Truck'];
    for ($i = 1; $i <= 15; $i++) {
        $vid = 'WP-CAB-' . rand(1000, 9999);
        $vehicles[$vid] = [
            'id' => $vid,
            'type' => $types[array_rand($types)],
            'capacity' => (rand(1, 2) === 1 ? '2,500 kg' : '1,500 kg'),
            'area' => 'Colombo ' . str_pad(rand(1, 10), 2, '0', STR_PAD_LEFT),
            'status' => (rand(1, 10) > 2) ? 'Available' : ((rand(1, 2) === 1) ? 'In Use' : 'Maintenance'),
            'assigned_collector' => 'None',
            'last_maintenance' => date('d M Y', strtotime('-'.rand(10, 60).' days')),
            'next_maintenance' => date('d M Y', strtotime('+'.rand(10, 30).' days'))
        ];
    }
    foreach($_SESSION['collectors'] as &$c) {
        if ($c['status'] !== 'Off Duty') {
            $v = &$vehicles[array_rand($vehicles)];
            $c['vehicle'] = $v['id'];
            $v['assigned_collector'] = $c['name'];
        }
    }
    $_SESSION['vehicles'] = $vehicles;
}

// 6. Initialize Routes if not exists
if (!isset($_SESSION['routes'])) {
    $routes = [];
    $statuses = ['Draft', 'Scheduled', 'Active', 'Completed', 'Paused'];
    for ($i = 1; $i <= 20; $i++) {
        $area = 'Colombo ' . str_pad(rand(1, 10), 2, '0', STR_PAD_LEFT);
        $rid = 'RT-2026-' . str_pad($i, 3, '0', STR_PAD_LEFT);
        $routes[$rid] = [
            'id' => $rid,
            'name' => $area . ' ' . (rand(1, 2) === 1 ? 'Morning' : 'Afternoon') . ' Route',
            'area' => $area,
            'collection_type' => 'Mixed',
            'stops' => rand(10, 25),
            'distance' => (rand(100, 250) / 10) . ' km',
            'duration' => rand(3, 6) . 'h 30m',
            'status' => $statuses[array_rand($statuses)],
            'schedule_time' => (rand(1, 2) === 1 ? '08:00 AM' : '01:00 PM'),
            'collector_name' => 'None',
            'collector_id' => 'None',
            'vehicle_id' => 'None'
        ];
    }
    $_SESSION['routes'] = $routes;
}

// 7. Initialize Users (Citizens, Admins, and Collectors)
if (!isset($_SESSION['users'])) {
    $users = [];
    $citizens_names = ['Nimal Perera', 'Amal Silva', 'Saman Perera', 'Dilshan Kumar', 'Tharindu Silva', 'Ravindu Perera', 'Kamal Fernando', 'Sunil Perera', 'Jagath Silva', 'Ruwan Fernando', 'Roshan Silva', 'Nuwan Perera', 'Ashan Kumar', 'Asiri Fernando', 'Chathura Perera', 'Darshana Silva', 'Pradeep Kumar', 'Nuwanthi Perera', 'Sanduni Silva', 'Oshadi Fernando'];
    
    // Add Citizens
    for ($i=1; $i<=20; $i++) {
        $uid = 'USR-' . str_pad(1000 + $i, 4, '0', STR_PAD_LEFT);
        $name = $citizens_names[$i-1] ?? ('Citizen ' . $i);
        $users[$uid] = [
            'id' => $uid,
            'name' => $name,
            'email' => strtolower(str_replace(' ', '.', $name)) . rand(10,99) . '@example.com',
            'phone' => '07' . rand(1,8) . ' ' . rand(100,999) . ' ' . rand(1000,9999),
            'role' => 'Citizen',
            'area' => 'Colombo ' . str_pad(rand(1, 10), 2, '0', STR_PAD_LEFT),
            'address' => rand(10, 200) . ', Main Street',
            'status' => (rand(1,10) > 1) ? 'Active' : 'Inactive',
            'joined' => date('d M Y', strtotime('-'.rand(10, 500).' days')),
            'reports_submitted' => rand(0, 15),
            'collections' => rand(0, 20)
        ];
    }
    
    // Add Collectors (link with $_SESSION['collectors'])
    foreach ($_SESSION['collectors'] as $c) {
        $uid = 'USR-' . str_pad(2000 + count($users), 4, '0', STR_PAD_LEFT);
        $users[$uid] = [
            'id' => $uid,
            'collector_ref' => $c['id'],
            'name' => $c['name'],
            'email' => $c['email'],
            'phone' => $c['phone'],
            'role' => 'Collector',
            'area' => $c['area'],
            'address' => 'Staff Quarters, ' . $c['area'],
            'status' => ($c['status'] === 'Suspended') ? 'Suspended' : 'Active',
            'joined' => $c['joined'],
            'vehicle' => $c['vehicle']
        ];
    }
    
    // Add Admins
    for ($i=1; $i<=5; $i++) {
        $uid = 'USR-' . str_pad(3000 + $i, 4, '0', STR_PAD_LEFT);
        $users[$uid] = [
            'id' => $uid,
            'name' => 'Admin ' . $i,
            'email' => 'admin'.$i.'@ecotrack.lk',
            'phone' => '011 222 ' . rand(1000,9999),
            'role' => 'Admin',
            'area' => 'All Areas',
            'address' => 'EcoTrack HQ',
            'status' => 'Active',
            'joined' => date('d M Y', strtotime('-'.rand(500, 1000).' days'))
        ];
    }
    
    $_SESSION['users'] = $users;
}

// 8. Extract Citizens to separate array for easy access in mock DB
if (!isset($_SESSION['citizens'])) {
    $citizens = [];
    foreach ($_SESSION['users'] as $u) {
        if ($u['role'] === 'Citizen') {
            $cid = str_replace('USR-', 'CIT-', $u['id']);
            $citizens[$cid] = $u;
            $citizens[$cid]['id'] = $cid;
            $citizens[$cid]['user_ref'] = $u['id'];
        }
    }
    $_SESSION['citizens'] = $citizens;
}

// 9. Initialize Waste Categories
if (!isset($_SESSION['waste_categories'])) {
    $_SESSION['waste_categories'] = [
        'CAT-001' => ['id' => 'CAT-001', 'name' => 'Organic Waste', 'description' => 'Food scraps, vegetable waste, and garden waste.', 'classification' => 'Biodegradable', 'recyclable' => 'No', 'disposal' => 'Composting', 'status' => 'Active', 'icon' => 'fa-leaf', 'color' => 'text-success'],
        'CAT-002' => ['id' => 'CAT-002', 'name' => 'Plastic Waste', 'description' => 'Bottles, containers, and clean plastic bags.', 'classification' => 'Recyclable', 'recyclable' => 'Yes', 'disposal' => 'Recycling', 'status' => 'Active', 'icon' => 'fa-bottle-water', 'color' => 'text-info'],
        'CAT-003' => ['id' => 'CAT-003', 'name' => 'Paper Waste', 'description' => 'Newspapers, cardboard, and office paper.', 'classification' => 'Recyclable', 'recyclable' => 'Yes', 'disposal' => 'Recycling', 'status' => 'Active', 'icon' => 'fa-newspaper', 'color' => 'text-primary'],
        'CAT-004' => ['id' => 'CAT-004', 'name' => 'Glass Waste', 'description' => 'Glass bottles and jars.', 'classification' => 'Recyclable', 'recyclable' => 'Yes', 'disposal' => 'Recycling', 'status' => 'Active', 'icon' => 'fa-wine-bottle', 'color' => 'text-secondary'],
        'CAT-005' => ['id' => 'CAT-005', 'name' => 'Metal Waste', 'description' => 'Aluminum cans, tins, and scrap metal.', 'classification' => 'Recyclable', 'recyclable' => 'Yes', 'disposal' => 'Recycling', 'status' => 'Active', 'icon' => 'fa-spray-can', 'color' => 'text-secondary'],
        'CAT-006' => ['id' => 'CAT-006', 'name' => 'E-Waste', 'description' => 'Old phones, computers, and batteries.', 'classification' => 'Special Waste', 'recyclable' => 'Yes', 'disposal' => 'Special Treatment', 'status' => 'Active', 'icon' => 'fa-plug', 'color' => 'text-warning'],
        'CAT-007' => ['id' => 'CAT-007', 'name' => 'Hazardous Waste', 'description' => 'Chemicals, medical waste, and paint.', 'classification' => 'Hazardous', 'recyclable' => 'No', 'disposal' => 'Special Treatment', 'status' => 'Active', 'icon' => 'fa-triangle-exclamation', 'color' => 'text-danger'],
        'CAT-008' => ['id' => 'CAT-008', 'name' => 'Mixed Waste', 'description' => 'General household waste not separated.', 'classification' => 'Non-Recyclable', 'recyclable' => 'Partially', 'disposal' => 'Landfill', 'status' => 'Active', 'icon' => 'fa-trash', 'color' => 'text-dark']
    ];
}

// 10. Initialize Recycling Centers
if (!isset($_SESSION['recycling_centers'])) {
    $_SESSION['recycling_centers'] = [
        'RC-001' => ['id' => 'RC-001', 'name' => 'GreenCycle Colombo', 'area' => 'Colombo 05', 'capacity' => 5000, 'current_load' => 3200, 'accepts' => ['Plastic', 'Paper', 'Glass', 'Metal'], 'status' => 'Active'],
        'RC-002' => ['id' => 'RC-002', 'name' => 'EcoRecycle Center', 'area' => 'Colombo 03', 'capacity' => 8000, 'current_load' => 4100, 'accepts' => ['Plastic', 'Metal', 'E-Waste'], 'status' => 'Active'],
        'RC-003' => ['id' => 'RC-003', 'name' => 'CleanEarth Recycling', 'area' => 'Colombo 08', 'capacity' => 3000, 'current_load' => 2800, 'accepts' => ['Paper', 'Glass'], 'status' => 'Active'],
        'RC-004' => ['id' => 'RC-004', 'name' => 'UrbanCycle Hub', 'area' => 'Colombo 02', 'capacity' => 10000, 'current_load' => 7500, 'accepts' => ['Plastic', 'Paper', 'Glass', 'Metal', 'E-Waste'], 'status' => 'Active'],
        'RC-005' => ['id' => 'RC-005', 'name' => 'TechScrap LK', 'area' => 'Colombo 10', 'capacity' => 2000, 'current_load' => 800, 'accepts' => ['E-Waste'], 'status' => 'Active']
    ];
}

// 11. Initialize Waste Records
if (!isset($_SESSION['waste_records'])) {
    $_SESSION['waste_records'] = [];
}

// 12. Initialize Recyclable Waste Tracking
if (!isset($_SESSION['recyclable_waste'])) {
    $rw = [];
    for ($i=1; $i<=20; $i++) {
        $id = 'RW-2026-' . str_pad($i, 3, '0', STR_PAD_LEFT);
        $types = ['Plastic', 'Paper', 'Glass', 'Metal'];
        $rw[$id] = [
            'id' => $id,
            'collection_id' => 'COL-2026-' . str_pad(rand(1,50), 3, '0', STR_PAD_LEFT),
            'type' => $types[array_rand($types)],
            'area' => 'Colombo ' . str_pad(rand(1,10), 2, '0', STR_PAD_LEFT),
            'quantity' => rand(5, 40) . ' kg',
            'destination' => 'GreenCycle Colombo',
            'date' => date('d M Y', strtotime('-'.rand(0,10).' days')),
            'status' => (rand(1,3) == 1) ? 'Processed' : 'Processing'
        ];
    }
    $_SESSION['recyclable_waste'] = $rw;
}

// 13. Initialize Complaints
if (!isset($_SESSION['complaints'])) {
    $complaints = [];
    $types = ['Missed Collection', 'Late Collection', 'Wrong Collection', 'Collector Behaviour', 'Vehicle Issue', 'Illegal Dumping', 'Overflowing Bin', 'Other'];
    $priorities = ['Normal', 'Normal', 'Normal', 'High', 'High', 'Emergency'];
    $statuses = ['New', 'Pending Review', 'Pending Review', 'Assigned', 'Investigating', 'Resolved', 'Resolved', 'Resolved', 'Closed', 'Escalated'];
    $areas = ['Colombo 01', 'Colombo 02', 'Colombo 03', 'Colombo 04', 'Colombo 05', 'Colombo 06', 'Colombo 07', 'Colombo 08'];
    
    for ($i=1; $i<=30; $i++) {
        $id = 'CMP-2026-' . str_pad($i, 4, '0', STR_PAD_LEFT);
        $status = $statuses[array_rand($statuses)];
        $complaints[$id] = [
            'id' => $id,
            'citizen' => 'Citizen ' . $i,
            'phone' => '071 234 56' . str_pad(rand(10,99), 2, '0', STR_PAD_LEFT),
            'email' => 'citizen'.$i.'@example.com',
            'type' => $types[array_rand($types)],
            'description' => 'This is a mock description for complaint ' . $id,
            'priority' => $priorities[array_rand($priorities)],
            'area' => $areas[array_rand($areas)],
            'location' => rand(10, 99) . ' Main Street, ' . $areas[array_rand($areas)],
            'date' => date('d M Y', strtotime('-'.rand(0,10).' days')),
            'status' => $status,
            'collection_id' => (rand(1,3) == 1) ? 'COL-2026-' . str_pad(rand(1,50), 3, '0', STR_PAD_LEFT) : null,
            'assigned_to' => in_array($status, ['Assigned', 'Investigating', 'Resolved', 'Escalated']) ? 'Support Officer ' . rand(1,5) : null,
            'resolution' => in_array($status, ['Resolved', 'Closed']) ? 'Issue was investigated and resolved successfully.' : null
        ];
    }
    $_SESSION['complaints'] = $complaints;
}

// 14. Initialize Feedback
if (!isset($_SESSION['feedback'])) {
    $feedback = [];
    for ($i=1; $i<=25; $i++) {
        $feedback[] = [
            'citizen' => 'Citizen ' . rand(1, 30),
            'rating' => rand(3, 5),
            'collector' => 'COL-' . str_pad(rand(1,15), 3, '0', STR_PAD_LEFT),
            'area' => 'Colombo ' . str_pad(rand(1,10), 2, '0', STR_PAD_LEFT),
            'comment' => 'Service was ' . (rand(1,2)==1 ? 'good' : 'excellent') . ' and on time.',
            'date' => date('d M Y', strtotime('-'.rand(0,10).' days'))
        ];
    }
    $_SESSION['feedback'] = $feedback;
}

// 15. Initialize Field Issues
if (!isset($_SESSION['field_issues'])) {
    $issues = [];
    $issueTypes = ['Vehicle Breakdown', 'Road Blocked', 'Unsafe Location', 'Excessive Waste'];
    for ($i=1; $i<=15; $i++) {
        $id = 'ISS-2026-' . str_pad($i, 4, '0', STR_PAD_LEFT);
        $issues[$id] = [
            'id' => $id,
            'type' => $issueTypes[array_rand($issueTypes)],
            'collector' => 'COL-' . str_pad(rand(1,15), 3, '0', STR_PAD_LEFT),
            'route' => 'Colombo ' . str_pad(rand(1,10), 2, '0', STR_PAD_LEFT) . ' Morning',
            'location' => 'Street ' . rand(1,10),
            'status' => (rand(1,3) == 1) ? 'Resolved' : 'Open',
            'date' => date('d M Y')
        ];
    }
    $_SESSION['field_issues'] = $issues;
}

// 16. Initialize Notifications
if (!isset($_SESSION['notifications'])) {
    $_SESSION['notifications'] = [];
}
?>
