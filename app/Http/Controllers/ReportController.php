<?php

namespace App\Http\Controllers;

use App\Exports\EmployeesExport;
use App\Models\Employee;
use PDF;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Facades\Excel;

class ReportController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index() {
        date_default_timezone_set('asia/ho_chi_minh');
        $format = 'Y/m/d';
        $now = date($format);
        $to = date($format, strtotime("+30 days"));
        $constraints = [
            'from' => $now,
            'to' => $to
        ];

        $employees = $this->getHiredEmployees($constraints);
        return view('system-mgmt/report/index', ['employees' => $employees, 'searchingVals' => $constraints]);
    }

    public function exportExcel(Request $request) {

        $employees = $this->getExportingData(['from'=> $request['from'], 'to' => $request['to']]);
        return (new EmployeesExport($employees))->download('employees.xlsx');
        
    }

    public function exportPDF(Request $request) {
         $constraints = [
            'from' => $request['from'],
            'to' => $request['to']
        ];
        $employees = $this->getExportingData($constraints);
        $pdf = PDF::loadView('system-mgmt/report/pdf', ['employees' => $employees, 'searchingVals' => $constraints]);
        return $pdf->download('report_from_'. $request['from'].'_to_'.$request['to'].'.pdf');
        // return view('system-mgmt/report/pdf', ['employees' => $employees, 'searchingVals' => $constraints]);
    }

    public function search(Request $request) {
        $constraints = [
            'from' => $request['from'],
            'to' => $request['to']
        ];

        $employees = $this->getHiredEmployees($constraints);
        return view('system-mgmt/report/index', ['employees' => $employees, 'searchingVals' => $constraints]);
    }

    private function getHiredEmployees($constraints) {
        $employees = Employee::where('date_hired', '>=', $constraints['from'])
                        ->where('date_hired', '<=', $constraints['to'])
                        ->paginate(10);
        return $employees;
    }

    private function getExportingData($constraints) {
        return DB::table('employees')
        ->leftJoin('city', 'employees.city_id', '=', 'city.id')
        ->leftJoin('department', 'employees.department_id', '=', 'department.id')
        ->leftJoin('state', 'employees.state_id', '=', 'state.id')
        ->leftJoin('country', 'employees.country_id', '=', 'country.id')
        ->leftJoin('division', 'employees.division_id', '=', 'division.id')
        ->select('employees.firstname', 'employees.middlename', 'employees.lastname', 
        'employees.age','employees.birthdate', 'employees.address', 'employees.zip', 'employees.date_hired',
        'department.name as department_name', 'division.name as division_name')
        ->where('date_hired', '>=', $constraints['from'])
        ->where('date_hired', '<=', $constraints['to'])
        ->get()
        ->map(function ($item, $key) {
        return (array) $item;
        })
        ->all();
    }
}
