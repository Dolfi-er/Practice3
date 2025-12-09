<?php
// app/Repositories/CmsBlockRepository.php
namespace App\Repositories;

use App\Models\CmsBlock;
use Illuminate\Support\Facades\DB;

class CmsBlockRepository
{
    public function findActiveBySlug(string $slug): ?CmsBlock
    {
        return CmsBlock::where('slug', $slug)
            ->where('is_active', true)
            ->first();
    }
    
    public function getDashboardContent(): array
    {
        return Cache::remember('dashboard_cms_blocks', 300, function () {
            return DB::table('cms_blocks')
                ->where('is_active', true)
                ->whereIn('slug', ['dashboard_experiment', 'welcome'])
                ->pluck('content', 'slug')
                ->toArray();
        });
    }
}