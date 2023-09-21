<?php

namespace App\Services;

use App\Models\Employee;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\LazyCollection;

class UploadService
{
    private $file_path;

    public function uploadEmployeeData(string $file_path)
    {

        $this->file_path = $file_path;

        try {
            $collection = LazyCollection::make(function () {
                $handle = fopen($this->file_path, 'r');

                while (($line = fgetcsv($handle)) !== false) {
                    $dataString = implode(", ", $line);
                    $row = explode(',', $dataString);
                    yield $row;
                }

                fclose($handle);
            })
                ->skip(1)
                ->chunk(1000)
                ->each(function (LazyCollection $chunk) {
                    $records = $chunk->map(function ($row) {
                        return [
                            'emp_id' => $row[0],
                            'name_prefix' => $row[1],
                            'first_name' => $row[2],
                            'middle_initial' => $row[3],
                            'last_name' => $row[4],
                            'gender' => $row[5],
                            'email' => $row[6],
                            'dob' => $this->formatDate($row[7]),
                            'time_of_birth' => $row[8],
                            'age_in_yrs' => $row[9],
                            'date_of_joining' => $this->formatDate($row[10]),
                            'age_in_company' => $row[11],
                            'phone_no' => $row[12],
                            'place_name' => $row[13],
                            'county' => $row[14],
                            'city' => $row[15],
                            'zip' => $row[16],
                            'region' => $row[17],
                            'user_name' => $row[18],
                        ];
                    })->toArray();

                    Employee::insert($records);
                });
        } catch (\Exception $e) {
            Log::error($e->getMessage());
            return (['message' => 'error uploading records', 'status' => 401]);
        }



        return (['message' => 'Records upload successful', 'status' => 200]);
    }

    public function formatDate($date)
    {
        $newDate = date("Y-m-d", strtotime($date));
        // echo "New date format is: ".$newDate. " (YYYY-MM-DD)";
        return $newDate;
    }
}
