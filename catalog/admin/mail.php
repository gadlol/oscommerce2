<?php
/*
  $Id$

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2010 osCommerce

  Released under the GNU General Public License
*/

  require('includes/application_top.php');

  $action = (isset($_GET['action']) ? $_GET['action'] : '');

  if ( ($action == 'send_email_to_user') && isset($_POST['customers_email_address']) && !isset($_POST['back_x']) ) {
    switch ($_POST['customers_email_address']) {
      case '***':
        $mail_query = tep_db_query("select customers_firstname, customers_lastname, customers_email_address from " . TABLE_CUSTOMERS);
        $mail_sent_to = TEXT_ALL_CUSTOMERS;
        break;
      case '**D':
        $mail_query = tep_db_query("select customers_firstname, customers_lastname, customers_email_address from " . TABLE_CUSTOMERS . " where customers_newsletter = '1'");
        $mail_sent_to = TEXT_NEWSLETTER_CUSTOMERS;
        break;
      default:
        $customers_email_address = tep_db_prepare_input($_POST['customers_email_address']);

        $mail_query = tep_db_query("select customers_firstname, customers_lastname, customers_email_address from " . TABLE_CUSTOMERS . " where customers_email_address = '" . tep_db_input($customers_email_address) . "'");
        $mail_sent_to = $_POST['customers_email_address'];
        break;
    }

    $from = tep_db_prepare_input($_POST['from']);
    $subject = tep_db_prepare_input($_POST['subject']);
    $message = tep_db_prepare_input($_POST['message']);

    //Let's build a message object using the email class
    $mimemessage = new email(array('X-Mailer: osCommerce'));

    // Build the text version
    $text = strip_tags($message);
    if (EMAIL_USE_HTML == 'true') {
      $mimemessage->add_html($message, $text);
    } else {
      $mimemessage->add_text($text);
    }

    $mimemessage->build_message();
    while ($mail = tep_db_fetch_array($mail_query)) {
      $mimemessage->send($mail['customers_firstname'] . ' ' . $mail['customers_lastname'], $mail['customers_email_address'], '', $from, $subject);
    }

    tep_redirect(tep_href_link(FILENAME_MAIL, 'mail_sent_to=' . urlencode($mail_sent_to)));
  }

  if ( ($action == 'preview') && !isset($_POST['customers_email_address']) ) {
    $messageStack->add(ERROR_NO_CUSTOMER_SELECTED, 'error');
  }

  if (isset($_GET['mail_sent_to'])) {
    $messageStack->add(sprintf(NOTICE_EMAIL_SENT_TO, $_GET['mail_sent_to']), 'success');
  }

  require(DIR_WS_INCLUDES . 'template_top.php');
?>

      <div class="pageHeading col-xs-12">
        <h1><?php echo HEADING_TITLE; ?></h1>
      </div>

<?php
  if ( ($action == 'preview') && isset($_POST['customers_email_address']) ) {
    switch ($_POST['customers_email_address']) {
      case '***':
        $mail_sent_to = TEXT_ALL_CUSTOMERS;
        break;
      case '**D':
        $mail_sent_to = TEXT_NEWSLETTER_CUSTOMERS;
        break;
      default:
        $mail_sent_to = $_POST['customers_email_address'];
        break;
    }
?>
      <div class="col-xs-12 col-md-9">
        <?php echo tep_draw_form('mail', FILENAME_MAIL, 'action=send_email_to_user'); ?>
          <table class="table table-hover table-bordered">
            <tr>
              <td class="smallText"><strong><?php echo TEXT_CUSTOMER; ?></strong><br /><?php echo $mail_sent_to; ?></td>
            </tr>
            <tr>
              <td class="smallText"><strong><?php echo TEXT_FROM; ?></strong><br /><?php echo htmlspecialchars(stripslashes($_POST['from'])); ?></td>
            </tr>
            <tr>
              <td class="smallText"><strong><?php echo TEXT_SUBJECT; ?></strong><br /><?php echo htmlspecialchars(stripslashes($_POST['subject'])); ?></td>
            </tr>
            <tr>
              <td class="smallText"><strong><?php echo TEXT_MESSAGE; ?></strong><br /><?php echo nl2br(htmlspecialchars(stripslashes($_POST['message']))); ?></td>
            </tr>
          </table>
          <table class="table" width="100%"> <!-- osCommerce table foot -->
            <tr>
              <td class="smallText" style="text-align: right;">
<?php
/* Re-Post all POST'ed variables */
    foreach ( $_POST as $key => $value ) {
      if (!is_array($_POST[$key])) {
        echo tep_draw_hidden_field($key, htmlspecialchars(stripslashes($value)));
      }
    }

    echo tep_draw_bs_button(IMAGE_SEND_EMAIL, 'mail-closed', null, 'primary') . tep_draw_bs_button(IMAGE_CANCEL, 'close', tep_href_link(FILENAME_MAIL));
?>
              </td>
            </tr>
          </table>
        </form>
      </div>
<?php
  } else {
?>
      <div class="col-xs-12 col-md-9">
      <?php echo tep_draw_form('mail', FILENAME_MAIL, 'action=preview'); ?>
        <table class="table borderless">
<?php
    $customers = array();
    $customers[] = array('id' => '', 'text' => TEXT_SELECT_CUSTOMER);
    $customers[] = array('id' => '***', 'text' => TEXT_ALL_CUSTOMERS);
    $customers[] = array('id' => '**D', 'text' => TEXT_NEWSLETTER_CUSTOMERS);
    $mail_query = tep_db_query("select customers_email_address, customers_firstname, customers_lastname from " . TABLE_CUSTOMERS . " order by customers_lastname");
    while($customers_values = tep_db_fetch_array($mail_query)) {
      $customers[] = array('id' => $customers_values['customers_email_address'],
                           'text' => $customers_values['customers_lastname'] . ', ' . $customers_values['customers_firstname'] . ' (' . $customers_values['customers_email_address'] . ')');
    }
?>
          <tr>
            <td class="main"><?php echo TEXT_CUSTOMER; ?></td>
            <td><?php echo tep_draw_pull_down_menu('customers_email_address', $customers, (isset($_GET['customer']) ? $_GET['customer'] : ''));?></td>
          </tr>
          <tr>
            <td class="main"><?php echo TEXT_FROM; ?></td>
            <td><?php echo tep_draw_input_field('from', EMAIL_FROM); ?></td>
          </tr>
          <tr>
            <td class="main"><?php echo TEXT_SUBJECT; ?></td>
            <td><?php echo tep_draw_input_field('subject'); ?></td>
          </tr>
          <tr>
            <td valign="top" class="main"><?php echo TEXT_MESSAGE; ?></td>
            <td><?php echo tep_draw_textarea_field('message', 'soft', '60', '15'); ?></td>
          </tr>
        </table>
        <table class="table" width="100%"> <!-- osCommerce table foot -->
          <tr>
            <td class="smallText" colspan="2" style="text-align: right;"><?php echo tep_draw_bs_button(IMAGE_PREVIEW, 'document', null, 'primary'); ?></td>
          </tr>
        </table>
      </form>
    </div>
<?php
  }

  require(DIR_WS_INCLUDES . 'template_bottom.php');
  require(DIR_WS_INCLUDES . 'application_bottom.php');
?>
