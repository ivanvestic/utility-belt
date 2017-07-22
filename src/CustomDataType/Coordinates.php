<?php
/**
 * (c) Ivan Veštić
 * http://ivanvestic.com
 */

namespace IvanVestic\UtilityBelt\CustomDataType;

use IvanVestic\UtilityBelt\Object\AbstractValueObject;

/**
 * Class Coordinates
 *
 * e.g. the max string length representation is
 * (note: signed symbol doesn't add additional "weight" to total length definition)
 * Latitude:  -90.1234567  [signed](9 length, 7 decimal points)
 * Longitude: -180.1234567 [signed](10 length, 7 decimal points)
 *
 * 7 decimals for GPS purposes is enough, "gis.stackexchange.com" quote:
 * "...The seventh decimal place is worth up to 11 mm: this is good for much surveying and is near the limit of what GPS-based techniques can achieve..."
 * @link https://gis.stackexchange.com/questions/8650/measuring-accuracy-of-latitude-and-longitude
 */
class Coordinates extends AbstractValueObject
{
    /**
     * Latitude range  -90 and +90
     * @var float|null
     */
    protected $latitude;

    /**
     * Longitude range -180 and +180
     * @var float|null
     */
    protected $longitude;


    /**
     * @return float|null
     */
    public function latitude()
    {
        return $this->latitude;
    }

    /**
     * @return float|null
     */
    public function longitude()
    {
        return $this->longitude;
    }
}