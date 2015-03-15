<?php
/*
  $Id$

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2010 osCommerce

  Released under the GNU General Public License
*/

  require('includes/application_top.php');

  $templateModules['footer_script'][] = 
'<!--[if IE]><script type="text/javascript" src="' . tep_catalog_href_link('ext/flot/excanvas.min.js', '', 'SSL') . '"></script><![endif]-->
<script type="text/javascript" src="' . tep_catalog_href_link('ext/flot/jquery.flot.min.js', '', 'SSL') . '"></script>
<script type="text/javascript" src="' . tep_catalog_href_link('ext/flot/jquery.flot.time.min.js', '', 'SSL') . '"></script>';

  $languages = tep_get_languages();
  $languages_array = array();
  $languages_selected = DEFAULT_LANGUAGE;
  for ($i = 0, $n = sizeof($languages); $i < $n; $i++) {
    $languages_array[] = array('id' => $languages[$i]['code'],
                               'text' => $languages[$i]['name']);
    if ($languages[$i]['directory'] == $language) {
      $languages_selected = $languages[$i]['code'];
    }
  }

  require(DIR_WS_INCLUDES . 'template_top.php');
?>

      <div class="pageHeading col-md-4 col-sm-4">
        <h1><?php echo STORE_NAME; ?></h1>
      </div>

<?php
  if (sizeof($languages_array) > 1) {
?>
      <div class="col-md-2 col-md-offset-6 col-sm-2 col-sm-offset-5"><?php echo tep_draw_form('adminlanguage', FILENAME_DEFAULT, '', 'get') . tep_draw_pull_down_menu('language', $languages_array, $languages_selected, 'onchange="this.form.submit();"') . tep_hide_session_id() . '</form>'; ?></div>

<?php
  }

  if ( defined('MODULE_ADMIN_DASHBOARD_INSTALLED') && tep_not_null(MODULE_ADMIN_DASHBOARD_INSTALLED) ) {
    $adm_array = explode(';', MODULE_ADMIN_DASHBOARD_INSTALLED);

    $col = 0;

    for ( $i=0, $n=sizeof($adm_array); $i<$n; $i++ ) {
      $adm = $adm_array[$i];

      $class = substr($adm, 0, strrpos($adm, '.'));

      if ( !class_exists($class) ) {
        include(DIR_WS_LANGUAGES . $language . '/modules/dashboard/' . $adm);
        include(DIR_WS_MODULES . 'dashboard/' . $class . '.php');
      }

      $ad = new $class();

      if ( $ad->isEnabled() ) {
        if ($col < 1) {
          echo '          <div class="col-xs-12">' . "\n";
        }

        $col++;

        if ($col <= 2) {
          echo '            <div class="col-xs-12 col-md-6">' . "\n";
        }

        echo $ad->getOutput();

        if ($col <= 2) {
          echo '            </div>' . "\n";
        }

        if ( !isset($adm_array[$i+1]) || ($col == 2) ) {
          if ( !isset($adm_array[$i+1]) && ($col == 1) ) {
            echo '            <div class="col-xs-12 col-md-6">&nbsp;</div>' . "\n";
          }

          $col = 0;

          echo '  </div>' . "\n";
        }
      }
    }
  }

  require(DIR_WS_INCLUDES . 'template_bottom.php');
  require(DIR_WS_INCLUDES . 'application_bottom.php');
?>
