<?php

namespace App\Repositories\Category;

use App\Model\Product;
use App\Model\Category;
use App\Model\CategoryGroup;
use App\Repositories\EloquentRepository;
use Illuminate\Database\Eloquent\Collection;
use App\Http\Resources\Category\CategoryResource;
use App\Http\Resources\Product\ProductCollection;
use App\Http\Resources\Category\CategoryCMSResource;
use App\Http\Resources\Attribute\AttributeCollection;
use App\Http\Resources\Category\CategoryCMSCollection;

class CategoryRepository extends EloquentRepository implements CategoryRepositoryInterface
{
    /**
     * get model
     * @return string
     */
    public function getModel()
    {
        return Category::class;
    }

    public function all($columns = array('*'))
    {
        return $this->_model::select($columns)->where('is_enabled', 1)->get();
    }

    public function create(array $attributes)
    {
        $category = $this->_model->create($attributes);
        return $category;
    }

    public function paginate($perPage = 15, $columns = array('*'))
    {
        return parent::paginate($perPage, $columns);
    }

    public function findBy($field, $value, $columns = array('*'))
    {
        return parent::findBy($field, $value, $columns);
    }

    public function getAttributesByCategory($categoryId) {
        $subCategories = $this->_model->where('slug', $categoryId)->orWhere('category_id', $categoryId)->firstOrFail()->descendants()->pluck('category_id');
        $categories = $this->_model->with('attributes')->active()
            ->whereIn('category_id', $subCategories->merge($categoryId))
            ->orWhere('slug', $categoryId)->get();

        $collection = new Collection();
        foreach ($categories as $category) {
            foreach($category->attributes as $attribute)
            {
                $collection->push($attribute);
            }
        }
        return new AttributeCollection($collection->unique());
    }

    public function getProductsByCategory($categoryId, $request)
    {
        $subCategories = $this->_model->where('slug', $categoryId)
            ->orWhere('category_id', $categoryId)
            ->firstOrFail()
            ->descendants()
            ->pluck('category_id');
        $products = Product::with('variants')
            ->whereIn('category_id', $subCategories->merge($categoryId))
            ->orderBy('created_at', 'desc')->active();

        if ($request->filled('from') || $request->filled('to')) {
            $products = $products->rangePrice($request->from, $request->to);
        }
        $perPage = $request->filled('perPage') ?? 5;
        $products = $products->paginate($perPage);
        return new ProductCollection($products);
    }


    public function getDiaryCategory() {
        return $this->_model->whereHas('group', function($query) {
           $query->where('group_name', env('CATEGORY_DIARY'));
        })->get()->first();
    }

    public function getCategoryByGroup($groupName, $type, $all = 0) {
        if ($groupName === 'all') {
            if($all == 1){
                if (!is_null($type))
                $categories = $this->_model
                    ->where('type',$type)
                    ->orderBy('name')
                    ->get();
                else{
                    $categories = $this->_model
                        ->orderBy('name')
                        ->get();
                }
                return CategoryCMSResource::collection($categories);
            }
            if (!is_null($type))
                $categories = $this->_model
                    ->where('type',$type)
                    ->orderBy('name')
                    ->paginate();
            else{
                $categories = $this->_model
                    ->orderBy('name')
                    ->paginate();
            }
        } else {
            if($all == 1){
                if (!empty($type)) {
                    $categories = $this->_model->
                    whereHas('group', function ($query) use ($groupName) {
                        $query->where('group_name', $groupName);
                    })
                        ->where('type',$type)
                        ->orderBy('name')
                        ->get();
                }
                else{
                    $categories = $this->_model->
                    whereHas('group', function ($query) use ($groupName) {
                        $query->where('group_name', $groupName);
                    })
                        ->orderBy('name')
                        ->get();
                } 
                return CategoryCMSResource::collection($categories);
            }
            if (!empty($type)) {
                $categories = $this->_model->
                whereHas('group', function ($query) use ($groupName) {
                    $query->where('group_name', $groupName);
                })
                    ->where('type',$type)
                    ->orderBy('name')
                    ->paginate();
            }
            else{
                $categories = $this->_model->
                whereHas('group', function ($query) use ($groupName) {
                    $query->where('group_name', $groupName);
                })
                    ->orderBy('name')
                    ->paginate();
            }
        }
        return new CategoryCMSCollection($categories);
    }

    public function update(array $attributes, $id)
    {
        $result = $this->find($id);
        if ($result) {
            $result->update($attributes);
            return $result;
        }
        return false;
    }

    public function delete($id)
    {
        $result = $this->find($id);
        if ($result) {
            $result->delete();
            return true;
        }
        return false;
    }
}
