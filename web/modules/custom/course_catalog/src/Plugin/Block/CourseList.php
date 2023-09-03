<?php

namespace Drupal\course_catalog\Plugin\Block;

use Drupal\Core\Block\BlockBase;
use Drupal\Core\Plugin\ContainerFactoryPluginInterface;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Provides a 'Course List' Block.
 *
 * @Block(
 *   id = "course_list",
 *   admin_label = @Translation("Course List"),
 *   category = @Translation("Lists"),
 * )
 */
class CourseList extends BlockBase implements ContainerFactoryPluginInterface {

  /**
   * Guzzle Http Client.
   *
   * @var GuzzleHttp\Client
   */
  protected $httpClient;

  /**
   * The logger factory used for logging.
   *
   * @var \Drupal\Core\Logger\LoggerChannelFactoryInterface
   */
  protected $loggerFactory;

  /**
   * @param array $configuration
   * @param $plugin_id
   * @param $plugin_definition
   * @param Client $http_client
   */
  public function __construct(
    array $configuration,
    $plugin_id,
    $plugin_definition,
    Client $http_client
  ) {
    parent::__construct($configuration, $plugin_id, $plugin_definition);
    $this->httpClient = $http_client;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container, array $configuration, $plugin_id, $plugin_definition) {
    return new static(
      $configuration,
      $plugin_id,
      $plugin_definition,
      $container->get('http_client')
    );
  }

  /**
   * {@inheritdoc}
   */
  public function build() {
    try {
      $response = $this->httpClient->get('https://feeb01ff-3081-415f-993e-5675ab999efd.mock.pstmn.io/api/v1/courses',
        array('headers' => array('Accept' => 'application/json')));
      $data = json_decode($response->getBody()->getContents());
      if (empty($data)) {
        return FALSE;
      }
      return [
        '#theme' => 'course_list',
        '#data' => $data,
      ];
    }
    catch (RequestException $e) {
      $variables = [
        '@error_message' => $e->getMessage(),
      ];
      $this->loggerFactory->get('course_catalog')
        ->error('Could not make a request to the API: @error_message', $variables);
    }
  }
}
