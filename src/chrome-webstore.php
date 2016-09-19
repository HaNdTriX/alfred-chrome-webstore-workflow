<?php

require_once('workflows.php');

function jsonp_decode($jsonp, $assoc = false) {
  if($jsonp[0] !== '[' && $jsonp[0] !== '{') {
     $jsonp = substr($jsonp, strpos($jsonp, '('));
  }
  return json_decode(trim($jsonp,'();'), $assoc);
}

function get_autocomplete_url($query) {
  return 'https://clients1.google.com/complete/search?client=partner&partnerid=002249742339192579153%3Aszkikz9ty5u&ds=cse&callback=window.cb&q=' . urlencode($query);
}

$wf = new Workflows();

$jsonp = $wf->request(get_autocomplete_url($query));
$jsonp = jsonp_decode(utf8_encode($jsonp));

$int = 1;
foreach( $jsonp[1] as $sugg ):
  $data = $sugg[0];
  $wf->result( $int.'.'.time(), "$data", "$data", 'Search Webstore for '.$data, 'icon.png'  );
  $int++;
endforeach;

$results = $wf->results();
if ( count( $results ) == 0 ):
  $wf->result( 'webstoresuggest', $query, 'No Suggestions', 'No search suggestions found. Search Webstore for '.$query, 'icon.png' );
endif;

echo $wf->toxml();
