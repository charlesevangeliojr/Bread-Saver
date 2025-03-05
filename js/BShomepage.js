let currentQuantity = 1;

function increaseQuantity() {
    const quantityInput = document.getElementById('quantityInput');
    currentQuantity++;
    quantityInput.value = currentQuantity;
}

function decreaseQuantity() {
    const quantityInput = document.getElementById('quantityInput');
    if (currentQuantity > 1) {
        currentQuantity--;
        quantityInput.value = currentQuantity;
    }
}

function addToCart() {
    const quantity = document.getElementById('quantityInput').value;
    // Implement your logic to add to cart here
    console.log(`Added ${quantity} items to cart.`);
    closeModal(); // Close modal after adding to cart
}

function openModal(name, bakery, price, discountedPrice, rating, totalRatings, imageSrc, description) {
    const modal = document.getElementById('breadModal');
    const modalImage = document.getElementById('breadImage');
    const modalName = document.getElementById('breadName');
    const modalBakery = document.getElementById('bakeShop');
    const modalPrice = document.getElementById('price');
    const modalDiscountedPrice = document.getElementById('discountedPrice');
    const modalRating = document.getElementById('rating');
    const modalTotalRatings = document.getElementById('totalRatings');
    const modalDescription = document.getElementById('breadDescription');

    modalImage.src = imageSrc;
    modalName.textContent = name;
    modalBakery.textContent = bakery;
    modalPrice.textContent = price;
    modalDiscountedPrice.textContent = discountedPrice;
    modalRating.textContent = rating;
    modalTotalRatings.textContent = totalRatings;
    modalDescription.textContent = description;

    modal.style.display = 'block';
}

// Close modal function
function closeModal() {
    const modal = document.getElementById('breadModal');
    modal.style.display = 'none';
}

// Close modal when clicking outside of it
window.addEventListener('click', (event) => {
    const modal = document.getElementById('breadModal');
    if (event.target === modal) {
        modal.style.display = 'none';
    }
});

document.addEventListener("DOMContentLoaded", () => {
    const burgerMenu = document.getElementById('burger-menu');
    const sidenav = document.getElementById('sidenav');
    const logoutModal = document.getElementById('logoutModal');
    const logoutButton = document.getElementById('log-out');
    const modalCloseButtons = document.querySelectorAll('.modal .close');

    burgerMenu.addEventListener('click', () => {
        sidenav.style.display = sidenav.style.display === 'block' ? 'none' : 'block';
    });

    logoutButton.addEventListener('click', () => {
        logoutModal.style.display = 'block';
    });

    function closeLogoutModal() {
        logoutModal.style.display = 'none';
    }

    function logout() {
        window.location.href = 'logout.php';
    }

    // Expose the closeLogoutModal function globally to use in inline onclick attributes
    window.closeLogoutModal = closeLogoutModal;
    window.logout = logout;

    modalCloseButtons.forEach(button => {
        button.addEventListener('click', () => {
            button.closest('.modal').style.display = 'none';
        });
    });

    // Close modals when clicking outside of them
    window.addEventListener('click', (event) => {
        if (event.target === logoutModal) {
            logoutModal.style.display = 'none';
        }
    });
});
