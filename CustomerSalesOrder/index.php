<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Customer Sales Order</title>
    <link rel="stylesheet" href="style.css">
    <script src="script.js" defer></script>
</head>
<body>
    <form id="salesOrderForm">
        <div>
            <label>ORDER NUMBER</label>
            <input type="text" name="order_no" required>
        </div>
        <div>
            <label>CUSTOMER REF</label>
            <input type="text" name="customer_ref" required>
        </div>
        <div>
            <label>ORDER DATE</label>
            <input type="date" name="order_date" required>
        </div>

        <table>
            <thead>
                <tr>
                    <th>PRODUCT</th>
                    <th>QTY</th>
                    <th>PRICE</th>
                    <th>DISCOUNT (%)</th>
                    <th>SUBTOTAL</th>
                    <th>REMOVE COLUMN</th>
                </tr>
            </thead>
            <tbody id="productsTable"></tbody>
        </table>
        <button type="button" onclick="addProductRow()">Add Product</button>
        <p>Total: <span id="totalAmount">0</span></p>
        <button type="submit">Submit Sales Order</button>
    </form>
</body>
</html>
