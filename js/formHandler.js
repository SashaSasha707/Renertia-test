document.getElementById("contactForm").onsubmit = function(event) {
    event.preventDefault();
    
    var formData = new FormData(this);
    var submitButton = document.querySelector(".btn-form");
    var responseMessage = document.getElementById("responseMessage");

    // Додаємо "loading" ефект
    submitButton.disabled = true;
    submitButton.innerText = "Sending...";

    fetch("send_email.php", {
        method: "POST",
        body: formData
    })
    .then(response => response.text())
    .then(result => {
        result = result.trim();

        if (result === "success") {
            responseMessage.innerText = "Thank you! Your form has been submitted successfully.";
            responseMessage.style.color = "green";
            responseMessage.style.display = "block";
            document.getElementById("contactForm").reset();
        } else {
            responseMessage.innerText = "There was an error submitting the form. Please try again later.";
            responseMessage.style.color = "red";
            responseMessage.style.display = "block";
        }
    })
    .catch(error => {
        responseMessage.innerText = "An unexpected error occurred. Please try again.";
        responseMessage.style.color = "red";
        responseMessage.style.display = "block";
        console.error("Error:", error);
    })
    .finally(() => {
        submitButton.disabled = false;
        submitButton.innerText = "Submit Your Project";
    });
};
