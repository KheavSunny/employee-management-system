<?php

namespace App\Http\Controllers;

use App\Models\City;
use App\Models\State;
use App\Models\Country;
use App\Models\Division;
use App\Models\Employee;
use App\Models\Department;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;

class EmployeeManagementController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $employees = DB::table('employees')
            ->leftJoin('city', 'employees.city_id', '=', 'city.id')
            ->leftJoin('department', 'employees.department_id', '=', 'department.id')
            ->leftJoin('state', 'employees.state_id', '=', 'state.id')
            ->leftJoin('country', 'employees.country_id', '=', 'country.id')
            ->leftJoin('division', 'employees.division_id', '=', 'division.id')
            ->select('employees.*', 'department.name as department_name', 'department.id as department_id', 'division.name as division_name', 'division.id as division_id')
            ->paginate(5);
            
        return view('employees-mgmt/index', ['employees' => $employees]);
    }

    public function create()
    {
        $cities = City::all();
        $states = State::all();
        $countries = Country::all();
        $departments = Department::all();
        $divisions = Division::all();
        return view('employees-mgmt.create', [
            'countries' => $countries,
            'departments' => $departments,
            'divisions' => $divisions,
            'cities' => $cities,
            'states' => $states,
        ]);
    }

    public function store(Request $request)
    {
        $this->validateInput($request);
        // Upload image

        if($request->hasFile('picture')){
            $path = $request->file('picture')->getClientOriginalName();
            $request->file('picture')->move('images',$path);
        }
        $keys = ['lastname', 'firstname', 'middlename', 'address', 'city_id', 'state_id', 'country_id', 'zip',
            'age', 'birthdate', 'date_hired', 'department_id', 'department_id', 'division_id'];
        $input = $this->createQueryInput($keys, $request);
        $input['picture'] = $path;
        // Not implement yet
        // $input['company_id'] = 0;
        Employee::create($input);

        return redirect()->intended('/employee-management')->with('succeed','Employee created successfully');
    }

    public function show($id)
    {
        //
    }

    public function edit($id)
    {
        $employee = Employee::findOrFail($id);

        $cities = City::all();
        $states = State::all();
        $countries = Country::all();
        $departments = Department::all();
        $divisions = Division::all();
        return view('employees-mgmt/edit', ['employee' => $employee, 'cities' => $cities, 'states' => $states, 'countries' => $countries,
            'departments' => $departments, 'divisions' => $divisions]);
    }

    public function update(Request $request, $id)
    {
        $employee = Employee::findOrFail($id);
        $this->validateInput($request);
        // Upload image
        $keys = ['lastname', 'firstname', 'middlename', 'address', 'city_id', 'state_id', 'country_id', 'zip',
            'age', 'birthdate', 'date_hired', 'department_id', 'department_id', 'division_id'];
        $input = $this->createQueryInput($keys, $request);
        if ($request->hasFile('picture')) {
            $path = $request->file('picture')->getClientOriginalName();
            $request->file('picture')->move('images',$path);
            $input['picture'] = $path;
        }

        Employee::where('id', $id)
            ->update($input);

        return redirect()->intended('/employee-management')->with('succeed','Employee updated successfully');
    }

    public function destroy($id)
    {
        Employee::where('id', $id)->delete();
        return redirect()->intended('/employee-management')->with('succeed','Employee deleted successfully');
    }

    public function search(Request $request, Employee $employee)
    {
        $constraints = [
            'firstname' => $request['firstname'],
            'department.name' => $request['department_name']
        ];
        $employees = $this->doSearchingQuery($constraints);
        $constraints['department_name'] = $request['department_name'];
        return view('employees-mgmt/index', ['employees' => $employees, 'searchingVals' => $constraints]);
    }

    private function doSearchingQuery($constraints)
    {
        $query = DB::table('employees')
            ->leftJoin('city', 'employees.city_id', '=', 'city.id')
            ->leftJoin('department', 'employees.department_id', '=', 'department.id')
            ->leftJoin('state', 'employees.state_id', '=', 'state.id')
            ->leftJoin('country', 'employees.country_id', '=', 'country.id')
            ->leftJoin('division', 'employees.division_id', '=', 'division.id')
            ->select('employees.firstname as employee_name', 'employees.*', 'department.name as department_name', 'department.id as department_id', 'division.name as division_name', 'division.id as division_id');
        $fields = array_keys($constraints);
        $index = 0;
        foreach ($constraints as $constraint) {
            if ($constraint != null) {
                $query = $query->where($fields[$index], 'like', '%' . $constraint . '%');
            }

            $index++;
        }
        return $query->paginate(5);
    }

    public function load($name)
    {
        $path = storage_path() . '/app/avatars/' . $name;
        if (file_exists($path)) {
            return Response::download($path);
        }
    }

    private function validateInput($request)
    {
        $this->validate($request, [
            'lastname' => 'required|max:60',
            'firstname' => 'required|max:60',
            'middlename' => 'required|max:60',
            'address' => 'required|max:120',
            'country_id' => 'required',
            'zip' => 'required|max:10',
            'age' => 'required',
            'birthdate' => 'required',
            'date_hired' => 'required',
            'department_id' => 'required',
            'division_id' => 'required',
        ]);
    }

    private function createQueryInput($keys, $request)
    {
        $queryInput = [];
        for ($i = 0; $i < sizeof($keys); $i++) {
            $key = $keys[$i];
            $queryInput[$key] = $request[$key];
        }

        return $queryInput;
    }
}
