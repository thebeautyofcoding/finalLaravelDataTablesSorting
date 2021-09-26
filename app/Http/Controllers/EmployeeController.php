<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\DataTables\EmployeeDataTable;
use Yajra\Datatables\Datatables;
use App\Models\Employee;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class EmployeeController extends Controller
{
    public function index()
    {
        return view('welcome');
    }

    /**
     * Process datatables ajax request.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function getEmployees(Request $request)
    {
        if ($request->ajax()) {
            $employees = Employee::select(
                'anrede',
                'vorname',
                'nachname',
                'email',
                'telefon',
                'handy',
                'firma',
                'created_at'
            );

            foreach ($employees as $employee) {
                $employee->firma = $employee->company;
            }

            return Datatables::of($employees)
                ->editColumn('created_at', function ($user) {
                    return [
                        'display' => e($user->created_at->format('d/m/Y')),
                        'timestamp' => $user->created_at->timestamp,
                    ];
                })

                ->addIndexColumn()
                ->addColumn('details_url', function ($employee) {
                    return url('personsCompany/' . $employee->id);
                })

                ->make(true);
        }
    }

    public function getPersonsCompany($id)
    {
        $company = Employee::find($id)->company();

        return Datatables::of($company)->make(true);
    }
}
