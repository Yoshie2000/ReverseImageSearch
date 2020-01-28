<?php

namespace Application\Service;

interface HammingDistanceServiceInterface
{
    public function calculateHammingDistance(string $image1, string $image2): int;
}