<?php
/*
  $Id$

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2010 osCommerce

  Released under the GNU General Public License
*/

  require('includes/application_top.php');

  function tep_opendir($path) {
    $path = rtrim($path, '/') . '/';

    $exclude_array = array('.', '..', '.DS_Store', 'Thumbs.db');

    $result = array();

    if ($handle = opendir($path)) {
      while (false !== ($filename = readdir($handle))) {
        if (!in_array($filename, $exclude_array)) {
          $file = array('name' => $path . $filename,
                        'is_dir' => is_dir($path . $filename),
                        'writable' => tep_is_writable($path . $filename));

          $result[] = $file;

          if ($file['is_dir'] == true) {
            $result = array_merge($result, tep_opendir($path . $filename));
          }
        }
      }

      closedir($handle);
    }

    return $result;
  }

  $whitelist_array = array();

  $whitelist_query = tep_db_query("select directory from " . TABLE_SEC_DIRECTORY_WHITELIST);
  while ($whitelist = tep_db_fetch_array($whitelist_query)) {
    $whitelist_array[] = $whitelist['directory'];
  }

  $admin_dir = basename(DIR_FS_ADMIN);

  if ($admin_dir != 'admin') {
    for ($i=0, $n=sizeof($whitelist_array); $i<$n; $i++) {
      if (substr($whitelist_array[$i], 0, 6) == 'admin/') {
        $whitelist_array[$i] = $admin_dir . substr($whitelist_array[$i], 5);
      }
    }
  }

  require(DIR_WS_INCLUDES . 'template_top.php');
?>

      <div class="pageHeading col-xs-12">
        <h1><?php echo HEADING_TITLE; ?></h1>
      </div>
      <div class="col-xs-12">
        <table class="table table-hover table-bordered">
          <thead>
            <tr class="dataTableHeadingRow">
              <th class="dataTableHeadingContent"><?php echo TABLE_HEADING_DIRECTORIES; ?></th>
              <th class="dataTableHeadingContent" style="text-align: center;"><?php echo TABLE_HEADING_WRITABLE; ?></th>
              <th class="dataTableHeadingContent" style="text-align: center;"><?php echo TABLE_HEADING_RECOMMENDED; ?></th>
            </tr>
          </thead>
          <tbody>
<?php
  foreach (tep_opendir(DIR_FS_CATALOG) as $file) {
    if ($file['is_dir']) {
?>
            <tr class="dataTableRow" onmouseover="rowOverEffect(this)" onmouseout="rowOutEffect(this)">
              <td class="dataTableContent"><?php echo substr($file['name'], strlen(DIR_FS_CATALOG)); ?></td>
              <td class="dataTableContent" style="text-align: center;"><?php echo (($file['writable'] == true) ? tep_glyphicon('ok', '', 'style="color:#13AF25;"') : tep_glyphicon('remove', '', 'style="color:#D41919;"')); ?></td>
              <td class="dataTableContent" style="text-align: center;"><?php echo (in_array(substr($file['name'], strlen(DIR_FS_CATALOG)), $whitelist_array) ? tep_glyphicon('ok', '', 'style="color:#13AF25;"') : tep_glyphicon('remove', '', 'style="color:#D41919;"')); ?></td>
            </tr>
<?php
    }
  }
?>
          </tbody>
        </table>
        <table class="table" width="100%"> <!-- osCommerce table foot -->
          <tr>
            <td class="smallText bg-info"><?php echo TEXT_DIRECTORY . ' ' . DIR_FS_CATALOG; ?></td>
          </tr>
        </table>
      </div>

<?php
  require(DIR_WS_INCLUDES . 'template_bottom.php');
  require(DIR_WS_INCLUDES . 'application_bottom.php');
?>
