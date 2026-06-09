document.addEventListener('DOMContentLoaded', function() {
    // Update copyright year automatically
    document.getElementById('current-year').textContent = new Date().getFullYear();
    
    // Add animation to social links
    const socialLinks = document.querySelectorAll('.footer-social-links a');
    socialLinks.forEach(link => {
        link.addEventListener('mouseover', function() {
            this.style.transform = 'translateY(-5px)';
        });
        
        link.addEventListener('mouseout', function() {
            this.style.transform = 'translateY(0)';
        });
    });
});