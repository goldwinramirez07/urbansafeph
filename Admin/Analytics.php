<?php
include('../config/mysql.php');

$heatData = [];
$result = $conn->query("SELECT latitude, longitude FROM firereport");

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $heatData[] = [
            'lat' => (float)$row['longitude'],
            'lng' => (float)$row['latitude'],
            'val' => 0.5
        ];
    }
}

// Prepare the heatmap data structure
$heatmapData = [
    'max' => 15, // You can adjust this based on your needs
    'data' => $heatData
];

$heatData1 = [];
$result1 = $conn->query("SELECT latitude, longitude FROM floodreport");

if ($result1->num_rows > 0) {
    while ($row1 = $result1->fetch_assoc()) {
        $heatData1[] = [
            'lat' => (float)$row1['longitude'],
            'lng' => (float)$row1['latitude'],
            'val' => 0.5
        ];
    }
}

// Prepare the heatmap data structure
$heatmapData1 = [
    'max' => 15, // You can adjust this based on your needs
    'data' => $heatData1
];

$heatData2 = [];
$result2 = $conn->query("SELECT latitude, longitude FROM incidentreport");

if ($result2->num_rows > 0) {
    while ($row2 = $result2->fetch_assoc()) {
        $heatData2[] = [
            'lat' => (float)$row2['longitude'],
            'lng' => (float)$row2['latitude'],
            'val' => 0.5
        ];
    }
}

// Prepare the heatmap data structure for map3
$heatmapData2 = [
    'max' => 15, // You can adjust this based on your needs
    'data' => $heatData2
];

$heatData3 = [];
$result3 = $conn->query("SELECT latitude, longitude FROM crimereport");

if ($result3->num_rows > 0) {
    while ($row3 = $result3->fetch_assoc()) {
        $heatData3[] = [
            'lat' => (float)$row3['longitude'],
            'lng' => (float)$row3['latitude'],
            'val' => 0.5
        ];
    }
}

// Prepare the heatmap data structure for map4
$heatmapData3 = [
    'max' => 15, // You can adjust this based on your needs
    'data' => $heatData3
];

// Initialize variables
$totalFireReports = 0;
$totalFloodReports = 0;
$totalIncidentReports = 0;
$totalCrimeReports = 0;

$totalRespondedFireReports = 0;
$totalRespondedFloodReports = 0;
$totalRespondedIncidentReports = 0;
$totalRespondedCrimeReports = 0;

if ($conn->query("SELECT COUNT(*) as count FROM firereport")->fetch_assoc()['count'] > 0) {
    $totalFireReports = $conn->query("SELECT COUNT(id) as count FROM firereport")->fetch_assoc()['count'];
    $totalRespondedFireReports = $conn->query("SELECT COUNT(id) as count FROM firereport WHERE respond = 1")->fetch_assoc()['count'];
}

if ($conn->query("SELECT COUNT(*) as count FROM incidentreport")->fetch_assoc()['count'] > 0) {
    $totalIncidentReports = $conn->query("SELECT COUNT(id) as count FROM incidentreport")->fetch_assoc()['count'];
    $totalRespondedIncidentReports = $conn->query("SELECT COUNT(id) as count FROM incidentreport WHERE respond = 1")->fetch_assoc()['count'];
}

if ($conn->query("SELECT COUNT(*) as count FROM crimereport")->fetch_assoc()['count'] > 0) {
    $totalCrimeReports = $conn->query("SELECT COUNT(id) as count FROM crimereport")->fetch_assoc()['count'];
    $totalRespondedCrimeReports = $conn->query("SELECT COUNT(id) as count FROM crimereport WHERE respond = 1")->fetch_assoc()['count'];
}

if ($conn->query("SELECT COUNT(*) as count FROM floodreport")->fetch_assoc()['count'] > 0) {
    $totalFloodReports = $conn->query("SELECT COUNT(id) as count FROM floodreport")->fetch_assoc()['count'];
    $totalRespondedFloodReports = $conn->query("SELECT COUNT(id) as count FROM floodreport WHERE respond = 1")->fetch_assoc()['count'];
}

$totalReports = $totalFireReports + $totalFloodReports + $totalIncidentReports + $totalCrimeReports;
$totalRespond = $totalRespondedFireReports + $totalRespondedIncidentReports + $totalRespondedCrimeReports;

$data = [
    'Fire' => $totalFireReports,
    'Flood' => $totalFloodReports,
    'Incident' => $totalIncidentReports,
    'Crime' => $totalCrimeReports
];

// Function to calculate Mean
function calculateMean($data) {
    return array_sum($data) / count($data);
}

// Function to calculate Median
function calculateMedian($data) {
    sort($data);
    $count = count($data);
    $middle = floor(($count - 1) / 2);
    
    if ($count % 2) {
        return $data[$middle];
    } else {
        return ($data[$middle] + $data[$middle + 1]) / 2;
    }
}

function calculateMode($data) {
    // Get the highest frequency value
    $maxCount = max($data);
    
    // Get the category(ies) with the highest value
    $modes = array_keys($data, $maxCount);
    
    // If every category has a unique value, there's no mode
    return (count($modes) == count($data)) ? [] : $modes;
}

// Function to calculate Standard Deviation
function calculateStandardDeviation($data, $mean) {
    $sumOfSquares = 0;
    foreach ($data as $value) {
        $sumOfSquares += pow($value - $mean, 2);
    }
    return sqrt($sumOfSquares / count($data));
}

// Function to calculate Variance
function calculateVariance($stdDev) {
    return pow($stdDev, 2);
}

// Function to calculate IQR
function calculateIQR($data) {
    sort($data);
    $count = count($data);
    $q1 = $data[floor(($count - 1) / 4)];
    $q3 = $data[floor(3 * ($count - 1) / 4)];
    return $q3 - $q1;
}

// Calculate Mean, Median, Mode, Standard Deviation, Variance, and IQR
$mean = calculateMean($data);
$median = calculateMedian($data);
$mode = calculateMode($data);
$stdDev = calculateStandardDeviation($data, $mean);
$variance = calculateVariance($stdDev);
$iqr = calculateIQR($data);

// Initialize variables
$wk1firereport = 0;
$wk2firereport = 0;
$wk3firereport = 0;
$wk4firereport = 0;

$wk1floodreport = 0; 
$wk2floodreport = 0;  
$wk3floodreport = 0;
$wk4floodreport = 0;

$wk1incidentreport = 0;  
$wk2incidentreport = 0; 
$wk3incidentreport = 0;
$wk4incidentreport = 0;

$wk1crimereport = 0;  
$wk2crimereport = 0;  
$wk3crimereport = 0; 
$wk4crimereport = 0; 

