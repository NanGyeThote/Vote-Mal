document.addEventListener("DOMContentLoaded", () => {
    const hamburger = document.querySelector(".hamburger");
    const navMenu = document.querySelector(".nav-menu");

    
    // Check if elements are found
    if (hamburger && navMenu) {
        // Toggle hamburger and nav menu on hamburger click
        hamburger.addEventListener("click", () => {
            hamburger.classList.toggle("active");
            navMenu.classList.toggle("active");
        });

        // Close nav menu when a nav link is clicked
        document.querySelectorAll(".nav-link").forEach(link => {
            link.addEventListener("click", () => {
                hamburger.classList.remove("active");
                navMenu.classList.remove("active");
            });
        });
    } else {
        console.error('Hamburger or nav menu element not found');
    }
});

