<?php

namespace DrupalPOC;

use Composer\Script\Event;
use Symfony\Component\Filesystem\Filesystem;

/**
 * Class PostBuild.
 *
 * @package DrupalPOC
 */
class PostBuild {

  /**
   * Replace default Drupal htaccess with a customized one.
   *
   * @param \Composer\Script\Event $event
   *   Event to echo output.
   */
  public static function replaceHtaccess(Event $event) {
    $fs = new Filesystem();
    $project_root = getcwd();

    $fs->copy($project_root . '/poc.htaccess', $project_root . '/docroot/.htaccess', TRUE);
    $event->getIO()->write("    Replaced .htaccess with a custom version.");
  }

  /**
   * Replace default Drupal robots.txt with a customized one.
   *
   * @param \Composer\Script\Event $event
   *   Event to echo output.
   */
  public static function replaceRobotsTxt(Event $event) {
    $fs = new Filesystem();
    $project_root = getcwd();

    $fs->copy($project_root . '/poc.robots.txt', $project_root . '/docroot/robots.txt', TRUE);
    $event->getIO()->write("    Replaced robots.txt with a custom version.");
  }

}
