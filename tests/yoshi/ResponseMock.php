<?php
/**
 * This file is part of Yoshi.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace yoshi;

class ResponseMock extends Response {

    public function send() {
        $this->has_been_sent = true;
    }

}