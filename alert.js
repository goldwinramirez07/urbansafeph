// alerts.js

// Function to format the day and time
function getFormattedDateTime() {
    var now = new Date();
    var options = { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric', hour: 'numeric', minute: 'numeric', second: 'numeric' };
    return now.toLocaleDateString(undefined, options);
}

// Function to show a login success alert
function showLoginAlert() {
    var loginTime = getFormattedDateTime();
    alert("Log in successful!\nCurrent Date and Time: " + loginTime);
}

// Function to show a report submission alert
function showReportAlert() {
    var reportTime = getFormattedDateTime();
    alert("Report submitted successfully!\nCurrent Date and Time: " + reportTime);
}

// Function to show a logout success alert
function showLogoutAlert() {
    var logoutTime = getFormattedDateTime();
    alert("Log out successful!\nCurrent Date and Time: " + logoutTime);
}
