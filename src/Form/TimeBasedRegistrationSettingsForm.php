

<!-- THEME DEBUG -->
<!-- THEME HOOK: 'dmu_form' -->
<!-- BEGIN OUTPUT from 'modules/contrib/drupalmoduleupgrader/templates/Form.html.twig' -->
<?php

/**
 * @file
 * Contains \Drupal\time_based_registration\Form\TimeBasedRegistrationSettingsForm.
 */

namespace Drupal\time_based_registration\Form;

use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Render\Element;

class TimeBasedRegistrationSettingsForm extends ConfigFormBase {

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'time_based_registration_settings_form';
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    $config = $this->config('time_based_registration.settings');

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
    return ['time_based_registration.settings'];
  }

  public function buildForm(array $form, \Drupal\Core\Form\FormStateInterface $form_state) {
    $form['tbr_config'] = [
      '#type' => 'fieldset',
      '#title' => t('Time based Registration Setting'),
      '#collapsible' => FALSE,
      '#tree' => TRUE,
    ];
    $form['tbr_config']['current_site_time_zone'] = [
      '#markup' => t('Site Time zone is: <a href="@tbr-timezone">@tbr-link</a><br />', [
        '@tbr-timezone' => \Drupal\Core\Url::fromRoute('system.regional_settings'),
        '@tbr-link' => date_default_timezone_get(),
      ])
      ];
    // @FIXME
    // Could not extract the default value because it is either indeterminate, or
    // not scalar. You'll need to provide a default value in
    // config/install/time_based_registration.settings.yml and config/schema/time_based_registration.schema.yml.
    $tbr_config = \Drupal::config('time_based_registration.settings')->get('time_based_registration_config');
    $time_based_from = $tbr_config['from'];
    $time_based_to = $tbr_config['to'];

    $form['tbr_config']['event'] = [
      '#markup' => t('When do you want to open Registration?')
      ];
    $form['tbr_config']['from'] = [
      '#type' => 'date_select',
      '#title' => t('From date'),
      '#default_value' => isset($time_based_from) ? $time_based_from : '',
      '#date_year_range' => '0:+1',
    ];
    $form['tbr_config']['to'] = [
      '#type' => 'date_select',
      '#title' => t('To date'),
      '#default_value' => isset($time_based_to) ? $time_based_to : '',
      '#date_year_range' => '0:+1',
    ];

    $form['#validate'][] = 'time_based_registration_configuration_validate';

    return parent::buildForm($form, $form_state);
  }

}
?>

<!-- END OUTPUT from 'modules/contrib/drupalmoduleupgrader/templates/Form.html.twig' -->

