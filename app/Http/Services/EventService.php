<?php namespace App\Http\Services;

use App\Models\Event;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class EventService {

    protected $model;
    public function __construct()
    {
        $this->model = new Event();
    }

    public function getEventsWithWorkshops() 
    {
        return $this->model->with('workshops')->get();
    }

    public function getFutureEventsWithWorkshops() 
    {
        $today = Carbon::now()->format('Y-m-d H:i:s');
        return $this->model->whereHas('workshops', function($q) use($today){
            $q->where('start', '>', $today);
        })->with('workshops')->get();
    }
}
