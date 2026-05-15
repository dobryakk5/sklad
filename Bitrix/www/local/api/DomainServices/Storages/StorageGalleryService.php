<?php

namespace Api\DomainServices\Storages;

use Api\Models\StorageGallery;

class StorageGalleryService
{
    public function getGalleryPictures(int $galleryId, $count = false)
    {
        $gallery = StorageGallery::getGallery($galleryId);
        $images = $gallery->getImages();

        if ($count) {
            $images = array_slice($images, 0, $count);
        }

        return $images;
    }
}