<?php

namespace Quasar\Platform\Support\Facades;


/**
* @see \Quasar\Platform\Session\Store
*/
class Session extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor() { return 'session'; }
}
