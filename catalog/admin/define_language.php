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
                        'writable' => tep_is_writable($path . $filename),
                        'size' => filesize($path . $filename),
                        'last_modified' => strftime(DATE_TIME_FORMAT, filemtime($path . $filename)));

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

  if (!isset($_GET['lngdir'])) $_GET['lngdir'] = $language;

  $languages_array = array();
  $languages = tep_get_languages();
  $lng_exists = false;
  for ($i=0, $n=sizeof($languages); $i<$n; $i++) {
    if ($languages[$i]['directory'] == $_GET['lngdir']) $lng_exists = true;

    $languages_array[] = array('id' => $languages[$i]['directory'],
                               'text' => $languages[$i]['name']);
  }

  if (!$lng_exists) $_GET['lngdir'] = $language;

  if (isset($_GET['filename'])) {
    $file = DIR_FS_CATALOG_LANGUAGES . $_GET['filename'];

    if (!file_exists($file)) {
      tep_redirect(tep_href_link(FILENAME_DEFINE_LANGUAGE, 'lngdir=' . $_GET['lngdir']));
    }
  }

  $action = (isset($_GET['action']) ? $_GET['action'] : '');

  if (tep_not_null($action)) {
    switch ($action) {
      case 'save':
        if (isset($_GET['lngdir']) && isset($_GET['filename'])) {
          $file = DIR_FS_CATALOG_LANGUAGES . $_GET['filename'];

          if (file_exists($file) && tep_is_writable($file)) {
            $new_file = fopen($file, 'w');
            $file_contents = stripslashes($_POST['file_contents']);
            fwrite($new_file, $file_contents, strlen($file_contents));
            fclose($new_file);
          }

          tep_redirect(tep_href_link(FILENAME_DEFINE_LANGUAGE, 'lngdir=' . $_GET['lngdir']));
        }
        break;
    }
  }

  require(DIR_WS_INCLUDES . 'template_top.php');
?>

      <div class="pageHeading col-md-4 col-sm-4">
        <h1><?php echo HEADING_TITLE; ?></h1>
      </div>

      <?php echo tep_draw_form('lng', FILENAME_DEFINE_LANGUAGE, '', 'get'); ?>
      <div class="col-md-2 col-md-offset-6 col-sm-2 col-sm-offset-5">
        <?php echo tep_draw_pull_down_menu('lngdir', $languages_array, $_GET['lngdir'], 'onchange="this.form.submit();"'); ?></td>
      </div>
      <?php echo tep_hide_session_id(); ?></form>

      <div class="col-xs-12">
<?php
  if (isset($_GET['lngdir']) && isset($_GET['filename'])) {
    $file = DIR_FS_CATALOG_LANGUAGES . $_GET['filename'];

    $file_array = file($file);
    $contents = implode('', $file_array);

    $file_writeable = true;
    if (!tep_is_writable($file)) {
      $file_writeable = false;
      $messageStack->reset();
      $messageStack->add(sprintf(ERROR_FILE_NOT_WRITEABLE, $file), 'error');
      echo $messageStack->output();
    }

    echo tep_draw_form('language', FILENAME_DEFINE_LANGUAGE, 'lngdir=' . $_GET['lngdir'] . '&filename=' . $_GET['filename'] . '&action=save'); ?>
          <table class="table">
            <tr>
              <td class="main"><strong><?php echo $_GET['filename']; ?></strong></td>
            </tr>
            <tr>
              <td class="main"><?php echo tep_draw_textarea_field('file_contents', 'soft', '80', '25', $contents, (($file_writeable) ? '' : 'readonly') . ' style="width: 100%;"'); ?></td>
            </tr>
            <tr>
              <td><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
            </tr>
            <tr>
              <td class="smallText" style="text-align: right;"><?php if ($file_writeable == true) { echo tep_draw_bs_button(IMAGE_SAVE, 'disk', null, 'primary') . tep_draw_bs_button(IMAGE_CANCEL, 'close', tep_href_link(FILENAME_DEFINE_LANGUAGE, 'lngdir=' . $_GET['lngdir'])); } else { echo tep_draw_bs_button(IMAGE_BACK, 'arrow-1-w', tep_href_link(FILENAME_DEFINE_LANGUAGE, 'lngdir=' . $_GET['lngdir'])); } ?></td>
            </tr>
          </table>
        </form>

        <div class="main"><?php echo TEXT_EDIT_NOTE; ?></div>

<?php
  } else {
    $filename = $_GET['lngdir'] . '.php';
    $file_extension = substr($PHP_SELF, strrpos($PHP_SELF, '.'));
?>
        <table class="table table-hover table-bordered">
          <thead>
            <tr class="dataTableHeadingRow">
              <th class="dataTableHeadingContent"><?php echo TABLE_HEADING_FILES; ?></th>
              <th class="dataTableHeadingContent" style="text-align: center;"><?php echo TABLE_HEADING_WRITABLE; ?></th>
              <th class="dataTableHeadingContent" style="text-align: right;"><?php echo TABLE_HEADING_LAST_MODIFIED; ?></th>
            </tr>
          </thead>
          <tbody>
            <tr class="dataTableRow" onmouseover="rowOverEffect(this)" onmouseout="rowOutEffect(this)">
              <td class="dataTableContent"><a href="<?php echo tep_href_link(FILENAME_DEFINE_LANGUAGE, 'lngdir=' . $_GET['lngdir'] . '&filename=' . $filename); ?>"><strong><?php echo $filename; ?></strong></a></td>
              <td class="dataTableContent" style="text-align: center;"><?php echo ((tep_is_writable(DIR_FS_CATALOG_LANGUAGES . $filename) == true) ? tep_glyphicon('ok', '', 'style="color:#13AF25;"') : tep_glyphicon('remove', '', 'style="color:#D41919;"')); ?></td>
              <td class="dataTableContent" style="text-align: right;"><?php echo strftime(DATE_TIME_FORMAT, filemtime(DIR_FS_CATALOG_LANGUAGES . $filename)); ?></td>
            </tr>
<?php
    foreach (tep_opendir(DIR_FS_CATALOG_LANGUAGES . $_GET['lngdir']) as $file) {
      if (substr($file['name'], strrpos($file['name'], '.')) == $file_extension) {
        $filename = substr($file['name'], strlen(DIR_FS_CATALOG_LANGUAGES));

        echo '            <tr class="dataTableRow" onmouseover="rowOverEffect(this)" onmouseout="rowOutEffect(this)">' .
             '              <td class="dataTableContent"><a href="' . tep_href_link(FILENAME_DEFINE_LANGUAGE, 'lngdir=' . $_GET['lngdir'] . '&filename=' . $filename) . '">' . substr($filename, strlen($_GET['lngdir'] . '/')) . '</a></td>' .
             '              <td class="dataTableContent" style="text-align: center;">' . (($file['writable'] == true) ? tep_glyphicon('ok', '', 'style="color:#13AF25;"') : tep_glyphicon('remove', '', 'style="color:#D41919;"')) . '</td>' .
             '              <td class="dataTableContent" style="text-align: right;">' . $file['last_modified'] . '</td>' .
             '            </tr>';
      }
    }
?>
          </tbody>
        </table>
<?php
  }
?>
      </div>

<?php
  require(DIR_WS_INCLUDES . 'template_bottom.php');
  require(DIR_WS_INCLUDES . 'application_bottom.php');
?>
