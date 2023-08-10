<?php

$uploads = 'uploads';

function last_id()
{
 global $connection;
 $last_id = mysqli_insert_id($connection);

 return mysqli_insert_id($connection);
}

function generateRandomString($length) {
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $randomString = substr(str_shuffle($characters), 0, $length);
 
    return $randomString;
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
function staffID_exist($staffID)
{
 extract($_POST);
 $sql = "SELECT admin_id, staffID FROM admin WHERE staffID = '{$staffID}'";
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
function display_image($picture)
{

 global $uploads;

 return $uploads . DS . $picture;
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

//**********************validating user login function***********************//

// function validate_adding_category()
// {
//  extract($_POST);
//  $erorrs = [];
//  if ($_SERVER['REQUEST_METHOD'] == "POST") {

//   $category_title = clean($category_title);

//   if (empty($category_title)) {
//    $erorrs[] = "Category field cannot be empty";
//   }
//   if (category_exist($category_title)) {
//    $erorrs[] = " This Category Already Exist!";
//   }
//   if (!empty($erorrs)) {
//    foreach ($erorrs as $erorr) {
//     //display erorr message//
//     echo validation_erorr($erorr);
//    }
//   } else {
//    if (adding_category_in_admin($category_title)) {
//     redirect("index.php?add_category");
//    } else {
//     echo validation_erorr(" Please Try Again!");
//    }
//   }
//  }
// }

// //**************************** IRS Adding Categories In Admin*************************//
// function adding_category_in_admin($category_title)
// {

//  extract($_POST);
//  if (isset($add_category)) {
//   $category_title = escape_string($category_title);
//   if (category_exist($category_title)) {
//    return false;
//   } else {
//    $query = query("INSERT INTO categories (category_title) VALUES('{$category_title}') ");
//    confirm($query);
//    $last_id = last_id();
//    set_message("<p class='text-success text-center'> New Category with id {$last_id} was Added</p>");
//    return true;
//   }

//  }

// }

// am coming
function validate_adding_ADMIN()
{
 extract($_POST);
 $erorrs = [];
 $password = generateRandomString(8);
 if ($_SERVER['REQUEST_METHOD'] == "POST") {

  $staffID = clean($staffid);
  $password = clean($password);
  $department = clean($department);
  $role = clean($role);

  if (empty($staffID)) {
   $erorrs[] = "staffID field cannot be empty";
  }
  if (staffID_exist($staffID)) {
   $erorrs[] = " This staffID Already Exist!";
  }
  if (!empty($erorrs)) {
   foreach ($erorrs as $erorr) {
    //display erorr message//
    echo validation_erorr($erorr);
   }
  } else {
   if (adding_admin_inAdmindashboard($staffID,$password,$role, $department)) {
    redirect("index.php?manage_admins");
   } else {
    echo validation_erorr(" Please Try Again!");
   }
  }
 }
}

//**************************** IRS Adding Categories In Admin*************************//
function adding_admin_inAdmindashboard($staffID, $password,$role, $department)
{

 extract($_POST);
 if (isset($add_admin)) {
  $staffID = escape_string($staffID);
  if (staffID_exist($staffID)) {
   return false;
  } else {
   $query = query("INSERT INTO admin (staffID, password, dept, role) VALUES('{$staffID}','{$password}','{$department}','{$role}') ");
   confirm($query);
   $last_id = last_id();
   set_message("<p class='text-success text-center'> New Staff with id {$last_id} was Added</p>");
   return true;
  }

 }

}
//**************************** Showing in Admin Users*************************//
//**************************** ALL Admin Users********************************//

function display_admin_users()
{

 $query = query(" SELECT *FROM admin");
 confirm($query);
 $i = 0;
 $grade = 'A';
 while ($row = fetch_array($query)) {
     $randomemID = 'IRS' . random_int(200, 1000);
  $i += 1;

//   $user_image = display_image($row['image']);
  $users = <<<DELIMETER
  <tr class="grade{$grade}">
  <td>{$i}</td>
  <td>{$randomemID}</td>
  <td class="hidden-phone">{$row['name']}</td>
  <td class="center hidden-phone">{$row['email']}</td>
  <td class="center hidden-phone">{$row['dept']}</td>
  <td class="center hidden-phone">{$row['role']}</td>
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
        sOut += '<tr><td>Staff ID :</td><td>'+ ' ' + aData[2] + '</td></tr>';
        sOut += '<tr><td>FullName :</td><td>'+ ' ' + aData[3] + '</td></tr>';
        sOut += '<tr><td>Role -> Dept:</td><td>'+ ' ' + aData[5] + ' -> ' + aData[6] + '</td></td></tr>';
        sOut += '</table>';

        return sOut;
    }
</script>
DELIMETER;
  echo $users;
 }

}
//************** end of  admin users *************/
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
//************** end of users *************/


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
//************** end of categories *************/

function display_payment_history()
{

    $query = query(" SELECT *FROM ((payment_tbl 
    INNER JOIN users ON payment_tbl.user_id = users.user_id)
    INNER JOIN payment_cat ON payment_tbl.category_id = payment_cat.category_id) ");
 confirm($query);

//  echo "<prev>";
//  print_r($query);
//  echo "</pre>";

 $i = 0; 
 $grade = 'A';
 while ($row = fetch_array($query)) {
     $randomemID = 'IRSpayee' . random_int(200, 1000);
  $i += 1;
$amount = number_format($row['amount']);
//   $user_image = display_image($row['image']);
  $users = <<<DELIMETER
  <tr class="gradeX">
  <td>{$i}</td>
  <td>{$row['username']}</td>
  <td class="hidden-phone">{$row['fullname']}</td>
  <td class="center hidden-phone">{$row['email']}</td>
  <td class="center hidden-phone">&#8358;{$amount}</td>
  <td class="center hidden-phone">{$row['status']}</td>
  <td class="center hidden-phone">{$row['month']}</td>
  <td class="center hidden-phone">{$row['transaction_id']}</td>
  <td class="center hidden-phone">{$row['payment_date']}</td>
<script>
    function fnFormatDetails(oTable, nTr) {
        var aData = oTable.fnGetData(nTr);
        var sOut = '<table cellpadding="5" cellspacing="0" border="0" style="padding-left:50px;">';
        sOut += '<tr><td>FullName:</td><td>'+ ' ' + aData[3] + '</td></tr>';
        sOut += '<tr><td>Email:</td><td>'+ ' ' + aData[4] + '</td></tr>';
        sOut += '<tr><td>Date:</td><td>'+ ' ' + aData[7] + '</td></td></tr>';
        sOut += '</table>';

        return sOut;
    }
</script>
DELIMETER;
  echo $users;
 }

}
//************** end of categories *************/


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