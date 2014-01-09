<?php
// Fetch the special auth key that you get when you login
function getAuthKey() {
	$data = file_get_contents("http://webdevrefinery.com/forums/index.php?app=core&module=global&section=login");
	return extractData($data, "auth_key' value='", "' />", 1);
}

function extractData($data, $search, $ending, $specific = -1) {
	$matches = findall($search, $data);
	foreach ($matches as &$val) {
		$offset = 0;
		$val += strlen($search);
        while (substr($data, $val+$offset, strlen($ending)) != $ending) {
            $offset++;
        }
		$val = substr($data, $val, $offset);
	}
    if ($matches == false) {
        return "Error, no matches found.";
    }

    if ($specific == -1) {
        if (count($matches) == 1) {
            return $matches[0];
        }
	    return $matches;
    }
    return $matches[$specific-1];
}

// Function I found online
// Rewrote it to look nicer (so many comments in the last version!)
function findall($needle, $haystack) { 
    $buffer = '';
    $pos = 0;
    $end = strlen($haystack);
    $getchar = '';
    $needlelen = strlen($needle); 
    $found = array();
    
    while ($pos < $end) { 
        $getchar = substr($haystack, $pos, 1);
        if ($getchar != "\\n" || $buffer < $needlelen) { 
            $buffer = $buffer . $getchar;
            if (strlen($buffer) > $needlelen) { 
                $buffer = substr($buffer, -$needlelen);
            }
            if ($buffer == $needle) { 
                $found[] = $pos - $needlelen + 1;
            } 
        } 
        $pos++;
    } 
    if (array_key_exists(0, $found)) { 
        return $found;
    }
    return false;
}

// Post to the General Discussion forum as the specified user with the specified message
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

	// Return content
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

	// Execute
	curl_exec($ch);

	#################################
	# POST WAS POSTED SUCCESSFULLY?
	# W00T IT WORKS!
	 
}
?>
