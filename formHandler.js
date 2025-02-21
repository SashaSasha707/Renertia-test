document.addEventListener("DOMContentLoaded", function () {
    document.getElementById("contactForm").addEventListener("submit", function (event) {
        event.preventDefault(); // Prevent default form submission

        let formData = new FormData(this);

        fetch("send_email.php", {
            method: "POST",
            body: formData
        })
        .then(response => response.text()) // Get response as text
        .then(data => {
            console.log("Server response:", data); // Log server response for debugging

            if (data.trim() === "success") {
                document.getElementById("responseMessage").textContent = "Thank you, your message has been sent!";
                document.getElementById("responseMessage").style.color = "green";
                document.getElementById("responseMessage").style.display = "block";
                document.getElementById("contactForm").reset(); // Reset form after successful submission
            } else {
                document.getElementById("responseMessage").textContent = "Error: " + data;
                document.getElementById("responseMessage").style.color = "red";
                document.getElementById("responseMessage").style.display = "block";
            }
        })
        .catch(error => {
            document.getElementById("responseMessage").textContent = "Error sending message. Please try again.";
            document.getElementById("responseMessage").style.color = "red";
            document.getElementById("responseMessage").style.display = "block";
        });
    });
});
