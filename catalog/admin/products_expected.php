<?php
/*
  $Id$

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2010 osCommerce

  Released under the GNU General Public License
*/

  require('includes/application_top.php');

  tep_db_query("update " . TABLE_PRODUCTS . " set products_date_available = '' where to_days(now()) > to_days(products_date_available)");

  require(DIR_WS_INCLUDES . 'template_top.php');
?>

      <div class="pageHeading col-xs-12">
        <h1><?php echo HEADING_TITLE; ?></h1>
      </div>

      <div class="col-xs-12 col-md-9">
        <table class="table table-hover table-bordered">
          <thead>
            <tr class="dataTableHeadingRow">
              <tr class="dataTableHeadingRow">
                <th class="dataTableHeadingContent"><?php echo TABLE_HEADING_PRODUCTS; ?></th>
                <th class="dataTableHeadingContent" style="text-align: center;"><?php echo TABLE_HEADING_DATE_EXPECTED; ?></th>
                <th class="dataTableHeadingContent" style="text-align: right;"><?php echo TABLE_HEADING_ACTION; ?>&nbsp;</th>
              </tr>
          </thead>
          <tbody>
<?php
  $products_query_raw = "select pd.products_id, pd.products_name, p.products_date_available from " . TABLE_PRODUCTS_DESCRIPTION . " pd, " . TABLE_PRODUCTS . " p where p.products_id = pd.products_id and p.products_date_available != '' and pd.language_id = '" . (int)$languages_id . "' order by p.products_date_available DESC";
  $products_split = new splitPageResults($_GET['page'], MAX_DISPLAY_SEARCH_RESULTS, $products_query_raw, $products_query_numrows);
  $products_query = tep_db_query($products_query_raw);
  while ($products = tep_db_fetch_array($products_query)) {
    if ((!isset($_GET['pID']) || (isset($_GET['pID']) && ($_GET['pID'] == $products['products_id']))) && !isset($pInfo)) {
      $pInfo = new objectInfo($products);
    }

    if (isset($pInfo) && is_object($pInfo) && ($products['products_id'] == $pInfo->products_id)) {
      echo '                  <tr id="defaultSelected" class="dataTableRowSelected" onmouseover="rowOverEffect(this)" onmouseout="rowOutEffect(this)" onclick="document.location.href=\'' . tep_href_link(FILENAME_CATEGORIES, 'pID=' . $products['products_id'] . '&action=new_product') . '\'">' . "\n";
    } else {
      echo '                  <tr class="dataTableRow" onmouseover="rowOverEffect(this)" onmouseout="rowOutEffect(this)" onclick="document.location.href=\'' . tep_href_link(FILENAME_PRODUCTS_EXPECTED, 'page=' . $_GET['page'] . '&pID=' . $products['products_id']) . '\'">' . "\n";
    }
?>
                <td class="dataTableContent"><?php echo $products['products_name']; ?></td>
                <td class="dataTableContent" style="text-align: center;"><?php echo tep_date_short($products['products_date_available']); ?></td>
                <td class="dataTableContent" style="text-align: right;"><?php if (isset($pInfo) && is_object($pInfo) && ($products['products_id'] == $pInfo->products_id)) { echo tep_glyphicon('play'); } else { echo '<a href="' . tep_href_link(FILENAME_PRODUCTS_EXPECTED, 'page=' . $_GET['page'] . '&pID=' . $products['products_id']) . '">' . tep_glyphicon('info-sign') . '</a>'; } ?>&nbsp;</td>
              </tr>
<?php
  }
?>
        </tbody>
        </table>
        <table class="table" width="100%"> <!-- osCommerce table foot -->
          <tr>
            <td class="smallText hidden-xs" valign="top"><?php echo $products_split->display_count($products_query_numrows, MAX_DISPLAY_SEARCH_RESULTS, $_GET['page'], TEXT_DISPLAY_NUMBER_OF_PRODUCTS_EXPECTED); ?></td>
            <td class="smallText" style="text-align: right;"><?php echo $products_split->display_links($products_query_numrows, MAX_DISPLAY_SEARCH_RESULTS, MAX_DISPLAY_PAGE_LINKS, $_GET['page']); ?></td>
          </tr>
        </table>
      </div>

<?php
  $heading = array();
  $contents = array();

  if (isset($pInfo) && is_object($pInfo)) {
    $heading[] = array('text' => '<strong>' . $pInfo->products_name . '</strong>');

    $contents[] = array('align' => 'center', 'text' => tep_draw_button(IMAGE_EDIT, 'document', tep_href_link(FILENAME_CATEGORIES, 'pID=' . $pInfo->products_id . '&action=new_product')));
    $contents[] = array('text' => '<br />' . TEXT_INFO_DATE_EXPECTED . ' ' . tep_date_short($pInfo->products_date_available));
  }

  if ( (tep_not_null($heading)) && (tep_not_null($contents)) ) {

    $box = new box;
    echo $box->infoBox($heading, $contents);

  }

  require(DIR_WS_INCLUDES . 'template_bottom.php');
  require(DIR_WS_INCLUDES . 'application_bottom.php');
?>
