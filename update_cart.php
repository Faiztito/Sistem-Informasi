<?php
session_start();
header('Content-Type: application/json');

if (!isset($_POST['id']) || !isset($_POST['quantity'])) {
    echo json_encode(['success' => false, 'message' => 'Invalid request']);
    exit;
}

$id = $_POST['id'];
$quantity = (int)$_POST['quantity'];

if (isset($_SESSION['cart'][$id])) {
    $_SESSION['cart'][$id]['quantity'] = $quantity;
    echo json_encode(['success' => true]);
} else {
    echo json_encode(['success' => false, 'message' => 'Item not found in cart']);
}
?>
<script>
function updateCart(inputElement, id) {
    const quantity = parseInt(inputElement.value);
    const price = parseInt(inputElement.closest('tr').querySelector('.cart-item-price').textContent.replace(/\D/g, ''));
    const subtotal = price * quantity;

    inputElement.closest('tr').querySelector('.cart-item-subtotal').textContent = 'Rp ' + subtotal.toLocaleString('id-ID');

    updateTotal();

    // Kirim ke server
    fetch('update_cart.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json'
        },
        body: JSON.stringify({ id: id, quantity: quantity })
    });
}
</script>