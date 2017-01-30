<?php

/**
 * @file
 * Contains \Drupal\bootstrap_modal_messages\Form\BootstrapModalMessagesAdminForm.
 */

namespace Drupal\bootstrap_modal_messages\Form;

use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Render\Element;

class BootstrapModalMessagesAdminForm extends ConfigFormBase {

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'bootstrap_modal_messages_admin_form';
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    $config = $this->config('bootstrap_modal_messages.settings');

    foreach (Element::children($form) as $variable) {
      $config->set($variable, $form_state->getValue($form[$variable]['#parents']));
    }
    $config->save();

    if (method_exists($this, '_submitForm')) {
      $this->_submitForm($form, $form_state);
    }

    parent::submitForm($form, $form_state);
  }

  /**
   * {@inheritdoc}
   */
  protected function getEditableConfigNames() {
    return ['bootstrap_modal_messages.settings'];
  }

  public function buildForm(array $form, \Drupal\Core\Form\FormStateInterface $form_state) {
    // @todo Option to use with any sourced BS library.
    $form['bootstrap_modal_messages_selector'] = [
      '#type' => 'textfield',
      '#title' => t('Messages div selector'),
      '#default_value' => variable_get('bootstrap_modal_messages_selector', '.messages'),
      '#description' => t('The CSS/JS selector to find messages for the modal.'),
    ];

    $form['bootstrap_modal_messages_multiple'] = [
      '#type' => 'select',
      '#title' => t('Multiple messages setting'),
      '#options' => [
        'single' => t('Single modal'),
        'multiple' => t('Multiple - New modal per message type'),
      ],
      '#default_value' => variable_get('bootstrap_modal_messages_multiple', 'single'),
      '#description' => t('How to handle multiple messages on one page?'),
    ];

    $form['bootstrap_modal_messages_ignore_admin'] = [
      '#type' => 'select',
      '#title' => t('Ignore admin pages'),
      '#options' => [
        1 => t('Yes'),
        0 => t('No'),
      ],
      '#default_value' => variable_get('bootstrap_modal_messages_ignore_admin', 1),
      '#description' => t('Ignore admin pages so that messages display normally?'),
    ];

    // Modal header.
    $form['modal_header'] = [
      '#type' => 'fieldset',
      '#title' => t('Modal Header'),
      '#collapsible' => TRUE,
    ];

    $form['modal_header']['bootstrap_modal_messages_show_header'] = [
      '#type' => 'select',
      '#title' => t('Show header'),
      '#options' => [
        1 => t('Yes'),
        0 => t('No'),
      ],
      '#default_value' => variable_get('bootstrap_modal_messages_show_header', 1),
      '#description' => t('Show the modal header.'),
    ];

    $form['modal_header']['bootstrap_modal_messages_title'] = [
      '#type' => 'textarea',
      '#title' => t('Modal Title'),
      '#default_value' => variable_get('bootstrap_modal_messages_title', BOOTSTRAP_MODAL_MESSAGES_HEADER),
      '#description' => t('Enter the HTML to be used in the modal title. Defaults to Bootstrap\'s default h4 title.'),
    ];

    $form['modal_header']['bootstrap_modal_messages_header_close'] = [
      '#type' => 'select',
      '#title' => t('Show close button (X)'),
      '#options' => [
        1 => t('Yes'),
        0 => t('No'),
      ],
      '#default_value' => variable_get('bootstrap_modal_messages_header_close', 1),
      '#description' => t('Show the close (X) button in modal header.'),
    ];

    // Modal footer.
    $form['modal_footer'] = [
      '#type' => 'fieldset',
      '#title' => t('Modal Footer'),
      '#collapsible' => TRUE,
    ];

    $form['modal_footer']['bootstrap_modal_messages_show_footer'] = [
      '#type' => 'select',
      '#title' => t('Show footer'),
      '#options' => [
        1 => t('Yes'),
        0 => t('No'),
      ],
      '#default_value' => variable_get('bootstrap_modal_messages_show_footer', 1),
      '#description' => t('Show the modal footer.'),
    ];

    $form['modal_footer']['bootstrap_modal_messages_footer_html'] = [
      '#type' => 'textarea',
      '#title' => t('Footer HTML'),
      '#default_value' => variable_get('bootstrap_modal_messages_footer_html', BOOTSTRAP_MODAL_MESSAGES_FOOTER),
      '#description' => t('Enter the HTML to be used in the modal footer. Defaults to Bootstrap\'s default Close button.'),
    ];

    // Modal controls.
    $form['controls'] = [
      '#type' => 'fieldset',
      '#title' => t('Controls'),
      '#collapsible' => TRUE,
    ];

    $form['controls']['bootstrap_modal_messages_show_onload'] = [
      '#type' => 'select',
      '#title' => t('Open modal on page load?'),
      '#options' => [
        1 => t('Yes'),
        0 => t('No - Should enable "Show controls" to display modal'),
      ],
      '#default_value' => variable_get('bootstrap_modal_messages_show_onload', 1),
      '#description' => t('Open modal immediately when the page loads.'),
    ];

    $form['controls']['bootstrap_modal_messages_show_controls'] = [
      '#type' => 'select',
      '#title' => t('Show controls?'),
      '#options' => [
        0 => t('No'),
        1 => t('Yes'),
      ],
      '#default_value' => variable_get('bootstrap_modal_messages_show_controls', 0),
      '#description' => t('Creates a div that allows you to show messages again. !note', [
        '!note' => '<strong>This setting can be overridden by the permission "View Bootstrap Modal Messages controls".</strong>'
        ]),
    ];

    $form['controls']['bootstrap_modal_messages_controls_html'] = [
      '#type' => 'textarea',
      '#title' => t('Controls HTML'),
      '#default_value' => variable_get('bootstrap_modal_messages_controls_html', t('Messages')),
      '#description' => t('Enter the HTML to be used in the div for controls.'),
    ];

    return parent::buildForm($form, $form_state);
  }

}
