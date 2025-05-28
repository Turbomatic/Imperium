document.addEventListener('DOMContentLoaded', () => {
    //loadCart();

    // Delegate remove button click
    document.querySelector('.cart-container')?.addEventListener('click', async (e) => {
        if (e.target.classList.contains('remove-item')) {
            const itemElement = e.target.closest('.cart-item');
            const productId = itemElement.dataset.id;
            if (productId) await removeFromCart(productId);
        }

        if (e.target.classList.contains('checkout-button')) {
            await checkoutCart();
        }
    });
});

async function loadCart() {
    try {
        const response = await fetch('../server/control/cart.control.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'Request-Type': 'get_cart'
            }
        });

        const data = await response.json();

        if (!Array.isArray(data)) {
            throw new Error("Invalid cart data received");
        }

        renderCart(data);
    } catch (error) {
        console.error('Failed to load cart:', error);
    }
    
}

function renderCart(cartItems) {
    const container = document.querySelector('.cart-container');
    if (!container) return;

    container.innerHTML = ''; // Clear old items
    let total = 0;

    cartItems.forEach(item => {
        const { id, name, description, price, quantity, image_url } = item;
        const subtotal = price * quantity;
        total += subtotal;

        const itemHTML = `
            <div class="cart-item" data-id="${id}">
                <img src="${image_url}" alt="${name}" class="cart-item-image">
                <div class="cart-item-details">
                    <h3 class="item-name">${name}</h3>
                    <p class="item-desc">${description}</p>
                    <p class="item-price">€${price.toFixed(2)}</p>
                    <div class="item-actions">
                        <label>Qty:</label>
                        <span class="qty-display">${quantity}</span>
                        <button class="remove-item">Remove</button>
                    </div>
                </div>
            </div>
        `;

        container.insertAdjacentHTML('beforeend', itemHTML);
    });

    const summaryHTML = `
        <div class="cart-summary">
            <h3>Cart Total: <span class="total-price">€${total.toFixed(2)}</span></h3>
            <button class="checkout-button">Proceed to Checkout</button>
        </div>
    `;

    container.insertAdjacentHTML('beforeend', summaryHTML);
}

async function removeFromCart(productId) {
    try {
        const response = await fetch('../server/control/cart.control.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'Request-Type': 'remove_item'
            },
            body: JSON.stringify({ product_id: productId })
        });

        const result = await response.json();

        if (result.success) {
            loadCart();
        } else {
            alert('Failed to remove item from cart.');
        }
    } catch (error) {
        console.error('Error removing item:', error);
    }
}

async function checkoutCart() {
    try {
        const response = await fetch('../server/control/cart.control.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'Request-Type': 'checkout'
            }
        });

        const result = await response.json();

        if (result.success) {
            alert('Checkout successful!');
            loadCart();
        } else {
            alert('Checkout failed. Try again later.');
        }
    } catch (error) {
        console.error('Error during checkout:', error);
    }
}
