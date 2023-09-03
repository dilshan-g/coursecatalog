<?php

namespace Drupal\course_catalog;

use GuzzleHttp\Client;

class HttpService {

  /**
   * GuzzleHttp\Client definition.
   *
   * @var GuzzleHttp\Client
   */
  protected $http_client;
  /**
   * Constructor.
   */
  public function __construct(Client $http_client) {
    $this->http_client = $http_client;
    parent::__construct();
  }
}
