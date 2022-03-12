<?php

use Illuminate\Database\Seeder;
use function GuzzleHttp\json_encode;

class DefaultSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;'); // ignore foreign
            $this->categories();
            $this->apps();
            $this->moreDetails();
            $this->appImages();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;'); // set foreign
    }


    public function moreDetails() {
        $jsonObj = $this->getJson('app_more_details');

        if ( $jsonObj && isset($jsonObj['data']) ) {

            try {
                DB::beginTransaction();


                $appMoreDetailModel = app(\App\App\Eloquent\Entities\AppMoreDetail::class);

                foreach ($jsonObj['data'] as $obj) {
                    $appId = $obj['app_id'] ;
                    $appMoreDetailModel->updateOrCreate(['app_id' => $appId], $obj);
                }

                DB::commit();

            } catch ( Exception $e ) {
                logger()->debug($e);
                DB::rollback();
            }
        }
    }

    public function appImages() {

        $jsonObj = $this->getJson('app_images');

        if ( $jsonObj && isset($jsonObj['data']) ) {

            try {
                DB::beginTransaction();

                $model = app(\App\App\Eloquent\Entities\AppImage::class);
                foreach ($jsonObj['data'] as $obj) {
                    $model->updateOrCreate($obj);
                }

                DB::commit();

            } catch ( Exception $e ) {
                logger()->debug($e);
                DB::rollback();
            }
        }
    }

    public function apps() {

        $jsonObj = $this->getJson('app');
        if ( $jsonObj && isset($jsonObj['data']) ) {

            try {
                DB::beginTransaction();

                DB::table('apps')->truncate();
                DB::table('app_more_details')->truncate();
                DB::table('app_developers')->truncate();
                DB::table('app_versions')->truncate();

                $appModel           = app(\App\App\Eloquent\Entities\App::class);
                $appDeveloperModel  = app(\App\App\Eloquent\Entities\AppDeveloper::class);
                $appMoreDetailModel = app(\App\App\Eloquent\Entities\AppMoreDetail::class);
                $categoryModel      = app(\App\App\Eloquent\Entities\Category::class);

                $apps          = [];
                $appDevelopers = [];

                // for($i = 1; $i < 2000; $i++) {
                    foreach ($jsonObj['data'] as $obj) {

                        $appId = $obj['app_id'] ;

                        $_data = $obj;
                        $app = $appModel->updateOrCreate(['app_id' => $appId], $_data);

                        if ( $app ) {

                            // connect related table to apps.
                            $this->_appDeveloper($obj, $app, $appDeveloperModel );
                            $this->_appMoreDetail($obj, $app, $appMoreDetailModel );
                            $this->_appCategory($obj, $app, $categoryModel);

                        }
                    }
                // }

                DB::commit();

            } catch ( Exception $e ) {

                logger()->debug($e);
                DB::rollback();
            }
        }
    }

    public function categories() {

        $jsonObj = $this->getJson('categories');
        if ( $jsonObj && isset($jsonObj['data']) ) {

            try {
                DB::beginTransaction();
                DB::table('categories')->truncate();



                $parentCategoryArr = ['Apps', 'Games'];
                foreach ($parentCategoryArr as $obj) {

                    $slug = str_slug($obj);
                    $item = [
                        'slug' => $slug,
                        'identifier' => str_slug($obj,'_'),
                        'title'           => $obj,
                        'description'     => $obj,
                        'seo_title'       => $obj,
                        'seo_keyword'     => $obj,
                        'seo_description' => $obj,
                        'is_enabled'      => 1,
                        'is_featured'     => 0,
                        'icon'            => '',
                        'views'           => 0,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ];
                    $_parentId = DB::table('categories')->insertGetId($item);
                }

                foreach ($jsonObj['data'] as $obj) {

                    $obj['slug']       = str_slug($obj['title']);
                    $obj['identifier'] = str_slug($obj['title'],'_');

                    if ( $obj['parent_category_id'] === 3)
                        continue;

                    $parentId =  (1 == $obj['parent_category_id']) ? 1 : 2;

                    $_data = [
                        'identifier'      => $obj['identifier'],
                        'slug'            => $obj['slug'],
                        'title'           => $obj['title'],
                        'description'     => $obj['description'],
                        'seo_title'       => $obj['seo_title'],
                        'seo_keyword'     => $obj['seo_keywords'],
                        'seo_description' => $obj['seo_descriptions'],
                        'is_enabled'      => $obj['is_enabled'],
                        'is_featured'     => $obj['is_featured'],
                        'icon'            => $obj['icon'],
                        'views'           => $obj['views'],
                        'parent_id'       => $parentId,
                        'created_at'      => now(),
                        'updated_at'      => now(),
                    ];

                    DB::table('categories')->insert($_data);

                }

                DB::commit();

            } catch ( Exception $e ) {

                logger()->debug($e);
                DB::rollback();
            }
        }

    }

    private function _appCategory($data, $appModel, $model) {


        // $ids = array_pluck($categories,'id');
        // $ids = array_filter($ids);
        // if(count($ids) > 0)
        //     $model->categories()->sync($ids);

        // return $model;
    }

    private function _appDeveloper($data, $appModel, $appDeveloperModel) {

        $developerName = $data['developer_name'] ?? 'DCM Group';

        $developerNameIdentifier = str_slug($developerName, '_');
        $details = [

        ];
        $_data = [
            'user_id'    => $appModel->user_id,
            'identifier' => $developerNameIdentifier,
            'title'      => $developerName,
            'slug'       => str_slug($developerName),
            'url'        => $data['developer_link'] ?? '#',

            'seo_title'       => $developerName,
            'seo_keyword'     => $developerName,
            'seo_description' => $developerName,
        ];
        $appDeveloper = $appDeveloperModel->updateOrCreate(['identifier' => $_data['identifier'] ],$_data);

        $appsDevelopersModel = app(\App\App\Eloquent\Entities\AppsDeveloper::class);

        $appsDevelopersModel->updateOrCreate([
            'app_id' => $appModel->id,
            'app_developer_id' => $appDeveloper->id
        ], [
            'app_id' => $appModel->id,
            'app_developer_id' => $appDeveloper->id
        ]);
        return $appDeveloper;
    }



    private function _appMoreDetail($data, $appModel, $model) {

        if ( isset( $data['required_android'] )) {

            $_data = [
                'app_id'     => $appModel->id,
                'title'      => 'Requirement',
                'identifier' => 'required_android',
                'value'      => $data['required_android'],

            ];
            $appModel->moreDetails()->create($_data);
        }

        if ( isset( $data['installs'] )) {

            $_data = [
                'app_id'     => $appModel->id,
                'title'      => 'Installs',
                'identifier' => 'total_installs',
                'value'      => $data['installs'],

            ];
            $appModel->moreDetails()->create($_data);
        }


        if ( isset( $data['published_date'] )) {

            $_data = [
                'app_id'     => $appModel->id,
                'title'      => 'Publish Date',
                'identifier' => 'published_date',
                'value'      => $data['published_date'],

            ];
            $appModel->moreDetails()->create($_data);
        }


    }


    private function getJson( $name ) {
        $storage = Storage::disk('database');
        $jsonPath = 'json-files/';
        $jsonObj  = $storage->get($jsonPath."/old/{$name}.json");

        $jsonObj= preg_replace('/\s+/', ' ',$jsonObj);
        $jsonObj  = json_decode($jsonObj, true);
        return $jsonObj;
    }


}
