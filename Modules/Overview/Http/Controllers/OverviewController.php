<?php

namespace Modules\Overview\Http\Controllers;

use App\Http\Controllers\GenericResponseController;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Overview\Entities\Events;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Carbon;


class OverviewController extends GenericResponseController
{
    public function createEvents(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'title' => 'required',
            'date_from' => 'required'
        ]);

        if ($validator->fails()) {
            return $this->sendError('Validation Error.', $validator->errors());
        }

        $Events = Events::create($request->all());

        return $this->sendResponse($Events, 'Events created successfully.');
    }

    public function getEvents()
    {
        $events = collect(Events::all())->sortBy("id")->reverse()->values();

        return $this->sendResponse($events, 'Events retrieved successfully.');
    }

    public function deleteEvents(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id' => 'required'
        ]);

        if ($validator->fails()) {
            return $this->sendError('Validation Error.', $validator->errors());
        }

        $events = Events::find($request->id);

        if (is_null($events)) {
            return $this->sendError('Events not found.');
        }

        $events->delete();

        return $this->sendResponse($events, 'Events deleted successfully.');
    }
}
