<?php
/*
  $Id$

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2014 osCommerce

  Released under the GNU General Public License
*/
?>

<nav id="header" class="navbar navbar-default navbar-fixed-top">
  <div class="container-fluid">
    <div class="navbar-header">
      <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#navbar">
        <span class="sr-only">Toggle Navigation</span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
      </button>
    </div>
    <div id="navbar" class="navbar-collapse collapse">
      <ul class="nav navbar-nav">
        <li><a class="brand" href="#"><img src="../images/store_logo.png" alt="osCommerce Online Merchant" width="202" height="30" ></a></li>
<?php
  if ( isset($_SESSION['admin']) ) {
    include(DIR_WS_INCLUDES . 'app_menu.php');
  }
?>
        <li><?php echo '<a href="' . tep_catalog_href_link() . '">' . HEADER_TITLE_ONLINE_CATALOG . '</a>'; ?></li>
        <li class="dropdown">
          <a href="#" class="dropdown-toggle" data-toggle="dropdown">Help<b class="caret"></b></a>
          <ul class="dropdown-menu">
            <li><a href="http://www.oscommerce.com" target="_blank"><?php echo HEADER_TITLE_SUPPORT_SITE; ?></a></li>
          </ul>
        </li>
      </ul>

<?php
  if ( isset($_SESSION['admin']) ) {
?>
      <ul class="nav navbar-nav navbar-right">
        <li class="dropdown">
          <a href="#" class="dropdown-toggle" data-toggle="dropdown"><?php echo tep_output_string_protected($_SESSION['admin']['username']); ?><b class="caret"></b></a>
          <ul class="dropdown-menu">
            <li><a href="<?php echo tep_href_link(FILENAME_LOGIN, 'action=logoff'); ?>"><?php echo HEADER_TITLE_LOGOFF; ?></a></li>
          </ul>
        </li>
      </ul>
<?php
  }
?>
    </div>
  </div>
</nav>

<?php
  $templateModules['footer'][] = <<<EOD
<!-- navbar jQuery menu script -->
<script>
$(function () {
  $('a.submenu').on("click", function (e) {
    e.preventDefault();
  });
});
</script>
EOD;
?>
