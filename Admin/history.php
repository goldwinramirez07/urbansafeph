<!DOCTYPE html>
<?php
include('../config/mysql.php');

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

// Close the database connection
mysqli_close($conn);
?>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Urban Safe</title>
    <link rel="stylesheet" href="../css/index.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
    body {
        height: 100px;
        font-family: Arial, sans-serif;
        margin: 0;
        padding: 0;
        background-color: #FFF;
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
        
        .button-container {
            margin: 20px 0;
        }

        .go-back-button {
            padding: 10px 20px;
            background-color: #007bff;
            color: white;
            border: none;
            border-radius: 5px;
            text-decoration: none;
            font-size: 16px;
            cursor: pointer;
        }

        .go-back-button:hover {
            background-color: #0056b3;
        }
        
        /* Modal styles */
        .modal {
            justify-content: center;
            align-items: center;
            flex-grow: 1;
            display: none;
            position: fixed;
            z-index: 1;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            overflow: auto;
            background-color: rgba(0,0,0,0.4);
        }

        .modal-content {
            background-color: #fefefe;
            margin: 15% auto;
            padding: 20px;
            border: 5px solid black; /* Adding a thick black border */
            border-color: #1B413A;
            border-radius: 10px;
            box-shadow: 0 0 15px rgba(0, 0, 0, 1);
            width: 80%;
            max-width: 600px;
            box-sizing: border-box; /* Ensures padding does not overflow */
        }

        .done-btn {
            background-color: #28a745;
            color: white;
            border: none;
            padding: 10px 20px;
            text-align: center;
            text-decoration: none;
            display: inline-block;
            font-size: 16px;
            margin: 4px 2px;
            cursor: pointer;
        }

        .done-btn:hover {
            background-color: #218838;
        }

        .complete {
            background-color: lightgreen;
            color: black;
        }

        .no-button {
            display: none;
        }
        
    .index-container {
        display: flex;
        justify-content: center;
        align-items: center;
        flex-grow: 1;
        height: auto;
        width: 100%; /* Adjust container width */
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


    .filter-container {
            margin: 20px;
        }
    .filter-container input[type="date"] {
            margin-right: 10px;
        }
        @media (max-width: 768px) {
            .title {
                font-size: 1.2rem;
            }

            .urban-logo {
                width: 100px;
            }

            .report-btn, .login-btn {
                padding: 8px 15px;
                font-size: 1rem;
            }
        }
          /* Responsive Table */
    @media (max-width: 768px) {
        table, thead, tbody, th, td, tr {
            display: block;
            width: 100%;
        }

        th {
            display: none; /* Hide table headers for mobile */
        }

        td {
            position: relative;
            padding-left: 50%;
            text-align: left;
            border: 1px solid black;
        }

        td:before {
            content: attr(data-label); /* Add data-label attribute to each cell */
            position: absolute;
            left: 10px;
            top: 10px;
            font-weight: bold;
            white-space: nowrap;
        }

        .modal-content {
            width: 90%; /* Adjust modal width on mobile */
        }
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

       .btn-success {
        width: auto;
        height: 100px;
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
        height: 100px;
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
    
            .input-group {
            position: relative;
            margin-bottom: 20px;
        }

        .input-field {
            width: auto; /* Ensures the input fills the container */
            padding: 12px;
            padding-left: 30px;
            border-radius: 5px;
            border: 1px solid #ccc;
            font-size: 14px;
            box-sizing: border-box; /* Prevents padding from affecting width */
        }
    

    </style>
</head>
<body>

    <input type="submit" class="menu-btn" value="URBANSAFE" onclick="toggleMenu()">
    <div id="menu" class="menu">
        <ul>
            <li><input type="submit" class="menu-display" value="URBANSAFE" onclick="toggleMenu()"></li>
            <li><input type="submit" class="menu-analytics" value="Home" onclick="goToHome()"></li>
            <li><input type="submit" class="menu-edit-contact" value="Responder" onclick="goToEditContactNumber()"></li>
            <li><input type="submit" class="menu-edit-radius" value="Area Covered" onclick="goToEditRadiusGeofencing()"></li>
            <li><input type="submit" class="menu-analytics" value="Analytics" onclick="goToAnalytics()"></li>
            <li><input type="submit" class="menu-analytics" value="Add Responder" onclick="goToResponder()"></li>
            <li><input type="submit" class="menu-logout" value="Log out" onclick="index()"></li>
        </ul>
    </div>

<div class="index-container">
    <div class="index-box">
    <center>
    <div class="mt-5">
        <?php if ($result->num_rows > 0): ?>
            <table>
                <form method="post" action="">
                    <label for="month">Select Month:</label>
                    <select id="month" name="month" required>
                        <?php for ($m = 1; $m <= 12; $m++): ?>
                            <option value="<?php echo $m; ?>" <?php if(isset($_POST['month']) && $_POST['month'] == $m) echo 'selected'; ?>>
                                <?php echo date('F', mktime(0, 0, 0, $m, 1)); ?>
                            </option>
                        <?php endfor; ?>
                    </select>
                    
                    <label for="year">Select Year:</label>
                    <select id="year" name="year" required>
                        <?php for ($y = date('Y'); $y >= 2000; $y--): ?>
                            <option value="<?php echo $y; ?>" <?php if(isset($_POST['year']) && $_POST['year'] == $y) echo 'selected'; ?>>
                                <?php echo $y; ?>
                            </option>
                        <?php endfor; ?>
                    </select>
                    
                    <input type="submit" class="btn-success" name="filter" value="Filter">
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
                    <?php while ($row = $result->fetch_assoc()): ?>
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
$result->data_seek(0); // Reset the result pointer
while ($row = $result->fetch_assoc()) {
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
    
    <!-- Modal for enlarged image -->
    <div id="imageModal" class="modal">
        <div class="modal-content">
            <img id="modal-full-image" style="width:100%; height:auto;" alt="Full Report Image">
            <button class="done-btn" onclick="closeImageModal()">Close</button>
        </div>
    </div>

    <!-- Modal -->
    <div id="myModal" class="modal">
        <div class="modal-content">
            <h2 id="modal-title">Report Details</h2>
            <p><strong>User ID:</strong> <span id="modal-user-id"></span></p>
            <p><strong>User Name:</strong> <span id="modal-user-name"></span></p>
            <p><strong>Report ID:</strong> <span id="modal-id"></span></p>
            <p><strong>Location:</strong> <span id="modal-loc"></span></p>
            <p><strong>Date Time:</strong> <span id="modal-date_time"></span></p>
            <p><strong>Report:</strong> <span id="modal-report"></span></p>
            <p><strong>Image:</strong></p>
            <img id="modal-image" width="100" height="100" alt="Report Image">
            <br><br>
            <center>
            <button id="done-btn" class="done-btn no-button" onclick="markAsComplete()">Done Respond</button>
            <button class="done-btn" onclick="closeModal()">Close</button></center>
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

    function goToAnalytics() {
        window.location.href = "Analytics.php";
    }

    function index() {
        alert("Log out successfully");
        window.location.href = "../index.php";
    }
    function goToResponder() {
        window.location.href = "add_responder.php";
    }
        let currentButton = null;
        let currentId = null;
        
        function showFullImage(imageSrc) {
            document.getElementById('modal-full-image').src = imageSrc;
            document.getElementById('imageModal').style.display = "block";
        }

        function closeImageModal() {
            document.getElementById('imageModal').style.display = "none";
        }
        
        function openModal(button) {
            const id = button.getAttribute('data-id');
            const isComplete = button.getAttribute('data-respond') === 'true';
            const imgSrc = button.getAttribute('data-img');
            const userId = button.getAttribute('data-user-id');
            const userName = button.getAttribute('data-user-name');
        
            currentButton = button;
            currentId = id;
        
            // Fill modal content
            document.getElementById('modal-id').textContent = id;
            document.getElementById('modal-loc').textContent = button.closest('tr').cells[3].textContent;
            document.getElementById('modal-date_time').textContent = button.closest('tr').cells[4].textContent;
            document.getElementById('modal-report').textContent = button.closest('tr').cells[5].textContent;
        
            // Display user details
            document.getElementById('modal-user-id').textContent = userId;
            document.getElementById('modal-user-name').textContent = userName;
        
            // Set image src
            document.getElementById('modal-image').src = imgSrc;
        
            // Update modal title and button visibility
            if (isComplete) {
                document.getElementById('modal-title').textContent = 'View Details';
                document.getElementById('done-btn').classList.add('no-button');
            } else {
                document.getElementById('modal-title').textContent = 'Report Details';
                document.getElementById('done-btn').classList.remove('no-button');
            }
        
            // Display modal
            document.getElementById('myModal').style.display = 'block';
        }

        function closeModal() {
            document.getElementById('myModal').style.display = 'none';
        }

        function markAsComplete() {
            const xhr = new XMLHttpRequest();
            xhr.open("POST", "update_status.php", true);
            xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
            xhr.onload = function() {
                if (xhr.responseText === "success") {
                    if (currentButton) {
                        currentButton.textContent = "Completed";
                        currentButton.classList.add('complete');
                        currentButton.setAttribute('data-respond', 'true');
                    }
                    closeModal(); // Close the modal after marking as complete
                } else {
                    alert("Failed to update status.");
                }
            };
            xhr.send("id=" + encodeURIComponent(currentId));
        }
    </script>
<br><br><br><br>
<br><br><br><br>

</body>
</html>
