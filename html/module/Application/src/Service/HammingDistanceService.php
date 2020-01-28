<?php


namespace Application\Service;


use Jenssegers\ImageHash\Hash;

class HammingDistanceService implements HammingDistanceServiceInterface
{
    public function calculateHammingDistance(string $image1, string $image2): int
    {
        /** @var Hash $imageHash1 */
        $imageHash1 = Hash::fromInt($image1);
        /** @var Hash $imageHash2 */
        $imageHash2 = Hash::fromInt($image2);
        return $imageHash1->distance($imageHash2);
    }

}