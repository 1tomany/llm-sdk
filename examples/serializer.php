<?php

use Symfony\Component\PropertyInfo\Extractor\ConstructorExtractor;
use Symfony\Component\PropertyInfo\Extractor\PhpDocExtractor;
use Symfony\Component\PropertyInfo\PropertyInfoExtractor;
use Symfony\Component\Serializer\Normalizer\ArrayDenormalizer;
use Symfony\Component\Serializer\Normalizer\BackedEnumNormalizer;
use Symfony\Component\Serializer\Normalizer\DateTimeNormalizer;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Normalizer\UnwrappingDenormalizer;
use Symfony\Component\Serializer\Serializer;

require_once __DIR__.'/../vendor/autoload.php';

// Create a suitable Symfony Serializer
$typeExtractor = new PropertyInfoExtractor([], [
    new ConstructorExtractor([new PhpDocExtractor()]),
]);

$objectNormalizer = new ObjectNormalizer(...[
    'propertyTypeExtractor' => $typeExtractor,
]);

return new Serializer([new BackedEnumNormalizer(), new DateTimeNormalizer(), new ArrayDenormalizer(), new UnwrappingDenormalizer(), $objectNormalizer]);
