document.addEventListener('DOMContentLoaded', function() {
        const burger = document.querySelector('.burger');
        const nav = document.querySelector('nav');
        const navLinks = nav.querySelectorAll('a'); // Select all navigation links

        // Function to toggle the nav menu
        function toggleNav() {
            if (window.innerWidth < 768) {
                nav.classList.toggle('active');
                burger.classList.toggle('active');
            }
        }

        // Add click event listener to burger button
        burger.addEventListener('click', function(event) {
            event.stopPropagation(); // Prevents the click event from propagating to other elements
            toggleNav();
        });

        // Close the nav menu if clicking outside of it
        document.addEventListener('click', function(event) {
            if (!burger.contains(event.target) && !nav.contains(event.target)) {
                nav.classList.remove('active');
                burger.classList.remove('active');
            }
        });

        // Close the nav menu when a nav link is clicked
        navLinks.forEach(link => {
            link.addEventListener('click', function() {
                nav.classList.remove('active');
                burger.classList.remove('active');
            });
        });

        // Close the nav menu if the window is resized to larger than 768px
        window.addEventListener('resize', function() {
            if (window.innerWidth >= 768) {
                nav.classList.remove('active');
                burger.classList.remove('active');
            }
        });
    });
