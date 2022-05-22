<?php

namespace App\Helper;

class AresHelper
{
    public function getStreet($aresRecord): ?string
    {
        $street = $aresRecord->street ?: 'unknown street';
        $houseNumber = $aresRecord->streetHouseNumber ?: 'unknown house number';
        $orientationNumber = $aresRecord->streetOrientationNumber ? ('/'.$aresRecord->streetOrientationNumber) : null;

        return $street . ' ' . $houseNumber . $orientationNumber;
    }
}