<?php
/*
  $Id$

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2014 osCommerce

  Released under the GNU General Public License
*/
?>
<!DOCTYPE html>
<html <?php echo HTML_PARAMS; ?>>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=<?php echo CHARSET; ?>">
<meta name="robots" content="noindex,nofollow">
<title><?php echo TITLE; ?></title>
<base href="<?php echo ($request_type == 'SSL') ? HTTPS_SERVER . DIR_WS_HTTPS_ADMIN : HTTP_SERVER . DIR_WS_ADMIN; ?>" />

<link rel="stylesheet" type="text/css" href="<?php echo tep_catalog_href_link('ext/bootstrap/css/bootstrap.css', '', 'SSL'); ?>">

<link rel="stylesheet" href="//maxcdn.bootstrapcdn.com/font-awesome/4.3.0/css/font-awesome.min.css">

<!-- <link rel="stylesheet" type="text/css" href="includes/stylesheet.css"> -->

<link rel="stylesheet" type="text/css" href="ext/css/custom.css">

<script type="text/javascript" src="includes/general.js"></script>
<?php
  if (isset($templateModules['header']) && !empty($templateModules['header'])) {
    foreach ($templateModules['header'] as $key => $value) {
      echo $value . "\n";
    }
  }
?>
</head>
<body>

<?php include(DIR_WS_INCLUDES . 'header.php'); ?>

<div class="container-fluid">
  <div class="row">

    <section id="bodyContent" class="col-xs-12">
<?php
  if ($messageStack->size > 0) {
    echo $messageStack->output();
  }
?>
