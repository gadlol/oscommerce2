<?php
/*
  $Id$

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2013 osCommerce

  Released under the GNU General Public License
*/

  require('includes/application_top.php');

  function tep_sort_secmodules($a, $b) {
    return strcasecmp($a['title'], $b['title']);
  }

  $types = array('info', 'warning', 'error');

  $modules = array();

  if ($secdir = @dir(DIR_FS_ADMIN . 'includes/modules/security_check/')) {
    while ($file = $secdir->read()) {
      if (!is_dir(DIR_FS_ADMIN . 'includes/modules/security_check/' . $file)) {
        if (substr($file, strrpos($file, '.')) == '.php') {
          $class = 'securityCheck_' . substr($file, 0, strrpos($file, '.'));

          include(DIR_FS_ADMIN . 'includes/modules/security_check/' . $file);
          $$class = new $class();

          $modules[] = array('title' => isset($$class->title) ? $$class->title : substr($file, 0, strrpos($file, '.')),
                             'class' => $class,
                             'code' => substr($file, 0, strrpos($file, '.')));
        }
      }
    }
    $secdir->close();
  }

  if ($extdir = @dir(DIR_FS_ADMIN . 'includes/modules/security_check/extended/')) {
    while ($file = $extdir->read()) {
      if (!is_dir(DIR_FS_ADMIN . 'includes/modules/security_check/extended/' . $file)) {
        if (substr($file, strrpos($file, '.')) == '.php') {
          $class = 'securityCheckExtended_' . substr($file, 0, strrpos($file, '.'));

          include(DIR_FS_ADMIN . 'includes/modules/security_check/extended/' . $file);
          $$class = new $class();

          $modules[] = array('title' => isset($$class->title) ? $$class->title : substr($file, 0, strrpos($file, '.')),
                             'class' => $class,
                             'code' => substr($file, 0, strrpos($file, '.')));
        }
      }
    }
    $extdir->close();
  }

  usort($modules, 'tep_sort_secmodules');

  require(DIR_WS_INCLUDES . 'template_top.php');
?>

      <div class="pageHeading col-md-4 col-sm-4">
        <h1><?php echo HEADING_TITLE; ?></h1>
      </div>
      <div class="col-md-2 col-md-offset-6 col-sm-2 col-sm-offset-5"><?php echo tep_draw_bs_button('Reload', 'arrowrefresh-1-e', tep_href_link('security_checks.php')); ?></div>
      <div class="col-xs-12">
        <table class="table table-hover table-bordered">
          <thead>
            <tr class="dataTableHeadingRow">
              <th class="dataTableHeadingContent" width="20">&nbsp;</th>
              <th class="dataTableHeadingContent"><?php echo TABLE_HEADING_TITLE; ?></th>
              <th class="dataTableHeadingContent"><?php echo TABLE_HEADING_MODULE; ?></th>
              <th class="dataTableHeadingContent"><?php echo TABLE_HEADING_INFO; ?></th>
              <th class="dataTableHeadingContent" width="40" style="text-align: right;">&nbsp;</th>
            </tr>
          </thead>
          <tbody>

<?php
  foreach ($modules as $module) {
    $secCheck = $$module['class'];

    if ( !in_array($secCheck->type, $types) ) {
      $secCheck->type = 'info';
    }

    $output = '';

    if ( $secCheck->pass() ) {
      $secCheck->type = 'success';
    } else {
      $output = $secCheck->getMessage();
    }

    echo '  <tr class="dataTableRow" onmouseover="rowOverEffect(this)" onmouseout="rowOutEffect(this)">' . "\n" .
         '    <td class="dataTableContent" style="text-align: center;" valign="top">' . tep_image(DIR_WS_IMAGES . 'ms_' . $secCheck->type . '.png', '', 16, 16) . '</td>' . "\n" .
         '    <td class="dataTableContent" valign="top" style="white-space: nowrap;">' . tep_output_string_protected($module['title']) . '</td>' . "\n" .
         '    <td class="dataTableContent" valign="top">' . tep_output_string_protected($module['code']) . '</td>' . "\n" .
         '    <td class="dataTableContent" valign="top">' . $output . '</td>' . "\n" .
         '    <td class="dataTableContent" style="text-align: center;" valign="top">' . ((isset($secCheck->has_doc) && $secCheck->has_doc) ? '<a href="http://library.oscommerce.com/Wiki&oscom_2_3&security_checks&' . $module['code'] . '" target="_blank">' . tep_glyphicon('eye-open') . '</a>' : '') . '</td>' . "\n" .
         '  </tr>' . "\n";
  }
?>
          </tbody>
        </table>
      </div>

<?php
  require(DIR_WS_INCLUDES . 'template_bottom.php');
  require(DIR_WS_INCLUDES . 'application_bottom.php');
?>
