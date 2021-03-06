<?php

/*
 *---------------------------------------------------------------
 * CONFIGURATION
 *---------------------------------------------------------------
 *
 * Different variables to be configured
 */

// The directory where your pointclouds are saved
define("DATAFOLDER", "data");

// The filenames of the pointclouds
define("PCFILE", "pc.csv");
define("PCINFO", "info.csv");
define("PCIMG", "img.png");
define("MESHVERTICESFILE", "mesh_vertices.csv");
define("MESHFACESFILE", "mesh_faces.csv");

// Can be development or production
define("ENVIRONMENT", "production");

// The development url
define("DEVELURL", "http://localhost/2016-watertight-meshes-v01/");

// The production url
define("PRODURL", "http://robotics.jacobs-university.de/datasets/2016-watertight-meshes-v01/");




/*
 *---------------------------------------------------------------
 * URL PARSER
 *---------------------------------------------------------------
 *
 * Parses the url and load the correct page.
 */

// Parse the url
include('app/helpers/url.php');
$pathInfo = parsePath();

// Load pages
if (sizeof($pathInfo['call_parts']) > 0) {
  $page = $pathInfo['call_parts'][0];
  switch ($page) {
    case 'view':
      $pcFolder = $pathInfo['call_parts'][1];
      include('app/views/viewer.php');
      break;
    case '404':
      include('app/views/404.php');
      break;
    default:
      include('app/views/home.php');
      break;
  }
}
else {
  include('app/views/home.php');
}

?>
