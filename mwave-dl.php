<?php
	define("VOD_INFO_URL", "http://mwave.interest.me/onair/vod_info.m?id=%d");
	define("HD_CORE", "3.0.3");

	function run_adobehds($manifest, $delete = true) {
		// warning: modifies global argc, argv parameters
		global $argc, $argv;

		$new_argv = array("AdobeHDS.php", "--manifest", $manifest);
		if ($delete) array_push($new_argv, "--delete");
		if ($argc > 4) $new_argv = array_merge($new_argv, array_slice($argv, 4));
		$argv = $new_argv;
		$argc = count($argv);

		require("AdobeHDS.php");
	}

	function generate_g_param($length = 12) {
		return substr(str_shuffle("ABCDEFGHIJKLMNOPQRSTUVWXYZ"), 0, $length);
	}

	function get_id_from_html($html) {
		libxml_use_internal_errors(true);
		$dom = new DomDocument;
		$dom->loadHTML($html);
		libxml_clear_errors();

		$pattern = '/clip_id=([0-9]+)/';
		foreach ($dom->getElementsByTagName('iframe') as $iframe) {
			preg_match($pattern, $iframe->getAttribute('src'), $matches);
			
			if (count($matches) > 0) return $matches[1];
		}

		return false;
	}

	function curl_get($url, array $options = array()) {
		$ch = curl_init();
		$defaults = array(
			CURLOPT_URL => $url,
			CURLOPT_HEADER => 0,
			CURLOPT_RETURNTRANSFER => 1,
          	CURLOPT_FOLLOWLOCATION => 1
       	);

       	curl_setopt_array($ch, ($options + $defaults));
       	$result = curl_exec($ch);

       	if (!$result) trigger_error(curl_error($ch));
       	curl_close($ch);

       	return $result;
	}

	if ($argc > 1) {
		global $argc, $argv;
		$page_url = $argv[1];
		if ($argc > 3 && strtolower($argv[2]) == "--resolution")
			$resolution = strtolower($argv[3]);
		else
			$resolution = false;

		$response = curl_get($page_url);
		$vod_id = get_id_from_html($response) 
			or die("ERROR: The Meet & Greet ID could not be found. It is either private or Mwave changed their site layout.");
		$vod_info = json_decode(curl_get(sprintf(VOD_INFO_URL, $vod_id)), true);
		$quality_opts = $vod_info['cdn'];

		print("Title: {$vod_info['title']}\nViews: {$vod_info['hit']}\n");
		if (!$resolution) {
			print("\nAvailable quality options:\n");
			foreach ($quality_opts as $quality) {
				print("\t{$quality['name']}, bitrate: {$quality['bit']}\n");
			}
		}
		else {
			$download_url = false;
			foreach ($quality_opts as $quality) {
				if ($quality['name'] == $resolution) $download_url = $quality['url']; 
			}

			if (!$download_url) die("Quality option " . $resolution . " is not available.");

			$params = array(
				'g' => generate_g_param(),
				'hdcore' => HD_CORE
				);
			$manifest_url = json_decode(curl_get($download_url), true)['fileurl'] . '&' . http_build_query($params);
			run_adobehds($manifest_url);
		}
	}
	else {
		global $argc, $argv;

		print("Usage: " . $argv[0] . " <url> [--resolution {360p, 480p, 720p, 1080p}] [adobehds_params]\n");
		$argv = array("AdobeHDS.php", "--help");
		$argc = 2;
		include("AdobeHDS.php");
	}
?>