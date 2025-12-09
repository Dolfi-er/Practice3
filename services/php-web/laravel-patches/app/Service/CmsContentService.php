<?php
// app/Services/CmsContentService.php
namespace App\Services;

use App\Repositories\CmsBlockRepository;
use Illuminate\Support\Facades\Cache;

class CmsContentService
{
    private $repository;
    
    public function __construct(CmsBlockRepository $repository)
    {
        $this->repository = $repository;
    }
    
    public function getBlockContent(string $slug, bool $sanitize = true): ?string
    {
        $cacheKey = "cms_block_{$slug}";
        
        return Cache::remember($cacheKey, 3600, function () use ($slug, $sanitize) {
            $block = $this->repository->findActiveBySlug($slug);
            
            if (!$block) {
                return null;
            }
            
            return $sanitize ? $this->sanitizeHtml($block->content) : $block->content;
        });
    }
    
    private function sanitizeHtml(string $html): string
    {
        // Используем HTMLPurifier или аналогичную библиотеку
        $config = HTMLPurifier_Config::createDefault();
        $purifier = new HTMLPurifier($config);
        return $purifier->purify($html);
    }
}