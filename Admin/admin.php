<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Search Records</title>
    <link rel="stylesheet" href="../css/admin.css">
    <style>

    body {
        height: 100px;
        font-family: Arial, sans-serif;
        margin: 0;
        padding: 0;
        background-color: #FFF;
    }
    
    /* Basic styling */
    body, h2, button, input {
        font-family: Arial, sans-serif;
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


    .search-section {
        margin-bottom: 20px;
    }
    
    .search-section h2 {
        color: #FFD700; /* Yellow color */
    }

    /* Table styling */
    .table-wrapper {
        overflow-x: auto;
        width: 100%;
    }

    table {
        width: 100%;
        border-collapse: collapse;
        margin-bottom: 20px;
        border: 2px solid black;
    }

    th, td {
        padding: 10px;
        text-align: left;
        border: 1px solid #ddd;
    }

    th span {
        font-size: 0.8em;
        margin-left: 5px;
    }
    tr {
        background-color: white;
    }
    
    /* Responsive styling */
    @media (max-width: 768px) {
        .content-container {
            margin-left: 0;
            padding: 10px;
        }

        .menu {
            width: 100%;
            height: auto;
            left: -100%;
            transition: left 0.3s ease;
        }

        .menu-btn {
            top: 10px;
            left: 10px;
        }
    }

    @media (max-width: 480px) {
        .menu-btn {
        width: 50%;
        padding: auto;
        font-size: 1.2em;
        }

        th, td {
            font-size: 12px;
            padding: 6px;
        }
                
        .content-container {
        width: 100%;
        }
    }
    #clearBtn {
        padding: 10px;
        background-color: #FFC72C;
        color: navy;
        border: none;
        cursor: pointer;
    }

       .btn-success {
        width: 100%;
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
        width: 100%;
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
        
</style>
</head>

<body>
    <input type="submit" class="menu-btn" value="URBANSAFE" onclick="toggleMenu()">
    <div id="menu" class="menu">
        <ul>
            <li><input type="submit" class="menu-display" value="URBANSAFE" onclick="toggleMenu()"></li>
            <li><input type="submit" class="menu-edit-contact" value="Responder" onclick="goToEditContactNumber()"></li>
            <li><input type="submit" class="menu-edit-radius" value="Area Covered" onclick="goToEditRadiusGeofencing()"></li>
            <li><input type="submit" class="menu-analytics" value="Analytics" onclick="goToAnalytics()"></li>
            <li><input type="submit" class="menu-analytics" value="Add Responder" onclick="goToResponder()"></li>
            <li><input type="submit" class="menu-logout" value="Log out" onclick="index()"></li>
        </ul>
    </div>

    <div class="content-container">
        <section class="search-section">
            <h2>SEARCH RECORDS</h2>
            <input type="text" id="searchBox" placeholder="Search by Email" value="<?= isset($_GET['email']) ? htmlspecialchars($_GET['email']) : '' ?>">
            <button id="searchBtn">Search</button>
            <button id="clearBtn" onclick="clearSearch()">Clear</button> 
        </section>

        <div class="table-wrapper">
            <table id="recordsTable">
                <tr>
                    <th>ID</th>
                    <th>Code</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Contact Number</th>
                    <th>Password</th>
                    <th>Role</th>
                    <th>Responder Type <span onclick="sortTable()" style="cursor: pointer;">&#9650;&#9660;</span></th>
                    <th>Action</th>
                </tr>

                <?php
                include "../config/mysql.php";

                // Fetch search query from URL if it exists
                $email_search = isset($_GET['email']) ? $_GET['email'] : '';
                $sql_query = "SELECT * FROM login";
                
                // Modify query if an email search is specified
                if ($email_search) {
                    $sql_query .= " WHERE email LIKE '%" . mysqli_real_escape_string($conn, $email_search) . "%'";
                }

                $sql = mysqli_query($conn, $sql_query);
                
                // Display records in the table
                while ($row = mysqli_fetch_array($sql)) {
                ?>
                    <tr>
                        <td><?= $row['id'] ?></td>
                        <td><?= $row['code'] ?></td>
                        <td><?= $row['name'] ?></td>
                        <td><?= $row['email'] ?></td>
                        <td><?= $row['contact_no'] ?></td>
                        <td data-full-password="<?= $row['password'] ?>"><?= substr($row['password'], 0, 10) ?>...</td>
                        <td><?= $row['role'] ?></td>
                        <td class="responder-type"><?= $row['responder_type'] ?></td>
                        <td>
                            <button type="button" id ="btn-success" class="btn btn-success" style="height:40px" onclick="editRow(this)">Edit</button>
                            <br><br>
                            <button type="button" class="btn btn-danger" style="height:40px" onclick="deleteRow(<?= $row['id'] ?>)">Delete</button>
                            <br><br>
                        </td>
                    </tr>
                <?php
                }
                ?>
            </table>
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
    function goToHistory() {
        window.location.href = "history.php";
    }
    
    function deleteRow(id) {
        if (confirm("Are you sure you want to delete this record?")) {
            // Send an AJAX request to delete the record
            fetch("delete_record1.php", {
                method: "POST",
                headers: {
                    "Content-Type": "application/json",
                },
                body: JSON.stringify({ id: id }),
            })
                .then((response) => response.json())
                .then((data) => {
                    if (data.success) {
                        alert("Record deleted successfully.");
                        // Reload the page or remove the row from the table
                        location.reload();
                    } else {
                        alert("Failed to delete record.");
                    }
                })
                .catch((error) => {
                    console.error("Error:", error);
                    alert("An error occurred while deleting the record.");
                });
        }
    }

    let ascending = true; // Track sorting order

    function sortTable() {
        const table = document.getElementById("recordsTable");
        let rows = Array.from(table.rows).slice(1); // Exclude header row

        rows.sort((a, b) => {
            const responderA = a.querySelector(".responder-type").textContent.toLowerCase();
            const responderB = b.querySelector(".responder-type").textContent.toLowerCase();
            return ascending ? responderA.localeCompare(responderB) : responderB.localeCompare(responderA);
        });

        rows.forEach(row => table.appendChild(row));
        ascending = !ascending; // Toggle sorting order for next click
    }

    document.getElementById("searchBtn").addEventListener("click", function () {
        const searchQuery = document.getElementById("searchBox").value;
        window.location.href = `?email=${searchQuery}`;
    });

    function clearSearch() {
        document.getElementById("searchBox").value = '';
        window.location.href = '?'; // Reloads page to show all data
    }

    function saveRow(button) {
        const row = button.closest("tr");
        const cells = row.querySelectorAll("td");
    
        // Collect the ID and new values from the input fields
        const id = cells[0].textContent;
        
        const updatedData = {
            id: id,
            code: cells[1].textContent,  // Do not edit this field
            name: cells[2].querySelector("input") ? cells[2].querySelector("input").value : cells[2].textContent,
            email: cells[3].querySelector("input") ? cells[3].querySelector("input").value : cells[3].textContent,
            contact_no: cells[4].querySelector("input") ? cells[4].querySelector("input").value : cells[4].textContent,
            password: cells[5].textContent, // Do not edit this field
            role: cells[6].textContent,  // Do not edit this field
            responder_type: cells[7].textContent  // Do not edit this field
        };
    
        // Send the data to the server using AJAX
        fetch("update_record.php", {
            method: "POST",
            headers: {
                "Content-Type": "application/json"
            },
            body: JSON.stringify(updatedData)
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Update the row with the new values
                cells[1].textContent = updatedData.code; // Code remains unchanged
                cells[2].textContent = updatedData.name;
                cells[3].textContent = updatedData.email;
                cells[4].textContent = updatedData.contact_no;
                cells[5].textContent = updatedData.password.length > 10 ? updatedData.password.substring(0, 10) + "..." : updatedData.password; // Truncate password for display
                cells[6].textContent = updatedData.role; // Role remains unchanged
                cells[7].textContent = updatedData.responder_type; // Responder type remains unchanged
    
                // Restore the "Edit" button and remove the "Cancel" button
                button.textContent = "Edit";
                button.setAttribute("onclick", "editRow(this)");
                cells[cells.length - 1].querySelector("button:last-child").remove(); // Remove Cancel button
    
                alert("Record updated successfully.");
            } else {
                alert("Failed to update record.");
            }
        })
        .catch(error => {
            console.error("Error:", error);
            alert("An error occurred while updating the record.");
        });
    }


    function editRow(button) {
        const row = button.closest("tr");
        const cells = row.querySelectorAll("td");
    
        // Loop through each cell, excluding the ID and Action columns
        for (let i = 1; i < cells.length - 1; i++) {
            const cell = cells[i];
            
            // Skip making code, password, role, and responder type cells editable
            if (i === 1 || i === 5 || i === 6 || i === 7) continue; // Indices for code, password, role, and responder type
    
            const text = cell.textContent;
            cell.innerHTML = `<input type="text" value="${text}" />`;
        }
    
        // Change "Edit" button to "Save" and add a "Cancel" button
        button.textContent = "Save";
        button.setAttribute("onclick", "saveRow(this)");
    
        const cancelButton = document.createElement("button");
        cancelButton.textContent = "Cancel";
        cancelButton.classList.add("btn", "btn-danger");
        cancelButton.style.height = "40px";
        cancelButton.setAttribute("onclick", "cancelEdit(this)");
    
        button.parentNode.appendChild(cancelButton);
    }



    function cancelEdit(button) {
        const row = button.closest("tr");
        const cells = row.querySelectorAll("td");

        // Loop through each cell, excluding the ID and Action columns, and restore the original text
        for (let i = 1; i < cells.length - 1; i++) {
            const cell = cells[i];
            const input = cell.querySelector("input");
            if (input) {
                cell.textContent = input.value;
            }
        }

        // Restore the "Edit" button
        const editButton = row.querySelector("button.btn-success");
        editButton.textContent = "Edit";
        editButton.setAttribute("onclick", "editRow(this)");

        // Remove the "Cancel" button
        button.remove();
    }
</script>
</body>

</html>