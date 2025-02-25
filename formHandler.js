document.addEventListener("DOMContentLoaded", function () {
    const form = document.getElementById("contactForm");
    if (!form) {
        console.error("Form #contactForm not found.");
        return;
    }

    form.addEventListener("submit", function (event) {
        event.preventDefault();

        let formData = new FormData(form);

        fetch("send_email.php", {
            method: "POST",
            body: formData
        })
        .then(response => response.text())
        .then(data => {
            console.log("Server response:", data);

            const responseMessage = document.getElementById("responseMessage");
            if (!responseMessage) return;

            if (data.trim() === "success") {
                responseMessage.textContent = "Thank you, your message has been sent!";
                responseMessage.style.color = "green";
                responseMessage.style.display = "block";
                form.reset();
            } else {
                responseMessage.textContent = "Error sending message. Please try again.";
                responseMessage.style.color = "red";
                responseMessage.style.display = "block";
            }
        })
        .catch(error => {
            console.error("Error:", error);
            const responseMessage = document.getElementById("responseMessage");
            if (responseMessage) {
                responseMessage.textContent = "An error occurred. Please try again later.";
                responseMessage.style.color = "red";
                responseMessage.style.display = "block";
            }
        });
    });
});
