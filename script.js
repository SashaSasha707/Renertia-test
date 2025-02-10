document.getElementById("contactForm").addEventListener("submit", async function (event) {
    event.preventDefault(); // Зупиняємо стандартну відправку форми

    const formData = new FormData();
    formData.append("firstName", document.getElementById("firstName").value);
    formData.append("email", document.getElementById("email").value);
    formData.append("file", document.getElementById("fileInput").files[0]);

    try {
        const response = await fetch("server.php", {
            method: "POST",
            body: formData
        });

        const result = await response.json();

        if (result.success) {
            document.getElementById("responseMessage").textContent = "Your form has been submitted successfully. We will contact you soon!";
            document.getElementById("responseMessage").style.display = "block";
            document.getElementById("contactForm").reset();
        } else {
            document.getElementById("responseMessage").textContent = "Error submitting the form. Please try again.";
            document.getElementById("responseMessage").style.color = "red";
            document.getElementById("responseMessage").style.display = "block";
        }
    } catch (error) {
        console.error("Error:", error);
        document.getElementById("responseMessage").textContent = "Server error. Please try again later.";
        document.getElementById("responseMessage").style.color = "red";
        document.getElementById("responseMessage").style.display = "block";
    }
});

