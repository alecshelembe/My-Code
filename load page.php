<?php  
include_once($_SERVER['DOCUMENT_ROOT'] . "/header/header-page.php");

if (isset($_POST['register'])) {
	$email = post_check("email");
	$email = sanitizeString($email);
	$surname = post_check("surname");
	$surname = sanitizeString($surname);
	$number = post_check("number");
	$number = sanitizeString($number);
	$password = post_check("password");
	$password = sanitizeString($password);
	$name = post_check("name");
	$name = sanitizeString($name);
	$confirm_password = post_check("confirm_password");
	$confirm_password = sanitizeString($confirm_password);

	confirm_match($password,$confirm_password);
	$password = password_hash($password, PASSWORD_DEFAULT);
	// $active = "";
	$id = rand(10000,99999);
	check_if_exists($conn,$dbname,'users','email',$email);
	check_if_exists($conn,$dbname,'users','id',$id);
	$verification_code = rand(1000,9999);
	insert_info($conn,$dbname,'users','email',$email);
	update_info($conn,$dbname,'users','id','email',$id,$email);
	update_info($conn,$dbname,'users','name','email',$name,$email);
	update_info($conn,$dbname,'users','number','email',$number,$email);
	update_info($conn,$dbname,'users','surname','email',$surname,$email);
	update_info($conn,$dbname,'users','last_email_verification_code','email',$verification_code,$email);
	update_info($conn,$dbname,'users','password','email',$password,$email);
	alert('Account created successfully');

	///////////////////////////////////////////////////////////////

	send_email('noreply@kingproteas.com',"$email","$name",'Kingproteas registration',"Thank you for registering to Kingproteas. Click here to verify your account. Your verification code is $verification_code <a href='https://kingproteas.com/verification-page.php'>Verify me</a>");

	///////////////////////////////////////////////////////////////
	$location = "https://kingproteas.com/login-page.php";
	go_to($location);
}


if (isset($_POST['login'])) {
	$email = post_check("email");
	$email = sanitizeString($email);
	$password = post_check("password");
	$password = sanitizeString($password);
	pair_for_login($conn,'users',"email",$email,"password",$password);
	$logs_id = rand(10000,99999);
	$name =  $_SESSION['name'];
	$email = $_SESSION['email'];
	// $datetime = "Date " . date("Y-m-d @ h:i:s a");
	// $ipaddress = get_ipaddress();
	$ipnumber = get_ipnumber();
	// $ipaddress = implode(" ",$ipaddress);
	$device = get_device();
	insert_info($conn,'','logs_in','id',$logs_id);
	update_info($conn,'','logs_in','name','id',$name,$logs_id);
	update_info($conn,'','logs_in','device','id',$device,$logs_id);
	update_info($conn,'','logs_in','ipnumber','id',$ipnumber,$logs_id);
	update_info($conn,'','logs_in','email','id',$email,$logs_id);
	// update_info($conn,'','logs_in','ipaddress','id',$ipaddress,$logs_id);
	$location = "https://kingproteas.com/shopping-cart-page.php";
	go_to($location);

}

if (isset($_POST['profile_button'])) {
	$location = "https://kingproteas.com/profile-page.php";
	go_to($location);
}

if (isset($_POST['upload_changes_button'])) {
	$location = "https://kingproteas.com/upload-changes-page.php";
	go_to($location);
}

if (isset($_POST['upload_form_button'])) {
	$location = "https://kingproteas.com/forms/upload-form.php";
	go_to($location);
}

if (isset($_POST['update_profile'])) {
	$session_details = check_login_email();
	$details = post_check("details");
	$details = sanitizeString($details);
	$value = post_check("value");
	$value = sanitizeString($value);
	update_info($conn,$dbname,'users',"$details",'email',$value,$session_details[0]);
	alert("Profile updated");
	redirect_back();
}

