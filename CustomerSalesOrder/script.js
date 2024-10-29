document.addEventListener("DOMContentLoaded", () => {
    fetchProducts();
    document.getElementById("salesOrderForm").addEventListener("submit", submitOrder);
});

let productsData = [];

function fetchProducts() {
    fetch('api.php?action=get_products')
        .then(response => response.json())
        .then(data => {
            productsData = data;
            addProductRow();
        });
}

function addProductRow() {
    const productsTable = document.getElementById("productsTable");
    const newRow = document.createElement("tr");
    newRow.innerHTML = `
        <td>
            <select name="product[]" onchange="updateProductPrice(this)">
                <option value="">Select Product</option>
                ${productsData.map(product => `<option value="${product.id}" data-price="${product.price}">${product.name}</option>`).join('')}
            </select>
        </td>
        <td><input type="number" name="qty[]" min="1" required onchange="calculateSubtotal(this)"></td>
        <td><span class="price">0</span></td>
        <td><input type="number" name="discount[]" min="0" max="100" value="0" onchange="calculateSubtotal(this)"></td>
        <td><span class="subtotal">0</span></td>
        <td><button type="button" onclick="removeRow(this)">Remove</button></td>
    `;
    productsTable.appendChild(newRow);
}

function updateProductPrice(select) {
    const price = select.options[select.selectedIndex].getAttribute("data-price");
    select.closest("tr").querySelector(".price").textContent = price || 0;
    calculateSubtotal(select);
}

function calculateSubtotal(element) {
    const row = element.closest("tr");
    const qty = parseFloat(row.querySelector("input[name='qty[]']").value) || 0;
    const price = parseFloat(row.querySelector(".price").textContent) || 0;
    const discount = parseFloat(row.querySelector("input[name='discount[]']").value) || 0;
    const subtotal = qty * price * (1 - discount / 100);
    row.querySelector(".subtotal").textContent = subtotal.toFixed(2);
    calculateTotal();
}

function calculateTotal() {
    const subtotals = document.querySelectorAll(".subtotal");
    let total = Array.from(subtotals).reduce((sum, el) => sum + parseFloat(el.textContent), 0);
    document.getElementById("totalAmount").textContent = total.toFixed(2);
}

function removeRow(button) {
    button.closest("tr").remove();
    calculateTotal();
}

function submitOrder(event) {
    event.preventDefault();

    const orderData = {
        customer_ref: document.querySelector("input[name='customer_ref']").value,
        order_date: document.querySelector("input[name='order_date']").value,
        total: parseFloat(document.getElementById("totalAmount").textContent),
        items: Array.from(document.querySelectorAll("#productsTable tr")).map(row => ({
            product_id: row.querySelector("select[name='product[]']").value,
            qty: row.querySelector("input[name='qty[]']").value,
            discount: row.querySelector("input[name='discount[]']").value,
            subtotal: row.querySelector(".subtotal").textContent
        }))
    };

    fetch('api.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
        body: `orderData=${encodeURIComponent(JSON.stringify(orderData))}`
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert("Product order successfully created!");
            document.getElementById("salesOrderForm").reset();
            document.getElementById("productsTable").innerHTML = "";
            document.getElementById("totalAmount").textContent = "0";
            addProductRow();
        } else {
            alert("Failed to save order.");
        }
    });
}