// Current Week Fire Reports
$wk1firereport = $conn->query("SELECT COUNT(*) AS total_reports FROM firereport WHERE YEAR(date_time) = YEAR(CURDATE()) AND WEEK(date_time, 1) = WEEK(CURDATE(), 1)")->fetch_assoc()['total_reports'];
$wk2firereport = $conn->query("SELECT COUNT(*) AS total_reports FROM firereport WHERE YEAR(date_time) = YEAR(CURDATE()) AND WEEK(date_time, 1) = WEEK(CURDATE(), 1) - 1")->fetch_assoc()['total_reports'];
$wk3firereport = $conn->query("SELECT COUNT(*) AS total_reports FROM firereport WHERE YEAR(date_time) = YEAR(CURDATE()) AND WEEK(date_time, 1) = WEEK(CURDATE(), 1) - 2")->fetch_assoc()['total_reports'];
$wk4firereport = $conn->query("SELECT COUNT(*) AS total_reports FROM firereport WHERE YEAR(date_time) = YEAR(CURDATE()) AND WEEK(date_time, 1) = WEEK(CURDATE(), 1) - 3")->fetch_assoc()['total_reports'];

// Current Week Flood Reports
$wk1floodreport = $conn->query("SELECT COUNT(*) AS total_reports FROM floodreport WHERE YEAR(date_time) = YEAR(CURDATE()) AND WEEK(date_time, 1) = WEEK(CURDATE(), 1)")->fetch_assoc()['total_reports'];
$wk2floodreport = $conn->query("SELECT COUNT(*) AS total_reports FROM floodreport WHERE YEAR(date_time) = YEAR(CURDATE()) AND WEEK(date_time, 1) = WEEK(CURDATE(), 1) - 1")->fetch_assoc()['total_reports'];
$wk3floodreport = $conn->query("SELECT COUNT(*) AS total_reports FROM floodreport WHERE YEAR(date_time) = YEAR(CURDATE()) AND WEEK(date_time, 1) = WEEK(CURDATE(), 1) - 2")->fetch_assoc()['total_reports'];
$wk4floodreport = $conn->query("SELECT COUNT(*) AS total_reports FROM floodreport WHERE YEAR(date_time) = YEAR(CURDATE()) AND WEEK(date_time, 1) = WEEK(CURDATE(), 1) - 3")->fetch_assoc()['total_reports'];

// Current Week Incident Reports
$wk1incidentreport = $conn->query("SELECT COUNT(*) AS total_reports FROM incidentreport WHERE YEAR(date_time) = YEAR(CURDATE()) AND WEEK(date_time, 1) = WEEK(CURDATE(), 1)")->fetch_assoc()['total_reports'];
$wk2incidentreport = $conn->query("SELECT COUNT(*) AS total_reports FROM incidentreport WHERE YEAR(date_time) = YEAR(CURDATE()) AND WEEK(date_time, 1) = WEEK(CURDATE(), 1) - 1")->fetch_assoc()['total_reports'];
$wk3incidentreport = $conn->query("SELECT COUNT(*) AS total_reports FROM incidentreport WHERE YEAR(date_time) = YEAR(CURDATE()) AND WEEK(date_time, 1) = WEEK(CURDATE(), 1) - 2")->fetch_assoc()['total_reports'];
$wk4incidentreport = $conn->query("SELECT COUNT(*) AS total_reports FROM incidentreport WHERE YEAR(date_time) = YEAR(CURDATE()) AND WEEK(date_time, 1) = WEEK(CURDATE(), 1) - 3")->fetch_assoc()['total_reports'];

// Current Week Crime Reports
$wk1crimereport = $conn->query("SELECT COUNT(*) AS total_reports FROM crimereport WHERE YEAR(date_time) = YEAR(CURDATE()) AND WEEK(date_time, 1) = WEEK(CURDATE(), 1)")->fetch_assoc()['total_reports'];
$wk2crimereport = $conn->query("SELECT COUNT(*) AS total_reports FROM crimereport WHERE YEAR(date_time) = YEAR(CURDATE()) AND WEEK(date_time, 1) = WEEK(CURDATE(), 1) - 1")->fetch_assoc()['total_reports'];
$wk3crimereport = $conn->query("SELECT COUNT(*) AS total_reports FROM crimereport WHERE YEAR(date_time) = YEAR(CURDATE()) AND WEEK(date_time, 1) = WEEK(CURDATE(), 1) - 2")->fetch_assoc()['total_reports'];
$wk4crimereport = $conn->query("SELECT COUNT(*) AS total_reports FROM crimereport WHERE YEAR(date_time) = YEAR(CURDATE()) AND WEEK(date_time, 1) = WEEK(CURDATE(), 1) - 3")->fetch_assoc()['total_reports'];


// Weekly Fire Reports (Total and Responded)
$wk1FireTotal = $conn->query("SELECT COUNT(*) AS total_reports FROM firereport WHERE YEAR(date_time) = YEAR(CURDATE()) AND WEEK(date_time, 1) = WEEK(CURDATE(), 1)")->fetch_assoc()['total_reports'];
$wk1FireResponded = $conn->query("SELECT COUNT(*) AS responded_reports FROM firereport WHERE YEAR(date_time) = YEAR(CURDATE()) AND WEEK(date_time, 1) = WEEK(CURDATE(), 1) AND respond = 1")->fetch_assoc()['responded_reports'];

$wk2FireTotal = $conn->query("SELECT COUNT(*) AS total_reports FROM firereport WHERE YEAR(date_time) = YEAR(CURDATE()) AND WEEK(date_time, 1) = WEEK(CURDATE(), 1) - 1")->fetch_assoc()['total_reports'];
$wk2FireResponded = $conn->query("SELECT COUNT(*) AS responded_reports FROM firereport WHERE YEAR(date_time) = YEAR(CURDATE()) AND WEEK(date_time, 1) = WEEK(CURDATE(), 1) - 1 AND respond = 1")->fetch_assoc()['responded_reports'];

$wk3FireTotal = $conn->query("SELECT COUNT(*) AS total_reports FROM firereport WHERE YEAR(date_time) = YEAR(CURDATE()) AND WEEK(date_time, 1) = WEEK(CURDATE(), 1) - 2")->fetch_assoc()['total_reports'];
$wk3FireResponded = $conn->query("SELECT COUNT(*) AS responded_reports FROM firereport WHERE YEAR(date_time) = YEAR(CURDATE()) AND WEEK(date_time, 1) = WEEK(CURDATE(), 1) - 2 AND respond = 1")->fetch_assoc()['responded_reports'];

$wk4FireTotal = $conn->query("SELECT COUNT(*) AS total_reports FROM firereport WHERE YEAR(date_time) = YEAR(CURDATE()) AND WEEK(date_time, 1) = WEEK(CURDATE(), 1) - 3")->fetch_assoc()['total_reports'];
$wk4FireResponded = $conn->query("SELECT COUNT(*) AS responded_reports FROM firereport WHERE YEAR(date_time) = YEAR(CURDATE()) AND WEEK(date_time, 1) = WEEK(CURDATE(), 1) - 3 AND respond = 1")->fetch_assoc()['responded_reports'];

