<?php

namespace App\Imports;

use App\Models\Component;
use Maatwebsite\Excel\Concerns\ToModel;

class ImportComponents implements ToModel
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        return new Component([
            'nama' => $row[0],
            'jenis'=> $row[1],
            'unit_id'=> $row[2],
            'user_id'=> $row[3],
            'hargaunit' => $row[4],
            'deskripsi'=> $row[5],
            'brand_id'=> $row[6],
        ]);
    }
}
