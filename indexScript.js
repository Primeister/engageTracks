let eyeIcon = document.getElementById("eye-icon");

let password = document.getElementById("password");

eyeIcon.onclick = function () {
  if (password.type == "password") {
    password.type = "text";
    eyeIcon.src = "assets/eye-open.png";
  } else {
    password.type = "password";
    eyeIcon.src = "assets/eye-close.png";
  }
};

async function loginUser(event) {
  event.preventDefault(); // Prevent form submission

  const userId = document.getElementById("userId").value.trim();
  const password = document.getElementById("password").value.trim();


  // Send login data to the server
  try {
    const response = await fetch("https://admittance-eng.co.za/login.php", {
      method: "POST",
      headers: {
        "Content-Type": "application/json",
      },
      body: JSON.stringify({ userId, password }),
    });

    const result = await response.json();

    if (result.success) {
      alert("Login successful! Welcome, " + result.user.userId);
      console.log("User data:", result.user); // Log user data for debugging
      // Redirect to dashboard or process the user data
      sessionStorage.setItem('name', result.user.name);
      sessionStorage.setItem('userId', result.user.userId);
      sessionStorage.setItem('token', result.user.token);
      sessionStorage.setItem('role', result.user.role);
      window.location.href = result.user.role + "Dashboard.html";

    } else {
      alert("Login failed: " + result.message);
    }
  } catch (error) {
    console.error("Error:", error);
    alert("An error occurred. Please try again.");
  }
}