// Weekly Flood Reports (Total and Responded)
$wk1FloodTotal = $conn->query("SELECT COUNT(*) AS total_reports FROM floodreport WHERE YEAR(date_time) = YEAR(CURDATE()) AND WEEK(date_time, 1) = WEEK(CURDATE(), 1)")->fetch_assoc()['total_reports'];
$wk1FloodResponded = $conn->query("SELECT COUNT(*) AS responded_reports FROM floodreport WHERE YEAR(date_time) = YEAR(CURDATE()) AND WEEK(date_time, 1) = WEEK(CURDATE(), 1) AND respond = 1")->fetch_assoc()['responded_reports'];

$wk2FloodTotal = $conn->query("SELECT COUNT(*) AS total_reports FROM floodreport WHERE YEAR(date_time) = YEAR(CURDATE()) AND WEEK(date_time, 1) = WEEK(CURDATE(), 1) - 1")->fetch_assoc()['total_reports'];
$wk2FloodResponded = $conn->query("SELECT COUNT(*) AS responded_reports FROM floodreport WHERE YEAR(date_time) = YEAR(CURDATE()) AND WEEK(date_time, 1) = WEEK(CURDATE(), 1) - 1 AND respond = 1")->fetch_assoc()['responded_reports'];

$wk3FloodTotal = $conn->query("SELECT COUNT(*) AS total_reports FROM floodreport WHERE YEAR(date_time) = YEAR(CURDATE()) AND WEEK(date_time, 1) = WEEK(CURDATE(), 1) - 2")->fetch_assoc()['total_reports'];
$wk3FloodResponded = $conn->query("SELECT COUNT(*) AS responded_reports FROM floodreport WHERE YEAR(date_time) = YEAR(CURDATE()) AND WEEK(date_time, 1) = WEEK(CURDATE(), 1) - 2 AND respond = 1")->fetch_assoc()['responded_reports'];

$wk4FloodTotal = $conn->query("SELECT COUNT(*) AS total_reports FROM floodreport WHERE YEAR(date_time) = YEAR(CURDATE()) AND WEEK(date_time, 1) = WEEK(CURDATE(), 1) - 3")->fetch_assoc()['total_reports'];
$wk4FloodResponded = $conn->query("SELECT COUNT(*) AS responded_reports FROM floodreport WHERE YEAR(date_time) = YEAR(CURDATE()) AND WEEK(date_time, 1) = WEEK(CURDATE(), 1) - 3 AND respond = 1")->fetch_assoc()['responded_reports'];

// Weekly Crime
$wk1CrimeTotal = $conn->query("SELECT COUNT(*) AS total_reports FROM crimereport WHERE YEAR(date_time) = YEAR(CURDATE()) AND WEEK(date_time, 1) = WEEK(CURDATE(), 1)")->fetch_assoc()['total_reports'];
$wk1CrimeResponded = $conn->query("SELECT COUNT(*) AS responded_reports FROM crimereport WHERE YEAR(date_time) = YEAR(CURDATE()) AND WEEK(date_time, 1) = WEEK(CURDATE(), 1) AND respond = 1")->fetch_assoc()['responded_reports'];

$wk2CrimeTotal = $conn->query("SELECT COUNT(*) AS total_reports FROM crimereport WHERE YEAR(date_time) = YEAR(CURDATE()) AND WEEK(date_time, 1) = WEEK(CURDATE(), 1) - 1")->fetch_assoc()['total_reports'];
$wk2CrimeResponded = $conn->query("SELECT COUNT(*) AS responded_reports FROM crimereport WHERE YEAR(date_time) = YEAR(CURDATE()) AND WEEK(date_time, 1) = WEEK(CURDATE(), 1) - 1 AND respond = 1")->fetch_assoc()['responded_reports'];

$wk3CrimeTotal = $conn->query("SELECT COUNT(*) AS total_reports FROM crimereport WHERE YEAR(date_time) = YEAR(CURDATE()) AND WEEK(date_time, 1) = WEEK(CURDATE(), 1) - 2")->fetch_assoc()['total_reports'];
$wk3CrimeResponded = $conn->query("SELECT COUNT(*) AS responded_reports FROM crimereport WHERE YEAR(date_time) = YEAR(CURDATE()) AND WEEK(date_time, 1) = WEEK(CURDATE(), 1) - 2 AND respond = 1")->fetch_assoc()['responded_reports'];

$wk4CrimeTotal = $conn->query("SELECT COUNT(*) AS total_reports FROM crimereport WHERE YEAR(date_time) = YEAR(CURDATE()) AND WEEK(date_time, 1) = WEEK(CURDATE(), 1) - 3")->fetch_assoc()['total_reports'];
$wk4CrimeResponded = $conn->query("SELECT COUNT(*) AS responded_reports FROM crimereport WHERE YEAR(date_time) = YEAR(CURDATE()) AND WEEK(date_time, 1) = WEEK(CURDATE(), 1) - 3 AND respond = 1")->fetch_assoc()['responded_reports'];

// Weekly Incident
$wk1IncidentTotal = $conn->query("SELECT COUNT(*) AS total_reports FROM incidentreport WHERE YEAR(date_time) = YEAR(CURDATE()) AND WEEK(date_time, 1) = WEEK(CURDATE(), 1)")->fetch_assoc()['total_reports'];
$wk1IncidentResponded = $conn->query("SELECT COUNT(*) AS responded_reports FROM incidentreport WHERE YEAR(date_time) = YEAR(CURDATE()) AND WEEK(date_time, 1) = WEEK(CURDATE(), 1) AND respond = 1")->fetch_assoc()['responded_reports'];

$wk2IncidentTotal = $conn->query("SELECT COUNT(*) AS total_reports FROM incidentreport WHERE YEAR(date_time) = YEAR(CURDATE()) AND WEEK(date_time, 1) = WEEK(CURDATE(), 1) - 1")->fetch_assoc()['total_reports'];
$wk2IncidentResponded = $conn->query("SELECT COUNT(*) AS responded_reports FROM incidentreport WHERE YEAR(date_time) = YEAR(CURDATE()) AND WEEK(date_time, 1) = WEEK(CURDATE(), 1) - 1 AND respond = 1")->fetch_assoc()['responded_reports'];

$wk3IncidentTotal = $conn->query("SELECT COUNT(*) AS total_reports FROM incidentreport WHERE YEAR(date_time) = YEAR(CURDATE()) AND WEEK(date_time, 1) = WEEK(CURDATE(), 1) - 2")->fetch_assoc()['total_reports'];
$wk3IncidentResponded = $conn->query("SELECT COUNT(*) AS responded_reports FROM incidentreport WHERE YEAR(date_time) = YEAR(CURDATE()) AND WEEK(date_time, 1) = WEEK(CURDATE(), 1) - 2 AND respond = 1")->fetch_assoc()['responded_reports'];

$wk4IncidentTotal = $conn->query("SELECT COUNT(*) AS total_reports FROM incidentreport WHERE YEAR(date_time) = YEAR(CURDATE()) AND WEEK(date_time, 1) = WEEK(CURDATE(), 1) - 3")->fetch_assoc()['total_reports'];
$wk4IncidentResponded = $conn->query("SELECT COUNT(*) AS responded_reports FROM incidentreport WHERE YEAR(date_time) = YEAR(CURDATE()) AND WEEK(date_time, 1) = WEEK(CURDATE(), 1) - 3 AND respond = 1")->fetch_assoc()['responded_reports'];

