<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'parent_id',
    ];

    // ============ СВЯЗИ ============
    public function products()
    {
        return $this->hasMany(Product::class);
    }

    public function parent()
    {
        return $this->belongsTo(Category::class, 'parent_id');
    }

    public function children()
    {
        return $this->hasMany(Category::class, 'parent_id');
    }


    public function getAllProducts()
{
    $categoryIds = $this->getAllChildrenIds();
    $categoryIds[] = $this->id;
    
    return Product::whereIn('category_id', $categoryIds)
        ->where('is_active', true)
        ->get();
}

public function getAllChildrenIds(): array
{
    $ids = [];
    
    foreach ($this->children as $child) {
        $ids[] = $child->id;
        $ids = array_merge($ids, $child->getAllChildrenIds());
    }
    
    return $ids;
}

public function getProductsCount(): int
{
    return $this->products()->where('is_active', true)->count();
}

public function getPath(): string
{
    $path = [$this->name];
    $parent = $this->parent;
    
    while ($parent) {
        array_unshift($path, $parent->name);
        $parent = $parent->parent;
    }
    
    return implode(' > ', $path);
}

public function hasChildren(): bool
{
    return $this->children()->count() > 0;
}

public function isParent(): bool
{
    return is_null($this->parent_id);
}

public function getUrl(): string
{
    return route('category.show', $this->id);
}
}