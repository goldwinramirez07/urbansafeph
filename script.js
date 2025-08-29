function toggleMenu() {
    var menu = document.getElementById("menu");
    if (menu.style.left === "0px") {
        menu.style.left = "-200px"; // Hide the menu
    } else {
        menu.style.left = "0px"; // Show the menu
    }
}

function goToHome() {
            
            window.location.href = "index.php";
        }

function goToReport() {
    window.location.href = "report.php";
}


function setDateTime() {
    const now = new Date();
    const formattedDateTime = now.toLocaleString(); // Formats the date and time according to the user's locale
    document.getElementById("dateTimeInput").value = formattedDateTime;
}

// Function to set the location automatically
function setLocation() {
    // Get the user's latitude and longitude using browser's Geolocation API
    if (navigator.geolocation) {
        navigator.geolocation.getCurrentPosition(position => {
            const latitude = position.coords.latitude;
            const longitude = position.coords.longitude;

            // Use LocationIQ API for reverse geocoding
            const accessToken = "xxxxxx";
            const reverseGeocodeApiUrl = `https://us1.locationiq.com/v1/reverse.php?key=${accessToken}&lat=${latitude}&lon=${longitude}&format=json`;

            // Fetch the reverse geocoded location
            fetch(reverseGeocodeApiUrl)
                .then(response => response.json())
                .then(data => {
                    // Handle the reverse geocoded data
                    console.log('Reverse Geocoded Data:', data);

                    const location = data.address;
                    if (location) {
                        const address = `${location.road || ''}, ${location.city || ''}, ${location.state || ''}, ${location.country || ''}`;
                        console.log('Address:', address);

                        // Find the input element by ID and set its value to the address
                        const locationInput = document.getElementById('locationInput');
                        if (locationInput) {
                            locationInput.value = address; // Set the input's value to the formatted address
                        }
                    }
                })
                .catch(error => {
                    console.error('Error fetching reverse geocoding data:', error);
                });
        }, error => {
            console.error('Error getting user location:', error);
        });
    } else {
        console.error('Geolocation is not supported by this browser.');
    }
}

window.onload = function () {
    setDateTime();
    setLocation();
}
