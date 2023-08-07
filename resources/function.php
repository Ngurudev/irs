<?php

$uploads = 'uploads';

function last_id()
{
 global $connection;
 $last_id = mysqli_insert_id($connection);

 return mysqli_insert_id($connection);
}

function set_message($msg)
{
 if (!empty($msg)) {
  $_SESSION['message'] = $msg;
 } else {
  $msg = "";
 }
}

function display_message()
{
 if (isset($_SESSION['message'])) {
  echo $_SESSION['message'];
  unset($_SESSION['message']);
 }
}

// helper functions
function redirect($location)
{
 return header("Location: $location");
}
function query($sql)
{
 global $connection;
 return mysqli_query($connection, $sql);
}

function confirm($result)
{
 global $connection;
 if (!$result) {

  die("QUERY FAILED " . mysqli_error($connection));

 }
}

function escape_string($string)
{
 global $connection;
 return mysqli_real_escape_string($connection, $string);
}

function fetch_array($result)
{
 return mysqli_fetch_array($result);
}
function row_count($result)
{

 return mysqli_num_rows($result);
}
function email_exist($email)
{

 $sql = "SELECT id FROM customers_users WHERE email = '{$email}'";
 $result = query($sql);
 if (row_count($result) == 1) {
  return true;
 } else {
  return false;
 }
}

function clean($string)
{
 return htmlentities($string);
}
function validation_erorr($erorr_message)
{

 $erorr_message = <<<DELIMETER

                        <div class="alert alert-danger alert-dismissible" role="alert">
                          <strong>Warning !</strong>$erorr_message
                          <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                          </button>
                        </div>
    DELIMETER;
 return $erorr_message;

}

function setMessage($message)
{
 if (!empty($message)) {
  $_SESSION['message'] = $message;

 } else {
  $message = '';
 }
}

function display_msg()
{
 if (isset($_SESSION['message'])) {
  echo $_SESSION['message'];
  unset($_SESSION['message']);
 }
}

function phone_exist($phone)
{

 $sql = "SELECT id FROM customers_users WHERE phone = '{$phone}'";
 $result = query($sql);
 if (row_count($result) == 1) {
  return true;
 } else {
  return false;
 }
}

//********************************Checking for Category *****************************//
function category_exist($category_title)
{
 extract($_POST);
 $sql = "SELECT id, category_title FROM categories WHERE category_title = '{$category_title}'";
 $result = query($sql);
 if (row_count($result) == 1) {
  return true;
 } else {
  return false;
 }
}

//*******************FRONT END FUNCTION************************

//get categories

function get_categories()
{
 $query = query(" SELECT *FROM categories   ORDER BY category_title");
 confirm($query);

 while ($row = fetch_array($query)) {
  $categories_link = <<<DELIMETER

        <li><a href='category.php?id={$row['id']}'>{$row['category_title']}</a></li>

DELIMETER;
  echo $categories_link;
 }

}

//Register USER
function validate_users_registration()
{

 $erorrs = [];
 $min = 3;
 $max = 50;
 $email_l = 50;

 if ($_SERVER['REQUEST_METHOD'] == "POST") {

  $fullname = clean($_POST['fullname']);
  $phone = clean($_POST['phone']);
  $email = clean($_POST['email']);
  $dob = clean($_POST['dob']);
  $gender = clean($_POST['gender']);
  $nationality = clean($_POST['nationality']);
  $state = clean($_POST['state']);
  $lga = clean($_POST['lga']);
  $address = clean($_POST['address']);
  $password = clean($_POST['password']);
  $confirm_password = clean($_POST['confirm_password']);
  $image = clean($_FILES['upload']['name']);
  $image_temp_loca = clean($_FILES['upload']['tmp_name']);

  //firstname
  if (strlen($fullname) < $min) {
   $erorrs[] = "Your Name cannot be less than {$min} characters";
  }

  if (strlen($fullname) > $max) {
   $erorrs[] = "Your Name cannot be greater than {$max} characters";
  }

  //last name
  if (strlen($phone) < $min) {
   $erorrs[] = "Your Phone Number cannot be less than {$min} characters";
  }

  if (strlen($phone) > $max) {
   $erorrs[] = "Your Phone Number cannot be greater than {$max} characters";
  }
  //email
  if (strlen($email) > $max) {
   $erorrs[] = "Your Email cannot be greater than {$$email_l} characters";
  }
  if (email_exist($email)) {

   $erorrs[] = "This Email Address Already Registered";

  }
  if (phone_exist($phone)) {

   $erorrs[] = "This Phone Number Already Registered";

  }
  if ($password !== $confirm_password) {
   $erorrs[] = "Your Password field do not match";
  }

  if (!empty($erorrs)) {
   foreach ($erorrs as $erorr) {
    //display message//
    echo validation_erorr($erorr);

   }
  } else {

   if (register_user($fullname, $phone, $email, $dob, $gender, $nationality, $state, $lga, $address, $password, $image)) {
    setMessage("<p class='bg-success text-center'>Please check your Email or Spam folder for activation code</p>");
    redirect('index.php');

   } else {

    setMessage("<p class='bg-danger text-center'>Sorry We could not register the user</p>");
    redirect('index.php');

   }
  }
 }
}
//*************** End of Validation user reg Function**********************//

