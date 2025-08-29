$heatData = [];
$result = $conn->query("SELECT latitude, longitude FROM floodreport");

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
    'max' => 5, // You can adjust this based on your needs
    'data' => $heatData
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

<script>
        // Initialize the map and set its view
        const map = L.map('map').setView([14.598382, 121.019379], 17); // Center on Barangay 600

        // Add a tile layer (OpenStreetMap in this case)
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            maxZoom: 19,
            attribution: '© OpenStreetMap'
        }).addTo(map);
        
    // Embed PHP-generated heatmap data directly into JavaScript
    const heatData = <?php echo json_encode($heatmapData['data']); ?>;
    console.log(heatData);
    // Create a heat layer and add it to the map
    const heatLayerData = heatData.map(point => [point.lat, point.lng, point.val]); //Value cannot pass here
    const heat = L.heatLayer(heatLayerData, { radius: 25, blur: 15 }).addTo(map);
        // Define the coordinates for the geofencing polygon
        const polygonCoordinates = [
            [14.598515, 121.017364],
            [14.597663, 121.017669],
            [14.596980, 121.018488],
            [14.597302, 121.019369],
            [14.597484, 121.019336],
            [14.598197, 121.021680],
            [14.598720, 121.021483],
            [14.598428, 121.020355],
            [14.598558, 121.020280],
            [14.598728, 121.020342],
            [14.598703, 121.020465],
            [14.598941, 121.020522],
            [14.599050, 121.020390],
            [14.598951, 121.020264],
            [14.599431, 121.019253],
            [14.598515, 121.017364] // Closing the polygon
        ];

        // Create a polygon and add it to the map
    const polygon = L.polygon(polygonCoordinates, {
        color: 'blue',
        weight: 2,
        opacity: 0.5,
        fillColor: 'blue',
        fillOpacity: 0.1
    }).addTo(map);

    // Optional: Bind a popup to the polygon
    polygon.bindPopup('Geofenced Area: Barangay 600');
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
