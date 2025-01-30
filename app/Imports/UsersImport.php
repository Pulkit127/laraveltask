<?php

namespace App\Imports;

use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;

class UsersImport implements ToModel, WithHeadingRow
{
    /**
     * @param array $row
     *
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function model(array $row)
    {
        // Skip rows if required fields are empty
        if (empty($row['name']) || empty($row['email'])) {
            return null;
        }

        // Prepare user data
        $userData[] = [
            'name' => $row['name'],
            'email' => $row['email'],
            'mobile' => $row['mobile'],
            'password' => '1234',
            'role' => $row['role'],
            'date' => Carbon::parse($row['date'])->format('Y-m-d'),
        ];
        if (session()->get('userData')) {
            $decryptData = $this->decryptData(session()->get('userData'), getenv('ENCRYPTION_KEY'));
            $userData = array_merge($decryptData, $userData);
        }

        // Encrypt and store updated data in session
        $encryptedData = $this->encryptData($userData, getenv('ENCRYPTION_KEY'));
        session(['userData' => $encryptedData]);

        return null; // Not saving to the database
    }
    private function encryptData(array $data, string $key)
    {
        $data = json_encode($data); // Convert data to JSON string

        // Generate an encryption key based on the API key
        $encryptionKey = hash('sha256', $key, true);

        // Encrypt the data
        $iv = openssl_random_pseudo_bytes(openssl_cipher_iv_length('aes-256-cbc'));
        $encrypted = openssl_encrypt($data, 'aes-256-cbc', $encryptionKey, 0, $iv);

        // Encode the IV with the encrypted data
        return base64_encode($iv . $encrypted);
    }
    private function decryptData(string $encrypted, string $key)
    {
        // Generate an encryption key based on the API key
        $encryptionKey = hash('sha256', $key, true);

        // Decode the encrypted string
        $encrypted = base64_decode($encrypted);

        // Extract the IV from the encrypted data
        $ivLength = openssl_cipher_iv_length('aes-256-cbc');
        $iv = substr($encrypted, 0, $ivLength);
        $encrypted = substr($encrypted, $ivLength);

        // Decrypt the data
        $decrypted = openssl_decrypt($encrypted, 'aes-256-cbc', $encryptionKey, 0, $iv);

        return json_decode($decrypted, true); // Convert JSON string to array
    }
}
