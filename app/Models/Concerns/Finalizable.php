<?php

namespace App\Models\Concerns;

trait Finalizable
{
    /**
     * Every model that uses this trait should define how
     * to access its related finalization or parent that has one.
     */
    public function getFinalizationSource()
    {
        // By default, assume the model itself has finalizations
        return $this;
    }

    /**
     * Check if the model (or its source) is finalized.
     */
    public function isFinalized(): bool
    {
        $source = $this->getFinalizationSource();

        if (method_exists($source, 'finalizations')) {
            return $source->finalizations->contains('confirmed', true);
        }

        if (property_exists($source, 'is_finalized')) {
            return (bool) $source->is_finalized;
        }

        return false;
    }
}
