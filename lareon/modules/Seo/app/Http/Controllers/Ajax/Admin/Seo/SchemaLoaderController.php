<?php

namespace Lareon\Modules\Seo\App\Http\Controllers\Ajax\Admin\Seo;

use Lareon\Modules\Seo\App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Lareon\Modules\Seo\App\Models\SeoSchemaModel;

class SchemaLoaderController extends Controller
{
    public function get(Request $request)
    {

        $data = $request->toArray();
        $modeId=decrypt($data['modelId'] ?? null);
        $model=decrypt($data['model'] ?? null);;
        $schemaType = $request->input('schema');

        $value=[];
        if ($modeId && $model){
            $value =SeoSchemaModel::query()->where('model_id',$modeId)->where('model_type',$model)->where('type', $schemaType)->first();
        }
        return view("seo::components.editor.types.schema-views" ,['data'=>$value['schema'] ?? [] ,'type'=>$schemaType])->render();


    }
}
