<?php
/**
 * @file
 * Contains Drupal\image_captioning\Form\ImageCaptioningAdminForm.
 */
namespace Drupal\image_captioning\Form;

use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormStateInterface;

class ImageCaptioningAdminForm extends ConfigFormBase {
  /**
   * {@inheritdoc}
   */
  protected function getEditableConfigNames() {
    return [
      'image_captioning.settings',
    ];
  }

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'image_captioning_form';
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    $config = $this->config('image_captioning.settings');
    $url = $config->get('image_captioning_base_url');
    if (empty($url)) {
      $url = 'http://max-image-caption-generator.max.us-south.containers.appdomain.cloud';
    }

    $form['image_captioning_base_url'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Base URL'),
      '#description' => $this->t('Set the base URL where the MAX Image Caption generation resides.'),
      '#default_value' => $url,
    ];

    return parent::buildForm($form, $form_state);
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    parent::submitForm($form, $form_state);

    $this->config('image_captioning.settings')
      ->set('image_captioning_base_url', $form_state->getValue('image_captioning_base_url'))
      ->save();
  }
}
