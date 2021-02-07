<?php declare(strict_types=1);
namespace GifGrabber;

use DateTime;
use Exception;
use Throwable;

class Gif extends Model
{
  private Category|

null $category = null;

  protected array $doNotSet = [
    'ImageUrl',
    'AnimationUrl',
    'VideoUrl',
  ];

  protected array $alsoSerialize = [
    'ImageUrl',
    'AnimationUrl',
    'VideoUrl',
  ];

  public static function getTableName(): string
  {
    return 'gif';
  }

  protected function getDefaults(): array
  {
    return [
      'Id' => null,
      'CreatedAt' => (new DateTime())->format('Y-m-d G:i:s'),
      'UpdatedAt' => (new DateTime())->format('Y-m-d G:i:s'),
      'Approved' => 0,
      'Title' => null,
      'Caption' => null,
      'Url' => null,
      'CategoryId' => null,
    ];
  }

  public function getApproved(): bool
  {
    return $this->getBool('approved');
  }

  public function setApproved(bool $approved): void
  {
    $this->setBool('approved', $approved);
  }

  public function getTitle(): string
  {
    return $this->getString('title');
  }

  public function setTitle(string $title): void
  {
    $this->setString('title', $title);
  }

  public function getCaption(): ?string
  {
    try {
      return $this->getString('caption');
    } catch (Throwable $e) {
      return null;
    }
  }

  public function setCaption(string $caption = null): void
  {
    if (is_null($caption)) {
      $this->setNull('caption');
    } else {
      $this->setString('caption', $caption);
    }
  }

  public function getUrl(): string
  {
    return $this->getString('url');
  }

  public function setUrl(string $url): void
  {
    $correctedUrl = $url;
    if (preg_match('~^//~', $correctedUrl)) {
      $correctedUrl = sprintf('https:%s', $correctedUrl);
    }
    if (!preg_match('~^http~', $correctedUrl)) {
      $correctedUrl = sprintf('https://%s', $correctedUrl);
    }
    if (parse_url($correctedUrl) === false) {
      throw new Exception('Provided URL is invalid.');
    }
    $headers = get_headers($correctedUrl, 1);
    if (empty($headers)) {
      throw new Exception(sprintf('Could not reach URL: %s', $url));
    }
    $integerKeyHeaders = array_filter($headers, function (mixed $key) {
      return is_int($key);
    }, ARRAY_FILTER_USE_KEY);
    if (array_pop($integerKeyHeaders) !== 'HTTP/1.1 200 OK') {
      throw new Exception(sprintf('Request did not return 200 OK for URL: %s', $correctedUrl));
    }
    $this->setString('url', $correctedUrl);
  }

  public function getFileType(): string
  {
    return $this->getString('file_type');
  }

  public function getCategoryId(): int
  {
    return $this->getInteger('category_id');
  }

  public function setCategoryId(int $categoryId): void
  {
    $this->setInteger('category_id', $categoryId);
  }

  public function getCategory(): Category
  {
    if (is_null($this->category)) {
      $this->category = Category::findById($this->getCategoryId());
    }
    return $this->category;
  }

  public function getStoragePath(): string
  {
    if (is_null($this->getId())) {
      throw new Exception('Tried to get storage path of un-saved Gif.');
    }
    $path = sprintf(
      '%s/gif/%d',
      Config::getStoragePath(),
      $this->getId() ?? 0
    );
    if (!file_exists($path)) {
      mkdir($path, 0777, true);
    }
    return $path;
  }

  public function getImageUrl(): ?string
  {
    $fileName = sprintf(
      '%s/image.jpg',
      $this->getStoragePath()
    );
    $url = sprintf(
      '%s/gif/%d/image.jpg',
      Config::getStorageUrl(),
      $this->getId() ?? 0
    );
    return file_exists($fileName) ? $url : null;
  }

  public function getAnimationUrl(): ?string
  {
    $fileName = sprintf(
      '%s/animation.gif',
      $this->getStoragePath()
    );
    $url = sprintf(
      '%s/gif/%d/animation.gif',
      Config::getStorageUrl(),
      $this->getId() ?? 0
    );
    return file_exists($fileName) ? $url : null;
  }

  public function getVideoUrl(): ?string
  {
    $fileName = sprintf(
      '%s/video.mp4',
      $this->getStoragePath()
    );
    $url = sprintf(
      '%s/gif/%d/video.mp4',
      Config::getStorageUrl(),
      $this->getId() ?? 0
    );
    return file_exists($fileName) ? $url : null;
  }

