<?php

namespace TechiesAfrica\Devpilot\Traits\General;

trait ErrorTrackerTrait
{
    public function filterMetadata($metadata)
    {
        foreach ($metadata as $key => $value) {
            if ($this->checkIfMetadataKeyMatchesFilter($key)) {
                unset($metadata[$key]);
            }
        }
        return $metadata;
    }

    public function checkIfMetadataKeyMatchesFilter($key)
    {
        foreach ($this->getErrorTrackerMetadataFilters() as $filter) {
            try {
                $matches = trim(strtolower($key)) == trim(strtolower($filter));

                if ($matches) {
                    return true;
                }

                if (preg_match($filter . "i", $key)) {
                    return true;
                }
            } catch (\Throwable $th) {
                //throw $th;
            }
        }
        return false;
    }
}
