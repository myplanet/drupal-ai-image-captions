<?php

namespace Drupal\IBM\MaxImageCaption;

define('IBM_FILE_NOT_FOUND', 10);

class Captioning {

  private $config;

  private $base_url = 'http://max-image-caption-generator.max.us-south.containers.appdomain.cloud';

  public $metadata_url;

  /**
   * Captioning constructor.
   *
   * @param array $config
   */
  function __construct($override_config = []) {
    $this->config = [
      'metadata_url' => $this->base_url . '/model/metadata',
      'predict_url' => $this->base_url . '/model/predict'
    ];
    $this->config += $override_config;
  }

  public function predict($filepath, $use_probability = TRUE) {
    $result = $this->call($this->config['predict_url'], $filepath);

    if ($result !== FALSE) {
      $data = json_decode($result, TRUE);
      if (!empty($data['predictions'])) {
        $predictions = [];
        foreach ($data['predictions'] as $key => $prediction) {
          $probability = sprintf('%.10f', $prediction['probability']);
          $value = $prediction['caption'];
          if ($use_probability) {
            $value .= ' (' . $probability . ')';
          }
          $predictions[$key] = $value;
        }
      }
    }

    return $predictions;
  }

  private function call($url, $filepath = null) {
    // create curl resource
    $ch = curl_init();

    // Set destination URL.
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_TIMEOUT, 30);
    curl_setopt($ch, CURLOPT_VERBOSE, true);
    $verbose = fopen('php://temp', 'w+');
    curl_setopt($ch, CURLOPT_STDERR, $verbose);

    if (!empty($filepath)) {
      // Add a file.
      $mime = mime_content_type($filepath);
      $info = pathinfo($filepath);
      $name = $info['basename'];
      $postfile = new \CURLFile($filepath, $mime, $name);

      $data = array(
        "image" => $postfile
      );

      curl_setopt($ch, CURLOPT_HTTPHEADER, [
        'accept', 'application/json',
        'Content-Type', 'multipart/form-data'
      ]);

      curl_setopt($ch, CURLOPT_POST, true); // enable posting
      curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
    } else {
      return IBM_FILE_NOT_FOUND;
    }

    curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
    $output = curl_exec($ch);

    if($output === FALSE) {
      return FALSE;
    }

    curl_close($ch);

    return $output;
  }

}
