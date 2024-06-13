<?php

namespace App\Imports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\ToModel;
use App\Models\User;

use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Concerns\WithValidation;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\Importable;



class UserImport implements ToCollection, ToModel, WithHeadingRow
{
       
    use Importable;

    private $current = 0;

    private $errors = [];

    /**
     * @param Collection $collection
     */
    public function collection(Collection $collection)
    {

    }

    public function model(array $row)
{
    // Find user by email
    $existingUser = User::where('email', $row['email'])->first();

    if ($existingUser) {
        // Update existing user
        $existingUser->update([
            'name' => $row['name'],
            'uid' => $row['uid'],
            'type' => $row['type'],
        ]);
        return $existingUser; // Return the updated user
    } else {
        // Create new user
        return User::create([
            'name' => $row['name'],
            'email' => $row['email'],
            'uid' => $row['uid'],
            'type' => $row['type'],
        ]);
    }
}


    public function getErrors()
    {
        return $this->errors;
    }
}