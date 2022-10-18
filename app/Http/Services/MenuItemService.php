<?php namespace App\Http\Services;

use App\Models\MenuItem;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class MenuItemService {

    protected $model;
    protected $responseData;
    public function __construct()
    {
        $this->model = new MenuItem();
        $this->responseData = [];

    }

    public function getMenuItems() 
    {
        $records = $this->model->with('children')->get();
        foreach($records as $key => $record){
            $this->responseData[$key] = [
                "id" =>  $record->id,
                "name" =>  $record->name,
                "url" =>  $record->url,
                "parent_id" =>  $record->parent_id ?? null,
                "created_at" => $record->created_at->toDateTimeString(),
                "updated_at" => $record->updated_at->toDateTimeString(),
                "children" => $this->formulateChildrens($record->children)
            ];
        }
        return array_values($this->responseData);
    }

    public function formulateChildrens($records){
        $response = [];
        foreach($records as $key => $record){
            if($record->children){
                $response[$key] = $this->formulateData($record);
            }else{
                $response[$key] = $this->formulateData($record, false);
            }
        }
    return $response;
    }

    public function formulateData($record, $childExist = true) : array
    {
        return [
            "id" =>  $record->id,
            "name" =>  $record->name,
            "url" =>  $record->url,
            "parent_id" =>  $record->parent_id ?? null,
            "created_at" => $record->created_at->toDateTimeString(),
            "updated_at" => $record->updated_at->toDateTimeString(),
            "children" => $childExist ? $this->formulateChildrens($record->children) : []
        ];
    }
}
