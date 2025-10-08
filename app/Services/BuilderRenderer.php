<?php 
namespace App\Services;

use App\Models\Product;
use Illuminate\Support\Arr;

class BuilderRenderer
{
    public function renderComponents(array $components): string
    {
        $html = '';

        foreach ($components as $comp) {
            $type = $comp['type'] ?? null;
            $attributes = $comp['attributes'] ?? [];
            $inner = '';

            if (!empty($comp['components']) && is_array($comp['components'])) {
                $inner = $this->renderComponents($comp['components']);
            }

            switch ($type) {
                case 'product-card':
                    $product = Product::find($attributes['productId'] ?? 0);
                    $html .= view($product ? 'builder.components.product_card' : 'builder.components.product_card_static', [
                        'product' => $product,
                        'attrs' => $attributes
                    ])->render();
                    break;

                case 'product-list':
                    $products = collect();
                    $source = $attributes['source'] ?? 'latest';
                    $limit = $attributes['limit'] ?? 4;

                    if ($source === 'manual') {
                        $ids = explode(',', $attributes['productIds'] ?? '');
                        $products = Product::whereIn('id', $ids)->take($limit)->get();
                    } elseif ($source === 'category' && !empty($attributes['categorySlug'])) {
                        $products = Product::where('category_slug', $attributes['categorySlug'])->take($limit)->get();
                    } else {
                        $products = Product::latest()->take($limit)->get();
                    }

                    $html .= '<div class="product-list">';
                    foreach ($products as $product) {
                        $html .= view('builder.components.product_card', ['product' => $product])->render();
                    }
                    $html .= '</div>';
                    break;

                default:
                    $tag = $comp['tagName'] ?? 'div';
                    $class = $attributes['class'] ?? '';
                    $html .= "<$tag class='$class'>$inner</$tag>";
            }
        }

        return $html;
    }
}