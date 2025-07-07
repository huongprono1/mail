<?php

namespace App\Models;

use Awcodes\Curator\Models\Media;
use Illuminate\Database\Eloquent\Casts\Attribute;

class CuratorMedia extends Media
{
    protected $table = 'media';

    public function getWidth(): int
    {
        return 920;
    }

    public function getHeight(): int
    {
        return 430;
    }

    // make attribute
    public function coverImage(): ?Attribute
    {
        return Attribute::make(
            get: fn() => asset($this->getSignedUrl()),
        );
    }

    // make attribute
    public function productImage(): ?Attribute
    {
        return Attribute::make(
            get: fn() => $this->getSignedUrl(['q' => 60, 'w' => $this->getWidth(), 'h' => $this->getHeight(), 'fit' => 'crop', 'fm' => 'webp']),
        );
    }

    public function thumbnailImage(): ?Attribute
    {
        return Attribute::make(
            get: fn() => $this->getSignedUrl(['q' => 80, 'w' => 200, 'h' => 200, 'fit' => 'crop', 'fm' => 'webp']),
        );
    }

    public function getImageUrl($quality = 100): string
    {
        return $this->getSignedUrl(['q' => $quality, 'w' => $this->getWidth(), 'h' => $this->getHeight(), 'fit' => 'crop', 'fm' => 'webp']);
    }
}
