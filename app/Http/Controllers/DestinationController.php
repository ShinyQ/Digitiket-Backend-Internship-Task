<?php

namespace App\Http\Controllers;

use Illuminate\Database\Eloquent\ModelNotFoundException;
use Validator;
use App\Helper\Api;
use Exception;
use Illuminate\Http\Request;
use App\Models\Destination;

class DestinationController extends Controller
{
    private $code;
    private $message;

    public function __construct()
    {
        $this->code = 200;
        $this->message = "success";
    }

    public function index()
    {
        try {
            $response = Destination::latest()->get();
        } catch (Exception $e) {
            $this->code = 500;
            $this->message = "An Error Has Occurred";
            $response = [];
        }
        return Api::apiRespond($this->code, $response, $this->message);
    }

    public function store(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'title' => ['required', 'string', 'unique:destination'],
                'description' => ['required', 'string'],
                'keyword' => ['required', 'string'],
                'images' => ['required', 'string'],
                'address' => ['required', 'string'],
            ]);

            if ($validator->fails()) {
                return Api::apiResponseValidationFails('Validation error messages!', $validator->errors()->all());
            }

            $response = Destination::create([
                'title' => $request->title,
                'description' => $request->description,
                'keyword' => $request->keyword,
                'images' => $request->images,
                'address' => $request->address,
                'views' => 0
            ]);

        } catch (Exception $e) {
                $this->code = 500;
                $this->message = $e;
                $response = [];
        }
        return Api::apiRespond($this->code, $response, $this->message);
    }

    public function show($id)
    {
        try {
            $response = Destination::findOrFail($id);
        } catch (Exception $e) {
            if ($e instanceof ModelNotFoundException) {
                $this->message = "Data Not Exist";
                $response = [];
            } else {
                $this->code = 500;
                $this->message = $e->getMessage();
                $response = [];
            }
        }
        return Api::apiRespond($this->code, $response, $this->message);
    }

    public function update(Request $request, $id)
    {
        try {
            $validator = Validator::make($request->all(), [
                'title' => ['string', 'unique:destination'],
                'description' => ['string'],
                'keyword' => ['string'],
                'images' => ['string'],
                'address' => ['string'],
            ]);

            if ($validator->fails()) {
                return Api::apiResponseValidationFails('Validation error messages!', $validator->errors()->all());
            }

            $response = Destination::findOrFail($id);
            $response->fill($request->input())->save();
        } catch (ModelNotFoundException $e) {
            $this->code = 500;
            $this->message = $e;
            $response = [];
        }
        return Api::apiRespond($this->code, $response, $this->message);
    }

    public function destroy($id)
    {
        try {
            $response = Destination::findOrFail($id)->delete();
        } catch (Exception $e) {
            if ($e instanceof ModelNotFoundException) {
                $this->message = "Data Not Exist";
                $response = [];
            } else {
                $this->code = 500;
                $this->message = $e->getMessage();
                $response = [];
            }
        }
        return Api::apiRespond($this->code, $response, $this->message);
    }
}
