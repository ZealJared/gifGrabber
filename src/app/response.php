<?php
namespace GifGrabber;

use stdClass;

class Response {
  /** @var stdClass */
  private $payload;

  public function __construct()
  {
    $this->payload = new stdClass();
  }

  public function setDataObject(object $data): void
  {
    $this->payload->data = $data;
  }

  public function addError(string $message): void
  {
    if(!property_exists($this->payload, 'errors'))
    {
      $this->payload->errors = [];
    }
    assert(is_array($this->payload->errors));
    $this->payload->errors[] = $message;
  }

  public function render(): void
  {
    header('Content-Type: application/json');
    die(json_encode($this->payload, JSON_PRETTY_PRINT));
  }
}
