<?php

namespace App\Orchid\Layouts\Service;

use Orchid\Screen\Layout;
use Orchid\Screen\Repository;

class ServiceStatusBlockLayout extends Layout
{
    protected $template = 'orchid.service.status';

    public function build(Repository $repository)
    {
        return view($this->template, [
            'service' => $repository->get('service'),
        ]);
    }
}