const fileInput = document.getElementById("imagen");
const fileNameDisplay = document.getElementById("file-name");

fileInput.addEventListener("change", function() {
    if (fileInput.files.length > 0) {
        fileNameDisplay.textContent = fileInput.files[0].name;
        fileNameDisplay.style.visibility = 'visible'; 
    } else {
        fileNameDisplay.style.visibility = 'hidden';
    }
});
