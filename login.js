async function loginUser(event) {
  event.preventDefault(); // Prevent form submission

  const userId = document.getElementById("userId").value.trim();
  const password = document.getElementById("password").value.trim();

  if (userId === "" || password === "") {
    alert("Both fields are required.");
    return;
  }

  // Send login data to the server
  try {
    const response = await fetch("login.php", {
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
      window.location.href = "dashboard.html";
    } else {
      alert("Login failed: " + result.message);
    }
  } catch (error) {
    console.error("Error:", error);
    alert("An error occurred. Please try again.");
  }
}