if (isset($_POST['update_address'])) {
	$session_details = check_login_email();
	$address_line_1 = post_check("address_line_1");
	$address_line_1 = sanitizeString($address_line_1);
	$address_line_2 = 'None';
	if(isset($_POST['address_line_2'])){
		$address_line_2 = post_check("address_line_2");
		$address_line_2 = sanitizeString($address_line_2);
	}
	$city_district = post_check("city_district");
	$city_district = sanitizeString($city_district);
	$state_province = post_check("state_province");
	$state_province = sanitizeString($state_province);
	$zip_postal_code = post_check("zip_postal_code");
	$zip_postal_code = sanitizeString($zip_postal_code);
	$country = post_check("country");
	$country = sanitizeString($country);
	update_info($conn,$dbname,'users',"address_line_1",'email',$address_line_1,$session_details[0]);
	update_info($conn,$dbname,'users',"address_line_2",'email',$address_line_2,$session_details[0]);
	update_info($conn,$dbname,'users',"city_district",'email',$city_district,$session_details[0]);
	update_info($conn,$dbname,'users',"state_province",'email',$state_province,$session_details[0]);
	update_info($conn,$dbname,'users',"zip_postal_code",'email',$zip_postal_code,$session_details[0]);
	update_info($conn,$dbname,'users',"country",'email',$country,$session_details[0]);
	alert("Address updated");
	$location = "https://kingproteas.com/shopping-cart-page.php";
	go_to($location);
}

if (isset($_POST['logout'])) {
	$name = $_SESSION['name'];
	$email = $_SESSION['email'];
	$logs_id = rand(10000,99999);
	// $ipaddress = get_ipaddress();
	// $ipaddress = implode(" ",$ipaddress);
	$device = get_device();
	$ipnumber = get_ipnumber();
	insert_info($conn,$dbname,'logs_out','id',$logs_id);
	update_info($conn,$dbname,'logs_out','name','id',$name,$logs_id);
	update_info($conn,$dbname,'logs_out','device','id',$device,$logs_id);
	update_info($conn,$dbname,'logs_out','ipnumber','id',$ipnumber,$logs_id);
	update_info($conn,$dbname,'logs_out','email','id',$email,$logs_id);
	// update_info($conn,$dbname,'logs_out','ipaddress','id',$ipaddress,$logs_id);
	logout();
	alert("You have logged out");
	$location = "https://kingproteas.com/";
	go_to($location);
	redirect_back();
}

if (isset($_POST['clear_shopping_cart'])) {
	foreach($_SESSION as $key => $val)
	{

		if ($key !== 'email' && $key !== 'name' && $key !== 'number' && $key !== 'account_status')
		{

			unset($_SESSION["$key"]);

		}

	}
	redirect_back();
}

if (isset($_POST['remove_from_cart'])) {
	$id = post_check("remove_from_cart");
	$id = sanitizeString($id);
	unset($_SESSION["product $id"]);
	unset($_SESSION["quantity $id"]);
	include($_SERVER['DOCUMENT_ROOT'] . "/loops/total_check.php");
	redirect_back();
}

if (isset($_POST['invite_payment'])) {
	alert('Coming soon');
	redirect_back();
}

if (isset($_POST['to_cart_form'])) {
	$to_cart_form = post_check("to_cart_form");
	$to_cart_form = sanitizeString($to_cart_form);
	// $session_details = check_login_email(); 
	$location = "https://kingproteas.com/shopping-cart-page.php";
	go_to($location);
}

