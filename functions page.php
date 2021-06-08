<?php
// sever
$dbsevername = "hidden";
$dbusername = "hidden";
$dbpassword = "hidden";
$dbname = "hidden";
$conn = mysqli_connect($dbsevername, $dbusername, $dbpassword);

mysqli_select_db($conn, $dbname);


use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\SMTP;

require_once "PHPMailer/src/PHPMailer.php";
require_once "PHPMailer/src/SMTP.php";
require_once "PHPMailer/src/Exception.php";

function stop(){
	die();
}

function sanitizeString($var) {    
	if (get_magic_quotes_gpc())

		$var = stripsloashes($var);   
	$var = htmlentities($var);    
	$var = strip_tags($var); 

	if (strlen($var) > 400 ) {
		stop("Charachter break fatal error"); 
	}
	$var = addslashes($var);
	return $var; 
}


function go_to($var){

	echo("<script type=\"text/javascript\">
		window.location.replace(\"$var\");
		</script>");
	stop();
}

function redirect_back() {
	echo("<script type=\"text/javascript\">
		window.history.go(-1);
		</script>");
	stop();
}

function reload() {
	echo("<script type=\"text/javascript\">
		location.reload();
		</script>");
}

function check_if_empty($var) {
	if (empty($var)) {
		alert("$var input left blank.");
		redirect_back();
		stop();
	}
}


function alert($var) {
	echo("<script type=\"text/javascript\">
		alert(\"$var\");
		</script>");
}

function logout() {
	session_destroy();
	$location = "index.php";
	alert("You have logged out");
	go_to($location);
}

function post_check($var){
	if (!isset($_POST[$var])) {
		alert("$var input left blank.");
		redirect_back();
	}
	$var = sanitizeString($_POST[$var]);
	check_if_empty($var);
	return $var;
}

function get_check($var){
	if (!isset($_GET[$var])) {
		alert("$var input left blank.");
		redirect_back();
	}
	$var = sanitizeString($_GET[$var]);
	check_if_empty($var);
	return $var;
}

function confirm_match($var,$var2) {
	if ($var === $var2) {
		//check
	} else {
		alert("Password do not match");
		redirect_back();
		stop();
	}
}

function check_login_email(){

	$email = $_SESSION['email'];

	if (!isset($email)) {
		$location = "index.php";
		alert("Please login to continue");
		go_to($location);
	}

	return $email;
}

function check_login_name(){

	$name = $_SESSION['name'];

	if (!isset($name)) {
		$location = "index.php";
		alert("Please login to continue");
		go_to($location);
	}

	return $name;
}

function check_if_exists($varconn,$dbname,$table,$row_title,$info){

	$query = "SELECT `$row_title` FROM `$table` WHERE `$row_title` = '$info';";

	// echo"$query"; 
	// stop("$query");

	$result = mysqli_query($varconn, $query) or die(mysqli_error($varconn));

	$row = mysqli_num_rows($result);
	if ($row > 0) {

		alert("The $info credential already exist");
		redirect_back();
		stop();

	}
}

function get_result($varconn,$dbname,$table,$row_title,$info){

	$query = "SELECT `$row_title` FROM `$table` WHERE `$row_title` = '$info';";

	// echo"$query"; 
	// stop("$query");

	$result = mysqli_query($varconn, $query) or die(mysqli_error($varconn));

	$row = mysqli_num_rows($result);
	if ($row > 0) {

		return 'yes';
	}	else {
		return 'no';
	}
}

function insert_info($varconn,$dbname,$table,$row_title,$info){
	
	$query = "INSERT INTO `$table` (`$row_title`) VALUES ('$info');";

	$result = mysqli_query($varconn, $query) or die(mysqli_error($varconn));

	// stop("$query");

}


function update_info($varconn,$dbname,$table,$row_title,$row_title_2,$info,$email){
	
	$query = "UPDATE `$table` SET `$row_title` = '$info' WHERE `$table`.`$row_title_2` = '$email';";

	// echo"$query"; 
	// stop("$query");

	$result = mysqli_query($varconn, $query) or die(mysqli_error($varconn));

	// stop("$query");
}

function pair_for_login($varconn,$table,$email,$email_info,$security_key,$security_key_info) {

	$query = "SELECT * FROM $table WHERE $email = '$email_info';";
	
	$result = mysqli_query($varconn, $query) or die(mysqli_error($varconn));
	
	//echo "$query";
	// stop();

	$row = mysqli_num_rows($result);
	if ($row == 0) {
		alert("No account is registerd to this email");
		redirect_back();
		stop();
	}

	$status = return_info($varconn,'users','verificated','email',$email_info);

	if ($status == 'no') {
		alert('To continue, please verify your account via email.');
		redirect_back();
	}

	$active = "";
	$security_key = "";
	
	while ($row = mysqli_fetch_assoc($result)) {
		$active = $row['active'];
		$security_key = $row['security_key'];
	}
	

	if ($active == "no" ){
		alert("Not allowed");
		redirect_back();
	}

	if (password_verify($security_key_info, $security_key)){
		$security_key = $security_key_info;
	}
	
	if ($security_key !== $security_key_info ){
		alert("Password incorrect");
		redirect_back();
		stop();
	}

	$query = "SELECT * FROM $table WHERE $email = '$email_info';";

	$result = mysqli_query($varconn, $query) or die(mysqli_error($varconn));


	$active = "";
	
	while ($row = mysqli_fetch_assoc($result)) {
		$active = $row['active'];
	}
	

	if ($active == "no" ){
		alert('Not allowed');
		$location = "index.php";
		go_to($location);
	}

	$query = "SELECT * FROM $table WHERE $email = '$email_info';";

	$result = mysqli_query($varconn, $query) or die(mysqli_error($varconn));

	while ($row = mysqli_fetch_assoc($result))
	{ 

		$email = $_SESSION['email'] = $row['email'];
		$name = $_SESSION['name'] = $row['name'];
	}

	alert("You have been logged in");

	$location = "home page.php";
	// https://github.com/alecshelembe
	go_to($location);

}

function return_info($varconn,$table,$row_title,$row_title_2,$email){
	
	$query = "SELECT $row_title FROM $table WHERE $row_title_2 = '$email' ;";
	
	$result = mysqli_query($varconn, $query);
	
	
	while ($row = mysqli_fetch_assoc($result)) {
		$value = $row["$row_title"];
	}
	return $value;
}

function show_reviews($varconn,$dbname,$table){

	$query = "SELECT * FROM `$table`;";
	
// 	echo"$query"; 

	$result = mysqli_query($varconn, $query);
	
	while ($row = mysqli_fetch_assoc($result)) {
		$rating = $row['rating'];
		$name = $row['name'];
		$comment = $row['comment'];

		echo"$name $comment $rating <br>";

	}
}

function show_shops($varconn,$dbname,$table,$value){

	$query = "SELECT * FROM `$table` WHERE `active` = '$value';";
	
	// echo"$query"; 
	// stop();

	$result = mysqli_query($varconn, $query);
	
	while ($row = mysqli_fetch_assoc($result)) {
		$name = $row['name'];
		$city = $row['city'];
		$province = $row['province'];
		
		echo "<form action=\"site page.php\" method=\"get\">
		<input type=\"submit\" name=\"site\" value =\"$name\">
		</form>";

	}


}

function remove($varconn,$dbname,$table,$row_title,$info){

	$query = "DELETE FROM `$table` WHERE `$table`.`$row_title` = '$info';";

	$result = mysqli_query($varconn, $query) or die(mysqli_error($varconn));

	echo("$query");
	stop();

}

function show_product($varconn,$dbname,$table,$value,$shop){

	$query = "SELECT * FROM `$table` WHERE `active` = '$value' and `shop` = '$shop' and `state` = 'current';";

	
	// echo"$query"; 
	// stop();

	$result = mysqli_query($varconn, $query);
	
	$row = mysqli_num_rows($result);
	if ($row == 0) {
		echo "";
	} else {

		while ($row = mysqli_fetch_assoc($result)) {
			$shop = $row['shop'];
			$name = $row['name'];
			$description = $row['description'];
			$image = $row['image'];
			$price = $row['price'];
			$id = $row['id'];

			$product = array("$shop", "$name", "$description", "$image", "$price");

			$shop = $product[0];
			$name = $product[1];
			$description = $product[2];
			$image = $product[3];
			$price = $product[4];

			echo"<h3></h3>
			<div class='product_circle'>
			<img src='product images/$image' class='product_picture'>
			<p class='product_tag'>
			<span class='product_tag_description'>Name: </span>$name<br>
			<span class='product_tag_description'>Price: </span>$price<br>
			<span class='product_tag_description'>Description: </span>$description<br>
			<span class='product_tag_description small_font'>Id: $id</span>
			</p>
			<input type=\"button\" name=\"\" onClick='add(\"$id\");' value=\"Add to cart\">
			<input type=\"button\" name=\"\" onClick='remove(\"$id\");' value=\"Remove\">
			</div>";

			return $product; 

		}

	}
}

function show_product_on_menu($varconn,$dbname,$table,$value,$shop){

	$query = "SELECT * FROM `$table` WHERE `active` = '$value' and `shop` = '$shop';";

	
	// echo"$query"; 
	// stop();

	$result = mysqli_query($varconn, $query);
	
	$row = mysqli_num_rows($result);
	if ($row == 0) {
		echo "";
	} else {

		while ($row = mysqli_fetch_assoc($result)) {
			$shop = $row['shop'];
			$name = $row['name'];
			$description = $row['description'];
			$image = $row['image'];
			$price = $row['price'];
			$id = $row['id'];

			$product = array("$shop", "$name", "$description", "$image", "$price");

			$shop = $product[0];
			$name = $product[1];
			$description = $product[2];
			$image = $product[3];
			$price = $product[4];

			echo"<h3></h3>
			<div class='product_circle_on_menu'>
			<img src='product images/$image' class='product_picture'>
			<p class='product_tag'>
			<span class='product_tag_description'>Name: </span>$name<br>
			<span class='product_tag_description'>Price: </span>$price<br>
			<span class='product_tag_description'>Description: </span>$description<br>
			<span class='product_tag_description small_font'>Id: $id</span>
			</p>
			</div>";

			return $product; 

		}

	}
}

function image_process($varconn,$dir,$image,$file_type,$file_size,$file_tem_loc){


	if( is_dir($dir) === false )
	{
		mkdir($dir);
	}
	switch($file_type)
	{
		case 'image/jpeg':  $ext = 'jpg';   break;
		case 'image/gif':   $ext = 'gif';   break;
		case 'image/png':   $ext = 'png';   break;
		case 'image/tiff':  $ext = 'tiff';  break;	
		default:       
		alert("$image is not a valid image file $file_type unallowed");
		redirect_back();
	} 

	if ($ext)
	{	

		$file_store = "$dir/$image";

		move_uploaded_file($file_tem_loc, $file_store);

		return "$image";

	}
	else
	{
		alert("Something went wrong with the upload. Try a different one.");
		redirect_back();

	}

}

function send_email($sender_email,$reciever_email,$reciever_name,$subject,$body){
	////////////////////////////////////////////////////////////////

	//Instantiation and passing `true` enables exceptions
	$mail = new PHPMailer(true);

	try {
    //Server settings
    // $mail->SMTPDebug = SMTP::DEBUG_SERVER;                      //Enable verbose debug output
    $mail->isSMTP();                                            //Send using SMTP
    $mail->Host       = 'smtp.gmail.com';                     //Set the SMTP server to send through
    $mail->SMTPAuth   = true;                                   //Enable SMTP authentication
    $mail->Username   = "$sender_email";                     //SMTP username
    $mail->Password   = 'hidden';                               //SMTP password
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;         //Enable TLS encryption; `PHPMailer::ENCRYPTION_SMTPS` encouraged
    $mail->Port       = 'hidden';                                    //TCP port to connect to, use 465 for `PHPMailer::ENCRYPTION_SMTPS` above

    //Recipients
    $mail->setFrom("$sender_email", 'Noreply');
    $mail->addAddress("$reciever_email");     //Add a recipient
    $mail->addAddress("$sender_email");               //Name is optional
    //$mail->addReplyTo('info@example.com', 'Information');
    //$mail->addCC('cc@example.com');
    //$mail->addBCC('bcc@example.com');

    //Attachments
    //$mail->addAttachment('/var/tmp/file.tar.gz');         //Add attachments
    //$mail->addAttachment('/tmp/image.jpg', 'new.jpg');    //Optional name

    //Content
    $mail->isHTML(true);                                  //Set email format to HTML
    $mail->Subject = "$subject";
    $mail->Body    = "$body";
    $mail->AltBody = "$body";

    $mail->send();
    alert("We sent you an email, please see your inbox");
} catch (Exception $e) {
	alert("Oops. Something went wrong. You might not recieve an email");
}

	///////////////////////////////////////////////////////////////
}
