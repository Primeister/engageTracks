const container1 = document.getElementById("container1");
const projects = document.getElementById("projects");
const Heading = document.getElementById("Heading");
const openProjects = document.getElementById("openProjects");
const playBox = document.getElementById("playBox");
const arrow = document.getElementById("arrow");
const employees = document.getElementById("employees");
const employeeForm = document.getElementById("employeeForm");
const role = sessionStorage.getItem("role");

function showContainer() {
  Heading.textContent = "DASHBOARD";
  container1.style.display = "flex";
  projects.style.display = "none";
  openProjects.style.display = "none";
  arrow.style.display = "none";
  employees.style.display = "none";
  employeeForm.style.display = "none";
}

function showProjects() {
  Heading.textContent = "PROJECTS";
  container1.style.display = "none";
  projects.style.display = "flex";
  openProjects.style.display = "none";
  employees.style.display = "none";
  arrow.style.display = "block";
  employeeForm.style.display = "none";

  arrow.onclick = showContainer;
}

function getOpenProjects() {
  Heading.textContent = "OPEN PROJECTS";
  container1.style.display = "none";
  projects.style.display = "none";
  openProjects.style.display = "flex";
  employees.style.display = "none";
  employeeForm.style.display = "none";
  arrow.onclick = showProjects;
}

function showEmployees() {
  Heading.textContent = "EMPLOYEES";
  container1.style.display = "none";
  projects.style.display = "none";
  openProjects.style.display = "none";
  employees.style.display = "flex";
  arrow.style.display = "block";
  employeeForm.style.display = "none";
  arrow.onclick = showContainer;
}

function showEmployeeForm() {
  Heading.textContent = "ADD EMPLOYEE";
  container1.style.display = "none";
  projects.style.display = "none";
  openProjects.style.display = "none";
  employees.style.display = "none";
  arrow.style.display = "block";
  employeeForm.style.display = "flex";
  arrow.onclick = showEmployees;
}

function showAccMenu() {
  document.getElementById("playBox").style.position = "";
  if (playBox.style.marginLeft === "0%") {
    playBox.style.marginLeft = "-100%";
  } else {
    playBox.style.marginLeft = "0%";
  }
}

function addEmployee(event) {
  event.preventDefault();

  const employeeId = document.getElementById("employeeId").value.trim();
  const firstName = document.getElementById("firstName").value.trim();
  const lastName = document.getElementById("lastName").value.trim();
  const formRole = document.getElementById("formRole").value.trim();

  signUpEmployee(employeeId, firstName, lastName, formRole);
}

async function signUpEmployee(employeeId, firstName, lastName, role) {
  const url = "https://admittance-eng.co.za/signup_employee.php"; // Replace with the actual PHP file path

  try {
    const response = await fetch(url, {
      method: "POST",
      headers: { 
        "Content-Type": "application/json",
      },
      body: JSON.stringify({ employeeId, firstName, lastName, role }),
    });

    if (response.ok) {
      const data = await response.json();
      if (data.success) {
        console.log(data.message);
      } else {
        console.error(data.message);
        alert(data.message);
      }
    } else {
      console.error("HTTP error:", response.status);
    }
  } catch (error) {
    console.error("Fetch error:", error);
  }
}

let globalToken = null;

// JavaScript function to retrieve the token
async function getToken(userId, token) {
  const url = "https://admittance-eng.co.za/getToken.php"; // Replace with the actual PHP file path

  try {
    const response = await fetch(url, {
      method: "POST",
      headers: {
        "Content-Type": "application/json",
      },
      body: JSON.stringify({ userId }), // Send the username as JSON
    });

    if (response.ok) {
      const data = await response.json();
      if (data.success) {
        console.log("Token retrieved:", data.token);
        console.log("Token here:", token);
        if (token != token) {
          window.location.href = "index.html";
        }
        //return data.token; // Return the token
      } else {
        window.location.href = "index.html";
      }
    } else {
      console.error("HTTP error:", response.status);
    }
  } catch (error) {
    console.error("Fetch error:", error);
  }
}

document.addEventListener("DOMContentLoaded", function () {
  const token = sessionStorage.getItem("token");

  if (token === null) {
    window.location.href = "index.html";
  } else {
    const name = document.getElementById("name");
    const userId = sessionStorage.getItem("userId");
    name.textContent = sessionStorage.getItem("name");

    getToken(userId, token);
  }

  if (role === "exec") {
    fetchEmployees();
  }
});

function logout() {
  sessionStorage.removeItem("token");
  window.location.href = "index.html";
}

window.addEventListener("pageshow", (event) => {
  if (event.persisted) {
    const token = sessionStorage.getItem("token");
    if (!token) {
      window.location.href="index.html";
    }
  }
});

function openCalendar() {
  window.location.href = "calendar.html";
}

async function fetchEmployees() {
  const url = "https://admittance-eng.co.za/fetch_employees.php"; // Replace with your PHP file's path

  try {
    const response = await fetch(url, {
      method: "GET",
      headers: {
        "Content-Type": "application/json",
      },
    });

    const data = await response.json();

    if (data.success) {
      console.log("Employees:", data.employees);
      displayEmployees(data.employees);
    } else {
      console.error("Error:", data.message);
    }
  } catch (error) {
    console.error("Fetch error:", error);
  }
}

function displayEmployees(employees) {
  const employeesDiv = document.getElementById("employees");

  employees.forEach((employee) => {
    // Get the parent div

    // Create the 'a' element
    const anchor = document.createElement("a");
     // Set the href attribute (modify as needed)

    // Create the outer div with class 'card-div'
    const cardDiv = document.createElement("div");
    cardDiv.className = "card-div";

    // Create the first inner div with class 'image-div' and an image inside it
    const imageDiv = document.createElement("div");
    imageDiv.className = "image-div";
    const image = document.createElement("img");
    image.src = "assets/profileIMG.jpg"; // Set the image source
    image.alt = "Description"; // Add alt text for the image
    imageDiv.appendChild(image);

    // Create the second inner div with class 'heading-div' and an h3 inside it
    const headingDiv = document.createElement("div");
    headingDiv.className = "heading-div";
    const heading = document.createElement("h3");
    heading.textContent = employee.name + " " + employee.surname; // Add text to the heading
    headingDiv.appendChild(heading);

    // Append the inner divs to the card-div
    cardDiv.appendChild(imageDiv);
    cardDiv.appendChild(headingDiv);

    // Append the card-div to the 'a' element
    anchor.appendChild(cardDiv);

    // Append the 'a' element to the parent div
    employeesDiv.appendChild(anchor);
  });
}
