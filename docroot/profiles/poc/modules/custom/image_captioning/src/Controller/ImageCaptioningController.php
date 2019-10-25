<?php

namespace Drupal\image_captioning\Controller;

require_once \Drupal::root() . '/profiles/poc/libraries/custom/ibm-max-image-caption-generator-api/src/api.php';

use Drupal\Core\Controller\ControllerBase;
use Drupal\file\Entity\File;
use Drupal\IBM\MaxImageCaption\Captioning;
use Symfony\Component\HttpFoundation\JsonResponse;

/**
 * Provides route responses for the Example module.
 */
class ImageCaptioningController extends ControllerBase {

  /**
   * Returns a simple page.
   *
   * @return array
   *   A simple renderable array.
   */
  public function content() {
    $config = \Drupal::config('image_captioning.settings');
    $url = $config->get('image_captioning_base_url');
    if (empty($url)) {
      $url = 'http://max-image-caption-generator.max.us-south.containers.appdomain.cloud';
    }

    $api = new Captioning([
      'metadata_url' => $url,
      'predict_url' => $url,
    ]);

    $fid = !empty($_GET['fid']) ? $_GET['fid'] : NULL;
    $use_probability = !empty($_GET['use_probability']) ? $_GET['use_probability'] : FALSE;
    if (!empty($fid)) {
      $file = File::load($fid);
      $absolute_path = \Drupal::service('file_system')->realpath($file->getFileUri());
      $output = $api->predict($absolute_path, $use_probability);

      if (!empty($output)) {
        return new JsonResponse([
          'data' => $output,
          'method' => 'GET',
        ]);
      }
    }

    return new JsonResponse([
      'error' => TRUE,
      'message' => t("No suggestions"),
      'method' => 'GET'
    ]);
  }

}
