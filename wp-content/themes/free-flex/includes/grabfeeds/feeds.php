<?php
	error_reporting(0);
	@ini_set('display_errors', 0);

	// =========================================================================
	// Set Variables
	// =========================================================================
	$feeds = array();
	
	$current_time = time();

	$facebook_handle = get_option('facebook_handle');
	$facebook_feed_url = get_option('facebook_feed_url');
	$facebook_app_id = get_option('facebook_app_id');
	$facebook_secret = get_option('facebook_secret');
	
	$twitter_handle = get_option('twitter_handle');
	$twitter_access_token = get_option('twitter_access_token');
	$twitter_access_token_secret = get_option('twitter_access_token_secret');
	$twitter_consumer_key = get_option('twitter_consumer_key');
	$twitter_consumer_secret = get_option('twitter_consumer_secret');
	
	$rss_feed_1 = get_option('rss_feed_1');
	$rss_feed_2 = get_option('rss_feed_2');
	
	
	
	// =========================================================================
	// Fetch Facebook
	// =========================================================================		
	if ($facebook_feed_url)
	{
		$facebook = check_cache('facebook');
		if (!$facebook)
		{
			$facebook['time'] = time();
			$facebook['feed'] = get_rss($facebook_feed_url, 'facebook');
			$facebook['cache'] = 0;
		}
		else
		{
			$facebook['cache'] = 1;
		}	
	}
	else
	{
		$facebook = array('feed' => array());
	}
	
	// =========================================================================
	// Fetch RSS 1
	// =========================================================================
	if ($rss_feed_1)
	{
		$rss_first = check_cache('rss_feed_1');
		if (!$rss_first)
		{
			$rss_first['time'] = time();
			$rss_first['feed'] = get_rss($rss_feed_1, 'rss_feed_1');
			$rss_first['cache'] = 0;
		}
		else
		{
			$rss_first['cache'] = 1;
		}
	}
	else
	{
		$rss_feed_1 = array('feed' => array());
	}
	

	
	// =========================================================================
	// Fetch RSS 2
	// =========================================================================
	if ($rss_feed_2)
	{
		$rss_second = check_cache('rss_feed_2');
		if (!$rss_second)
		{
			$rss_second['time'] = time();
			$rss_second['feed'] = get_rss($rss_second, 'rss_feed_2');
			$rss_second['cache'] = 0;
		}
		else
		{
			$rss_second['cache'] = 1;
		}		
	}
	else
	{
		$rss_second = array('feed' => array());
	}

	
	// =========================================================================
	// Fetch Twitter
	// =========================================================================
	if ($twitter_access_token && $twitter_access_token_secret && $twitter_consumer_key && $twitter_consumer_secret)
	{
		$twitter = check_cache('twitter');
		if (!$twitter)
		{
			$twitter['time'] = time();
			$twitter['feed'] = get_twitter($twitter_handle, $twitter_access_token, $twitter_access_token_secret, $twitter_consumer_key, $twitter_consumer_secret);
			$twitter['cache'] = 0;
		}
		else
		{
			$twitter['cache'] = 1;
		}
	}
	else
	{
		$twitter = array('feed' => array());
	}

	
	// =========================================================================
	// DEBUG
	// =========================================================================
	/*
		echo '<pre>';
		print_r($facebook);
		print_r($twitter);
		print_r($rss_first);
		print_r($rss_second);
		exit;
	*/
			
	
	
	
	// =========================================================================
	// MERGE ARRAYS
	// =========================================================================
	if ($facebook['feed'] == FALSE)
		$facebook['feed'] = array();
	
	if ($twitter['feed'] == FALSE)
		$twitter['feed'] = array();
		
	if ($rss_feed_1['feed'] == FALSE)
		$rss_feed_1['feed'] = array();
		
	if ($rss_feed_2['feed'] == FALSE)
		$rss_reed_2['feed'] == array();
	
	$merged = array_merge($facebook['feed'], $twitter['feed'], $rss_first['feed'], $rss_second['feed']);
	
	$sorted = array();
	foreach ($merged as $item)
	{
		$sorted[$item['created_at']] = $item;
		$sorted[$item['created_at']]['created_at'] = human_time($item['created_at']);
	}
	krsort($sorted);
	
	
	// =========================================================================
	// OUTPUT
	// =========================================================================
	$feeds = $sorted;

	
	
	
	// =========================================================================
	// Functions
	// =========================================================================
	function get_twitter($twitter_handle, $oauth_access_token, $oauth_access_token_secret, $consumer_key, $consumer_secret, $count = 5)
	{
		require(dirname(__FILE__).'/../twitter/TwitterAPIExchange.php');
	
		$settings = array(
		    'oauth_access_token' => $oauth_access_token,
		    'oauth_access_token_secret' => $oauth_access_token_secret,
		    'consumer_key' => $consumer_key,
		    'consumer_secret' => $consumer_secret
		);
		
		$url = 'https://api.twitter.com/1.1/statuses/user_timeline.json';
		$getfield = '?screen_name='.$twitter_handle.'&count='.$count;
		$requestMethod = 'GET';
		$twitter = new TwitterAPIExchange($settings);
		
		$result = $twitter->setGetfield($getfield)
		             ->buildOauth($url, $requestMethod)
		             ->performRequest();
		
		$result = json_decode($result, TRUE);
		
		if ($result && !isset($result['errors']))
		{
			$current_feed = array();
			$i = 0;
			foreach ($result as $item)
			{
				$current_feed[$i]['title'] = $item['id_str'];
				$current_feed[$i]['link'] = 'https://twitter.com/'.$twitter_handle.'/status/'.$item['id_str'];
				$current_feed[$i]['author'] = $twitter_handle;
				$current_feed[$i]['created_at'] = strtotime($item['created_at']);
				$current_feed[$i]['description'] = format_description($item['text'], 'twitter');
				$current_feed[$i]['source'] = 'twitter';
				
				$i++; 
			}
			
			$feed_json = array();
			$feed_json['time'] = time();
			$feed_json['feed'] = $current_feed; 
			
			$feed_json = json_encode($feed_json);
			
			$path = realpath(dirname(__FILE__));
			file_put_contents($path.'/twitter.json', $feed_json);
			
			return $current_feed;
		}
		else
		{
			$feed_json = array();
			$feed_json['time'] = time();
			$feed_json['feed'] = FALSE;
		
			$feed_json = json_encode($feed_json);
		
			$path = realpath(dirname(__FILE__));
			file_put_contents($path.'/'.$source.'.json', $feed_json);

			return FALSE;
		}
	}
	
	
	function check_cache($source)
	{
		$cache_url = get_template_directory_uri().'/includes/grabfeeds/'.$source.'.json';
	
		$result = @file_get_contents($cache_url);
		$result = json_decode($result, TRUE);
		
		if ($result)
		{
			// CACHE EXISTS
			$cache_time = $result['time'];
			
			$difference = time() - $cache_time;

			// Is cache less than 5 minutes old?
			if ($difference <= 1)
			{
				// CACHE IS STILL GOOD
				return $result;
			}
			else
			{
				return FALSE;
			}		
		}
		else
		{
			// CACHE DOESN'T EXIST
			return FALSE;
		}
	}
	
	function get_rss($url, $source)
	{
		// CACHE DOESN'T EXIST
		$result = file_get_contents('https://ajax.googleapis.com/ajax/services/feed/load?v=2.0&q='.$url.'&num=5');
		$result = json_decode($result, TRUE);
		
		if ($result && isset($result['responseStatus']) && $result['responseStatus'] == 200)
		{	
			$current_feed = array();
			$i = 0;
			foreach ($result['responseData']['feed']['entries'] as $item)
			{	
				$current_feed[$i]['title'] = $item['title'];
				$current_feed[$i]['link'] = $item['link'];
				$current_feed[$i]['author'] = $item['author'];
				$current_feed[$i]['created_at'] = strtotime($item['publishedDate']);
				$current_feed[$i]['description'] = format_description($item['contentSnippet'], $source);
				$current_feed[$i]['source'] = $source; 
				
				$i++;
			}
			
			$feed_json = array();
			$feed_json['time'] = time();
			$feed_json['feed'] = $current_feed; 
			
			$feed_json = json_encode($feed_json);
			
			$path = realpath(dirname(__FILE__));
			file_put_contents($path.'/'.$source.'.json', $feed_json);
			
			return $current_feed;
		}
		else
		{
			$feed_json = array();
			$feed_json['time'] = time();
			$feed_json['feed'] = FALSE;
		
			$feed_json = json_encode($feed_json);
		
			$path = realpath(dirname(__FILE__));
			file_put_contents($path.'/'.$source.'.json', $feed_json);

			return FALSE;
		}
	}
	
	function human_time ($time) {
		$time = time() - $time;
		$tokens = array (
			31536000 => 'year',
			2592000 => 'month',
			604800 => 'week',
			86400 => 'day',
			3600 => 'hr',
			60 => 'min',
			1 => 'sec'
		);
	
		foreach ($tokens as $unit => $text) {
			if ($time < $unit) continue;
			$numberOfUnits = floor($time / $unit);
			return $numberOfUnits.' '.$text.(($numberOfUnits>1)?'s':'');
		}
	}

	
	function format_description($text, $source) {
		if ($source == 'facebook')
			$hash_url = 'https://www.facebook.com/hashtag/';
		else
			$hash_url = 'http://twitter.com/search?q=';
	
			$text = str_replace('&#039;', '\'', $text);
			$text = str_replace('&#8217;', '\'', $text);
	
			$text = preg_replace('@(https?://([-\w\.]+[-\w])+(:\d+)?(/([\w/_\.#-]*(\?\S+)?[^\.\s])?)?)@', '<a href="$1" target="_blank">$1</a>', $text);
			$text = preg_replace('#@([\\d\\w]+)#', '<a target="_blank" href="http://twitter.com/$1">$0</a>', $text);
			$text = preg_replace('/#([\\d\\w]+)/', '<a target="_blank" href="'.$hash_url.'$1">$0</a>', $text);
			$text = preg_replace('/[^(\x20-\x7F)]*/','', $text);
			
			return $text;

	}
	
	