//TOTALs
$totalwk1 = $wk1FireTotal + $wk1FloodTotal + $wk1CrimeTotal + $wk1IncidentTotal;
$totalrespondwk1 = $wk1FireResponded + $wk1FloodResponded + $wk1CrimeResponded + $wk1IncidentResponded;

$totalwk2 = $wk2FireTotal + $wk2FloodTotal + $wk2CrimeTotal + $wk2IncidentTotal;
$totalrespondwk2 = $wk2FireResponded + $wk2FloodResponded + $wk2CrimeResponded + $wk2IncidentResponded;

$totalwk3 = $wk3FireTotal + $wk3FloodTotal + $wk3CrimeTotal + $wk3IncidentTotal;
$totalrespondwk3 = $wk3FireResponded + $wk3FloodResponded + $wk3CrimeResponded + $wk3IncidentResponded;

$totalwk4 = $wk4FireTotal + $wk4FloodTotal + $wk4CrimeTotal + $wk4IncidentTotal;
$totalrespondwk4 = $wk4FireResponded + $wk4FloodResponded + $wk4CrimeResponded + $wk4IncidentResponded;

$totals = [$totalwk1, $totalwk2, $totalwk3, $totalwk4];

// Sort the totals to calculate the median
sort($totals);

// Calculate median (since there are 4 values, it's the average of the 2 middle values)
$median = ($totals[1] + $totals[2]) / 2;

// Initialize date variables
$startDate = '';
$endDate = '';

if (isset($_POST['clear'])) { 
    // Reset to fetch all data
    $query4 = "
        SELECT 
            'Fire' AS report_type,
            f.id, 
            f.loc, 
            f.date_time, 
            f.report, 
            f.img, 
            f.respond, 
            f.active, 
            f.longitude, 
            f.latitude, 
            f.user_id, 
            l.name
        FROM firereport f
        LEFT JOIN login l ON f.user_id = l.id
        
        UNION ALL
        
        SELECT 
            'Crime' AS report_type,
            c.id, 
            c.loc, 
            c.date_time, 
            c.report, 
            c.img, 
            c.respond, 
            c.active, 
            c.longitude, 
            c.latitude, 
            c.user_id, 
            l.name
        FROM crimereport c
        LEFT JOIN login l ON c.user_id = l.id
        
        UNION ALL
        
        SELECT 
            'Incident' AS report_type,
            i.id, 
            i.loc, 
            i.date_time, 
            i.report, 
            i.img, 
            i.respond, 
            i.active, 
            i.longitude, 
            i.latitude, 
            i.user_id, 
            l.name
        FROM incidentreport i
        LEFT JOIN login l ON i.user_id = l.id
        
        UNION ALL
        
        SELECT 
            'Flood' AS report_type,
            fl.id, 
            fl.loc, 
            fl.date_time, 
            fl.report, 
            fl.img, 
            fl.respond, 
            fl.active, 
            fl.longitude, 
            fl.latitude, 
            fl.user_id, 
            l.name
        FROM floodreport fl
        LEFT JOIN login l ON fl.user_id = l.id
        
        ORDER BY date_time DESC
    ";
} elseif (isset($_POST['filter'])) {
    $month = $_POST['month'];
    $year = $_POST['year'];
    $startDate = "$year-$month-01";
    $endDate = date("Y-m-t", strtotime($startDate));
    // Query to fetch filtered data based on the date range from all tables
    $query4 = "
        SELECT 
            'Fire' AS report_type,
            f.id, 
            f.loc, 
            f.date_time, 
            f.report, 
            f.img, 
            f.respond, 
            f.active, 
            f.longitude, 
            f.latitude, 
            f.user_id, 
            l.name
        FROM firereport f
        LEFT JOIN login l ON f.user_id = l.id
        WHERE f.date_time BETWEEN '$startDate' AND '$endDate'
        
        UNION ALL
        
        SELECT 
            'Crime' AS report_type,
            c.id, 
            c.loc, 
            c.date_time, 
            c.report, 
            c.img, 
            c.respond, 
            c.active, 
            c.longitude, 
            c.latitude, 
            c.user_id, 
            l.name
        FROM crimereport c
        LEFT JOIN login l ON c.user_id = l.id
        WHERE c.date_time BETWEEN '$startDate' AND '$endDate'
        
        UNION ALL
        
        SELECT 
            'Incident' AS report_type,
            i.id, 
            i.loc, 
            i.date_time, 
            i.report, 
            i.img, 
            i.respond, 
            i.active, 
            i.longitude, 
            i.latitude, 
            i.user_id, 
            l.name
        FROM incidentreport i
        LEFT JOIN login l ON i.user_id = l.id
        WHERE i.date_time BETWEEN '$startDate' AND '$endDate'
        
        UNION ALL
        
        SELECT 
            'Flood' AS report_type,
            fl.id, 
            fl.loc, 
            fl.date_time, 
            fl.report, 
            fl.img, 
            fl.respond, 
            fl.active, 
            fl.longitude, 
            fl.latitude, 
            fl.user_id, 
            l.name
        FROM floodreport fl
        LEFT JOIN login l ON fl.user_id = l.id
        WHERE fl.date_time BETWEEN '$startDate' AND '$endDate'
        
        ORDER BY date_time DESC
    ";
} else {
    // Query to fetch all data from all tables
    $query4 = "
        SELECT 
            'Fire' AS report_type,
            f.id, 
            f.loc, 
            f.date_time, 
            f.report, 
            f.img, 
            f.respond, 
            f.active, 
            f.longitude, 
            f.latitude, 
            f.user_id, 
            l.name
        FROM firereport f
        LEFT JOIN login l ON f.user_id = l.id
        
        UNION ALL
        
        SELECT 
            'Crime' AS report_type,
            c.id, 
            c.loc, 
            c.date_time, 
            c.report, 
            c.img, 
            c.respond, 
            c.active, 
            c.longitude, 
            c.latitude, 
            c.user_id, 
            l.name
        FROM crimereport c
        LEFT JOIN login l ON c.user_id = l.id
        
        UNION ALL
        
        SELECT 
            'Incident' AS report_type,
            i.id, 
            i.loc, 
            i.date_time, 
            i.report, 
            i.img, 
            i.respond, 
            i.active, 
            i.longitude, 
            i.latitude, 
            i.user_id, 
            l.name
        FROM incidentreport i
        LEFT JOIN login l ON i.user_id = l.id
        
        UNION ALL
        
        SELECT 
            'Flood' AS report_type,
            fl.id, 
            fl.loc, 
            fl.date_time, 
            fl.report, 
            fl.img, 
            fl.respond, 
            fl.active, 
            fl.longitude, 
            fl.latitude, 
            fl.user_id, 
            l.name
        FROM floodreport fl
        LEFT JOIN login l ON fl.user_id = l.id
        
        ORDER BY date_time DESC
    ";
}
$result4 = $conn->query($query4);
mysqli_close($conn);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Urban Safe</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" type="text/css" href="assets/css/bootstrap.min.css"/>
    <link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css" />
    <link rel="stylesheet" href="https://unpkg.com/leaflet.heat/dist/leaflet-heat.css" />
    <link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css" />
    <script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>
    <script src="https://unpkg.com/leaflet.heat/dist/leaflet-heat.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    
        <style>
        /* General Styles */
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #fff;
        }
