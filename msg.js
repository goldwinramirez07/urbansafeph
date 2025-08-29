const form = document.querySelector("#reportForm");

function sendMessage(event) {
    event.preventDefault();
    
    const apiKey = 'xxxxx';
    const number = document.querySelector("");
    const message = "Incoming report: http://urbansafeph.com/User/member.php";
    
    const parameters = {
        apiKey,
        number,
        message,
    }
    
    fetch(' https://api.semaphore.co/api/v4/messages', {
        method : 'POST',
        headers: {
            'Conent-Type' : 'application/x-www-form-urlencoded'
        },
        body: new URLSearchParams(parameters)
    }).then(response => response.text())
    .then(output => {
        console.log(output)
    })
    .catch(error => {
        console.error(error)
    })
}