//********************Registering user function***************************//

function register_user($fullname, $phone, $email, $dob, $gender, $nationality, $state, $lga, $address, $password, $image)
{

 $fullname = escape_string($fullname);
 $phone = escape_string($phone);
 $email = escape_string($email);
 $dob = escape_string($dob);
 $gender = escape_string($gender);
 $nationality = escape_string($nationality);
 $state = escape_string($state);
 $lga = escape_string($lga);
 $address = escape_string($address);
 $password = escape_string($password);
 $image = $_FILES['upload']['name'];
 $image_temp_loca = $_FILES['upload']['tmp_name'];

 if (email_exist($email)) {

  return false;
 } elseif (phone_exist($phone)) {

  return false;
 } else {

  $image = $_FILES['upload']['name'];
  $image_temp_loca = $_FILES['upload']['tmp_name'];

  $password = password_hash($password, PASSWORD_BCRYPT, array('cost' => 12));

  $sql = "INSERT INTO customers_users(fullname, phone, email, dob, gender, nationality, state, lga, address, password, passport) VALUES ('{$fullname}','{$phone}', '{$email}', '{$dob}', '{$gender}', '{$nationality}', '{$state}', '{$lga}', '{$address}', '{$password}', '{$image}')";

  $result = query($sql);
  confirm($result);

  move_uploaded_file($image_temp_loca, IMAGE_DIRECTORY . DS . $image);

  // $subject = "Activate Account";
  // $msg = " Please click on the link to Activate your Account <a href =\"http://localhost/ndpt/login/activate.php?email=$email&code=$validation\">Activate Your Account<a/>

  // ";

  // $headers = "From: ngurudeveloper@gmail.com";
  // send_email($email, $subject, $msg, $headers);

  return true;
 }

}

//**********************validating user login function***********************//

function validate_users_login()
{

 $erorrs = [];
 $min = 3;
 $max = 40;
 $email_l = 50;

 if ($_SERVER['REQUEST_METHOD'] == "POST") {

  $email = clean($_POST['email']);
  $password = clean($_POST['password']);
  $remember = isset($_POST['remember']);

  if (empty($email)) {
   $erorrs[] = "Email field cannot be empty";
  }

  if (empty($password)) {
   $erorrs[] = "Password field cannot be empty";
  }

  if (!empty($erorrs)) {
   foreach ($erorrs as $erorr) {

    //display erorr message//
    echo validation_erorr($erorr);

   }
  } else {

   if (users_login($email, $password, $remember)) {
    redirect("shopping-cart.php");
   } else {
    echo validation_erorr(" Sorry! Your Email/Password is Incorrect");
   }
  }

 }
}

//**********************User login function***********************//

function users_login($email, $password, $remember)
{

 $query = query("SELECT password, id FROM customers_users WHERE email = '" . escape_string($email) . "'");

 if (row_count($query) == 1) {

  $row = fetch_array($query);
  $db_password = $row['password'];

  if (password_verify($password, $db_password)) {
   # code...
   if ($remember == "on") {
    setcookie('email', $email, time() + 86400);
   }

   $_SESSION['email'] = $email;
   return true;
  } else {
   return false;
  }
 }

}
//end of user login function

