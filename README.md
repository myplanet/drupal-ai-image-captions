# Proof-of-Concept for Drupal AI Image Captions

## Table of Contents

1. [Build Instructions](#1-build-instructions)
2. [Documentation](#3-documentation)

## 1. Build Instructions

### Requirements

* PHP 7.1.8+
* Drush 8.0.*
* Drupal Console 8.0.*
* Compass 1.0.*

May be useful to get [multiple versions of drush locally](https://www.lullabot.com/articles/switching-drush-versions)

### Building drupal

From the project root:

`composer install`

Then, to install the site:

`drush site-install poc --db-url=mysql://user:pass@127.0.0.1/poc --account-name=admin --account-pass=xx`

At times, it may be required to update entity configuration, especially when translations are set for an entity, you need to run `entity update` in order to correctly set all properties:

`drush entity-updates`

## 2. Documentation

Documentation for this project is available at the [github wiki](/wiki).