/* Menu styling */
    .menu {
        position: fixed;
        top: 0;
        left: -200px;
        width: 200px;
        height: 100%;
        background-color: #333;
        transition: left 0.3s ease;
        z-index: 2;
    }
.menu-btn {
    position: absolute;
    top: 50px;
    background-color: #1B413A;
    color: #FFFFFF;
    border: none;
    padding: 20px 40px;
    font-size: 20px;
    cursor: pointer;
    border-top-right-radius: 12px;
    border-bottom-right-radius: 12px;
    background-image: url('../pics/urban-logo.png');
    background-size: 40px;
    background-repeat: no-repeat;
    background-position: 10px center;
    padding-left: 60px;
}
    .menu ul {
        list-style: none;
        padding: 0;
    }
    .menu ul li {
        padding: 10px;
    }
    .menu ul li input {
        width: 100%;
        background-color: #FFE500;
        border: none;
        padding: 8px;
        font-size: 14px;
        cursor: pointer;
        border-radius: 4px;
    }
    /* Content container styling */
    .content-container {
        background-color: #1B413A;
        display: flex;
        flex-direction: column;
        align-items: stretch;
        z-index: 1; /* Ensure content container has a higher z-index */
        position: relative;
        padding-top: 100px; /* Adjust as needed if the menu is fixed at the top */
    }
        .index-box {
        padding: 30px;
        border: 5px solid black; /* Adding a thick black border */
        border-color: #1B413A;
        border-radius: 10px;
        box-shadow: 0 0 15px rgba(0, 0, 0, 1);
        width: auto;
        max-width: auto;
        box-sizing: border-box; /* Ensures padding does not overflow */
        }
        .report-btn {
            background-color: #EA1010;
            color: #FFFFFF;
            border-radius: 12px;
            width: 231px;    
            height: 88px;
        }
        .login-btn {
            background-color: #000000;
            color: #FFFFFF;
            border-radius: 12px;
            width: 212px;
            height: 53px;
        }
        .form {
        display: flex;
        flex-direction: column;
        justify-content: center;
        align-items: center;
        text-align: center;
        box-sizing: border-box;
        background-color: white;
        border: 1px solid;
        border-radius: 15px;
        max-width: 400px;
        margin: auto; /* Ensure it's centered */
        padding-top: 20px;
        padding-bottom: 40px;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1), /* Main shadow */
                    0 6px 20px rgba(0, 0, 0, 0.1); 
    }
    .record {
    display: flex;
    flex-wrap: wrap; /* Allow the items to wrap into rows */
    justify-content: space-between; /* Space items evenly across the row */
    align-items: center;
    text-align: center;
    box-sizing: border-box;
    border: 1px solid;
    border-radius: 15px;
    max-width: 800px; /* Adjust the width to accommodate two items per row */
    margin: auto;
    padding: 20px;
    background-color: white; /* White background for the container */
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1), /* Main shadow */
                0 6px 20px rgba(0, 0, 0, 0.1);
}
.reports, .responds {
    display: flex;
    flex-direction: column;
    justify-content: flex-start;
    align-items: center;
    text-align: left;
    color: white;
    font-weight: bold;
    box-sizing: border-box;
    border: 1px solid;
    border-radius: 15px;
    width: 48%; /* Set width to 48% to fit two items per row with spacing */
    height: 130px;
    padding: 10px;
    margin-bottom: 20px; /* Add margin between rows */
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1), 
                0 6px 20px rgba(0, 0, 0, 0.1);
}
.reports {
    background-color: green; /* Background color for reports */
    background-image: url(''); /* Optional image */
    background-size: 50px;
    background-repeat: no-repeat;
    background-position: calc(100% - 15px) center;
}
.responds {
    background-color: green; /* Background color for responds */
    background-image: url(''); /* Optional image */
    background-size: 50px;
    background-repeat: no-repeat;
    background-position: calc(100% - 8px) center;
}
.total-report, .total-respond {
    display: block;
    text-align: center;
    margin-top: -5px;
}
     footer {
        position: fixed;
        bottom: 0;
        left: 0;
        width: 100%;
        height: 60px;
        background-color: #1B413A; /* Same as header background */
        color: #fff; /* Text color */
        display: flex;
        align-items: center; /* Vertical alignment */
        justify-content: center; /* Center text horizontally */
        padding: 0 20px; /* Add padding for spacing */
    }
    /* Responsive adjustments */
@media (max-width: 768px) {
    .footer {
        padding: 15px;
        font-size: 14px;
    }
}

