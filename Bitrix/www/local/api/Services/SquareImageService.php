<?php


namespace Api\Services;


class SquareImageService
{
    const PICTURE_SIZE_SMALL = 'SMALL',
        PICTURE_SIZE_MEDIUM = 'MEDIUM',
        PICTURE_SIZE_BIG = 'BIG';

    const RENTAL_CATALOG_PATH = '/bitrix/templates/aspro-priority/components/bitrix/catalog.section.list/fotogalereya_skladov_rental_catallog';

    public static function getRentalCatalogSquareImage(string $pictureSize, int $squareFrom)
    {
        $path = '';

        if ($pictureSize == self::PICTURE_SIZE_SMALL) {
            $path = "/upload/images/PICTURE_".$squareFrom.".jpg";

        } elseif ($pictureSize == self::PICTURE_SIZE_MEDIUM) {
            $path = self::RENTAL_CATALOG_PATH."/images/square_".$squareFrom.".jpg";

        } elseif ($pictureSize == self::PICTURE_SIZE_BIG) {
            $path = self::RENTAL_CATALOG_PATH."/images/square_".$squareFrom.".jpg";

        }

        return $path;
    }
}