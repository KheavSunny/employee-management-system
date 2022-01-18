<?php

namespace App\Http\Controllers;

use App\Models\City;
use App\Models\State;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CityController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth')->only(["index", "create", "store", "edit", "update", "search", "destroy"]);
    }

    public function index()
    {
        $cities = City::paginate(10);
        return view('system-mgmt/city/index', ['cities' => $cities]);
    }

    public function create()
    {
        $states = State::all();
        return view('system-mgmt/city/create', ['states' => $states]);
    }

    public function store(Request $request)
    {
        State::findOrFail($request['state_id']);
        $this->validateInput($request);
         City::create([
            'name' => $request['name'],
            'state_id' => $request['state_id']
        ]);

        return redirect()->intended('system-management/city');
    }

    public function show($id)
    {
        //
    }

    public function edit($id)
    {
        $city = City::findOrFail($id);

        $states = State::all();
        return view('system-mgmt/city/edit', ['city' => $city, 'states' => $states]);
    }

    public function update(Request $request, $id)
    {
        $city = City::findOrFail($id);
         $this->validate($request, [
        'name' => 'required|max:60'
        ]);
        $input = [
            'name' => $request['name'],
            'state_id' => $request['state_id']
        ];
        $city->where('id', $id)->update($input);
        
        return redirect()->intended('system-management/city');
    }

    public function destroy($id)
    {
        City::where('id', $id)->delete();
         return redirect()->intended('system-management/city');
    }

    public function loadCities($stateId) {
        $cities = City::where('state_id', '=', $stateId)->get(['id', 'name']);
        dd($cities);
        return response()->json($cities);
    }

    public function search(Request $request) {
        $constraints = [
            'name' => $request['name']

            ];

       $cities = $this->doSearchingQuery($constraints);
       return view('system-mgmt/city/index', ['cities' => $cities, 'searchingVals' => $constraints]);
    }
    
    private function doSearchingQuery($constraints) {
        $query = City::query();
        $fields = array_keys($constraints);
        $index = 0;
        foreach ($constraints as $constraint) {
            if ($constraint != null) {
                $query = $query->where( $fields[$index], 'like', '%'.$constraint.'%');
            }

            $index++;
        }
        return $query->paginate(5);
    }
    private function validateInput($request) {
        $this->validate($request, [
        'name' => 'required|max:60|unique:city'
    ]);
    }
}