  protected function hookBeforeInsert(): void
  {
    if ($this->getApproved()) {
      Admin::guard();
    }
  }

  protected function hookBeforeUpdate(): void
  {
    Admin::guard();
  }

  protected function hookAfterSave(): void
  {
    if ($this->wasChanged('url')) {
      GifGrabber::grab($this);
    }
  }

  protected function hookBeforeDelete(): void
  {
    Admin::guard();
    $files = glob(sprintf('%s/*', $this->getStoragePath())) ?: [];
    foreach ($files as $file) {
      unlink($file);
    }
    rmdir($this->getStoragePath());
  }

  public function getAnimationPath(): string
  {
    return sprintf('%s/animation.gif', $this->getStoragePath());
  }

  public function getVideoPath(): string
  {
    return sprintf('%s/video.mp4', $this->getStoragePath());
  }

  public function getImagePath(): string
  {
    return sprintf('%s/image.jpg', $this->getStoragePath());
  }

  public function saveVideo(string $videoUrl): void
  {
    $videoExtension = Utility::getUrlFileExtension($videoUrl);
    if ($videoExtension === 'ts') {
      $this->saveTsVideo($videoUrl);
      return;
    }
    $videoPath = $this->getVideoPath();
    if (file_exists($videoPath)) {
      unlink($videoPath);
    }
    copy($videoUrl, $videoPath);
  }

  private function saveTsVideo(string $tsVideoUrl): void
  {
    $destinationTs = sprintf('%s/video.ts', $this->getStoragePath());
    $destinationMp4 = $this->getVideoPath();
    if (file_exists($destinationTs)) {
      unlink($destinationTs);
    }
    copy($tsVideoUrl, $destinationTs);
    // delete preexisting video
    if (file_exists($destinationMp4)) {
      unlink($destinationMp4);
    }
    // convert TS to MP4
    $command = sprintf(
      'ffmpeg -i %s -c:v libx264 -c:a aac %s',
      $destinationTs,
      $destinationMp4
    );
    exec($command);
  }

  public function saveAnimation(string $animationUrl): void
  {
    $animationPath = $this->getAnimationPath();
    if (file_exists($animationPath)) {
      unlink($animationPath);
    }
    copy($animationUrl, $animationPath);
  }

  public function saveImage(string $imageUrl): void
  {
    $imagePath = $this->getImagePath();
    if (file_exists($imagePath)) {
      unlink($imagePath);
    }
    // if png, convert
    $extension = Utility::getUrlFileExtension($imageUrl);
    $imagePath = $this->getImagePath();
    if (in_array($extension, ['jpg', 'jpeg'])) {
      copy($imageUrl, $imagePath);
      return;
    }
    if ($extension === 'png') {
      $destinationPng = sprintf('%s/image.png', $this->getStoragePath());
      if (file_exists($destinationPng)) {
        unlink($destinationPng);
      }
      copy($imageUrl, $destinationPng);
      $command = sprintf(
        'gm mogrify -resize "15000x15000>" -background white -extent 0x0 -format jpg -quality 75 %s',
        $destinationPng
      );
      exec($command);
    }
  }

  public function animationToVideo(): void
  {
    $animationPath = $this->getAnimationPath();
    $videoPath = $this->getVideoPath();
    if (file_exists($videoPath)) {
      unlink($videoPath);
    }
    exec(sprintf(
      'ffmpeg -i %s -movflags faststart -pix_fmt yuv420p -vf "scale=trunc(iw/2)*2:trunc(ih/2)*2" %s',
      $animationPath,
      $videoPath
    ));
  }

  public function videoToAnimation(): void
  {
    $videoPath = $this->getVideoPath();
    $animationPath = $this->getAnimationPath();
    if (file_exists($animationPath)) {
      unlink($animationPath);
    }
    exec(sprintf(
      'ffmpeg -i %s -vf "fps=12,scale=320:-1" -loop 0 %s',
      $videoPath,
      $animationPath
    ));
  }

  private function firstFrameToImage(string $sourcePath): void
  {
    $imagePath = $this->getImagePath();
    if (file_exists($imagePath)) {
      unlink($imagePath);
    }
    exec(sprintf(
      'ffmpeg -i %s -vframes 1 %s',
      $sourcePath,
      $imagePath
    ));
  }

  public function animationToImage(): void
  {
    $this->firstFrameToImage($this->getAnimationPath());
  }

  public function videoToImage(): void
  {
    $this->firstFrameToImage($this->getVideoPath());
  }
}
