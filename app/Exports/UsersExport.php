<?php 
namespace App\Exports;

use App\Models\User;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class UsersExport implements FromCollection, WithHeadings
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return User::select('id', 'name', 'email', 'mobile', 'role','date')->get();
    }

    /**
     * @return array
     */
    public function headings(): array
    {
        return [
            'Id',
            'Name',
            'Email',
            'Mobile',
            'Role',
            'Date',
        ];
    }
}
