<!DOCTYPE html>
<?php
include('../../../config/mysql.php');

// Initialize date variables
$startDate = '';
$endDate = '';

// Check if the filter form has been submitted
if (isset($_POST['filter'])) {
    $startDate = $_POST['start_date'];
    $endDate = $_POST['end_date'];

    // Query to fetch filtered data based on the date range
    $query = "
        SELECT 
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
        FROM floodreport f
        LEFT JOIN login l ON f.user_id = l.id
        WHERE f.date_time BETWEEN '$startDate' AND '$endDate'
        ORDER BY f.id DESC
    ";
} else {
    // Query to fetch all data from the database
    $query = "
        SELECT 
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
        FROM floodreport f
        LEFT JOIN login l ON f.user_id = l.id
        ORDER BY f.id DESC
    ";
}

$result = mysqli_query($conn, $query);
// Close the database connection
mysqli_close($conn);
?>

<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Urban Safe</title>
    <link rel="stylesheet" href="../../../css/index.css">
    <style>
        header {
            height: 100px;
            display: flex;
            align-items: center; /* Vertically centers the logo */
            justify-content: flex-start; /* Keeps the logo to the left */
            padding-left: 20px; /* Adds some padding from the left edge */
            white-space: nowrap; /* Prevents text from wrapping */
        }
        
        .logo {
            height: 110px; /* Adjust this value for logo size */
            width: auto;
        }
    
        .title {
            margin-left: 10px; /* Adds space between the logo and title */
            font-size: 2rem;
            white-space: nowrap; /* Ensures the text stays on one line */
        }
        
    body {
        height: 100px;
        font-family: Arial, sans-serif;
        margin: 0;
        padding: 0;
        background-color: #FFF;
    }

    
        .urban-logo {
            margin-top: 20px;
            width: auto;
            height: auto;
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
                width: 100px; /* Adjust width for smaller screens */
            }

            .report-btn, .login-btn {
                padding: 8px 15px; /* Smaller padding for smaller screens */
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
    </style>
</head>
<body>
    <header>
        <div class="header-container">
            <img src="../../../pics/logo.png" alt="Logo" class="logo">
            <h1 class="title"><i>NOTIFICATION REPORTS</i></h1>
        </div>
    </header>
    
    <div class="button-container">
        <a href="flood.php" class="go-back-button">Go Back</a>
    </div>

<div class="index-container">
    <div class="index-box">
        <center>
            <div class="filter-container">
                <form method="post" action="">
                    <label for="start_date">Start Date:</label>
                    <input type="date" id="start_date" name="start_date" value="<?php echo htmlspecialchars($startDate); ?>" required>
                    <label for="end_date">End Date:</label>
                    <input type="date" id="end_date" name="end_date" value="<?php echo htmlspecialchars($endDate); ?>" required>
                    <input type="submit" name="filter" value="Filter">
                    <input type="submit" name="clear" value="Clear" formaction="">
                </form>
            </div>
        </center> 
   </div>
</div><br><br>

<div class="index-container">
    <div class="index-box">
        <div class="mt-5">
            <center>
                <?php if ($result->num_rows > 0): ?>
                    <table>
                        <thead>
                            <tr>
                                <th>Report ID</th>
                                <th>User ID</th>
                                <th>User Name</th>
                                <th>Location</th>
                                <th>Date Time</th>
                                <th>Report</th>
                                <th>Image</th>
                                <th>Respond</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php while ($row = $result->fetch_assoc()): ?>
                                <tr>
                                    <td data-label="Report ID"><?php echo htmlspecialchars($row['id']); ?></td>
                                    <td data-label="User ID"><?php echo htmlspecialchars($row['user_id'] ?? 'N/A'); ?></td>
                                    <td data-label="User Name"><?php echo htmlspecialchars($row['name'] ?? 'Anonymous'); ?></td>
                                    <td data-label="Location"><?php echo htmlspecialchars($row['loc']); ?></td>
                                    <td data-label="Date Time"><?php echo htmlspecialchars($row['date_time']); ?></td>
                                    <td data-label="Report"><?php echo htmlspecialchars($row['report']); ?></td>
                                    <td data-label="Image">
                                        <?php if (!empty($row['img'])): ?>
                                            <?php
                                            $base64_image = base64_encode($row['img']);
                                            $image_src = 'data:image/jpeg;base64,' . $base64_image;
                                            ?>
                                            <img src="<?php echo $image_src; ?>" width="100" height="100" alt="Report Image" onclick="showFullImage('<?php echo $image_src; ?>')">
                                        <?php else: ?>
                                            No image available
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <button type="button" class="respond-btn <?php echo $row['respond'] ? 'complete' : ''; ?>"
                                            data-id="<?php echo htmlspecialchars($row['id']); ?>"
                                            data-img="<?php echo htmlspecialchars($image_src ?? ''); ?>"
                                            data-respond="<?php echo $row['respond'] ? 'true' : 'false'; ?>"
                                            data-user-id="<?php echo htmlspecialchars($row['user_id']); ?>" 
                                            data-user-name="<?php echo htmlspecialchars($row['name']); ?>" 
                                            onclick="openModal(this)">
                                        <?php echo $row['respond'] ? 'Completed' : 'Respond'; ?>
                                        </button>
                                    </td>
                                </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                <?php else: ?>
                    <p>No reports available.</p>
                <?php endif; ?>
            </center>
        </div>
    </div>
</div>
    
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
            <p><strong>User ID:</strong> <span id="modal-user-id"></span></p> <!-- Add User ID -->
            <p><strong>User Name:</strong> <span id="modal-user-name"></span></p> <!-- Add User Name -->
            <p><strong>Report ID:</strong> <span id="modal-id"></span></p>
            <p><strong>Location:</strong> <span id="modal-loc"></span></p>
            <p><strong>Date Time:</strong> <span id="modal-date_time"></span></p>
            <p><strong>Report:</strong> <span id="modal-report"></span></p>
            <p><strong>Image:</strong></p>
            <img id="modal-image" width="100" height="100" alt="Report Image">
            <br><br>
            <button id="done-btn" class="done-btn no-button" onclick="markAsComplete()">Done Respond</button>
            <button class="done-btn" onclick="closeModal()">Close</button>
        </div>
    </div>

    <script>
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
            const userId = button.getAttribute('data-user-id'); // Get user ID
            const userName = button.getAttribute('data-user-name'); // Get user name
        
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
            xhr.open("POST", "../../../update_status3.php", true);
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

        // Close the modal when clicking outside of it
        window.onclick = function(event) {
            const modal = document.getElementById('myModal');
            if (event.target === modal) {
                closeModal();
            }
        }
    </script>
<br><br><br><br>
<br><br><br><br>

    <footer>
        <p>&copy; 2024 ES Tech Innovations. All Rights Reserved.</p>
    </footer>
    
</body>
</html>