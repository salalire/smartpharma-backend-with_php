<?php
session_start();

// Initialize cart if not set
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

// Function to add item to cart
function addToCart($productId, $productName, $price, $quantity = 1) {
    // If product already exists in cart, update quantity
    if (isset($_SESSION['cart'][$productId])) {
        $_SESSION['cart'][$productId]['quantity'] += $quantity;
    } else {
        $_SESSION['cart'][$productId] = [
            'name' => $productName,
            'price' => $price,
            'quantity' => $quantity
        ];
    }
}

// Function to remove item from cart
function removeFromCart($productId) {
    if (isset($_SESSION['cart'][$productId])) {
        unset($_SESSION['cart'][$productId]);
    }
}

// Function to display cart items
function displayCart() {
    if (empty($_SESSION['cart'])) {
        echo "<p>Your cart is empty.</p>";
        return;
    }

    echo "<table border='1' cellpadding='10'>";
    echo "<tr><th>Product</th><th>Price</th><th>Quantity</th><th>Total</th><th>Action</th></tr>";

    $grandTotal = 0;
    foreach ($_SESSION['cart'] as $id => $item) {
        $total = $item['price'] * $item['quantity'];
        $grandTotal += $total;

        echo "<tr>
                <td>{$item['name']}</td>
                <td>\${$item['price']}</td>
                <td>{$item['quantity']}</td>
                <td>\${$total}</td>
                <td><a href='cart.php?action=remove&id={$id}'>Remove</a></td>
              </tr>";
    }

    echo "<tr><td colspan='3'><strong>Grand Total</strong></td><td colspan='2'><strong>\${$grandTotal}</strong></td></tr>";
    echo "</table>";
}

// Handle actions
if (isset($_GET['action'])) {
    switch ($_GET['action']) {
        case 'add':
            // Example: cart.php?action=add&id=1&name=Paracetamol&price=5&qty=2
            addToCart($_GET['id'], $_GET['name'], $_GET['price'], $_GET['qty']);
            break;
        case 'remove':
            removeFromCart($_GET['id']);
            break;
    }
}

// Display cart
displayCart();
?>

