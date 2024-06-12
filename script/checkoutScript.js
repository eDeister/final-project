

const row = 3;
const listingsContainer = document.getElementById('listings');
const summaryItemsContainer = document.getElementById('summary-items');
const totalCostElement = document.getElementById('total-cost');

function renderListings() {
    let ctr = 0;
    let rowDiv;
    listings.forEach((listing, index) => {
        if (ctr % row === 0) {
            rowDiv = document.createElement('div');
            rowDiv.className = 'row';
            listingsContainer.appendChild(rowDiv);
        }

        const colDiv = document.createElement('div');
        colDiv.className = `col-${12 / row}`;
        colDiv.id = `${listing.code}-col`;

        colDiv.innerHTML = `
            <a href="listing-${listing.code}">
                <div class="card" id="${listing.code}-card">
                    <img src="images/listings/${listing.code}.jpg" class="image rounded card-img-top" alt="Image of ${listing.brand}'s ${listing.name}">
                    <div class="card-body d-flex align-items-center">
                        <p class="card-title">${listing.name} | ${listing.brand}</p>
                        <p class="card-text">$${listing.sale ? (listing.price * listing.sale).toFixed(2) : listing.price}</p>
                    </div>
                </div>
            </a>
        `;

        rowDiv.appendChild(colDiv);
        ctr++;
    });
}

function updateSummary() {
    let totalCost = 0;
    summaryItemsContainer.innerHTML = '';
    listings.forEach(listing => {
        const price = listing.sale ? listing.price * listing.sale : listing.price;
        totalCost += price;
        const itemDiv = document.createElement('div');
        itemDiv.textContent = `${listing.name} - $${price.toFixed(2)}`;
        summaryItemsContainer.appendChild(itemDiv);
    });
    totalCostElement.textContent = `Total Cost: $${totalCost.toFixed(2)}`;
}

document.getElementById('checkout-button').addEventListener('click', () => {
    alert('Proceeding to checkout...');
});

renderListings();
updateSummary();