@media (max-width: 480px) {
    .footer {
        padding: 10px;
        font-size: 12px;
    }
}
    
        /* Responsive Styles */
        @media (max-width: 600px) {
            .header-container {
                flex-direction: row;
                justify-content: center;
            }

            .logo {
                height: 40px;
                margin-right: 10px;
            }

            .title {
                font-size: 20px;
            }
        }
        
    #pieChart {
    background-color: white;
    width: 300px;
    height: 300px;
    border: 1px solid #000;
    margin: 10px;           /* Example: Add margin around the pie chart */
    padding: 5px; 
    }

    /* Style for the line chart canvas */
    #lineChart {
        background-color: white;
    width: 300px;
    height: 300px;
    border: 1px solid #000;
    margin: 10px;           /* Example: Add margin around the line chart */
    padding: 5px; 
    }
    
    #mapTitle h3 {
    color: #333;              /* Change text color */
    font-size: 24px;         /* Adjust font size */
    font-weight: bold;       /* Make it bold */
    margin: 0;               /* Remove default margin */
    }
    
    #mapContainer {
    background-color: white;           /* Background color larger than the map */
    padding: 20px;                     /* Add padding around the map */
    border-radius: 8px;                /* Optional: Add rounded corners */
    display: inline-block;              /* Keep the container to the size of its content */
    margin: 20px auto;                      /* Add margin if needed */
    box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1); /* Optional: Add a subtle shadow */
    max-width: 100%;                   /* Ensure it doesn't exceed the screen width */
    width: 100%;                       /* Set the width to be responsive */
    }

    #map {
     height: 50vh;                      /* Height of the map (50% of the viewport height) */
    width: calc(100% - 40px);         /* Set the width to fill the container, accounting for padding */
    border: 2px solid black;           /* Add a black border */
    border-radius: 4px;                /* Optional: Add rounded corners */
    margin: 10px auto;
    }
    
     #map1 {
     height: 50vh;                      /* Height of the map (50% of the viewport height) */
    width: calc(100% - 40px);         /* Set the width to fill the container, accounting for padding */
    border: 2px solid black;           /* Add a black border */
    border-radius: 4px;                /* Optional: Add rounded corners */
    margin: 10px auto;
    }
    
     #map2 {
     height: 50vh;                      /* Height of the map (50% of the viewport height) */
    width: calc(100% - 40px);         /* Set the width to fill the container, accounting for padding */
    border: 2px solid black;           /* Add a black border */
    border-radius: 4px;                /* Optional: Add rounded corners */
    margin: 10px auto;
    }
    
     #map3 {
     height: 50vh;                      /* Height of the map (50% of the viewport height) */
    width: calc(100% - 40px);         /* Set the width to fill the container, accounting for padding */
    border: 2px solid black;           /* Add a black border */
    border-radius: 4px;                /* Optional: Add rounded corners */
    margin: 10px auto;
    }
    
    #map4 {
     height: 50vh;                      /* Height of the map (50% of the viewport height) */
    width: calc(100% - 40px);         /* Set the width to fill the container, accounting for padding */
    border: 2px solid black;           /* Add a black border */
    border-radius: 4px;                /* Optional: Add rounded corners */
    margin: 10px auto;
    }
    
        
    #chart-container {
    display: flex; /* Optional: to align charts in a row */
    justify-content: center; /* Center the charts horizontally */
    gap: 20px; /* Add some space between charts */
    padding: 20px; /* Optional: add padding around the container */
    }
    
    #chart-container canvas {
    background-color: white; /* Only applies to chart canvases */
    width: 300px;           /* Set a specific width for the chart canvases */
    height: 300px;          /* Set a specific height for the chart canvases */
    border: 1px solid #000; /* Optional: Add a border to see the canvas size clearly */
    }
    
    .chart-container {
    display: flex;           /* Use flexbox to center the charts */
    justify-content: center; /* Center charts horizontally */
    align-items: center;     /* Center charts vertically */
    margin: 20px;           /* Add margin to space out the charts */
    }
    
        .index-container {
        display: relative;
        flex-grow: 1;
        height: auto;
        width: 100%; /* Adjust container width */
        }
        .index-container1 {
            display: flex;
            justify-content: space-between;
            flex-wrap: wrap;
            gap: 20px;
        }

        /* Box styles */
        .index-box1 {
        padding: 30px;
        border: 5px solid black; /* Adding a thick black border */
        border-color: #1B413A;
        border-radius: 10px;
        box-shadow: 0 0 15px rgba(0, 0, 0, 1);
        width: 48%; /* Adjust box width for responsiveness */
        max-width: 1000px; /* Optional maximum width */
        box-sizing: border-box; /* Ensures padding does not overflow */
        }
    .index-box {
        padding: 30px;
        border: 5px solid black; /* Adding a thick black border */
        border-color: #1B413A;
        border-radius: 10px;
        box-shadow: 0 0 15px rgba(0, 0, 0, 1);
        width: 100%; /* Adjust box width for responsiveness */
        max-width: 1000px; /* Optional maximum width */
        box-sizing: border-box; /* Ensures padding does not overflow */
        }
 table {
            width: 90%;
            margin-top: 10px;
            background-color: white;
            border-collapse: collapse;
        }

        th, td {
            border: 2px solid black;
            padding: 10px;
            text-align: left;
        }
.btn-success {
        width: auto;
        height: auto;
        padding: 12px;
        background-color: #FFD700;;
        color: black;
        font-size: 16px;
        border: none;
        border-radius: 5px;
        cursor: pointer;
        }

    .btn-success:hover {
        background-color: #FFEB00;;
        }
    
        .btn-danger {
        width: auto;
        height: auto;
        background-color: #DC3545; /* Red color */
        color: white;
        border: none;
        padding: 12px;
        cursor: pointer;
        border-radius: 5px;
        font-size: 16px;
    }
    
    .btn-danger:hover {
        background-color: #C82333;
    }
    </style>
    <script>
        function toggleDropdown() {
            const dropdown = document.getElementById('notificationDropdown');
            dropdown.classList.toggle('active');
            
            // Only update active status if dropdown is opened
            if (dropdown.classList.contains('active')) {
                updateNotificationStatus();
            }
        }

        function updateNotificationStatus() {
            const xhr = new XMLHttpRequest();
            xhr.open("POST", "../update_notification_status.php", true); // Create a new PHP file for this
            xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
            xhr.onload = function() {
                if (xhr.status === 200) {
                    // Optionally, you can handle the response here
                    document.getElementById("notification-count").textContent = "0"; // Set count to 0
                }
            };
            xhr.send(); // Send the request
        }

        // Close the dropdown if clicked outside
        window.onclick = function(event) {
            if (!event.target.matches('.notification-bell')) {
                const dropdowns = document.getElementsByClassName('notification-dropdown');
                for (let i = 0; i < dropdowns.length; i++) {
                    const openDropdown = dropdowns[i];
                    if (openDropdown.classList.contains('active')) {
                        openDropdown.classList.remove('active');
                    }
                }
            }
        }
    </script>
    
</head>

<body>

    <input type="submit" class="menu-btn" value="URBANSAFE" onclick="toggleMenu()">
    <div id="menu" class="menu">
        <ul>
            <li><input type="submit" class="menu-display" value="URBANSAFE" onclick="toggleMenu()"></li>
            <li><input type="submit" class="menu-edit-contact" value="Home" onclick="goToHome()"></li>
            <li><input type="submit" class="menu-edit-contact" value="Responder" onclick="goToEditContactNumber()"></li>
            <li><input type="submit" class="menu-edit-radius" value="Area Covered" onclick="goToEditRadiusGeofencing()"></li>
            <li><input type="submit" class="menu-analytics" value="Add Responder" onclick="goToResponder()"></li>
            <li><input type="submit" class="menu-logout" value="Log out" onclick="index()"></li>
        </ul>
    </div>

    <br><center>
        <div class="index-container">
    <div class="index-box">
    <center>
    <div class="mt-5">
        <?php if ($result->num_rows > 0): ?>
            <table>
                <form method="post" action="">
                    <label for="month">Month:&nbsp</label>
                    <select id="month" name="month" required>
                        <?php for ($m = 1; $m <= 12; $m++): ?>
                            <option value="<?php echo $m; ?>" <?php if(isset($_POST['month']) && $_POST['month'] == $m) echo 'selected'; ?>>
                                <?php echo date('F', mktime(0, 0, 0, $m, 1)); ?>
                            </option>
                        <?php endfor; ?>
                    </select>
                    
                    <label for="year">&nbsp&nbspYear:&nbsp</label>
                    <select id="year" name="year" required>
                        <?php for ($y = date('Y'); $y >= 2000; $y--): ?>
                            <option value="<?php echo $y; ?>" <?php if(isset($_POST['year']) && $_POST['year'] == $y) echo 'selected'; ?>>
                                <?php echo $y; ?>
                            </option>
                        <?php endfor; ?>
                    </select>
                    
                    &nbsp<input type="submit" class="btn-success" name="filter" value="Filter">&nbsp
                    <input type="submit" class="btn-danger" name="clear" value="Clear">
                </form>
                <thead>
                    <tr>
                        <th>Report ID</th>
                        <th>User ID</th>
                        <th>User Name</th>
                        <th>Location</th>
                        <th>Detail</th>
                        <th>Date Time</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = $result4->fetch_assoc()): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($row['id']); ?></td>
                            <td><?php echo htmlspecialchars($row['user_id']); ?></td>
                            <td><?php echo htmlspecialchars($row['name']); ?></td>
                            <td><?php echo htmlspecialchars($row['loc']); ?></td>
                            <td><?php echo htmlspecialchars($row['report']); ?></td>
                            <td><?php echo htmlspecialchars($row['date_time']); ?></td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
            <canvas id="reportChart" width="400" height="200"></canvas>

