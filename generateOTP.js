async function generateOTP(event) {
    event.preventDefault();
    const userId = document.getElementById("userId").value.trim();
    const url = "https://admittance-eng.co.za/generate_otp.php"; // Replace with the actual PHP file path

    try {
        const response = await fetch(url, {
            method: "POST",
            headers: {
                "Content-Type": "application/json",
            },
            body: JSON.stringify({ userId }),
        });

        const data = await response.json();
        if(data.success){
            alert("An email containing an otp has been sent to your email");
            window.location.href="ResetPassword2.html";
        }else{
            alert(data.message);
        }
        
    } catch (error) {
        console.error("Error:", error);
    }
}