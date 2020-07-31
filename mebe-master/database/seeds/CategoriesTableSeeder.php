<?php

use Faker\Factory;
use Illuminate\Database\Seeder;
use App\Model\Category;
use App\Model\CategoryGroup;

class CategoriesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // create Root Category
        $rootCategoryData = [
            'name' => 'Root Category',
            'slug' => 'root-category',
            'created_user' => 1,
            'parent_id' => 0,
            'group_id' => 0
        ];
        $rootCategory = Category::create($rootCategoryData);
        // create category for information tab
        $group = CategoryGroup::where('group_name', 'info')->first();
        $categories = [
            [
                'name' => 'info',
                'slug' => 'info',
                'created_user' => 1,
                'parent_id' => 1
            ],
            [
                'name' => 'care / couple',
                'slug' => 'care-couple',
                'parent_id' => 2,
                'created_user' => 1,
            ],
            [
                'name' => 'symptom pregnancy per week',
                'slug' => 'symptom-pregnancy-per-week',
                'parent_id' => 2,
                'created_user' => 1,
            ],
            [
                'name' => 'Health pupplement food for pregnancy',
                'slug' => 'Health-pupplement-food-for-pregnancy',
                'parent_id' => 2,
                'created_user' => 1,
            ],
            [
                'name' => 'Pain / syptom',
                'slug' => 'Pain-syptom',
                'parent_id' => 3,
                'created_user' => 1,
            ],
            [
                'name' => 'Pernatal education',
                'slug' => 'Pernatal-education',
                'parent_id' => 3,
                'created_user' => 1,
            ],
            [
                'name' => 'life and yoga',
                'slug' => 'life-and-yoga',
                'parent_id' => 3,
                'created_user' => 1,
            ],
            [
                'name' => 'hospital information',
                'slug' => 'hospital-information',
                'parent_id' => 4,
                'created_user' => 1,
            ],
            [
                'name' => 'Bỉm / tã',
                'slug' => 'bim-ta',
                'parent_id' => 4,
                'created_user' => 1,
            ],
            [
                'name' => 'Sữa',
                'slug' => 'sua',
                'parent_id' => 4,
                'created_user' => 1,
            ],
        ];

        for ($element = 0; $element < sizeOf($categories); $element++) {
            $categories[$element]['created_at'] = $categories[$element]['updated_at'] = \Carbon\Carbon::now();
            if ($group) {
                $categories[$element]['group_id'] = $group->group_id;
            }
            Category::create($categories[$element]);
        }

        // create categories for shop
        $shopGroup = CategoryGroup::where('group_name', '=', 'shop')->first();
        $shopCategory = Category::create([
            'name' => 'shop',
            'slug' => 'shop',
            'parent_id' => 1,
            'created_user' => 1,
            'group_id' => $shopGroup->group_id
        ]);
        if ($shopGroup) {
            factory(Category::class, 5)->create([
                'parent_id' => $shopCategory->category_id,
                'group_id' => $shopGroup->group_id
            ])->each(function($category) {
                // create attributes for categories
                $this->setAttributes($category);
                factory(Category::class, random_int(1, 4))->create([
                    'parent_id' => $category->category_id,
                    'group_id' => $category->group_id
                ])->each(function($subCategory) {
                    $this->setAttributes($subCategory);
                    factory(Category::class, random_int(1, 3))->create([
                        'parent_id' => $subCategory->category_id,
                        'group_id' => $subCategory->group_id
                    ]);
                });
            });
        }
        // create category for diary
        $diaryGroup = CategoryGroup::where('group_name', 'diary')->first();
        if ($diaryGroup) {
            $diaryCategory = Category::create([
                'name' => 'diary',
                'slug' => 'diary',
                'parent_id' => 1,
                'created_user' => 1,
                'group_id' => $diaryGroup->group_id
            ]);
        }
    }

    public function setAttributes($category) {
        $faker = Faker\Factory::create();
        $attributes = \App\Model\Attribute::pluck('attribute_id')->toArray();
        if (count($attributes) > 0) {
            $randomAttributes = $faker->randomElements($attributes, $faker->numberBetween(1, count($attributes)));
            // set product attribute for master products
            foreach ($randomAttributes as $attribute) {
                $category->attributes()->sync($attribute, false);
            }
        }
    }
}
