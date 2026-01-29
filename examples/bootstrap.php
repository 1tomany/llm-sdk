<?php

require_once __DIR__.'/../vendor/autoload.php';

use Symfony\Component\PropertyInfo\Extractor\ConstructorExtractor;
use Symfony\Component\PropertyInfo\Extractor\PhpDocExtractor;
use Symfony\Component\PropertyInfo\PropertyInfoExtractor;
use Symfony\Component\Serializer\Normalizer\ArrayDenormalizer;
use Symfony\Component\Serializer\Normalizer\BackedEnumNormalizer;
use Symfony\Component\Serializer\Normalizer\DateTimeNormalizer;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Normalizer\UnwrappingDenormalizer;
use Symfony\Component\Serializer\Serializer;

function createSerializer(): Serializer
{
    // Construct the Symfony Normalizers and Denormalizers
    $propertyInfoExtractor = new PropertyInfoExtractor([], [
        new ConstructorExtractor([new PhpDocExtractor()]),
    ]);

    $serializer = new Serializer([
        new ArrayDenormalizer(),
        new BackedEnumNormalizer(),
        new DateTimeNormalizer(),
        new UnwrappingDenormalizer(),

        // This must come last so it can use the UnwrappingDenormalizer
        new ObjectNormalizer(propertyInfoExtractor: $propertyInfoExtractor),
    ]);

    return $serializer;
}
