<?php
/*
  $Id$

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2010 osCommerce

  Released under the GNU General Public License
*/
?>

    </section>

  </div>

<?php require(DIR_WS_INCLUDES . 'footer.php'); ?>

</div> <!-- container-fluid -->

<script type="text/javascript" src="<?php echo tep_catalog_href_link('ext/jquery/jquery-1.11.1.min.js', '', 'SSL'); ?>"></script>
<script type="text/javascript" src="<?php echo tep_catalog_href_link('ext/bootstrap/js/bootstrap.min.js', '', 'SSL'); ?>"></script>


<?php
  if (tep_not_null(JQUERY_DATEPICKER_I18N_CODE)) {
?>
<script type="text/javascript" src="<?php echo tep_catalog_href_link('ext/jquery/ui/i18n/jquery.ui.datepicker-' . JQUERY_DATEPICKER_I18N_CODE . '.js', '', 'SSL'); ?>"></script>
<script type="text/javascript">
$.datepicker.setDefaults($.datepicker.regional['<?php echo JQUERY_DATEPICKER_I18N_CODE; ?>']);
</script>
<?php
  }
  if (isset($templateModules['footer_script']) && !empty($templateModules['footer_script'])) {
    foreach ($templateModules['footer_script'] as $key => $value) {
      echo $value . "\n";
    }
  }

  if (isset($templateModules['footer']) && !empty($templateModules['footer'])) {
    foreach ($templateModules['footer'] as $key => $value) {
      echo $value . "\n";
    }
  }
?>
</body>
</html>

