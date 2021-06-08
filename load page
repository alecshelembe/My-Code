<?php  

session_start();

include_once("header.php");

include_once("functions.php");

if (isset($_POST['signup'])) {
	$email = post_check("email");
	$email = sanitizeString($email);
	$name = post_check("name");
	$name = sanitizeString($name);
	$confirm_security_key = post_check("confirm_security_key");
	$security_key = post_check("security_key");
	$security_key = sanitizeString($security_key);
	confirm_match($security_key,$confirm_security_key);
	$security_key = password_hash($security_key, PASSWORD_DEFAULT);
	$active = "yes";
	$table = "users";
	$dbname = "hidden";
	check_if_exists($conn,$dbname,$table,'email',$email);
	$verification_code = rand(1,100000000000);
	insert_info($conn,$dbname,$table,'email',$email);
	update_info($conn,$dbname,$table,'name','email',$name,$email);
	update_info($conn,$dbname,$table,'verification_code','email',$verification_code,$email);
	update_info($conn,$dbname,$table,'verificated','email','no',$email);
	update_info($conn,$dbname,$table,'email','email',$email,$email);
	update_info($conn,$dbname,$table,'security_key','email',$security_key,$email);

	///////////////////////////////////////////////////////////////

	send_email('kpautoreplyservice@gmail.com',"$email","$name",'Kingproteas Sign Up',"Thank you for signing up to the Kingproteas group. Click here to verify your account. Your verification code is $verification_code
		<a href='https://intown-lubrication.000webhostapp.com/verification page.php'>Verify me</a>
		");

	///////////////////////////////////////////////////////////////

	alert('Account created sucessfuly please check your email inbox');
	redirect_back();
}

if (isset($_POST['forgot_password'])) {
	$email = post_check("email");
	$email = sanitizeString($email);
	$email_verification_code = rand(1,1000000000);
	if ($x =  get_result($conn,$dbname,'users','email',$email) == 'yes') {
	send_email('kpautoreplyservice@gmail.com',"$email","Password",'Password reset',"Here is the link and  the code to reset your password.<br> <a href='https://intown-lubrication.000webhostapp.com/verification page.php'>Reset password</a> <br>Verification Code: $email_verification_code <br>
		<a href='https://intown-lubrication.000webhostapp.com/index.php'>Kingproteas</a>");
	update_info($conn,$dbname,'users','security_key','email',$email_verification_code,$email);
	}
	redirect_back();
}

if (isset($_POST['change_password'])) {
	$email = post_check("email");
	$email = sanitizeString($email);
	$security_key = post_check("security_key");
	$security_key = sanitizeString($security_key);
	$security_key = password_hash($security_key, PASSWORD_DEFAULT);
	$email_verification_code = post_check("email_verification_code");
	$email_verification_code = sanitizeString($email_verification_code);
	if ($x =  get_result($conn,$dbname,'users','email',$email) == 'yes') {
	update_info($conn,$dbname,'users','security_key','email',$security_key,$email);
	alert('Password changed. You may login');
	$location = "index.php";
	go_to($location);
	}
	redirect_back();
}

if (isset($_POST['verify_password'])) {
	$email = post_check("email");
	$email = sanitizeString($email);
	$code = post_check("code");
	$code = sanitizeString($code);
	$authentication = return_info($conn,'users','verification_code','email',$email);
	if ($authentication !== $code) {
		alert('The verification code you entered does not match the one sent via the email.');
		redirect_back();
	}

	update_info($conn,$dbname,'users','verificated','email','yes',$email);
	
	alert('Your account has been verified thank you. You may login.');
	$location = "index.php";
	go_to($location);

} 

if (isset($_POST['login'])) {
	$email = post_check("email");
	$email = sanitizeString($email);
	$security_key = post_check("security_key");
	$security_key = sanitizeString($security_key);
	$table = "users";
	$dbname = "id16681620_techacademy";
	// echo "$email<br>$security_key";
	pair_for_login($conn,$table,"email",$email,"security_key",$security_key);
	alert('You have been logged in');
	stop("<br>Done");
}

if (isset($_POST['logout'])) {
	logout();
	stop("<br>Done");
}

if (isset($_POST['shop'])) {
	$email = check_login_email();
	$name = check_login_name();
	$location = "shop signup page.php";
	go_to($location);
	stop("<br>Done");
}

if (isset($_POST['update_product'])) {
	$email = check_login_email();
	$name = check_login_name();
	$shop = return_info($conn,'shops','name','email',$email);
	$product_number = post_check("product_number");
	$product_number = sanitizeString($product_number);
	$title_change = post_check("title_change");
	$title_change = sanitizeString($title_change);
	$title = post_check("title");
	$title = sanitizeString($title);

	update_info($conn,$dbname,"$product_number","$title",'email',"$title_change",$email);
	alert("The $title for $product_number has been updated");
	redirect_back();
}

		///////////
if (isset($_POST['update_photo'])){
	$email = check_login_email();
	$name = check_login_name();

	$product_number = post_check("product_number");
	$product_number = sanitizeString($product_number);

	$shop = return_info($conn,'shops','name','email',$email);
	$photo = return_info($conn,"$product_number",'image','email',$email);

	$original_file_name = $_FILES['image']['name'];
	$file_type = $_FILES['image']['type'];
	$image = sanitizeString($original_file_name);
	$file_type = sanitizeString($file_type);
	$file_size = $_FILES['image']['size']; 	
	$file_tem_loc = $_FILES['image']['tmp_name']; 

	$random = rand(1,100000000000);
	$image = "$shop $product_number $random";

	check_if_exists($conn,$dbname,"$product_number",'image',$image);

	$dir = "product images";

	$image = image_process($conn,$dir,$image,$file_type,$file_size,$file_tem_loc);

	alert("The $image has been updated");

	unlink("$dir/$photo");

	update_info($conn,$dbname,"$product_number",'image','email',$image,$email);

	redirect_back();

}
	/////////////

if (isset($_POST['product'])) {
	$email = check_login_email();
	$name = check_login_name();
	$shop = return_info($conn,'shops','name','email',$email);
	$state = post_check("state");
	$state = sanitizeString($state);
	$product = post_check("product");
	$product = sanitizeString($product);

	if ($state == 'Update or change') {
		$state = 'not current';
		alert("$product has been deactivated for changes");
		update_info($conn,$dbname,"$product",'state','email',"$state",$email);
		$location = "update shop page.php";
		go_to($location);
		stop("<br>Done");
	}

	if ($state == 'Activate') {
		$state = 'current';
		alert("$product has been activated");
	} else{

		$state = 'not current';
		alert("$product has been deactivated");
	}

	update_info($conn,$dbname,"$product",'state','email',"$state",$email);
	redirect_back();


}

if (isset($_POST['shopname'])) {
	$email = check_login_email();
	$name = check_login_name();
	$nameofshop = post_check("nameofshop");
	$nameofshop = sanitizeString($nameofshop);
	$province = post_check("province");
	$province = sanitizeString($province);
	$city = post_check("city");
	$city = sanitizeString($city);
	$number = post_check("number");
	$number = sanitizeString($number);

	$original_file_name = $_FILES['logo']['name'];
	$file_type = $_FILES['logo']['type'];
	$image = sanitizeString($original_file_name);
	$file_type = sanitizeString($file_type);
	$file_size = $_FILES['logo']['size']; 	
	$file_tem_loc = $_FILES['logo']['tmp_name'];

	check_if_exists($conn,$dbname,'shops','email',$email);
	check_if_exists($conn,$dbname,'shops','logo',$image);
	check_if_exists($conn,$dbname,'shops','name',$nameofshop);

	$dir = "logo images";
	
	$image = image_process($conn,$dir,$image,$file_type,$file_size,$file_tem_loc);

	insert_info($conn,$dbname,'shops','email',$email);
	update_info($conn,$dbname,'shops','name','email',$nameofshop,$email);
	update_info($conn,$dbname,'shops','province','email',$province,$email);
	update_info($conn,$dbname,'shops','number','email',$number,$email);
	update_info($conn,$dbname,'shops','logo','email',$image,$email);
	update_info($conn,$dbname,'shops','city','email',$city,$email);
	alert("$nameofshop has been created");
	$location = "home page.php";
	go_to($location);
	stop("<br>Done");
}

if (isset($_POST['new_product_number'])) {
	$email = check_login_email();
	$name = check_login_name();
	$shop = return_info($conn,'shops','name','email',$email);

	$product_number = post_check("product_number");
	$product_number = sanitizeString($product_number);


	$original_file_name = $_FILES['img']['name'];
	$file_type = $_FILES['img']['type'];
	$image = sanitizeString($original_file_name);
	$file_type = sanitizeString($file_type);
	$file_size = $_FILES['img']['size']; 	
	$file_tem_loc = $_FILES['img']['tmp_name'];

	$random = rand(1,100000000000);

	$image = "$shop $product_number $random";
	$id = rand(1,100000000000);

	check_if_exists($conn,$dbname,"$product_number",'email',$email);
	check_if_exists($conn,$dbname,"$product_number",'image',$image);
	check_if_exists($conn,$dbname,"$product_number",'id',"$shop $product_number $random");
	check_if_exists($conn,$dbname,'all_products_id','id',"$id");

	$dir = "product images";

	$image = image_process($conn,$dir,$image,$file_type,$file_size,$file_tem_loc);

	$name = post_check("name");
	$name = sanitizeString($name);
	$price = post_check("price");
	$price = sanitizeString($price);
	if (!is_numeric($price)) {
		alert('Price must only contain numbers');
		redirect_back();

	}
	$description = post_check("description");
	$description = sanitizeString($description);
	

	insert_info($conn,$dbname,"$product_number",'email',$email);
	insert_info($conn,$dbname,'all_products_id','id',"$id");
	update_info($conn,$dbname,"$product_number",'id','email',$id,$email);
	update_info($conn,$dbname,"$product_number",'shop','email',$shop,$email);
	update_info($conn,$dbname,"$product_number",'name','email',$name,$email);
	update_info($conn,$dbname,"$product_number",'price','email',$price,$email);
	update_info($conn,$dbname,"$product_number",'description','email',$description,$email);
	update_info($conn,$dbname,"$product_number",'image','email',$image,$email);
	$location = "menu page.php";
	go_to($location);
	stop("<br>Done");
}

if (isset($_POST['review'])) {
	$email = check_login_email();
	$name = check_login_name();
	$comment = post_check("comment");
	$comment = sanitizeString($comment);
	$stars = post_check("stars");
	$stars = sanitizeString($stars);
	check_if_exists($conn,$dbname,'reviews','email',$email);
	insert_info($conn,$dbname,'reviews','email',$email);
	update_info($conn,$dbname,'reviews','name','email',$name,$email);
	update_info($conn,$dbname,'reviews','comment','email',$comment,$email);
	update_info($conn,$dbname,'reviews','rating','email',$stars,$email);
	alert("Review successful");
	$location = "reviews page.php";
	go_to($location);
	stop("<br>Done");
}

if (isset($_POST['order_now'])) {
	$list = post_check("list");
	$list = sanitizeString($list);
	$email = post_check("email");
	$email = sanitizeString($email);
	$number = post_check("number");
	$number = sanitizeString($number);
	$name = post_check("name");
	$name = sanitizeString($name);
	$note_to_driver = post_check("note_to_driver");
	$note_to_driver = sanitizeString($note_to_driver);
	$address = post_check("address");
	$address = sanitizeString($address);
	$order_id = rand(1,100000000000);
	insert_info($conn,$dbname,'customer_orders','email',"$email");
	update_info($conn,$dbname,"customer_orders",'list','email',$list,$email);
	update_info($conn,$dbname,"customer_orders",'number','email',$number,$email);
	update_info($conn,$dbname,"customer_orders",'name','email',$name,$email);
	update_info($conn,$dbname,"customer_orders",'note_to_driver','email',$note_to_driver,$email);
	update_info($conn,$dbname,"customer_orders",'address','email',$address,$email);
	update_info($conn,$dbname,"customer_orders",'id','email',$order_id,$email);

	send_email('kpautoreplyservice@gmail.com',"$email","$name",'Kingproteas order',"Email: $email <br> Number: $number <br> Your order: $list <br> Address: $address <br> Note to driver: $note_to_driver <br> Order id: $order_id <br> 
		Thank you from <a href='https://intown-lubrication.000webhostapp.com/'>Kingproteas</a>
		");

	///////////////////////////////////////////////////////////////

	alert('Thank you for your order');
	$location = "index.php";
	go_to($location);
	redirect_back();

}

alert('Please return and try again');
$location = "index.php";
go_to($location);
?>
