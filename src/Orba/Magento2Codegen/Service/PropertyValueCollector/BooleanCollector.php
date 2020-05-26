<?php

namespace Orba\Magento2Codegen\Service\PropertyValueCollector;

use InvalidArgumentException;
use Orba\Magento2Codegen\Model\BooleanProperty;
use Orba\Magento2Codegen\Model\PropertyInterface;

class BooleanCollector extends AbstractInputCollector
{
    protected function validateProperty(PropertyInterface $property): void
    {
        if (!$property instanceof BooleanProperty) {
            throw new InvalidArgumentException('Invalid property type.');
        }
    }

    /**
     * @inheritDoc
     */
    protected function collectValueFromInput(PropertyInterface $property)
    {
        return $this->io->getInstance()
            ->confirm($this->questionPrefix . $property->getName(), $property->getDefaultValue());
    }
}