//**********************Admin FUNCTION**********************************//
function validate_admin_login()
{

 $erorrs = [];
 $min = 3;
 $max = 40;
 $email_l = 50;

 if ($_SERVER['REQUEST_METHOD'] == "POST") {

  $email = clean($_POST['email']);
  $password = clean($_POST['password']);
  $remember = isset($_POST['remember']);

  if (empty($email)) {
   $erorrs[] = "Email field cannot be empty";
  }

  if (empty($password)) {
   $erorrs[] = "Password field cannot be empty";
  }

  if (!empty($erorrs)) {
   foreach ($erorrs as $erorr) {

    //display erorr message//
    echo validation_erorr($erorr);
   }
  }
  if (!email_exist($email)) {
   echo validation_erorr("This Email does not exist");
  } else {

   if (admin_login($email, $password, $remember)) {
    redirect("index.php");
   } else {
    echo validation_erorr("Your Credentials are not correct ");
   }
  }
 }
}
//**********************Admin login function***********************//

function admin_login($email, $password, $remember)
{
 if (isset($_POST['submit'])) {
  $email = $_POST['email'];
  $password = $_POST['password'];

  $query = query("SELECT password, id FROM customers_users WHERE email = '" . escape_string($email) . "' AND isAdmin= 1 ");

  if (row_count($query) == 1) {

   $row = fetch_array($query);
   $db_password = $row['password'];

   if (password_verify($password, $db_password)) {
    # code...
    if ($remember == "on") {
     setcookie('email', $email, time() + 86400);
    }

    $_SESSION['email'] = $email;
    return true;
   } else {
    return false;
   }
  }
 }
}

//**********************User logged in function***********************//

function logged_in_admin()
{

 if (isset($_SESSION['email'])) {

  $query = query("SELECT isAdmin FROM customers_users WHERE email = '" . escape_string($_SESSION['email']) . "' AND isAdmin=1 ");
  if (row_count($query) == 1) {
   $row = fetch_array($query);
   //$role = $row['role'];

   return true;
  } else {
   return false;
  }
 }
}

//**********************User Information function***********************//
function Userorders()
{
 $email = $_SESSION['email'];
 $query1 = query(" SELECT  *FROM customers_users as a, orders as b WHERE a.id= b.user_id AND a.email = '{$email}'");
 confirm($query1);
 while ($row = fetch_array($query1)) {
  $json_data = json_decode($row['ordersItems']);
  if ($row['status'] == 1) {
   $status = "Delivered";
   $color = "text-success";
  } else {
   $status = " Not Delivered";
   $color = "text-danger";
  }

  $product = <<<DELIMETER
                <tr>
                    <td>{$row['orderId']}</td>
                    <td>{$json_data->product_title}</td>
                    <td>{$json_data->items_quantity}</td>
                    <td>&#8358;{$json_data->product_price}</td>
                    <td>&#8358;{$json_data->sub}</td>
                    <td>{$row['transaction_id']}</td>
                    <td class="$color">{$status}</td>
                    <td class="$color">{$row['oderedDate']}</td>
                </tr>
        DELIMETER;
  echo $product;
 }
}
//**********************End User Information function***********************//

