<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use Illuminate\Http\Request;
use App\Services\UploadService;
use Illuminate\Support\Facades\Log;
use Illuminate\Database\QueryException;


class UploadController extends Controller
{

    /**
     * Receive and upload employee data from api call
     *
     * pass the the data to uploadService to store in the database
     *
     * I would have implemented a queue to process the data in the background and then notify users upon the task's completion.
     * This approach would better handle very large data sets.
     *
     * Also, I will Implement the  logic to check for duplicate data entries, ensuring that repeated
     * uploads don't clutter the database with redundant information. This will be done by making the employee Id, username fields are unique.
     *
     *
     * @param $request
     * @param $uploadService
     * @return Response
     */
    public function uploadEmployeeData(Request $request, UploadService $uploadService)
    {
        $data = $request->getContent();
        // request data passed to upload service for processin
        $upload = $uploadService->uploadEmployeeData($data);

        return response()->json(['message' => $upload['message']], $upload['status']);
    }

    /**-
     * Lists paginates the employee record in the DB
     *
     * @return JsonResponse
     */
    public function index()
    {

        try {
            $employees = Employee::paginate(20);
            return $employees;
        } catch (QueryException $e) {
            Log::error($e->getMessage());
            return response()->json(['message' => 'Error getting employee data'], 500);
        }
    }

    /**
     * Fetch the employee data by employee id
     *
     * @param $id
     * @return JsonResponse
     */
    public function show($id)
    {
        try {
            $employee = Employee::where('emp_id', $id);
            // return $employees;
            if ($employee->count() > 0) {
                return $employee->first();
            } else {
                return response()->json(['message' => 'Employee record not found '], 404);
            }
        } catch (QueryException $e) {
            Log::error($e->getMessage());
            return response()->json(['message' => 'Error getting employee data'], 500);
        }
    }

    /**
     * Delete Employee record by the emplyee id
     *
     * @param $id
     * @return JsonResponse
     */
    public function delete($id)
    {

        try {
            $employee = Employee::where('emp_id', $id);
            // return $employees;
            if ($employee->count() > 0) {
                $employee->first()->delete();
                return response()->json(['message' => 'Employee record deleted successfully'], 200);
            } else {
                return response()->json(['message' => 'Employee record not found in the database '], 404);
            }
        } catch (QueryException $e) {
            Log::error($e->getMessage());
            return response()->json(['message' => 'Error getting employee data'], 500);
        }
    }
}
