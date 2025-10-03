<?php

namespace App\Orchid\Screens\Product;

use App\Enums\Product\ProductStatus;
use App\Http\Requests\Admin\Product\SaveProductRequest;
use App\Models\Product;
use App\Orchid\Layouts\Product\ProductEditLayout;
use Orchid\Screen\Actions\Button;
use Orchid\Screen\Actions\Link;
use Orchid\Screen\Screen;
use Orchid\Support\Facades\Toast;

class ProductEditScreen extends Screen
{
    public ?Product $product = null;
    
    public function query(Product $product): iterable
    {
        $product->load(['images', 'mainImage', 'detailImages']);

        return [
            'product' => $product,
            'product.main_image' => $product->getMainImagePath(),
            'product.detail_images' => $this->getDetailImageAttachments($product),
            'product.status' => $product->status?->value ?? 'active',
        ];
    }

    public function name(): ?string
    {
        return $this->product->exists ? 'Редактирование товара' : 'Создание товара';
    }

    public function commandBar(): iterable
    {
        return [
            Link::make('Назад')
                ->icon('bs.arrow-left')
                ->route('products.index'),

            Button::make(__('Save'))
                ->icon('bs.check-circle')
                ->method('save'),
        ];
    }

    public function layout(): iterable
    {
        return [
            ProductEditLayout::class,
        ];
    }

    public function save(Product $product, SaveProductRequest $request)
    {
        $data = collect($request->validated())->get('product');
        
        $mainImage = $data['main_image'] ?? null;
        $detailImages = $data['detail_images'] ?? null;
        
        unset($data['main_image'], $data['detail_images']);

        if (isset($data['status'])) {
            $data['status'] = ProductStatus::from($data['status']);
        }

        $product->fill($data)->save();

        $product->saveMainImage($this->extractRelativePath($mainImage));
        
        if ($detailImages && is_array($detailImages)) {
            $relativePaths = [];
            foreach ($detailImages as $attachmentId) {
                if (is_numeric($attachmentId)) {
                    $attachment = \DB::table('attachments')->find($attachmentId);
                    if ($attachment) {
                        $fullPath = $attachment->path . $attachment->name . '.' . $attachment->extension;
                        $relativePaths[] = $fullPath;
                    }
                } elseif (is_string($attachmentId)) {
                    $relativePaths[] = $this->extractRelativePath($attachmentId);
                }
            }
            $product->saveDetailImages($relativePaths);
        } else {
            $product->saveDetailImages(null);
        }

        Toast::success($product->wasRecentlyCreated ? 'Товар добавлен' : 'Изменения сохранены');

        $status = $product->status;

        return redirect()->route('products.index', ['status' => $status->value]);
    }

    private function extractRelativePath(?string $fullPath): ?string
    {
        if (!$fullPath) {
            return null;
        }

        if (str_starts_with($fullPath, 'http')) {
            $parsedUrl = parse_url($fullPath);
            $path = $parsedUrl['path'] ?? '';
            return ltrim($path, '/storage/');
        }

        return $fullPath;
    }

    private function getDetailImageAttachments(Product $product): array
    {
        $detailImagePaths = $product->getDetailImagePaths();
        $attachmentIds = [];

        foreach ($detailImagePaths as $imagePath) {
            $attachment = \DB::table('attachments')
                ->where('path', 'like', '%' . dirname($imagePath) . '/')
                ->where('name', basename($imagePath, '.' . pathinfo($imagePath, PATHINFO_EXTENSION)))
                ->first();

            if ($attachment) {
                $attachmentIds[] = (string)$attachment->id;
            }
        }

        return $attachmentIds;
    }
}
