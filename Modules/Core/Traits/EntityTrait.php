<?php

namespace Modules\Core\Traits;

/**
 * Module Core Providers: Modules\Core\Traits\EntityTrait
 *
 * Long description for class (if any)...
 *
 * @package    DCM
 * @author     Anthony Pillos <dev.anthonypillos@gmail.com>
 * @copyright  2018 (c) DCM
 * @version    Release: v1.0.0
 * @link       http://devcorpmanila.com
*/


trait EntityTrait
{

    public $cacheTTL     = 1440;//24hours
    public $cache_prefix = 'dcm_tbl';

    public function tableNameWithPrefix()
    {
        return "{$this->cache_prefix}:{$this->getTable()}";
    }

}