document.addEventListener('DOMContentLoaded', function() {
    const menuToggle = document.getElementById('mobile-menu');
    const navLinks = document.getElementById('nav-links');
    
    menuToggle.addEventListener('click', function() {
        navLinks.classList.toggle('active');
        
        // Animate hamburger icon
        const bars = document.querySelectorAll('.bar');
        if (navLinks.classList.contains('active')) {
            bars[0].style.transform = 'rotate(45deg) translate(5px, 6px)';
            bars[1].style.opacity = '0';
            bars[2].style.transform = 'rotate(-45deg) translate(5px, -6px)';
        } else {
            bars.forEach(bar => {
                bar.style.transform = '';
                bar.style.opacity = '';
            });
        }
    });
    
    // Close menu when clicking a link
    document.querySelectorAll('.nav-links a').forEach(link => {
        link.addEventListener('click', function() {
            if (window.innerWidth <= 768) {
                navLinks.classList.remove('active');
                document.querySelectorAll('.bar').forEach(bar => {
                    bar.style.transform = '';
                    bar.style.opacity = '';
                });
            }
        });
    });
    
    // Change navbar style on scroll
    window.addEventListener('scroll', function() {
        const navbar = document.querySelector('.navbar');
        if (window.scrollY > 50) {
            navbar.style.padding = '10px 0';
            navbar.style.background = 'rgba(27, 27, 47, 0.95)';
            navbar.style.backdropFilter = 'blur(10px)';
        } else {
            navbar.style.padding = '20px 0';
            navbar.style.background = 'linear-gradient(135deg, #162447 0%, #1b1b2f 100%)';
            navbar.style.backdropFilter = 'none';
        }
    });
});