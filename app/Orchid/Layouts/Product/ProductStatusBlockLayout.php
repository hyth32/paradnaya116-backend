<?php

namespace App\Orchid\Layouts\Product;

use Orchid\Screen\Layout;
use Orchid\Screen\Repository;

class ProductStatusBlockLayout extends Layout
{
    protected $template = 'orchid.product.status';

    public function build(Repository $repository)
    {
        return view($this->template, [
            'product' => $repository->get('product'),
        ]);
    }
}
