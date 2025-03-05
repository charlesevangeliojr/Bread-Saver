document.addEventListener("DOMContentLoaded", function () {
    // Smooth scrolling for the search button
    const findPart = document.querySelector(".findPart");
    if (findPart) {
        findPart.addEventListener("click", function () {
            const searchInput = document.querySelector(".search input[type='search']");
            if (searchInput) {
                searchInput.focus();
            }
        });
    }

    // Profile Photo Preview
    const profilePhotoInput = document.getElementById("modal-profile-photo");
    const profilePhotoDisplay = document.getElementById("profile-photo-display");

    if (profilePhotoInput && profilePhotoDisplay) {
        profilePhotoInput.addEventListener("change", function () {
            const file = profilePhotoInput.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function (e) {
                    profilePhotoDisplay.src = e.target.result;
                };
                reader.readAsDataURL(file);
            }
        });
    }
    

    // Form Validation
    const profileForm = document.getElementById("profileDetailsForm");
    if (profileForm) {
        profileForm.addEventListener("submit", function (event) {
            const requiredFields = profileForm.querySelectorAll("[required]");
            let valid = true;

            requiredFields.forEach(field => {
                if (!field.value.trim()) {
                    field.classList.add("is-invalid");
                    valid = false;
                } else {
                    field.classList.remove("is-invalid");
                }
            });

            if (!valid) {
                event.preventDefault();
                const invalidFields = Array.from(requiredFields).filter(field => !field.value.trim());
                invalidFields.forEach(field => {
                    const error = document.createElement("div");
                    error.className = "error-message";
                    error.textContent = "This field is required.";
                    field.parentElement.appendChild(error);
                });
                alert("Please fill out all required fields.");
            }
        });
    }
});
