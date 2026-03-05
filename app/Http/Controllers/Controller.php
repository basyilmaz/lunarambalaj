<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController
{
    use AuthorizesRequests;
    use ValidatesRequests;

    protected function seo(
        string $title,
        string $description,
        string $canonical,
        array $alternates = [],
        array $jsonLd = [],
        string $type = 'website',
        ?string $image = null,
    ): array {
        $title = mb_substr(trim($title), 0, 60);
        $description = mb_substr(trim($description), 0, 160);

        return [
            'title' => $title,
            'description' => $description,
            'canonical' => $canonical,
            'alternates' => $alternates,
            'jsonLd' => $jsonLd,
            'type' => $type,
            'image' => $image,
        ];
    }

    protected function absoluteUrl(string $path): string
    {
        return rtrim(config('app.url'), '/') . $path;
    }
}
