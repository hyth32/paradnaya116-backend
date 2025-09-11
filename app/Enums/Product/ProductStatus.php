<?php

namespace App\Enums\Product;

enum ProductStatus: string
{
    case Active = 'active';
    case Archived = 'archived';
    case Trashed = 'trashed';
}