if (isset($_POST['order'])) {
	if ($session_details[0] == 'none') {
		$session_details = check_login_email();
		alert("Please login to finish your order");
		$location = "https://kingproteas.com/login-page.php";
		go_to($location);

	}
	$email = $session_details[0];
	//////////////////////////////////
	$total = 0;
	$order_list = '';
	$t_answer = 0;
	foreach ($_SESSION as $key=>$val){
		// echo "$key + $val<br>";
		if(strpos($key, 'product') !== false){
			$t_price = return_info($conn,'products','price','id',$val);
			$t_quantity = $_SESSION["quantity $val"];
			$t_answer = $t_quantity * $t_price;

			$t_name = return_info($conn,'products','name','id',$val);

			$order_list .= 'Name= '.$t_name.' Quantity= '.$t_quantity.' Price(1)= '.$t_price.' id= '.$val.'\n';

			$total += $t_answer;

		// echo "$total<br>";
		}

	}
	$total = number_format($total,2);

	//////////////////////////////////
	if ($total == '0.00') {
		alert('Your cart is empty');
		redirect_back();
	}

	$user_id = return_info($conn,'users','id','email',$email);

	$address_line_1 = return_info($conn,'users','address_line_1','id',$user_id);
	$address_line_2 = return_info($conn,'users','address_line_2','id',$user_id);
	$state_province = return_info($conn,'users','state_province','id',$user_id);
	$zip_postal_code = return_info($conn,'users','zip_postal_code','id',$user_id);
	$city_district = return_info($conn,'users','city_district','id',$user_id);
	$country = return_info($conn,'users','country','id',$user_id);

	if ($city_district == null){
		$location = "https://kingproteas.com/profile-page.php";
		go_to($location);
	} 

	$query = "SELECT * FROM `orders` WHERE `user_id` = '$user_id' and `status` = 'pending payment';";
	
	// echo"$query"; 
	// stop();

	$result = mysqli_query($conn, $query);

	$row = mysqli_num_rows($result);
	if ($row >= 1) {

		while ($row = mysqli_fetch_assoc($result)) {
			$order_id = $row['id'];

			// remove($conn,$dbname,'orders','id',$order_id);
			// alert('old payment');
			update_info($conn,$dbname,'orders','payment','id','Not successful',$order_id);
			update_info($conn,$dbname,'orders','status','id','Interrupted',$order_id);

		}

	}

	$name = return_info($conn,'users','name','email',$email);
	$email = return_info($conn,'users','email','id',$user_id);
	$number = return_info($conn,'users','number','id',$user_id);
	$account_status = return_info($conn,'users','account_status','id',$user_id);
	$name = return_info($conn,'users','name','id',$user_id);

	$recieve_code = rand(1000,9999);
	$order_id = "$user_id"."$recieve_code";
	$pending = 'pending';
	$ipnumber = get_ipnumber();
	
	insert_info($conn,$dbname,'orders','id',$order_id);
	update_info($conn,$dbname,'orders','payment','id','pending',$order_id);
	update_info($conn,$dbname,'orders','ipaddress','id',$ipnumber,$order_id);
	update_info($conn,$dbname,'orders','status','id',$pending,$order_id);
	update_info($conn,$dbname,'orders','total','id',$total,$order_id);
	update_info($conn,$dbname,'orders','user_id','id',$user_id,$order_id);
	update_info($conn,$dbname,'orders','account_status','id',$account_status,$order_id);
	update_info($conn,$dbname,'orders','name','id',$name,$order_id);
	update_info($conn,$dbname,'orders','recieve_code','id',$recieve_code,$order_id);
	update_info($conn,$dbname,'orders','number','id',$number,$order_id);
	update_info($conn,$dbname,'orders','email','id',$email,$order_id);
	update_info($conn,$dbname,'orders','order_list','id',$order_list,$order_id);

	$_SESSION['order_id'] = "$order_id";
	$location = "https://kingproteas.com/payfast-page.php";
	go_to($location);

}

if (isset($_POST['upload_changes'])) {
	$id = post_check("upload_changes");
	$id = sanitizeString($id);
	$name = post_check("name");
	$name = sanitizeString($name);
	$catagory = post_check("catagory");
	$catagory = sanitizeString($catagory);
	$price = post_check("price");
	$price = sanitizeString($price);
	$stock = post_check("stock");
	$stock = sanitizeString($stock);
	$status = post_check("status");
	$status = sanitizeString($status);
	$rank = post_check("rank");
	$rank = sanitizeString($rank);
	$rating = post_check("rating");
	$rating = sanitizeString($rating);

	$update_image = post_check("update_image");
	$update_image = sanitizeString($update_image);

	$price = get_number($price);

	$description = post_check("description");
	$long_description =  filter_var($description, FILTER_SANITIZE_STRING);

	$long_description = post_check("long_description");
	$long_description =  filter_var($long_description, FILTER_SANITIZE_STRING);
	
	$store = post_check("store");
	$store = sanitizeString($store);
	
	$original_file_name = $_FILES['image']['name'];
	$file_type = $_FILES['image']['type'];
	$image = sanitizeString($original_file_name);
	$file_type = sanitizeString($file_type);
	$file_size = $_FILES['image']['size']; 	
	$file_tem_loc = $_FILES['image']['tmp_name'];

	$rand = rand(1000,9999);
	$image = "$rand $id";

	$dir = "product-images";


	if ($update_image == 'Yes') {
		$old_image = return_info($conn,'products','image','id',$id);
		
		if (unlink($dir.'/'.$old_image)) {
			alert("$old_image has been deleted");
		}
		else {
			alert("$old_image cannot be deleted due to an error");

		}
		$image = image_process($conn,$dir,$image,$file_type,$file_size,$file_tem_loc);
		update_info($conn,$dbname,'products','image','id',$image,$id);
	}

	update_info($conn,$dbname,'products','name','id',$name,$id);
	update_info($conn,$dbname,'products','stock','id',$stock,$id);
	update_info($conn,$dbname,'products','status','id',$status,$id);
	update_info($conn,$dbname,'products','rank','id',$rank,$id);
	update_info($conn,$dbname,'products','rating','id',$rating,$id);
	update_info($conn,$dbname,'products','store','id',$store,$id);
	update_info($conn,$dbname,'products','price','id',$price,$id);
	update_info($conn,$dbname,'products','catagory','id',$catagory,$id);
	update_info($conn,$dbname,'products','description','id',$description,$id);
	update_info($conn,$dbname,'products','long_description','id',$long_description,$id);

	alert("Product Updated. Please wait one minute then refresh");
	redirect_back();
	
}

