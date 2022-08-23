<?php

namespace ZapMeSdk\Actions\Contacts;

use Exception;
use ZapMeSdk\Traits\PerformRequest;
use ZapMeSdk\Traits\ShareableConstructor;

class GetContacts
{
    use PerformRequest;
    use ShareableConstructor;

    /**
     * Path related with the action.
     *
     * @param  bool  $paginate
     * @param  int  $page
     * @param  int  $quantity
     * @return string
     */
    public function path(bool $paginate = false, int $page = 1, int $quantity = 10): string
    {
        $url = sprintf('/contacts?api=%s&secret=%s', $this->api, $this->secret);

        if ($paginate) {
            $url .= sprintf('&paginate=true&page=%s&quantity=%s', $page, $quantity);
        }

        return $url;
    }

    /**
     * @throws Exception
     */
    public function __invoke(bool $paginate = false, int $page = 1, int $quantity = 10)
    {
        $this->method('GET');

        return $this->request($this->path($paginate, $page, $quantity));
    }
}
