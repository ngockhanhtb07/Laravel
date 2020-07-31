<?php

namespace App\Http\Controllers\API;

use App\Traits\UploadMedia;
use Illuminate\Support\Str;
use App\Model\CategoryGroup;
use Illuminate\Http\Request;
use App\Traits\CommonResponse;
use App\Http\Controllers\Controller;
use App\Traits\TransformUserGateway;
use App\Http\Resources\Category\CategoryResource;
use App\Repositories\Category\CategoryRepository;
use App\Repositories\User\UserRepositoryInterface;
use App\Http\Resources\Category\CategoryCollection;
use App\Http\Resources\Category\CategoryCMSResource;
use App\Http\Resources\Category\CategoryCMSCollection;
use App\Http\Resources\Category\CategoryShortCollection;
use App\Repositories\Category\CategoryRepositoryInterface;
use App\Repositories\CategoryGroup\CategoryGroupRepositoryInterface;
use App\Repositories\AttributeValue\AttributeValueRepositoryInterface;


class CategoryController extends Controller
{
    use CommonResponse;
    use UploadMedia;
    use TransformUserGateway;
    /**
     * @var CategoryRepository
     */

    const GROUP_INFO = 3;
    protected $_categoryRepository;
    protected $_userRepository;
    protected $_attributeValueRepository;
    protected $_categoryGroupRepository;

    public function __construct(
        CategoryRepositoryInterface $categoryRepository,
        UserRepositoryInterface $userRepository,
        AttributeValueRepositoryInterface $attributeValueRepository,
        CategoryGroupRepositoryInterface $categoryGroupRepository
    ) {
        $this->_categoryRepository = $categoryRepository;
        $this->_userRepository = $userRepository;
        $this->_attributeValueRepository = $attributeValueRepository;
        $this->_categoryGroupRepository = $categoryGroupRepository;
    }

    public function show($id)
    {
        $list_condition = ['parent_id' => $id, 'is_enabled' => 1, 'group_id' => self::GROUP_INFO];
        $categories = $this->_categoryRepository->findByListCondition($list_condition)->get();
        $data = new CategoryShortCollection($categories);
        $data = $data->count() == 0 ? [] : $data;
        return $this->successResponse($data, 'success');
    }

    public function getListByParent(Request $request, $parent)
    {
        if ($this->_checkValidAttribute($request)) {
            $list_condition = ['parent_id' => $parent, 'is_enabled' => 1, 'group_id' => self::GROUP_INFO];
            $categories = $this->_categoryRepository->findByListCondition($list_condition)->get()->load([
                'children',
                'posts'
            ]);
            $data = new CategoryCollection($categories);
            $data = $data->count() == 0 ? [] : $data;
            return $this->successResponse($data, 'success');
        }
        return $this->successResponse([], 'Data not found', 200);
    }

    protected function _checkValidAttribute($request)
    {
        $attribute = $request->input('attribute');
        // filter location attribute
        if ($request->input('type') == 2) {
            $dataField = $this->_attributeValueRepository->findBy('value', $attribute);
            if ($dataField->count() == 0) {
                return false;
            }
        }
        return true;
    }

    public function store(Request $request)
    {
        if (!$request->input('slug')) {
            $request->merge(['slug' => Str::slug($request->input('name'))]);
        }
        $userId = $this->getUser($request)->user_id;
        $request->merge(['created_user', $userId]);
        $request_accept = $request->only([
            'url_image',
            'slug',
            'name',
            'type',
            'group_id',
            'created_user',
            'is_enabled',
            'parent_id'
        ]);
        $category = $this->_categoryRepository->create($request_accept);
        $data = new CategoryResource($category);
        return $this->successResponse($data, "Create category success", 200);
    }

    public function update(Request $request, $categoryId)
    {
        if (!$request->input('slug')) {
            $request->merge(['slug' => Str::slug($request->input('name'))]);
        }
        $userId = $this->getUser($request)->user_id;
        $request->merge(['updated_user', $userId]);
        $category = $this->_categoryRepository->update($request->only([
            'url_image',
            'slug',
            'name',
            'type',
            'group_id',
            'updated_user',
            'is_enabled',
            'parent_id'
        ]), $categoryId);
        $data = new CategoryResource($category);
        return $this->successResponse($data, "Updated Successfully!", 200);
    }

    public function delete($categoryId)
    {
        if (in_array($categoryId, [1, 2, 3, 4, 5])) { //root category
            return $this->successResponse(new \stdClass(), 'Can\'t delete this category', 400);
        }
        $this->_categoryRepository->delete($categoryId);
        return $this->successResponse('', 'Deleted successfully');
    }

    public function detail(Request $request, $categoryId)
    {
        $category = $this->_categoryRepository->find($categoryId);
        $data = new CategoryResource($category);
        if ($request->header('isCMS')) {
            $data = new CategoryCMSResource($category);
        }
        return $this->successResponse($data, 'success');
    }


    /*
     * product by category: id or slug
     */
    public function productByCategory(Request $request, $categoryId)
    {
        $products = $this->_categoryRepository->getProductsByCategory($categoryId, $request);
        if ($products && $products->count() > 0) {
            return $this->successResponse($products, 'success');
        }
        return $this->errorResponse("Can't find any attribute of category '$categoryId'", 400);
    }

    public function categoryGroupList()
    {
        $group = $this->_categoryGroupRepository->all();
        if ($group && $group->count() > 0) {
            return $this->successResponse($group, 'success');
        }
        return $this->errorResponse("Can't find any category group ", 400);
    }

    // category by group - CMS
    public function categoryByGroup(Request $request)
    {
        $groupName = $request->group_name;
        $all = $request->get('all',0);
        $categories = $this->_categoryRepository->getCategoryByGroup($groupName, $request->type,$all);
        return $this->successResponse($categories, 'success');
    }
}
