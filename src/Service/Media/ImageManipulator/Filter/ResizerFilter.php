<?php

namespace App\Service\Media\ImageManipulator\Filter;

use Intervention\Image\Constraint;
use Intervention\Image\Filters\FilterInterface;
use Intervention\Image\Image;

class ResizerFilter implements FilterInterface
{
    private int $width;

    private int $height;

    public function __construct(array $options)
    {
        $this->width = $options['width'] ?? null;
        $this->height = $options['height'];
    }

    public function applyFilter(Image $image): Image
    {
        if (null !== $this->width && null !== $this->height) {
            $image->fit($this->width, $this->height);
        } else if (null !== $this->width) {
            $image
                ->resize($this->width, null, function (Constraint $constraint) {
                    $constraint->aspectRatio();
                });
        }

        return $image;
    }
}