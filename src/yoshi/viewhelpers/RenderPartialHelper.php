<?php
/**
 * This file is part of Yoshi.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace yoshi\viewhelpers;

use yoshi\Views;

class RenderPartialHelper
{
    private $views;

    public function __construct(Views $views)
    {
        $this->views = $views;
    }

    public function renderPartial($template, $data = array())
    {
        return $this->views->create($template, null)
            ->bind($data)
            ->render();
    }
}