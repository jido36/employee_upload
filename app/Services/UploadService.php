<?php

namespace App\Services;

use App\Models\Employee;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\LazyCollection;
use Illuminate\Database\QueryException;

class UploadService
{

    /**
     *
     * the service processes the data from the Api and stores it in the database
     *
     * @param $employees
     * @return array
     */
    public function uploadEmployeeData($employees)
    {


        $collection = LazyCollection::make(function () use ($employees) {
            // split data by new lines
            $ret_employee = preg_split('/\n/', $employees);

            foreach ($ret_employee as $employee) {
                // skip null lines in the data
                if ($employee == NULL) continue;
                $row = explode(',', $employee);
                yield $row;
            }
        })
            // Skip the first line which is the csv file header
            ->skip(1)
            ->chunk(1000)
            ->each(function (LazyCollection $chunk) {
                $records = $chunk->map(function ($row) {
                    return [
                        'emp_id' => $row[0],
                        'name_prefix' => $row[1] ?? '',
                        'first_name' => $row[2] ?? '',
                        'middle_initial' => $row[3] ?? '',
                        'last_name' => $row[4] ?? '',
                        'gender' => $row[5] ?? '',
                        'email' => $row[6] ?? '',
                        'dob' => isset($row[7]) ? $this->formatDate($row[7]) : null,
                        'time_of_birth' => $row[8] ?? '',
                        'age_in_yrs' => $row[9] ?? '',
                        'date_of_joining' => isset($row[10]) ? $this->formatDate($row[10]) : null,
                        'age_in_company' => $row[11] ?? '',
                        'phone_no' => $row[12] ?? '',
                        'place_name' => $row[13] ?? '',
                        'county' => $row[14] ?? '',
                        'city' => $row[15] ?? '',
                        'zip' => $row[16] ?? '',
                        'region' => $row[17] ?? '',
                        'user_name' => $row[18] ?? '',
                    ];
                })->toArray();

                try {
                    Employee::insert($records);
                } catch (QueryException $e) {
                    Log::error($e->getMessage());
                }
            });


        return (['message' => 'Records upload successful', 'status' => 200]);
    }

    /**
     * Format date in to MySQL format
     *
     * @param $date
     * @retur string
     */
    public function formatDate($date)
    {
        $newDate = date("Y-m-d", strtotime($date));
        return $newDate;
    }
}
