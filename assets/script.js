function togglePasswordVisibility() {
    var mdpInput = document.getElementById('mdpInput');
    var bouton = document.querySelector('.bouton-afficher');
    
    if (mdpInput.type === "password") {
        mdpInput.type = "text";
        bouton.textContent = "Masquer";
    } else {
        mdpInput.type = "password";
        bouton.textContent = "Afficher";
    }
}
