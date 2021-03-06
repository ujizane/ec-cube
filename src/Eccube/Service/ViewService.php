<?php
/*
 * This file is part of EC-CUBE
 *
 * Copyright(c) 2000-2015 LOCKON CO.,LTD. All Rights Reserved.
 *
 * http://www.lockon.co.jp/
 *
 * This program is free software; you can redistribute it and/or
 * modify it under the terms of the GNU General Public License
 * as published by the Free Software Foundation; either version 2
 * of the License, or (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 59 Temple Place - Suite 330, Boston, MA  02111-1307, USA.
 */


namespace Eccube\Service;

use Eccube\Event\RenderEvent;

class ViewService
{
    private $app;

    public function __construct($app)
    {
        $this->app = $app;
    }

    public function render($name, array $context = array())
    {
        $template = $this->app['twig']->loadTemplate($name);
        $compiledSource = $template->render($context);

        $event = new RenderEvent($compiledSource);

        $route = str_replace('_', '.', $this->app['request']->attributes->get('_route'));
        $this->app['eccube.event.dispatcher']->dispatch('eccube.event.render.' . $route . '.before', $event);

        $source = $event->getSource();

        return $source;
    }
}
