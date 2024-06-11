<?php

/*******w******** 
    
    Name: Alyssa Trinidad  
    Date: 2024-05-26
    Description:

****************/

function orderSummary(){}
        $items = [
            ['index' => 1, 'name' => 'MacBook', 'price' => 1899.99, 'quantity' => 0],
            ['index' => 2, 'name' => 'Razer Gaming Mouse', 'price' => 79.99, 'quantity' => 0],
            ['index' => 3, 'name' => 'Portable Hard Drive', 'price' => 179.99, 'quantity' => 0],
            ['index' => 4, 'name' => 'Google Nexus 7', 'price' => 249.99, 'quantity' => 0],
            ['index' => 5, 'name' => 'Footpedal', 'price' => 119.99, 'quantity' => 0]
        ];

// Validate all the inputs.

function filterinput(){
    $errors = [];

    $email = filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL);
        if(!$email){
            $errors [] = "Invalid email.";
        }

    $postal = filter_input(INPUT_POST, 'postal', FILTER_VALIDATE_REGEXP, array('options' => array('regexp' => '/^[ABCEGHJ-NPRSTVXY]\d[ABCEGHJ-NPRSTV-Z][ -]?\d[ABCEGHJ-NPRSTV-Z]\d$/i')));
        if(!$postal){
            $errors [] = "Invalid postal code.";
        }

    $cardnumber = filter_input(INPUT_POST, 'cardnumber', FILTER_VALIDATE_INT) && ($POST['cardnumber'] = 10);
        if(!$cardnumber){
            $errors [] = "Invalid card number.";
        }
    
    $month = filter_input(INPUT_POST, 'month', FILTER_VALIDATE_INT, array('options' => array('min_range' => 1, 'max_range' => 12)));
        if(!$month){
            $errors [] = "Invalid card month.";
        }
    
    $year = filter_input(INPUT_POST, 'year', FILTER_VALIDATE_INT, array('options' => array('min_range' => 2024, 'max_range' => 2029)));
        if(!$year){
            $errors [] = "Invalid year number";
        }

     if(isset($_POST['cardtype'])){
    $creditCardType = $_POST['cardtype'];
    $validCardType = filter_var($creditCardType, FILTER_VALIDATE_BOOLEAN);
        if ($validCardType){
            $errors [] = "Please choose a card type.";
        }
    }

        $pattern = '/\S+/';

    $fullName = $_POST['fullname'];
    $validFullName = filter_var($fullName, FILTER_VALIDATE_REGEXP, array('options' => array('regexp' =>$pattern)));
        if (!$validFullName){
            $errors [] = "Please enter your full name.";
        }
    
    $cardName = $_POST['cardname'];
    $validCardName = filter_var($cardName, FILTER_VALIDATE_REGEXP, array('options' => array('regexp' =>$pattern)));
        if (!$validCardName){
            $errors [] = "Please enter a valid cardholder name.";
        }

    $address = $_POST['address'];
    $validAddress = filter_var($address, FILTER_VALIDATE_REGEXP, array('options' => array('regexp' =>$pattern)));
        if (!$validAddress){
            $errors [] = "Please enter a valid address.";
        }

    $city = $_POST['city'];
    $validCity = filter_var($city, FILTER_VALIDATE_REGEXP, array('options' => array('regexp' =>$pattern)));
        if (!$validCity){
            $errors [] = "Please enter a valid city.";
        }

    $provinces = ["AB", "BC", "MB", "NB", "NL", "NS", "NT", "NU", "ON", "PE", "QC", "SK", "YT"];
    $province = $_POST['province'];
    if (!in_array($province, $provinces)) {
        $errors [] = true;
    }

    return $errors;
}


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
    <!-- Remember that alternative syntax is good and html inside php is bad -->
    <div class="invoice">
        <h2>Thanks for your order <?= $_POST['fullname'] ?></h2>
        <h3> Here's a summary of your order:</h3>
    <?php
      $errors = filterinput(); // Call the function to get errors
      if (empty($errors)) { // Check if there are no errors
    ?>
      <table>
      <tbody>
    <tr>
      <td colspan="4"><h3>Address Information</h3>
      </td>
    </tr>
    <tr>
      <td class="alignright"><span class="bold">Address:</span>
      </td>
      <td> <?= $_POST['address'] ?> </td>
      <td class="alignright"><span class="bold">City:</span>
      </td>
      <td> <?= $_POST['city'] ?> </td>
    </tr>
    <tr>
      <td class="alignright"><span class="bold">Province:</span>
      </td>
      <td> <?= $_POST['province'] ?></td>
      <td class="alignright"><span class="bold">Postal Code:</span>
      </td>
      <td> <?= $_POST['postal'] ?> </td>
    </tr>
    <tr>
      <td colspan="2" class="alignright"><span class="bold">Email:</span>
      </td>
      <td colspan="2"> <?= $_POST['email'] ?> </td>
    </tr>
    </tbody>
  </table>

  <table>
    <tbody>
    <?php 
    for($i = 1; $i <= 5; $i++){
        $quantity = $_POST["qty$i"];
        if($quantity > 0) {
            $description = $items[$i - 1]["name"];
            $cost = $items[$i - 1]["price"] * $quantity;
            

            $output = "<tr>
                        <td>$quantity</td>
                        <td>$description</td>
                        <td>$cost</td>
                    </tr>";
            echo $output;
        }
    }
?>
</tbody>
</table>
</div>
    <?php } else {  ?>
        <h4>The form could not be processed. Please try again.</h4>
      errors:
      <ul>
        <?php foreach ($errors as $error) { ?>
          <li><?= $error ?></li>
        <?php } ?>
      </ul>
    <?php } ?> </div>
</body>
</html>