if (isset($_POST['forgot_password'])) {
	$email = post_check("email");
	$email = sanitizeString($email);
	$last_email_verification_code = return_info($conn,'users','last_email_verification_code','email',"$email");

	send_email('noreply@kingproteas.com',"$email","Client",'Kingproteas password reset',"Did you forget your password? Click here to reset your password. Your reset code is $last_email_verification_code <a href='https://kingproteas.com/reset-password-page.php'>Reset password</a>");
	alert('Follow the instructions sent to your email address to log in');

	$location = "https://kingproteas.com/reset-password-page.php";
	go_to($location);
	
}

if (isset($_POST['order_status'])) {
	$id = post_check("id");
	$id = sanitizeString($id);
	$status = post_check("status");
	$status = sanitizeString($status);
	$notice = post_check("notice");
	$notice = sanitizeString($notice);
	$staff_user = post_check("staff_user");
	$staff_user = sanitizeString($staff_user);
	$date = " on " . date("Y-m-d @ h:i:s a");
	$name = return_info($conn,'orders','name','id',"$id");
	$email = return_info($conn,'orders','email','id',"$id");
	update_info($conn,$dbname,'orders','status','id',$status,$id);
	update_info($conn,$dbname,'orders','staff_user','id',$staff_user . $date,$id);
	update_info($conn,$dbname,'orders','notice','id',$notice,$id);

	// send_email('noreply@kingproteas.com',"$email","$name",'Kingproteas delivery',"Hello $name, We have processed your order. #$id <br><br><a href='https://kingproteas.com/'>Kingproteas</a>");

	alert('Updated');
	redirect_back();
}

if (isset($_POST['reset_password'])) {
	$email = post_check("email");
	$email = sanitizeString($email);
	$code = post_check("code");
	$code = sanitizeString($code);
	$password = post_check("password");
	$password = sanitizeString($password);
	$confirm_password = post_check("confirm_password");
	$confirm_password = sanitizeString($confirm_password);
	confirm_match($password,$confirm_password);
	$last_email_verification_code = return_info($conn,'users','last_email_verification_code','email',$email);

	if ($last_email_verification_code == $code) {

		$password = password_hash($password, PASSWORD_DEFAULT);
		update_info($conn,$dbname,'users','password','email',$password,$email);
	} else{
		alert("Code is incorrect");
		redirect_back();
	}
	send_email('noreply@kingproteas.com',"$email","Client",'Kingproteas password reset',"Your password was reset successfully. <a href='https://kingproteas.com/'>Kingproteas</a>");
	$new_code = rand(1000,9999);
	update_info($conn,$dbname,'users','last_email_verification_code','email',$new_code,$email);
	alert('Password reset you may now login.');
	$location = "https://kingproteas.com/";
	go_to($location);
}