<script>
<?php
// Prepare data for the chart
$reports = []; // Initialize array
$result4->data_seek(0); // Reset the result pointer
while ($row = $result4->fetch_assoc()) {
    $date = substr($row['date_time'], 0, 10); // Extract date only
    $reports[$date] = ($reports[$date] ?? 0) + 1; // Count reports per day
}
?>

const ctx = document.getElementById('reportChart').getContext('2d');
const data = {
    labels: <?php echo json_encode(array_keys($reports)); ?>,
    datasets: [{
        label: 'Number of Reports',
        data: <?php echo json_encode(array_values($reports)); ?>,
        borderColor: 'rgba(75, 192, 192, 1)',
        backgroundColor: 'rgba(75, 192, 192, 0.2)',
        fill: true,
    }]
};

const reportChart = new Chart(ctx, {
    type: 'line',
    data: data,
    options: {
        responsive: true,
        scales: {
            x: { title: { display: true, text: 'Date' }},
            y: { title: { display: true, text: 'Reports' }, beginAtZero: true }
        }
    }
});
</script>

        <?php else: ?>
            <p>No reports available.</p>
        <?php endif; ?>
    </center></div> </div></div><br><br>
                
                <center>
                    <div id="mapTitle">
                    <h3>Heatmap of Barangay 600</h3>
                </div>
                    <h4>Based on Type of Reports</h4>
                </center>

        <div class="index-container1">
        <div class="index-box1">
            <h4>Based on Fire Reports</h4>
            <div class="map-container">
                <div class="map" id="map"></div>
            </div>
        </div>

        <div class="index-box1">
            <h4>Based on Flood Reports</h4>
            <div class="map-container">
                <div class="map" id="map2"></div>
            </div>
        </div>
    </div><br><br><br>

    <div class="index-container1">
        <div class="index-box1">
            <h4>Based on Incident Reports</h4>
            <div class="map-container">
                <div class="map" id="map3"></div>
            </div>
        </div>

        <div class="index-box1">
            <h4>Based on Crime Reports</h4>
            <div class="map-container">
                <div class="map" id="map4"></div>
            </div>
        </div>
    </div>
    <br><br><br>
    <div class="index-container1">
        <div class="index-box1">
             <div id="mapTitle">
                <h3>Pie Chart of Reports</h3>
            </div>
            <div class="chart-container">
                <canvas id="pieChart"></canvas>
            </div>
        </div>
    <center><br>
        
    </center> 
        <div class="index-box1">
            <div id="mapTitle">
            <h3>Line Chart of Reports</h3>
        </div>
            <div class="chart-container">
                <canvas id="lineChart"></canvas>
            </div>
        </div>
    </div>

<script>
    function toggleMenu() {
        const menu = document.getElementById("menu");
        menu.style.left = menu.style.left === "0px" ? "-200px" : "0px";
    }
    
    function goToHome() {
        window.location.href = "admin.php";
    }
    
    function goToEditContactNumber() {
        window.location.href = "econtactnum.php";
    }

    function goToEditRadiusGeofencing() {
        window.location.href = "geofencing.php";
    }

    function goToResponder() {
        window.location.href = "add_responder.php";
    }

    function forumData() {
        window.location.href = "forumdata.php"; 
    }

    function index() {
        alert("Log out successfully");
        window.location.href = "../index.php";
    }
    function goToHistory() {
        window.location.href = "history.php";
    }

    const map = L.map('map').setView([14.598382, 121.019379], 17); // Center on Barangay 600

    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        maxZoom: 19,
        attribution: '© OpenStreetMap'
    }).addTo(map);

    // Embed PHP-generated heatmap data directly into JavaScript
    const heatData = <?php echo json_encode($heatmapData['data']); ?>;
    const heatLayerData = heatData.map(point => [point.lat, point.lng, point.val]);
    L.heatLayer(heatLayerData, { radius: 25, blur: 15 }).addTo(map);

    // Geofencing Polygon (same polygon as in your example)
    const polygonCoordinates = [
        [14.598515, 121.017364], [14.597663, 121.017669], [14.596980, 121.018488], [14.597302, 121.019369],
        [14.597484, 121.019336], [14.598197, 121.021680], [14.598720, 121.021483], [14.598428, 121.020355],
        [14.598558, 121.020280], [14.598728, 121.020342], [14.598703, 121.020465], [14.598941, 121.020522],
        [14.599050, 121.020390], [14.598951, 121.020264], [14.599431, 121.019253], [14.598515, 121.017364]
    ];

    const polygon = L.polygon(polygonCoordinates, {
        color: 'red', weight: 2, opacity: 0.5, fillColor: 'blue', fillOpacity: 0.1
    }).addTo(map);

    polygon.bindPopup('Geofenced Area: Barangay 600');
</script>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const map2 = L.map('map2').setView([14.598382, 121.019379], 17);

        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            maxZoom: 19,
            attribution: '© OpenStreetMap'
        }).addTo(map2);

        const heatData1 = <?php echo json_encode($heatmapData1['data']); ?>;
        const heatLayerData1 = heatData1.map(point => [point.lat, point.lng, point.val]);
        L.heatLayer(heatLayerData1, { radius: 25, blur: 15 }).addTo(map2);

        const polygonCoordinates = [
            [14.598515, 121.017364], [14.597663, 121.017669], [14.596980, 121.018488], [14.597302, 121.019369],
            [14.597484, 121.019336], [14.598197, 121.021680], [14.598720, 121.021483], [14.598428, 121.020355],
            [14.598558, 121.020280], [14.598728, 121.020342], [14.598703, 121.020465], [14.598941, 121.020522],
            [14.599050, 121.020390], [14.598951, 121.020264], [14.599431, 121.019253], [14.598515, 121.017364]
        ];

        const polygon = L.polygon(polygonCoordinates, {
            color: 'green', weight: 2, opacity: 0.5, fillColor: 'blue', fillOpacity: 0.1
        }).addTo(map2);

        polygon.bindPopup('Geofenced Area: Barangay 600');
    });
