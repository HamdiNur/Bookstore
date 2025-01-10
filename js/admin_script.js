
let navbar = document.querySelector('.header .navbar');
let header = document.querySelector('.header');
let mainContent = document.querySelector('section'); // Adjust this selector if needed

// Session expiration time (5 minutes = 300,000 milliseconds)
const sessionTimeout = 300000; // 5 minutes
const warningTime = 240000; // 4 minutes (show warning 1 minute before logout)
let timeout;

// Function to show a warning
function showWarning() {
    console.log("Showing warning..."); // Debugging
    alert("Your session will expire in 1 minute. Please save your work.");
    // Set a timeout for logout after 1 minute (60,000 milliseconds)
    timeout = setTimeout(logout, 60000); // 1 minute after warning
}

// Function to log out the user and redirect to the login page
function logout() {
    console.log("Logging out..."); // Debugging
    // Redirect to the login page with a query parameter indicating session expiry
    window.location.replace("login.php?session_expired=true");
}

// Reset the timer on user activity
function resetTimer() {
    console.log("Resetting timer..."); // Debugging
    clearTimeout(timeout);
    // Set the timeout for the warning (4 minutes before session expires)
    timeout = setTimeout(showWarning, warningTime); // Show warning after 4 minutes
}

// Start the timer when the page loads
resetTimer();

// Reset the timer on user activity (e.g., mouse movement, clicks)
document.addEventListener("mousemove", resetTimer);
document.addEventListener("keypress", resetTimer);

// Function to toggle sidebar state
function toggleSidebar() {
    header.classList.toggle('collapsed');
    if (mainContent) {
        mainContent.classList.toggle('collapsed');
    }
    // Save the state to localStorage
    const isCollapsed = header.classList.contains('collapsed');
    localStorage.setItem('sidebarCollapsed', isCollapsed);

    // Log the current state to the console for debugging
    console.log(`Sidebar is now ${isCollapsed ? 'collapsed' : 'expanded'}`);
}

// Function to initialize sidebar state
function initializeSidebar() {
    const isCollapsed = localStorage.getItem('sidebarCollapsed') === 'true';
    if (isCollapsed) {
        header.classList.add('collapsed');
        if (mainContent) {
            mainContent.classList.add('collapsed');
        }
    } else {
        header.classList.remove('collapsed');
        if (mainContent) {
            mainContent.classList.remove('collapsed');
        }
    }

    // Log the initial state to the console for debugging
    console.log(`Sidebar initialized as ${isCollapsed ? 'collapsed' : 'expanded'}`);
}

// Initialize sidebar state on page load
initializeSidebar();

// Dashboard icon controls collapse/expand
document.querySelector('#dashboard-btn').onclick = (event) => {
    event.preventDefault(); // Prevent the default behavior of the <a> tag
    toggleSidebar();
};

// Home icon redirects to the home page (no additional functionality needed)
document.querySelector('#home-btn').onclick = (event) => {
    // No need to prevent default behavior since it should redirect to admin_page.php
};

// Remove active classes on scroll
window.onscroll = () => {
    if (navbar) {
        navbar.classList.remove('active');
    }
};