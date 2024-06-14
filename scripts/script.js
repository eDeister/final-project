// document.addEventListener("DOMContentLoaded", function() {
//     const searchForm = document.getElementById('search-form');
//     const searchQuery = document.getElementById('search-query');
//     const searchButton = document.getElementById('search-button');
//     let currentFilter = 'all';
//
//     document.querySelectorAll('.dropdown-item').forEach(item => {
//         item.addEventListener('click', function() {
//             currentFilter = this.getAttribute('data-filter');
//             document.querySelector('.dropdown-toggle').innerText = this.innerText;
//         });
//     });
//
//     searchForm.addEventListener('submit', function(event) {
//         event.preventDefault();
//         const query = searchQuery.value.trim();
//         if (query) {
//             const url = `/search?query=${encodeURIComponent(query)}&filter=${encodeURIComponent(currentFilter)}`;
//             window.location.href = url;
//         }
//     });
//
//     // Cart functionality
//     const cartItems = document.getElementById('cart-items');
//     const emptyCartButton = document.getElementById('empty-cart');
//
//     // Fetch cart items from the server or local storage
//     function loadCartItems() {
//         // Example cart items
//         const items = [
//             { id: 1, name: 'Guitar', price: 299.99, quantity: 1, image: 'images/guitar.jpg' },
//             { id: 2, name: 'Keyboard', price: 199.99, quantity: 1, image: 'images/keyboard.jpg' }
//         ];
//
//         cartItems.innerHTML = '';
//         items.forEach(item => {
//             const itemDiv = document.createElement('div');
//             itemDiv.className = 'cart-item';
//             itemDiv.innerHTML = `
//                 <img src="${item.image}" alt="${item.name}">
//                 <span>${item.name} - $${item.price.toFixed(2)} x ${item.quantity}</span>
//                 <button onclick="removeFromCart(${item.id})">Remove</button>
//             `;
//             cartItems.appendChild(itemDiv);
//         });
//     }
//
//     // Example function to remove an item from the cart
//     window.removeFromCart = function(id) {
//         // TODO: Implement removal from cart logic
//         console.log('Remove item with id:', id);
//         loadCartItems();
//     }
//
//     // Empty the cart
//     emptyCartButton.addEventListener('click', function() {
//         // TODO: Implement empty cart logic
//         console.log('Empty cart');
//         loadCartItems();
//     });
//
//     // Load cart items initially
//     loadCartItems();
//
//     // Change color and item on link click
//     document.querySelectorAll('.nav-link').forEach(link => {
//         link.addEventListener('click', function() {
//             document.querySelectorAll('.nav-link').forEach(lnk => lnk.classList.remove('active-link'));
//             this.classList.add('active-link');
//         });
//     });
//
//     // Add class to active link
//     const activePage = window.location.pathname.split('/').pop();
//     document.querySelectorAll('.nav-link').forEach(link => {
//         if (link.getAttribute('href') === activePage) {
//             link.classList.add('active-link');
//         }
//     });
//
// });
//Load new listings whenever the apply filters button is clicked
function applyFilters() {
    let filters = {};
    //TODO: Use jQuery to update the listings div with filters{}
    document.getElementsByName('filter-type').forEach(type => {
        filters[type.value] = [];
        document.getElementsByName('filter-'+type.value+'-value').forEach(checkbox => {
            if(checkbox.checked) {
                filters[type.value].push(checkbox);
            }
        })
    });
    console.log(filters);

}

//Select each child filter when the parent filter is selected
function selectAllChildFilters(filterType) {
    document.getElementsByName('filter-'+filterType+'-value').forEach(child => {

        child.checked = document.getElementById(filterType+'-check').checked;
    });
}

function removeListing(listingCode) {
    $('#listings-div').empty();
    $.post('listing/remove-'+listingCode);
    $('#listings-div').load('get-listings');
}

function addListing() {
    // Get listing data
    let code = $('#input-code').val();
    let name = $('#input-name').val();
    let brand = $('#input-brand').val();
    let price = $('#input-price').val();
    let sale = $('#input-sale').val();
    $('#listings-div').empty();
    $.post('listing/add', {
        code: code,
        name: name,
        brand: brand,
        price: price,
        sale: sale
    });
    $('#listings-div').load('get-listings');
}

function addToCart(listingCode) {
    $('#cart-listings').empty();
    $('#cart-listings').load('cart/add', {code: listingCode});
}

function emptyCart() {
    $('#cart-listings').empty();
    $.post('cart/empty');
}

function removeFromCart(listingCode) {
    $('#cart-'+listingCode).remove();
    $.post('cart/remove', {code: listingCode});
}

