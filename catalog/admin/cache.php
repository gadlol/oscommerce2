<?php
/*
  $Id$

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2014 osCommerce

  Released under the GNU General Public License
*/

  require('includes/application_top.php');

  $action = (isset($_GET['action']) ? $_GET['action'] : '');

  if (tep_not_null($action)) {
    if ($action == 'reset') {
      tep_reset_cache_block($_GET['block']);
    }

    tep_redirect(tep_href_link(FILENAME_CACHE));
  }

// check if the cache directory exists
  if (is_dir(DIR_FS_CACHE)) {
    if (!tep_is_writable(DIR_FS_CACHE)) $messageStack->add(ERROR_CACHE_DIRECTORY_NOT_WRITEABLE, 'error');
  } else {
    $messageStack->add(ERROR_CACHE_DIRECTORY_DOES_NOT_EXIST, 'error');
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
              <th class="dataTableHeadingContent"><?php echo TABLE_HEADING_CACHE; ?></th>
              <th class="dataTableHeadingContent" style="text-align: right;"><?php echo TABLE_HEADING_DATE_CREATED; ?></th>
              <th class="dataTableHeadingContent" style="text-align: right;"><?php echo TABLE_HEADING_ACTION; ?>&nbsp;</th>
            </tr>
          </thead>
          <tbody>
<?php
  if ($messageStack->size < 1) {
    $languages = tep_get_languages();

    for ($i=0, $n=sizeof($languages); $i<$n; $i++) {
      if ($languages[$i]['code'] == DEFAULT_LANGUAGE) {
        $language = $languages[$i]['directory'];
      }
    }

    for ($i=0, $n=sizeof($cache_blocks); $i<$n; $i++) {
      $cached_file = preg_replace('/-language/', '-' . $language, $cache_blocks[$i]['file']);

      if (file_exists(DIR_FS_CACHE . $cached_file)) {
        $cache_mtime = strftime(DATE_TIME_FORMAT, filemtime(DIR_FS_CACHE . $cached_file));
      } else {
        $cache_mtime = TEXT_FILE_DOES_NOT_EXIST;
        $dir = dir(DIR_FS_CACHE);

        while ($cache_file = $dir->read()) {
          $cached_file = preg_replace('/-language/', '-' . $language, $cache_blocks[$i]['file']);

          if (preg_match('/^' . $cached_file. '/', $cache_file)) {
            $cache_mtime = strftime(DATE_TIME_FORMAT, filemtime(DIR_FS_CACHE . $cache_file));
            break;
          }
        }

        $dir->close();
      }
?>
            <tr class="dataTableRow" onmouseover="rowOverEffect(this)" onmouseout="rowOutEffect(this)">
              <td class="dataTableContent"><?php echo $cache_blocks[$i]['title']; ?></td>
              <td class="dataTableContent" style="text-align: right;"><?php echo $cache_mtime; ?></td>
              <td class="dataTableContent" style="text-align: right;"><?php echo '<a href="' . tep_href_link(FILENAME_CACHE, 'action=reset&block=' . $cache_blocks[$i]['code']) . '">' . tep_glyphicon('refresh') . '</a>'; ?>&nbsp;</td>
            </tr>
<?php
    }
  }
?>
          </tbody>
        </table>
        <table class="table" width="100%"> <!-- osCommerce table foot -->
          <tr>
            <td class="smallText bg-info" colspan="3"><?php echo TEXT_CACHE_DIRECTORY . ' ' . DIR_FS_CACHE; ?></td>
          </tr>
        </table>
      </div>

<?php
  require(DIR_WS_INCLUDES . 'template_bottom.php');
  require(DIR_WS_INCLUDES . 'application_bottom.php');
?>
