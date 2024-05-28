const cartButton = document.querySelector('.cart-button');
const cartBadge = document.querySelector('.cart-badge');
const modal = document.querySelector('.modal');
const modalClose = document.querySelector('.close');
const buyButton = document.querySelector('.buy-btn');
const cartItemsList = document.querySelector('.cart-items');
const cartTotal = document.querySelector('.cart-total');
const itemsGrid = document.querySelector('.items-grid');
const addToCartButtons = document.querySelectorAll('.add-to-cart-btn');
const latestProduct = document.querySelector('.latest-product');

let fetchedProducts = []
let cart = [];
let order = [];

function fetchProducts() {
    let products = [];
    fetch('home.php?getProducts=true')
        .then(response => response.json())
        .then(items => {
            products.push(items);
            for(const product of products[0]){
                fetchedProducts.push(product)
            }
            fillItemsGrid(items);
        })
        .catch(error => console.error('Error fetching items:', error));
}

function fillItemsGrid(items) {
    itemsGrid.innerHTML = '';
    for (let i = 0; i < items.length; i++) {
        const item = items[i];
        let itemElement = document.createElement('div');
        itemElement.classList.add('item');
        itemElement.innerHTML = `
            <img src="${item.image}" alt="${item.name}">
            <h2>${item.name}</h2>
            <p>$${item.price}</p>
            <button class="add-to-cart-btn" data-id="${item.id}">Add to cart</button>
        `;
        itemsGrid.appendChild(itemElement);

        const addToCartButton = itemElement.querySelector('.add-to-cart-btn');
        const itemId = parseInt(addToCartButton.getAttribute('data-id'));
        addToCartButton.addEventListener('click', () => addToCart(itemId));

        if(i == items.length - 1){
            let latestProductElement = itemElement.cloneNode(true);
            latestProduct.appendChild(latestProductElement);

            const latestAddToCartButton = latestProductElement.querySelector('.add-to-cart-btn');
            latestAddToCartButton.addEventListener('click', () => addToCart(itemId));
        }
    }
}

function addToCart(itemId){
    const item = fetchedProducts.find(item => item.id == itemId);
    const cartItem = cart.find(cartItem => cartItem.id == itemId)

    if (item) {
        if(cartItem){
            cartItem.quantity++
        }
        else{
            const addItem = {id: item.id, name: item.name, price: item.price, quantity: 1}
            cart.push(addItem)
        }
        localStorage.setItem('cartItems', JSON.stringify(cart));
        updateCartBadge();
    }
}

function updateCartBadge(){
    cartBadge.textContent = cart.length
}

function toggleModal() {
    modal.classList.toggle('show-modal');
    showCart()
}

function showCart(){
    cartItemsList.innerHTML = '';
    cart.forEach(item => {
        const cartItemElement = document.createElement('li');
        cartItemElement.innerHTML = `
            <span>${item.name} - Quantity: ${item.quantity} - Price: $${(item.price * item.quantity).toFixed(2)}</span>
            <div class="buttons">
                <button class="increase-btn" data-id="${item.id}">+</button>
                <button class="decrease-btn" data-id="${item.id}">-</button>
            </div>`;
        cartItemsList.appendChild(cartItemElement);

        const increaseButton = cartItemElement.querySelector('.increase-btn');
        const itemId = parseInt(increaseButton.getAttribute('data-id'))
        increaseButton.addEventListener('click', () => increaseQuantity(itemId));

        const decreaseButton = cartItemElement.querySelector('.decrease-btn');
        const itemId2 = parseInt(decreaseButton.getAttribute('data-id'))
        decreaseButton.addEventListener('click', () => decreaseQuantity(itemId2));
        

        let orderItem = [item.quantity, item.name, item.price];
        order.push(orderItem);
    });

    const totalPrice = cart.reduce((total, item) => total + (item.price * item.quantity), 0);
    cartTotal.textContent = `$${totalPrice.toFixed(2)}`;
    console.log(order);
}


function increaseQuantity(itemId){
    const itemIndex = cart.findIndex(item => item.id == itemId);
    cart[itemIndex].quantity++;
    showCart();
}

function decreaseQuantity(itemId){
    const itemIndex = cart.findIndex(item => item.id == itemId);
    if(cart[itemIndex].quantity > 1)
        cart[itemIndex].quantity--;
    else{
        cart = cart.filter(item => item.id != itemId);
        updateCartBadge()
    }
    showCart();
}

document.addEventListener('DOMContentLoaded', function() {
    fetchProducts();
});

cartButton.addEventListener('click', toggleModal);
modalClose.addEventListener('click', toggleModal);

buyButton.addEventListener('click', () => {
    if(cart.length > 0)
        window.location.href = 'cart.php';
    else{
        alert('Your cart is empty.');
    }
});