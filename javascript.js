// Function to display a notification with fade-in and fade-out effects
function showNotification() {
    // Get the notification element by its ID
    var notification = document.getElementById('notification');
    
    // Set initial styles to make the notification invisible
    notification.style.opacity = '0';
    notification.style.display = 'block';

    // Use setTimeout to gradually fade in the notification after a short delay
    setTimeout(function() {
        notification.style.opacity = '1';
    }, 20);
    
    // Use setTimeout to fade out the notification after a specific duration
    setTimeout(function() {
        notification.style.opacity = '0';
    }, 3000);

    // Use setTimeout to hide the notification element after the fade-out animation
    setTimeout(function() {
        notification.classList.add('hidden');
    }, 3500);
}

// Function to fade in a header element
function fadeInHeader() {
    // Select the header element with a specific class
    var header = document.querySelector('.header-fade');
    
    // Set the opacity to fully visible to create a fade-in effect
    header.style.opacity = '1';
}

// Event listener for when the DOM is fully loaded, triggering the notification display
document.addEventListener('DOMContentLoaded', function() {
    showNotification();
});

// Window onload event to trigger the header fade-in effect
window.onload = function() {
    fadeInHeader();
};