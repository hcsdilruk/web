// Function to load external HTML into an element
function loadComponent(id, file) {
    fetch(file)
        .then(response => {
            if (!response.ok) throw new Error(`HTTP error! Status: ${response.status}`);
            return response.text();
        })
        .then(data => {
            document.getElementById(id).innerHTML = data;
        })
        .catch(error => {
            console.error('Error loading component:', error);
        });
}

// Load navbar and footer
window.addEventListener('DOMContentLoaded', () => {
    loadComponent('navbar', 'components/navbar/navbar.html');
    loadComponent('footer', 'components/footer/footer.html');
});
