<?php
   /*
   Plugin Name: md-github
   Description: A plugin to inject markdown files directly into a post from Github
   Version: 0.1
   Author: Nils Nolde
   Author URI: https://gis-ops.com
   License: MIT
   */

function md_github_handler($atts) {
  //get API path from file URL
  $json = get_api_path($atts, 'file');
  $md_output = base64_decode($json['content']);
  //send back text to replace shortcode in post
  return $md_output;
}

function md_github_checkout($atts) {
  $last_update_htnl = get_github_checkout($atts, 'checkout');
  return $last_update_htnl;
}

function get_api_path($atts, $method) {

  extract(shortcode_atts(array(
          'url' => "",
        ), $atts
      )
   );

  $url_list = explode('/', $url);
  $owner = $url_list[3];
  $repo = $url_list[4];
  $branch = $url_list[6];
  $path = implode("/", array_slice($url_list, 7));

  if ($method === 'file') {
    $request_url = 'https://api.github.com/repos/'.$owner.'/'.$repo.'/contents/'.$path.'?ref='.$branch.;
  } else {
    $request_url = 'https://api.github.com/repos/'.$owner.'/'.$repo.'/commits/'.$branch.'?path='. $path.'&page=1';
  }

  $context_params = array(
    'http' => array(
      'method' => 'GET',
      'timeout' => 1
    )
  );

  $res = file_get_contents($request_url, FALSE, stream_context_create($context_params));
  $json = json_decode($res, true);

  return $json;
}

function get_github_checkout($json) {

  $datetime = $json['commit']['committer']['date'];

  $max_datetime = strtotime($datetime);
  $max_datetime_f = date('d/m/Y H:i:s', $max_datetime);

  $checkout_label = '<div class="markdown-github"
      <div class="markdown-github-labels">
        <label class="github-link">
          <a href="'.$json.'" target="_blank">Check it out on github</a>
          <label class="github-last-update"> Last updated: '.$max_datetime_f.'</label>
        </label>
      </div>
    </div>';

  return $checkout_label;
  }

function md_github_enqueue_style() {
	wp_enqueue_style( 'md-github', plugins_url( '/css/md-github.css', __FILE__ ));
}
add_action( 'wp_enqueue_scripts', 'md_github_enqueue_style' );
add_shortcode('checkout_github', "md_github_checkout")
add_shortcode("md_github", "md_github_handler");
