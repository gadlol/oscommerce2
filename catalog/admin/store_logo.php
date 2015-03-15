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
    switch ($action) {
      case 'save':
        $error = false;

        $store_logo = new upload('store_logo');
        $store_logo->set_extensions(array('png', 'gif', 'jpg'));
        $store_logo->set_destination(DIR_FS_CATALOG_IMAGES);

        if ($store_logo->parse()) {
          if ($store_logo->save()) {
            $messageStack->add_session(SUCCESS_LOGO_UPDATED, 'success');
            tep_db_query("update configuration set configuration_value = '" . tep_db_input($store_logo->filename) . "', last_modified = now() where configuration_value = '" . STORE_LOGO . "'");
          } else {
            $error = true;
          }
        } else {
          $error = true;
        }

        if ($error == false) {
          tep_redirect(tep_href_link(FILENAME_STORE_LOGO));
        }
        break;
    }
  }

  $templateModules['header'][] = '<link rel="stylesheet" type="text/css" href="' . tep_href_link('ext/css/fileupload.css') . '"></script>';
  $templateModules['footer'][] = '<script type="text/javascript" src="' . tep_href_link('ext/js/fileupload.js') . '"></script>';
 
  if (!tep_is_writable(DIR_FS_CATALOG_IMAGES)) {
    $messageStack->add(sprintf(ERROR_IMAGES_DIRECTORY_NOT_WRITEABLE, tep_href_link(FILENAME_SEC_DIR_PERMISSIONS)), 'error');
  }

  require(DIR_WS_INCLUDES . 'template_top.php');
?>

      <div class="pageHeading col-md-4 col-sm-4">
        <h1><?php echo HEADING_TITLE; ?></h1>
      </div>

      <div class="col-xs-12">
        <?php echo tep_image(HTTP_CATALOG_SERVER . DIR_WS_CATALOG_IMAGES . STORE_LOGO); ?>
      </div>

      <?php echo tep_draw_form('logo', FILENAME_STORE_LOGO, 'action=save', 'post', 'enctype="multipart/form-data"'); ?>
        <div class="col-xs-12" style="margin-top: 20px;">
          <div class="col-xs-2"><p><?php echo TEXT_LOGO_IMAGE; ?></p></div>
          <div class="col-xs-3 fileupload fileupload-new" data-provides="fileupload">
            <span class="btn btn-primary btn-file"><span class="fileupload-new"><?php echo IMAGE_SELECT; ?></span>
            <span class="fileupload-exists"><?php echo IMAGE_CHANGE; ?></span><?php echo tep_draw_file_field('store_logo'); ?></span>
            <span class="fileupload-preview"></span>
            <a href="#" class="close fileupload-exists" data-dismiss="fileupload" style="float: none">×</a>
          </div>
          <div class="col-xs-2"><?php echo tep_draw_bs_button(IMAGE_SAVE, 'disk', null, 'primary'); ?></div>
        </div>
      </form>

      <div class="col-xs-12">
        <p><?php echo TEXT_FORMAT_AND_LOCATION; ?></p>
        <p><?php echo DIR_FS_CATALOG_IMAGES . STORE_LOGO; ?></p>
      </div>
<?php
  require(DIR_WS_INCLUDES . 'template_bottom.php');
  require(DIR_WS_INCLUDES . 'application_bottom.php');
?>
