<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\Export;
use App\Imports\Import;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Exports\UsersExport;
use App\Imports\UsersImport;


class UserController extends Controller
{
    public function index()
    {  
        $data = session()->get('userData');
        if(!empty($data)) {
          $data = $this->decryptData($data, getenv('ENCRYPTION_KEY'));
        }
        return view('index', ['data' => $data]);
    }

    public function create()
    {
        return view('create');
    }

    public function store(Request $request)
    {
        try{ 
            // Image file storage/public folder
            if ($request->hasFile('image')) {
                $imagePath = $request->file('image')->store('uploads', 'public');
            } else {
                $imagePath = null;
            }
             
            $data[] = [
                'name'     => $request->name,
                'password' => $request->password,
                'email'    => $request->email,
                'image'    => $imagePath,
                'mobile'   => $request->mobile,
                'date'     => $request->date,
                'role'     => $request->role,
            ];

            if (session()->get('userData')) { 
                $decryptData = $this->decryptData(session()->get('userData'), getenv('ENCRYPTION_KEY'));
                $data = array_merge($data, $decryptData);
            }
            $encryptData = $this->encryptData($data, getenv('ENCRYPTION_KEY'));
            session()->put('userData', $encryptData);
            return redirect()->route('index')->with('success', 'Data added successfully!');
        }catch(\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }

    public function edit($key)
    {
        $decryptData = $this->decryptData(session()->get('userData'), getenv('ENCRYPTION_KEY'));
        $data = isset($decryptData[$key]) ? $decryptData[$key] : [];
        return view('create', compact('data','key'));
    }

    public function update(Request $request)
    {   
        try{
            if ($request->hasFile('image')) {
                $imagePath = $request->file('image')->store('uploads', 'public');
            } else {
                $imagePath = $request->image;
            }
            $data[] = [
                'name'     => $request->name,
                'password' => $request->password,
                'email'    => $request->email,
                'image'    => $imagePath,
                'mobile'   => $request->mobile,
                'date'     => $request->date,
                'role'     => $request->role,
            ];
            if (session()->get('userData')) { 
                $decryptData = $this->decryptData(session()->get('userData'), getenv('ENCRYPTION_KEY'));
                unset($decryptData[$request->key]);
                $data = array_merge($decryptData, $data);
            }
            $encryptData = $this->encryptData($data, getenv('ENCRYPTION_KEY'));
            session()->put('userData', $encryptData);
            return redirect()->route('index')->with('success', 'Update successfully');
        }catch(\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }

    public function delete($key)
    {
        $decryptData = $this->decryptData(session()->get('userData'), getenv('ENCRYPTION_KEY'));
        unset($decryptData[$key]);
        $encryptData = $this->encryptData(array_values($decryptData), getenv('ENCRYPTION_KEY'));
        session()->put('userData', $encryptData);
        return back()->with('success', 'Delete successfully!');
    }

    public function finalSubmit()
    {
        try {
            $data = $this->decryptData(session()->get('userData'), getenv('ENCRYPTION_KEY'));
            foreach ($data as $key => $value) {
                $userData = User::updateOrCreate([
                    'name' => $value['name'],
                    'email' => $value['email'],
                    'password' => Hash::make($value['password']),
                    'image' => $value['image'],
                    'role' => $value['role'],
                    'mobile' => $value['mobile'],
                    'date' => $value['date'],
                ]);
            }
            return redirect()->route('index')->with('success', 'Data saved successfully!');
        }catch(\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }
    
    public function import(Request $request)
    {
        Excel::import(new UsersImport, request()->file('file'));
        return back()->with('success', 'Data Imported Successfully');
    }

    public function export()
    {    
        return Excel::download(new UsersExport, 'users.xlsx');
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
