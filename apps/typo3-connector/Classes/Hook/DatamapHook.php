<?php
namespace PixelCoda\HeadlessSearchConnector\Hook;

use TYPO3\CMS\Core\DataHandling\DataHandler;

class DatamapHook {
  public function processDatamap_afterDatabaseOperations($status, $table, $id, $fieldArray, DataHandler $pObj) {
    $api = getenv('PC_API_BASE') ?: 'http://localhost:8787';
    $key = getenv('PC_API_WRITE_KEY') ?: 'write_example_key';
    $project = getenv('PC_PROJECT_ID') ?: 'demo';
    $collection = $table;
    $url = $api . '/v1/index/' . rawurlencode($project) . '/' . rawurlencode($collection);
    $payload = [
      'id' => (string)$table . ':' . (string)$id,
      'project_id' => $project,
      'collection' => $collection,
      'lang' => 'de',
      'title' => $fieldArray['title'] ?? ($fieldArray['header'] ?? ($fieldArray['name'] ?? 'Eintrag')),
      'content' => strip_tags(json_encode($fieldArray))
    ];
    $opts = [
      'http' => [
        'method' => 'POST',
        'header' => "Content-Type: application/json\r\n" . "x-api-key: $key\r\n",
        'content' => json_encode($payload),
        'timeout' => 2
      ]
    ];
    @file_get_contents($url, false, stream_context_create($opts));
  }
}
