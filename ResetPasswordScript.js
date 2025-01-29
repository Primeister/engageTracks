let eyeIcon = document.getElementById("eyeIcon");
let eyeIcon2 = document.getElementById("eyeIcon2");

let password1 = document.getElementById("password");

eyeIcon.onclick = function () {
  if (password1.type == "password") {
    password1.type = "text";
    eyeIcon.src = "assets/eye-open.png";
  } else {
    password1.type = "password";
    eyeIcon.src = "assets/eye-close.png";
  }
};

eyeIcon2.onclick = function () {
  if (confirmPassword.type == "password") {
    confirmPassword.type = "text";
    eyeIcon2.src = "assets/eye-open.png";
  } else {
    confirmPassword.type = "password";
    eyeIcon2.src = "assets/eye-close.png";
  }
};

document
  .getElementById("reset-password-form")
  .addEventListener("submit", function (event) {
    event.preventDefault();
    const password = document.getElementById("password").value.trim();
    const confirmPassword = document
      .getElementById("confirmPassword")
      .value.trim();
    const userId = document.getElementById("userId").value.trim();
    const otp = document.getElementById("otp").value.trim();

    const passwordCriteria = {
      length: password.length >= 8,
      uppercase: /[A-Z]/.test(password),
      lowercase: /[a-z]/.test(password),
      number: /\d/.test(password),
      specialChar: /[@$!%*?&]/.test(password),
    };

    const allCriteriaMet = Object.values(passwordCriteria).every(Boolean);

    if (!allCriteriaMet) {
      // Prevent form submission
      alert(
        "Password must meet the following requirements:\n" +
          "- At least 8 characters\n" +
          "- At least one uppercase letter\n" +
          "- At least one lowercase letter\n" +
          "- At least one number\n" +
          "- At least one special character (@$!%*?&)"
      );
    }

    if (password !== confirmPassword) {
      event.preventDefault();
      alert("Passwords do not match");
    } else {
      alert("You've done it fam");
      resetPassword(userId, otp, password);
    }
  });

async function resetPassword(userId, otp, newPassword) {
  const url = "https://admittance-eng.co.za/reset_password.php"; // Replace with the actual PHP file path

  try {
    const response = await fetch(url, {
      method: "POST",
      headers: {
        "Content-Type": "application/json",
      },
      body: JSON.stringify({ userId, otp, new_password: newPassword }),
    });

    const data = await response.json();
    if (data.success) {
      alert("Password successfully changed");
      loginUser(userId, newPassword);
    } else {
      alert(data.message);
    }
  } catch (error) {
    console.error("Error:", error);
  }
}

async function loginUser(userId, password) {
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
      sessionStorage.setItem("name", result.user.name);
      sessionStorage.setItem("userId", result.user.userId);
      sessionStorage.setItem("token", result.user.token);
      sessionStorage.setItem("role", result.user.role);

      if (result.user.role == "admin") {
        window.location.href = "execDashboard.html";
      }
    } else {
      alert("Login failed: " + result.message);
    }
  } catch (error) {
    console.error("Error:", error);
    alert("An error occurred. Please try again.");
  }
}
