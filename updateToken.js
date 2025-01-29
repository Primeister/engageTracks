async function updateUserField(userId, field, value) {
  try {
    const response = await fetch("updateUserField.php", {
      method: "POST",
      headers: {
        "Content-Type": "application/json",
      },
      body: JSON.stringify({ user_id: userId, field, value }),
    });

    const result = await response.json();

    if (result.success) {
      alert("Field updated successfully!");
      console.log("Updated field:", field, "Value:", value);
    } else {
      alert("Failed to update field: " + result.message);
    }
  } catch (error) {
    console.error("Error:", error);
    alert("An error occurred. Please try again.");
  }
}

// Example usage:
updateUserField(1, "email", "updatedemail@example.com");
