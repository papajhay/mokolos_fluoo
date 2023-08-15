<?php

namespace App\Entity;

use Doctrine\ORM\Mapping\ClassMetadata;
use Doctrine\ORM\Mapping\MappingException;
use Doctrine\Persistence\ManagerRegistry;

class BaseEntity
{

    /**
     * @throws MappingException
     */
    public function toArray(ManagerRegistry $doctrine): array
    {
        $entityManager = $doctrine->getManager();
        $classMetadata = $entityManager->getClassMetadata(get_class($this));

        $data = [];
        $reflectionClass = new \ReflectionClass($this);
        $properties = $reflectionClass->getProperties();

        foreach ($properties as $property) {
            $property->setAccessible(true);
            $propertyName = $property->getName();
            $propertyValue = $property->getValue($this);

            if ($classMetadata->hasAssociation($propertyName)) {
                $associationMapping = $classMetadata->getAssociationMapping($propertyName);

                // Exclude OneToMany relations
                if ($associationMapping['type'] === ClassMetadata::ONE_TO_MANY) {
                    continue;
                }

                if ($associationMapping['type'] === ClassMetadata::MANY_TO_ONE || $associationMapping['type'] === ClassMetadata::ONE_TO_ONE) {
                    if (isset($associationMapping['joinColumns'][0]['name'])) {
                        $columnName = $associationMapping['joinColumns'][0]['name'];
                        $data[$columnName] = $propertyValue->getId();
                    }
                }
            } else {
                $columnMapping = $classMetadata->getFieldMapping($propertyName);
                if ($columnMapping && isset($columnMapping['columnName'])) {
                    $columnName = $columnMapping['columnName'];
                    $data[$columnName] = $propertyValue;
                }
            }
        }

        return $data;
    }

}