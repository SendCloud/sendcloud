<?php

namespace SendCloud\SendCloud\CheckoutCore\Contracts\Proxies;

use SendCloud\SendCloud\CheckoutCore\Exceptions\HTTP\HttpException;

/**
 * Interface CheckoutProxy
 *
 * @package SendCloud\SendCloud\CheckoutCore\Contracts\Proxies
 */
interface CheckoutProxy
{
    /**
     * Deletes configuration on middleware.
     *
     * @return void
     *
     * @throws HttpException
     */
    public function delete();
}