function get_products_in_cat_page()
{
 $query = query(" SELECT *FROM products as a, categories as b WHERE a.product_category_id= " . escape_string($_GET['id']) . "
    AND b.id = " . escape_string($_GET['id']) . " AND product_quantity>=1 ");
 confirm($query);
 while ($row = fetch_array($query)) {
//   echo "<pre>";
//   // print_r($query);
//   print_r($row);
//   echo "</pre>";

  //image fun
  $product_image = display_image($row['product_image']);
//end of imgfun

  $product = <<<DELIMETER
            <div class="col-lg-4 col-sm-6">
            <div class="product-item">
                <div class="pi-pic">
                <a href="product.php?id={$row[0]}"><img src="../resources/uploads/$row[3]" alt=""></a>
                    <div class="sale pp-sale">Sale</div>
                    <div class="icon">
                        <i class="icon_heart_alt"></i>
                    </div>
                    <ul>
                        <li class="w-icon active"><a href="#"><i class="icon_bag_alt"></i></a></li>
                        <li class="quick-view"><a href="#">+ Quick View</a></li>
                        <li class="w-icon"><a href="#"><i class="fa fa-random"></i></a></li>
                    </ul>
                </div>
                <div class="pi-text">
                    <div class="catagory-name">{$row['category_title']}</div>
                    <a href="#">
                        <h5>{$row['product_title']}</h5>
                    </a>
                    <div class="product-price">
                    &#8358;{$row['product_price']}
                        <span>&#8358;{$row['product_price']}</span>
                    </div>
                </div>
            </div>
        </div>
DELIMETER;
  echo $product;
 }
}

//get the product in shop
function get_products_in_shop_page()
{
 $query = query(" SELECT a.id, a.product_title, a.product_image,a.product_price,b.category_title FROM products as a, categories as b WHERE a.product_category_id=b.id AND product_quantity>=1");
 confirm($query);

 while ($row = fetch_array($query)) {
  //image fun
  $product_image = display_image($row['product_image']);
  //end of imgfun

  $product = <<<DELIMETER
        <div class="col-lg-4 col-sm-6">
            <div class="product-item">
                <div class="pi-pic">
                <a href="product.php?id={$row['id']}"><img src="../resources/{$product_image}" alt=""></a>
                    <div class="sale pp-sale">Sale</div>
                    <div class="icon">
                        <i class="icon_heart_alt"></i>
                    </div>
                    <ul>
                        <li class="w-icon active"><a href="#"><i class="icon_bag_alt"></i></a></li>
                        <li class="quick-view"><a href="#">+ Quick View</a></li>
                        <li class="w-icon"><a href="#"><i class="fa fa-random"></i></a></li>
                    </ul>
                </div>
                <div class="pi-text">
                    <div class="catagory-name">{$row['category_title']}</div>
                    <a href="#">
                        <h5>{$row['product_title']}</h5>
                    </a>
                    <div class="product-price">
                    &#8358;{$row['product_price']}
                        <span>&#8358;{$row['product_price']}</span>
                    </div>
                </div>
            </div>
        </div>
        DELIMETER;
  echo $product;
 }
}

// ***************WomenBanner***********************//
function womenBanner()
{
 $query = query(" SELECT a.id, a.product_title, a.product_image,a.product_price,b.category_title FROM products as a, categories as b WHERE a.product_category_id=4 AND product_quantity>=1");
 confirm($query);

 while ($row = fetch_array($query)) {
  //image fun
  $product_image = display_image($row['product_image']);
  //end of imgfun

  $amount = number_format($row['product_price']);

  $product = <<<DELIMETER
        <div class="product-item">
                        <div class="pi-pic">
                            <img src="../resources/$product_image" alt="">
                            <div class="icon">
                                <i class="icon_heart_alt"></i>
                            </div>
                            <ul>
                                <li class="w-icon active"><a href="#"><i class="icon_bag_alt"></i></a></li>
                                <li class="quick-view"><a href="#">+ Quick View</a></li>
                                <li class="w-icon"><a href="#"><i class="fa fa-random"></i></a></li>
                            </ul>
                        </div>
                        <div class="pi-text">
                            <div class="catagory-name">Towel</div>
                            <a href="#">
                                <h5>{$row['product_title']}</h5>
                            </a>
                            <div class="product-price">
                                &#8358;{$amount}
                            </div>
                        </div>
            </div>
        DELIMETER;
  echo $product;
 }
}

//display orders
function display_order()
{

 $query = query("SELECT *FROM orders");
 confirm($query);

 while ($row = fetch_array($query)) {
  $json_data = json_decode($row['ordersItems']);
  $json_ret = json_decode(json_encode($json_data), true);

  //

  // <td>{$row[$json_data->product_ID]}</td>

  $order = <<<ORDER
        <tr>
        <td>{$row['orderId']}</td>
        <td>{$row['user_id']}</td>
        <td>{$json_data->product_title}</td>
        <td>{$json_data->items_quantity}</td>
        <td>&#8358; {$json_data->product_price}</td>
        <td>&#8358; {$json_data->sub}</td>
        <td>{$row['transaction_id']}</td>
        <td><a class="btn btn-danger" href="../../resources/templates/back/delete_orders.php?id={$row['orderId']}"><span class="glyphicon glyphicon-remove"></span>Delete</a></td>
        </tr>

ORDER;
  echo $order;
 }

}

//----------------PRODUCTS IN ADMIN---------------------\\

function display_image($picture)
{

 global $uploads;

 return $uploads . DS . $picture;
}

function get_products_in_admin()
{
 $query = query(" SELECT *FROM products");
 confirm($query);

 while ($row = fetch_array($query)) {
  $product_image = display_image($row['product_image']);

  $category = show_product_category_title($row['product_category_id']);

  $product_in_admin = <<<DELIMETER

          <tr>

            <td>{$row['id']}</td>
            <td>{$row['product_title']}</td>
              <td><a href="index.php?edit_product&id={$row['id']}"><img width='80'height='80' src="../resources/{$product_image}" alt=""></a>
            </td>
            <td>{$category}</td>
            <td>&#8358;{$row['product_price']}</td>
            <td>{$row['product_quantity']}</td>
            <td><a class="btn btn-danger" onclick="if (confirm('Are you sure you want to delete {$row['product_title']} ?')) commentDelete(1); return false" href="../resources/templates/backend/delete_product.php?id={$row['id']}"><span class="glyphicon glyphicon-remove">Delete</span></a></td>
        </tr>

DELIMETER;
  echo $product_in_admin;
 }

}

//---------------- END OF PRODUCTS IN ADMIN---------------------\\
function show_product_category_title($product_category_id)
{

 $category_query = query("SELECT *FROM categories WHERE id = '{$product_category_id}' ");
 confirm($category_query);

 while ($category_row = fetch_array($category_query)) {
  return $category_row['category_title'];
 }

}

//---------------------Adding_product_in_admin--------------------------//
function adding_product_in_admin()
{
 extract($_POST);
 if (isset($_POST['publish'])) {

  $product_title = escape_string($product_title);
  $product_description = escape_string($product_description);
  $product_price = escape_string($product_price);
  $product_category = escape_string($product_category_id);
  $product_short_desc = escape_string($product_short_desc);
  $product_quantity = escape_string($product_quantity);
  $product_burst = escape_string($product_burst);
  $product_hip = escape_string($product_hip);
  $product_lwirst = escape_string($product_lwirst);
  $product_uwirst = escape_string($product_uwirst);
  $product_flength = escape_string($product_flength);
  $product_size = escape_string($product_size);
  $product_image = $_FILES['file']['name'];
  $image_temp_loca = $_FILES['file']['tmp_name'];
  $images = new stdClass();
  $images->img1 = $product_image;
  $tojson = json_encode($images);

  $query = query("INSERT INTO products(product_title, product_price, product_desc,  product_category_id, product_quantity, product_short_desc, product_image,size,product_images,burst,heap,full_length,upper_wirst,wirst_lower) VALUES('{$product_title}', '{$product_price}', '{$product_description}', '{$product_category}', '{$product_quantity}', '{$product_short_desc}', '{$product_image}','{$product_size}','{$tojson}','{$product_burst}','{$product_hip}','{$product_flength}','{$product_uwirst}','{$product_lwirst}')");
  $last_id = last_id();
  confirm($query);

  redirect("index.php?view_products");
  if (move_uploaded_file($image_temp_loca, IMAGE_DIRECTORY . DS . $product_image)) {
   set_message("New Product with id {$last_id} was Added");
  }
 }
}

///***********option to select categories in add prod through admin*************//

function show_categories_in_add_product_page()
{

 $query = query(" SELECT *FROM categories");
 confirm($query);

 while ($row = fetch_array($query)) {
  $categories_options = <<<DELIMETER
        <option value="{$row['id']}">{$row['category_title']}</option>
DELIMETER;
  echo $categories_options;
 }

}

//*****************Updating Product in Admin*************************//

function update_product()
{

 if (isset($_POST['update_product'])) {

  $product_title = escape_string($_POST['product_title']);
  $product_description = escape_string($_POST['product_description']);
  $product_price = escape_string($_POST['product_price']);
  $product_category = escape_string($_POST['product_category_id']);
  $product_short_desc = escape_string($_POST['product_short_desc']);
  $product_quantity = escape_string($_POST['product_quantity']);
  $product_sizes = escape_string($_POST['product_size']);
  $product_image = $_FILES['file']['name'];
  $image_temp_loca = $_FILES['file']['tmp_name'];

  if (empty($product_image)) {

   $get_pic = query("SELECT product_image FROM products WHERE id=" . escape_string($_GET['id']) . "");
   confirm($get_pic);

   while ($pic = fetch_array($get_pic)) {
    $product_image = $pic['product_image'];
   }
  }

  $query = "UPDATE products SET ";
  $query .= "product_title                  = '{$product_title}'        , ";
  $query .= "product_desc                   = '{$product_description}'  , ";
  $query .= "product_price                  = '{$product_price}'        , ";
  $query .= "product_category_id          = '{$product_category}'     , ";
  $query .= "product_short_desc             = '{$product_short_desc}'   , ";
  $query .= "size                           = '{$product_sizes}'        , ";
  $query .= "product_quantity               = '{$product_quantity}'     , ";
  $query .= "product_image                  = '{$product_image}'          ";
  $query .= "WHERE id=" . escape_string($_GET['id']);
  $send_update = query($query);
  confirm($send_update);
  redirect("index.php");
  if (move_uploaded_file($image_temp_loca, IMAGE_DIRECTORY . DS . $product_image)) {
   set_message("Product has been Updated");
  }
 }
}

//****************************Show Categories In Admin*************************//

function show_categories_in_admin()
{

 $query = query(" SELECT *FROM categories  ORDER BY category_title");
 confirm($query);

 while ($row = fetch_array($query)) {
  $categories = <<<DELIMETER
        <tr>
            <td>{$row['id']}</td>
            <td>{$row['category_title']}</td>
            <td></td>
            <td><a class="btn btn-danger" href="../../resources/templates/backend/delete_cat.php?id={$row['id']}"><span class="glyphicon glyphicon-remove"></span></a></td>
        </tr>
DELIMETER;
  echo $categories;
 }
}

//**********************validating user login function***********************//

function validate_adding_category()
{
 extract($_POST);
 $erorrs = [];
 if ($_SERVER['REQUEST_METHOD'] == "POST") {

  $category_title = clean($category_title);

  if (empty($category_title)) {
   $erorrs[] = "Category field cannot be empty";
  }
  if (category_exist($category_title)) {
   $erorrs[] = " This Category Already Exist!";
  }
  if (!empty($erorrs)) {
   foreach ($erorrs as $erorr) {
    //display erorr message//
    echo validation_erorr($erorr);
   }
  } else {
   if (adding_category_in_admin($category_title)) {
    redirect("index.php?add_category");
   } else {
    echo validation_erorr(" Please Try Again!");
   }
  }
 }
}

//****************************Adding Categories In Admin*************************//
function adding_category_in_admin($category_title)
{

 extract($_POST);
 if (isset($add_category)) {
  $category_title = escape_string($category_title);
  if (category_exist($category_title)) {
   return false;
  } else {
   $query = query("INSERT INTO categories (category_title) VALUES('{$category_title}') ");
   confirm($query);
   $last_id = last_id();
   set_message("<p class='text-success text-center'> New Category with id {$last_id} was Added</p>");
   return true;
  }

 }

}

//**************************** Showing in Admin Users*************************//

function display_users()
{

 $query = query(" SELECT *FROM users");
 confirm($query);
 $i = 0;
 $grade = 'A';
 while ($row = fetch_array($query)) {
     $randomemID = 'IRSpayee' . random_int(200, 1000);
  $i += 1;

//   $user_image = display_image($row['image']);
  $users = <<<DELIMETER
  <tr class="grade{$grade}">
  <td>{$i}</td>
  <td>{$randomemID}</td>
  <td class="hidden-phone">{$row['fullname']}</td>
  <td class="center hidden-phone">{$row['contact']}</td>
  <td class="center hidden-phone">{$row['email']}</td>
  <td class="center hidden-phone">{$row['created_at']}</td>
  <td class="center hidden-phone">{$row['address']}</td>
  <td>
      <button class="btn btn-success btn-xs"><i class="fa fa-check"></i></button>
      <button class="btn btn-primary btn-xs"><i class="fa fa-pencil"></i></button>
      <button class="btn btn-danger btn-xs"><i
              class="fa fa-trash-o "></i></button>
  </td>
</tr>
<script>
    function fnFormatDetails(oTable, nTr) {
        var aData = oTable.fnGetData(nTr);
        var sOut = '<table cellpadding="5" cellspacing="0" border="0" style="padding-left:50px;">';
        sOut += '<tr><td>FullName:</td><td>'+ ' ' + aData[3] + '</td></tr>';
        sOut += '<tr><td>Phone Number:</td><td>'+ ' ' + aData[4] + '</td></tr>';
        sOut += '<tr><td>Address info:</td><td>'+ ' ' + aData[7] + '</td></td></tr>';
        sOut += '</table>';

        return sOut;
    }
</script>
DELIMETER;
  echo $users;
 }

}


function display_category()
{

 $query = query(" SELECT *FROM payment_cat");
 confirm($query);
 $i = 0; 
 $grade = 'A';
 while ($row = fetch_array($query)) {
     $randomemID = 'IRSpayee' . random_int(200, 1000);
  $i += 1;
$amount = number_format($row['amount']);
//   $user_image = display_image($row['image']);
  $users = <<<DELIMETER
  <tr class="grade{$grade}">
  <td>{$i}</td>
  <td class="hidden-phone">{$row['category_name']}</td>
  <td class="center hidden-phone">&#8358;{$amount}</td>
  <td class="center hidden-phone">{$row['created_at']}</td>
  <td>
      <button class="btn btn-success btn-xs"><i class="fa fa-check"></i></button>
      <button class="btn btn-primary btn-xs"><i class="fa fa-pencil"></i></button>
      <button class="btn btn-danger btn-xs"><i
              class="fa fa-trash-o "></i></button>
  </td>
</tr>
<script>
    function fnFormatDetails(oTable, nTr) {
        var aData = oTable.fnGetData(nTr);
        var sOut = '<table cellpadding="5" cellspacing="0" border="0" style="padding-left:50px;">';
        sOut += '<tr><td>FullName:</td><td>'+ ' ' + aData[3] + '</td></tr>';
        sOut += '<tr><td>Phone Number:</td><td>'+ ' ' + aData[4] + '</td></tr>';
        sOut += '<tr><td>Address info:</td><td>'+ ' ' + aData[7] + '</td></td></tr>';
        sOut += '</table>';

        return sOut;
    }
</script>
DELIMETER;
  echo $users;
 }

}


//******************Adding User in Admin*************************//

function add_user()
{

 if (isset($_POST['add_user'])) {

  $username = escape_string($_POST['username']);
  $email = escape_string($_POST['email']);
  $password = escape_string($_POST['password']);
  $user_image = $_FILES['file']['name'];
  $user_temp_loca = $_FILES['file']['tmp_name'];

  if (empty($username) || empty($email) || empty($password)) {
   echo "<h4 class='bg-danger text-center'> Most hve a name</h4>";
  } else {

   $query = query("INSERT INTO admin_users (username,image,email_address,password) VALUES('{$username}','{$user_image}','{$email}','{$password}') ");
   confirm($query);
   $last_id = last_id();

   redirect("index.php?user");

   if (move_uploaded_file($user_temp_loca, IMAGE_DIRECTORY . DS . $user_image)) {

    set_message("New User with id {$last_id} was Added");

   }

  }

 }

}

//****************************Displaying Reports**************************//

function reports()
{

 $query = query(" SELECT *FROM reports");
 confirm($query);

 while ($row = fetch_array($query)) {
  $product_image = display_image($row['product_image']);

  $product_in_admin = <<<DELIMETER

          <tr>
            <td>{$row['report_id']}</td>
            <td>{$row['product_id']}</td>
            <td>{$row['order_id']}</td>
            <td>{$row['product_title']}<br>
            <img style="" width="30" src="../../resources/{$product_image}" alt="">
            </td>
            <td>&#8358;{$row['product_price']}</td>
             <td>{$row['transaction_id']}</td>
            <td>{$row['product_quantity']}</td>

            <td><a class="btn btn-danger" href="../../resources/templates/back/delete_report.php?id={$row['report_id']}"><span class="glyphicon glyphicon-remove"></span></a></td>
        </tr>

DELIMETER;
  echo $product_in_admin;
 }

}

//*********************SLIDES******************************//

function add_slides()
{
 if (isset($_POST['add_slide'])) {

  $slide_title = escape_string($_POST['slide_title']);
  $slide_image = $_FILES['file']['name'];
  $slide_image_loc = $_FILES['file']['tmp_name'];

  if (empty($slide_title) || empty($slide_image)) {
   echo "<p class='bg-danger text-center'>This field cannot be empty</p> ";
  } else {
   $query = query("INSERT INTO slides (slide_title,slide_image) VALUES('{$slide_title}','{$slide_image}') ");
   $last_id = last_id();
   confirm($query);
   set_message("New Slide with id {$last_id} was Added");
   redirect('index.php?slides');
   if (move_uploaded_file($slide_image_loc, IMAGE_DIRECTORY . DS . $slide_image)) {

   }
  }
 }

}

function get_slides()
{

 $query = query("SELECT *FROM slides");
 confirm($query);

 while ($row = fetch_array($query)) {
  $slide_image = display_image($row['slide_image']);
  $slides = <<<DELIMETER

 <div class="item">
    <img height="30" class="slide-image" src="../resources/{$slide_image}" alt="">
 </div>



DELIMETER;
  echo $slides;
 }

}

function get_current_slides_admin()
{
 $query = query("SELECT *FROM slides ORDER BY slide_id DESC LIMIT 1");
 confirm($query);

 while ($row = fetch_array($query)) {
  $slide_image = display_image($row['slide_image']);
  $slides_current_admin = <<<DELIMETER

 <div class="item active">
    <img class='img-responsive' src="../../resources/{$slide_image}">
 </div>



DELIMETER;
  echo $slides_current_admin;
 }
}

function active_slides()
{
 $query = query("SELECT *FROM slides ORDER BY slide_id DESC LIMIT 1");
 confirm($query);

 while ($row = fetch_array($query)) {
  $slide_image = display_image($row['slide_image']);
  $slides_active = <<<DELIMETER

 <div class="item active">
    <img  class="slide-image" src="../resources/{$slide_image}" alt="">
 </div>



DELIMETER;
  echo $slides_active;
 }
}

function get_slide_thumbnails()
{

 $query = query("SELECT *FROM slides ORDER BY slide_id ASC ");
 confirm($query);

 while ($row = fetch_array($query)) {
  $slide_image = display_image($row['slide_image']);
  $slides_thumnails = <<<DELIMETER


<div class="col-xs-6 col-md-3 image_container">
    <a href="index.php?delete_slide_id={$row['slide_id']}">
        <img width='200' class="img-responsive slide_image" src="../../resources/{$slide_image}">
    </a>

    <div class='caption'>
    <p>{$row['slide_title']}</p>
    </div>
</div>



DELIMETER;
  echo $slides_thumnails;
 }

}

function logged_in()
{

 if (isset($_SESSION['email'])) {

  return true;
 } else {
  return false;
 }
} //end of logged in function

function admin_logged()
{
 if (isset($_SESSION['email'])) {

  $query = query("SELECT email,isAdmin FROM customers_users WHERE email= '" . escape_string(($_SESSION['email'])) . "' AND isAdmin=1");
  if (row_count($query) == 1) {
   $row = fetch_array($query);
   return true;
  } else {
   return false;
  }
 }
}

function select_customer_order()
{

 if (isset($_POST['get_order'])) {

  $transaction_id = escape_string($_POST['get_orders']);

  if (empty($transaction_id)) {
   set_message("Sorry You have to Put the Transaction Id");
  } else {

   $query = query("SELECT * FROM reports WHERE transaction_id ='{$transaction_id}'");
   confirm($query);
   $query1 = query("SELECT * FROM orders WHERE order_transaction ='{$transaction_id}'");
   confirm($query1);
   while ($row1 = fetch_array($query1)) {

    while ($row = fetch_array($query)) {
     $product_image = display_image($row['product_image']);

     $product_in_admin = <<<DELIMETER

                <tr>

                    <td>{$row['product_id']}</td>
                    <td>{$row['order_id']}</td>
                    <td>{$row['product_title']}<br>
                    <img style="" width="30" src="../../resources/{$product_image}" alt="">

                    </td>
                    <td>&#8358;{$row['product_price']}</td>
                    <td>{$row['product_quantity']}</td>
                    <td>{$row1['customer_name']}</td>
                    <td>{$row1['customer_email']}</td>
                    <td>{$row1['customer_address']}</td>
                    <td>{$row1['customer_phone']}</td>
                    <td><a class="btn btn-danger" href="../../resources/templates/back/delete_report.php?id={$row['report_id']}"><span class="glyphicon glyphicon-remove"></span></a></td>
                </tr>

DELIMETER;
     echo $product_in_admin;
    }
   }
  }
 }

}