if (isset($_POST['verify_me'])) {
	$date = "Email verified on " . date("Y-m-d @ h:i:s a");
	$email = post_check("email");
	$email = sanitizeString($email);
	$code = post_check("code");
	$code = sanitizeString($code);
	$last_email_verification_code = return_info($conn,'users','last_email_verification_code','email',$email);
	$password = return_info($conn,'users','password','email',$email);

	if ($last_email_verification_code == $code) {
		update_info($conn,$dbname,'users','email_verified','email',$date,$email);
	} else{
		alert("Code's do not match the email you entered");
		redirect_back();
	}

	alert("Thank you. Your email has been verified.");
	//////////////////////////////////
	pair_for_login($conn,'users',"email",$email,"password",$password);
	$logs_id = rand(10000,99999);
	$name =  $_SESSION['name'];
	$email = $_SESSION['email'];
	// $datetime = "Date " . date("Y-m-d @ h:i:s a");
	// $ipaddress = get_ipaddress();
	$ipnumber = get_ipnumber();
	// $ipaddress = implode(" ",$ipaddress);
	$device = get_device();
	insert_info($conn,'','logs_in','id',$logs_id);
	update_info($conn,'','logs_in','name','id',$name,$logs_id);
	update_info($conn,'','logs_in','device','id',$device,$logs_id);
	update_info($conn,'','logs_in','ipnumber','id',$ipnumber,$logs_id);
	update_info($conn,'','logs_in','email','id',$email,$logs_id);
	// update_info($conn,'','logs_in','ipaddress','id',$ipaddress,$logs_id);
	$location = "https://kingproteas.com/shopping-cart-page.php";
	go_to($location);
	//////////////////////////////////
}

if (isset($_POST['new_product'])) {
	$session_details = check_login_email();

	if ($session_details[0] == 'none') {
		$location = "https://kingproteas.com/";
		go_to($location);
	} 

	$id = rand(1000,9999);

	$name = post_check("name");
	$name = sanitizeString($name);
	$price = post_check("price");
	$price = sanitizeString($price);
	$description = post_check("description");
	$long_description =  filter_var($description, FILTER_SANITIZE_STRING);

	$long_description = post_check("long_description");
	$long_description =  filter_var($long_description, FILTER_SANITIZE_STRING);
	$store = post_check("store");
	$store = sanitizeString($store);
	$catagory = post_check("catagory");
	$catagory = sanitizeString($catagory);
	$rank = post_check("rank");
	$rank = sanitizeString($rank);
	$rating = post_check("rating");
	$rating = sanitizeString($rating);

	$price = get_number($price);

	$original_file_name = $_FILES['image']['name'];
	$file_type = $_FILES['image']['type'];
	$image = sanitizeString($original_file_name);
	$file_type = sanitizeString($file_type);
	$file_size = $_FILES['image']['size']; 	
	$file_tem_loc = $_FILES['image']['tmp_name'];

	$rand = rand(1000,9999);
	$image = "$rand $id";

	check_if_exists($conn,$dbname,"products",'id',$id);

	$dir = "product-images";

	$image = image_process($conn,$dir,$image,$file_type,$file_size,$file_tem_loc);

	insert_info($conn,$dbname,'products','id',$id);
	update_info($conn,$dbname,'products','email','id',$session_details[0],$id);
	update_info($conn,$dbname,'products','name','id',$name,$id);
	update_info($conn,$dbname,'products','store','id',$store,$id);
	update_info($conn,$dbname,'products','price','id',$price,$id);
	update_info($conn,$dbname,'products','catagory','id',$catagory,$id);
	update_info($conn,$dbname,'products','description','id',$description,$id);
	update_info($conn,$dbname,'products','image','id',$image,$id);
	update_info($conn,$dbname,'products','rank','id',$rank,$id);
	update_info($conn,$dbname,'products','rating','id',$rating,$id);
	update_info($conn,$dbname,'products','long_description','id',$long_description,$id);

	alert("Upload successful.");

	// send_email('kpautoreplyservice@gmail.com',"$session_details[0]","$session_details[1]",'New upload',"New upload. product name: \"$name\" <br> Price: \"$price\" <br> Description: \"$description\"<br> Catagory: \"$catagory\"<br> product id: \"$id\"<br> created on
	// 	<a href='https://kingproteas.com/index.php'>Kingproteas</a>
	// 	");

	$location ="https://kingproteas.com/forms/upload-form.php";
	go_to($location);
}

alert('Please return and try again, if the problem persits please contact support.');
$location = "https://kingproteas.com/index.php";
go_to($location);
?>
