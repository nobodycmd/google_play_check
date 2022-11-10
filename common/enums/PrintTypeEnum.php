<?php

namespace common\enums;

/**
 * Class PrintTypeEnum
 * @package common\enums
 * @author Rf <1458015476@qq.com>
 */
class PrintTypeEnum extends BaseEnum
{
    const YI_LIAN_YUN = 'yi_lian_yun';
    const FEI_E = 'fei_e';

    /**
     * @return array|string[]
     */
    public static function getMap(): array
    {
        return [
            self::YI_LIAN_YUN => '易联云小票打印',
            self::FEI_E => '飞鹅小票打印',
        ];
    }
}