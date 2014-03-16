<?php
// Fetch the special auth key that you get when you login
function getAuthKey() {
	$data = file_get_contents("http://webdevrefinery.com/forums/index.php?app=core&module=global&section=login");
	return extractData($data, "auth_key' value='", "' />", 1);
}

/**
 * @param $id, id of the user
 * @param $username, username of the user
 * @return string, a formatted string of the username enclosed in BBCode coloring
 *
 * This function is the same as userColor(), except it returns the BBCode equivalent of the HTML code.
 */

function userBBColor($id, $username) {
    if (in_array($id, array(1, 2))) {
        return "[color=red][b]" . $username . "[/b][/color]";
    } else if (in_array($id, array(602, 3291, 3008, 5574, 4637))) {
        return "[color=#f94][b]" . $username . "[/b][/color]";
    } else {
        return $username;
    }
}

/**
 * @param $image, the path to the image you wish to upload.
 * @return string, returns the URL of the image
 * This function uploads an image to imgur, and returns the URL to that image.
 */

function uploadImage($image) {

    $client_id = "525cd0d8daad6de";
    $image = file_get_contents($image);

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, 'https://api.imgur.com/3/image.json');
    curl_setopt($ch, CURLOPT_POST, TRUE);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
    curl_setopt($ch, CURLOPT_HTTPHEADER, array('Authorization: Client-ID ' . $client_id));
    curl_setopt($ch, CURLOPT_POSTFIELDS, array('image' => base64_encode($image)));
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);

    $reply = curl_exec($ch);
    curl_close($ch);

    $reply = json_decode($reply);
    return $reply->data->link;

}

/**
 * @param $username string, username to post with
 * @param $password string, password of the user to post with
 * @param $title, title of the post
 * @param $msg, the post contents
 * @return mixed, string, the url of the post
 */

function post($username, $password, $title, $msg) {
	############# LOGIN #############

	$loginUrl = 'http://webdevrefinery.com/forums/index.php?app=core&module=global&section=login&do=process';
	$refer = 'a';
	$auth = getAuthKey();

	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, $loginUrl);
	curl_setopt($ch, CURLOPT_POST, 1);

	// Post values
	curl_setopt($ch, CURLOPT_POSTFIELDS, 'ips_username='.$username.'&ips_password='.$password.'&auth_key='.$auth.'&referer='.$refer.'rememberMe=1');
	 
	// Store cookies (don't think it's needed though...)
	curl_setopt($ch, CURLOPT_COOKIEJAR, realpath('cookie.txt'));

	// Return content
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

	// Execute
	curl_exec($ch);

	#################################
	# LOGIN IS COMPLETE, NOW WE POST
	 
	// Get contents of new post to general discussion page
	curl_setopt($ch, CURLOPT_URL, 'http://webdevrefinery.com/forums/index.php?app=forums&module=post&section=post&do=new_post&f=22');
	$content = curl_exec($ch);

	// Extract specific data values that we have to $_POST with
	$data["s"] = extractData($content, "name='s' value='", "' />");
	$data["p"] = extractData($content, "name='p' value='", "' />");
	$data["t"] = extractData($content, "name='t' value='", "' />");
	$data["f"] = extractData($content, "name='f' value='", "' />");
	$data["attach_post_key"] = extractData($content, "name='attach_post_key' value='", "' />");
	$data["auth_key"] = extractData($content, "name='auth_key' value='", "' />");


	#################################
	####### POSTING SECTION #########

	$postingUrl = 'http://webdevrefinery.com/forums/index.php?';
	$refer = 'http://webdevrefinery.com/forums/index.php?';
	$auth = $data["auth_key"];

	// Since form type is multidata, we have to submit it as an array
	$postfields = array(
		"s" => $data["s"],
		"p" => $data["p"],
		"t" => $data["t"],
		"f" => $data["f"],
		"attach_post_key" => $data["attach_post_key"],
		"auth_key" => $data["auth_key"],
		"TopicTitle" => $title,
		"Post" => "<p>" . $msg . "</p>",
		"poll_question" => "",
		"enableemo" => "yes",
		"enablesig" => "yes",
		"ipsTags" => "wdr-contrib",
		"isRte" => "1",
		"noSmilies" => "0",
		"noCKEditor" => "0",
		"st" => "0",
		"app" => "forums",
		"module" => "post",
		"section" => "post",
		"do" => "new_post_do",
		"parent_id" => "0",
		"removeattachid" => "0",
		"return" => "0",
		"_from" => "",
		"dosubmit" => "Post New Topic"
		);

	curl_setopt($ch, CURLOPT_URL, $postingUrl);
	curl_setopt($ch, CURLOPT_POST, 1);

	// Post values
	curl_setopt($ch, CURLOPT_POSTFIELDS, $postfields);
	 
	// Store cookies (don't think it's needed though...)
	curl_setopt($ch, CURLOPT_COOKIEJAR, realpath('cookie.txt'));

    // Get the headers returned as well, so the new post URL can be found
    curl_setopt($ch, CURLOPT_HEADER, TRUE);

	// Return content
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

	// Execute
	$return = curl_exec($ch);

	#################################
	# POST WAS POSTED SUCCESSFULLY?
	# W00T IT WORKS!

    // Get the location header from the HTTP response

    $url = explode("\n", substr($return, strpos($return, "Location: ") + 10));

    $post_location = $url[0];

    return $post_location;
	 
}
?>
