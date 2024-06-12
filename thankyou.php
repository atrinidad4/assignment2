<?php

/*******w********

Name: Alyssa Trinidad
Date: 2024-05-26
Description:

 ****************/

function getItems() {
    return [
        ['index' => 1, 'name' => 'MacBook', 'price' => 1899.99, 'quantity' => 0],
        ['index' => 2, 'name' => 'Razer Gaming Mouse', 'price' => 79.99, 'quantity' => 0],
        ['index' => 3, 'name' => 'Portable Hard Drive', 'price' => 179.99, 'quantity' => 0],
        ['index' => 4, 'name' => 'Google Nexus 7', 'price' => 249.99, 'quantity' => 0],
        ['index' => 5, 'name' => 'Footpedal', 'price' => 119.99, 'quantity' => 0]
    ];
}

// Validate all the inputs.
function filterInput() {
    $errors = [];

    // Validate email
    $email = filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL);
    if (!$email) {
        $errors[] = "Invalid email.";
    }

    // Validate postal code
    $postal = filter_input(INPUT_POST, 'postal', FILTER_VALIDATE_REGEXP, [
        'options' => ['regexp' => '/^[ABCEGHJ-NPRSTVXY]\d[ABCEGHJ-NPRSTV-Z][ -]?\d[ABCEGHJ-NPRSTV-Z]\d$/i']
    ]);
    if (!$postal) {
        $errors[] = "Invalid postal code.";
    }

    // Validate card number (exactly 10 digits)
    $cardNumber = filter_input(INPUT_POST, 'cardnumber', FILTER_VALIDATE_REGEXP, [
        'options' => ['regexp' => '/^\d{10}$/']
    ]);
    if (!$cardNumber) {
        $errors[] = "Invalid card number.";
    }

    // Validate month
    $month = filter_input(INPUT_POST, 'month', FILTER_VALIDATE_INT, [
        'options' => ['min_range' => 1, 'max_range' => 12]
    ]);
    if (!$month) {
        $errors[] = "Invalid card month.";
    }

    // Validate year
    $year = filter_input(INPUT_POST, 'year', FILTER_VALIDATE_INT, [
        'options' => ['min_range' => 2024, 'max_range' => 2029]
    ]);
    if (!$year) {
        $errors[] = "Invalid year number.";
    }

    // Validate card type
    if (!isset($_POST['cardtype'])) {
        $errors[] = "Please choose a card type.";
    }

    $pattern = '/\S+/';

    // Validate full name
    $fullName = $_POST['fullname'];
    $validFullName = filter_var($fullName, FILTER_VALIDATE_REGEXP, [
        'options' => ['regexp' => $pattern]
    ]);
    if (!$validFullName) {
        $errors[] = "Please enter your full name.";
    }

    // Validate cardholder name
    $cardName = $_POST['cardname'];
    $validCardName = filter_var($cardName, FILTER_VALIDATE_REGEXP, [
        'options' => ['regexp' => $pattern]
    ]);
    if (!$validCardName) {
        $errors[] = "Please enter a valid cardholder name.";
    }

    // Validate address
    $address = $_POST['address'];
    $validAddress = filter_var($address, FILTER_VALIDATE_REGEXP, [
        'options' => ['regexp' => $pattern]
    ]);
    if (!$validAddress) {
        $errors[] = "Please enter a valid address.";
    }

    // Validate city
    $city = $_POST['city'];
    $validCity = filter_var($city, FILTER_VALIDATE_REGEXP, [
        'options' => ['regexp' => $pattern]
    ]);
    if (!$validCity) {
        $errors[] = "Please enter a valid city.";
    }

    // Validate province
    $provinces = ["AB", "BC", "MB", "NB", "NL", "NS", "NT", "NU", "ON", "PE", "QC", "SK", "YT"];
    $province = $_POST['province'];
    if (!in_array($province, $provinces)) {
        $errors[] = "Invalid province.";
    }

    return $errors;
}

$items = getItems();
$errors = filterInput(); // Call the function to get errors
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="p2formstyles.css">
  <title>Thanks for your order!</title>
</head>
<body>
<div class="invoice">
  <h2>Thanks for your order <?= htmlspecialchars($_POST['fullname']) ?></h2>
  <h3>Here's a summary of your order:</h3>
    <?php if (empty($errors)) { ?>
      <table>
        <tbody>
        <tr>
          <td colspan="4"><h3>Address Information</h3></td>
        </tr>
        <tr>
          <td class="alignright"><span class="bold">Address:</span></td>
          <td><?= htmlspecialchars($_POST['address']) ?></td>
          <td class="alignright"><span class="bold">City:</span></td>
          <td><?= htmlspecialchars($_POST['city']) ?></td>
        </tr>
        <tr>
          <td class="alignright"><span class="bold">Province:</span></td>
          <td><?= htmlspecialchars($_POST['province']) ?></td>
          <td class="alignright"><span class="bold">Postal Code:</span></td>
          <td><?= htmlspecialchars($_POST['postal']) ?></td>
        </tr>
        <tr>
          <td colspan="2" class="alignright"><span class="bold">Email:</span></td>
          <td colspan="2"><?= htmlspecialchars($_POST['email']) ?></td>
        </tr>
        </tbody>
      </table>

      <table>
        <tbody>
        <?php
        for ($i = 1; $i <= count($items); $i++) {
            $quantity = (int)$_POST["qty$i"];
            if ($quantity > 0) {
                $description = htmlspecialchars($items[$i - 1]["name"]);
                $cost = $items[$i - 1]["price"] * $quantity;
                echo "<tr>
                                    <td>$quantity</td>
                                    <td>$description</td>
                                    <td>$cost</td>
                                  </tr>";
            }
        }
        ?>
        </tbody>
      </table>
    <?php } else { ?>
      <h4>The form could not be processed. Please try again.</h4>
      <ul>
          <?php foreach ($errors as $error) { ?>
            <li><?= htmlspecialchars($error) ?></li>
          <?php } ?>
      </ul>
    <?php } ?>
</div>
</body>
</html>
