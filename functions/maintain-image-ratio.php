<?php

/**
 * Maintain the image ratio for cropped images when the requested sizes are larger than the original size.
 */

add_filter('image_resize_dimensions', static function (
    mixed $default,
    int   $originalWidth,
    int   $originalHeight,
    ?int  $targetWidth,
    ?int  $targetHeight,
          $crop,
): ?array {
    // return early if
    if (
        // no crop
        !$crop
        // already short-circuited
        || $default !== null
        // both dimensions are 0 (should not happen but saves checks later)
        || (($targetWidth ?? 0) === 0 && ($targetHeight ?? 0) === 0)
        // whether it is in landscape orientation and the requested image fits in the original
        || ($originalWidth > $originalHeight && ($originalWidth >= $targetWidth && $originalHeight >= $targetHeight))
    ) {
        return $default;
    }

    $aspectRatioOriginal = $originalWidth / $originalHeight;

    // Handle division by zero: Calculate missing dimension(s) from the aspect ratio.
    if ($targetHeight === 0) {
        $targetHeight = (int)floor($targetWidth * ($originalHeight / $originalWidth));
    } elseif ($targetWidth === 0) {
        $targetWidth = (int)floor($targetHeight * $aspectRatioOriginal);
    }

    $aspectRatioTarget = $targetWidth / $targetHeight;

    if ($aspectRatioOriginal > $aspectRatioTarget) {
        $newHeight = min($originalHeight, $targetHeight);
        $newWidth  = (int)round($newHeight * $aspectRatioTarget);
        $cropX     = $crop[0] ?? floor(($originalWidth - $newWidth) / 2);
        $cropY     = $crop[1] ?? floor(($originalHeight - $newHeight) / 2);
    } else {
        // Original is taller relative to the target aspect ratio
        $newWidth  = min($originalWidth, $targetWidth);
        $newHeight = (int)round($newWidth / $aspectRatioTarget);
        $cropX     = 0;
        $cropY     = 0;
    }


    return [0, 0, $cropX, $cropY, $targetWidth, $targetHeight, $newWidth, $newHeight];
}, 10, 6);