</script>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const map3 = L.map('map3').setView([14.598382, 121.019379], 17);

        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            maxZoom: 19,
            attribution: '© OpenStreetMap'
        }).addTo(map3);

        const heatData2 = <?php echo json_encode($heatmapData2['data']); ?>;
        const heatLayerData2 = heatData2.map(point => [point.lat, point.lng, point.val]);
        L.heatLayer(heatLayerData2, { radius: 25, blur: 15 }).addTo(map3);

        const polygonCoordinates = [
            [14.598515, 121.017364], [14.597663, 121.017669], [14.596980, 121.018488], [14.597302, 121.019369],
            [14.597484, 121.019336], [14.598197, 121.021680], [14.598720, 121.021483], [14.598428, 121.020355],
            [14.598558, 121.020280], [14.598728, 121.020342], [14.598703, 121.020465], [14.598941, 121.020522],
            [14.599050, 121.020390], [14.598951, 121.020264], [14.599431, 121.019253], [14.598515, 121.017364]
        ];

        const polygon = L.polygon(polygonCoordinates, {
            color: 'yellow', weight: 2, opacity: 0.5, fillColor: 'blue', fillOpacity: 0.1
        }).addTo(map3);

        polygon.bindPopup('Geofenced Area: Barangay 600');
    });
</script>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const map4 = L.map('map4').setView([14.598382, 121.019379], 17);

        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            maxZoom: 19,
            attribution: '© OpenStreetMap'
        }).addTo(map4);

        const heatData3 = <?php echo json_encode($heatmapData3['data']); ?>;
        const heatLayerData3 = heatData3.map(point => [point.lat, point.lng, point.val]);
        L.heatLayer(heatLayerData3, { radius: 25, blur: 15 }).addTo(map4);

        const polygonCoordinates = [
            [14.598515, 121.017364], [14.597663, 121.017669], [14.596980, 121.018488], [14.597302, 121.019369],
            [14.597484, 121.019336], [14.598197, 121.021680], [14.598720, 121.021483], [14.598428, 121.020355],
            [14.598558, 121.020280], [14.598728, 121.020342], [14.598703, 121.020465], [14.598941, 121.020522],
            [14.599050, 121.020390], [14.598951, 121.020264], [14.599431, 121.019253], [14.598515, 121.017364]
        ];

        const polygon = L.polygon(polygonCoordinates, {
            color: 'blue', weight: 2, opacity: 0.5, fillColor: 'blue', fillOpacity: 0.1
        }).addTo(map4);

        polygon.bindPopup('Geofenced Area: Barangay 600');
    });
</script>

    
    
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        // Define the report data
        const pieChartData = {
            labels: ['Fire Reports', 'Flood Reports', 'Incident Reports', 'Crime Reports'],
            datasets: [{
                label: 'Reports',
                data: [
                    <?php echo $totalFireReports; ?>,
                    <?php echo $totalFloodReports; ?>,
                    <?php echo $totalIncidentReports; ?>,
                    <?php echo $totalCrimeReports; ?>
                    ],
                backgroundColor: [
                    'rgba(255, 0, 0, 1)',
                    'rgba(0, 0, 139, 1)',
                    'rgba(0, 128, 0, 1)',
                    'rgba(0, 128, 128, 1)'
                ],
                borderColor: [
                    'rgba(0, 0, 0, 1)',
                    'rgba(0, 0, 0, 1)',
                    'rgba(0, 0, 0, 1)',
                    'rgba(0, 0, 0, 1)'
                ],
                borderWidth: 3
            }]
        };

        // Create the pie chart
        const ctx = document.getElementById('pieChart').getContext('2d');
        const myPieChart = new Chart(ctx, {
            type: 'pie',
            data: pieChartData,
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        position: 'top',
                    },
                    title: {
                        display: true,
                        text: 'Report Distribution'
                    }
                }
            }
        });
    });
    </script>

    <script>
    document.addEventListener('DOMContentLoaded', function() {
        // Define the report data for the line chart
        const lineChartData = {
            labels: ['Week 1', 'Week 2', 'Week 3', 'Week 4'], // Example labels
            datasets: [
                {
                    label: 'Fire Reports',
                    data: [
                        <?php echo $wk1firereport; ?>, 
                        <?php echo $wk2firereport; ?>, 
                        <?php echo $wk3firereport; ?>, 
                        <?php echo $wk4firereport; ?> 
                    ],
                    fill: false,
                    borderColor: 'rgba(255, 99, 132, 1)',
                    tension: 0.1
                },
                {
                    label: 'Flood Reports',
                    data: [
                        <?php echo $wk1floodreport; ?>, 
                        <?php echo $wk2floodreport; ?>, 
                        <?php echo $wk3floodreport; ?>, 
                        <?php echo $wk4floodreport; ?>
                    ],
                    fill: false,
                    borderColor: 'rgba(54, 162, 235, 1)',
                    tension: 0.1
                },
                {
                    label: 'Incident Reports',
                    data: [
                        <?php echo $wk1incidentreport; ?>, 
                        <?php echo $wk2incidentreport; ?>, 
                        <?php echo $wk3incidentreport; ?>, 
                        <?php echo $wk4incidentreport; ?>
                    ],
                    fill: false,
                    borderColor: 'rgba(255, 206, 86, 1)',
                    tension: 0.1
                },
                {
                    label: 'Crime Reports',
                    data: [
                        <?php echo $wk1crimereport; ?>, 
                        <?php echo $wk2crimereport; ?>, 
                        <?php echo $wk3crimereport; ?>, 
                        <?php echo $wk4crimereport; ?>
                    ],
                    fill: false,
                    borderColor: 'rgba(75, 192, 192, 1)',
                    tension: 0.1
                }
            ]
        };

        // Create the line chart
        const ctx = document.getElementById('lineChart').getContext('2d');
        const myLineChart = new Chart(ctx, {
            type: 'line',
            data: lineChartData,
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        position: 'top',
                    },
                    title: {
                        display: true,
                        text: 'Reports Over Time (Span of 1 Month)'
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });
    });
</script>
<br><br>

<div class="index-container">
                <div class="index-box">
<center><h2>Descriptive Analytics</h2></center>
<div class="record">
            <div class="reports">
                <p>Total Number of Reports <br><br>
                    <span class="total-report"><?php echo $totalReports ?></span>
                </p>
            </div>
            <div class="responds">
                <p>Total Number of Responds <br><br>
                <span class="total-respond"><?php echo $totalRespond ?></span>
            </div>
            <div class="reports">
                <p>Mean of incoming reports <br><br>
                    <span class="total-report"><?php echo $mean ?></span>
                </p>
            </div>
            <div class="responds">
                <p>Median of incoming reports <br><br>
                <span class="total-respond"><?php echo $median ?></span>
            </div>
            <div class="responds">
                <p>Mode of incoming reports <br><br>
                <span class="total-respond">
            <?php
            $mode = calculateMode($data);
            if (!empty($mode)) {
            echo 'Mode(s): ' . implode(', ', $mode);
            } else {
            echo 'There is no mode for the reports.';
            }
            ?></span>
            </div>
            <div class="responds">
                <p>Standard Deviation of incoming reports <br><br>
                <span class="total-respond"><?php echo $stdDev ?></span>
            </div>
            <div class="responds">
                <p>Variance of incoming reports <br><br>
                <span class="total-respond"><?php echo $variance ?></span>
            </div>        
            <div class="responds">
                <p>Inter Quartile Range of incoming reports <br><br>
                <span class="total-respond"><?php echo $iqr ?></span>
            </div>
        
        </div>
        </div></div><br><br><br>
</body>
</html>
