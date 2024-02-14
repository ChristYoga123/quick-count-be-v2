<?php

namespace App\Exports\Admin\Sheets;

use App\Models\SuaraPilpres;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithTitle;

class RealCountPresidenSheets implements FromQuery, WithTitle, WithHeadings
{
    public $idDapil;

    public function __construct($id)
    {
        $this->idDapil = $id;
    }

    public function query()
    {
        // $realCountPresiden = DB::table('suara_pilpres')
        //     ->select('capres.no_urut_paslon', 'capres.nama_paslon', DB::raw('SUM(suara_pilpres.jumlah_suara) as jumlah_suara'))
        //     ->join('capres', 'suara_pilpres.capres_id', '=', 'capres.id')
        //     ->join('pilpres', 'suara_pilpres.pilpres_id', '=', 'pilpres.id')
        //     ->where('pilpres.dapil_id', $this->idDapil)
        //     ->groupBy('suara_pilpres.capres_id')
        //     ->get();

        return SuaraPilpres::query()
            ->select('capres.no_urut_paslon', 'capres.nama_paslon', DB::raw('SUM(suara_pilpres.jumlah_suara) as jumlah_suara'))
            ->join('capres', 'suara_pilpres.capres_id', '=', 'capres.id')
            ->join('pilpres', 'suara_pilpres.pilpres_id', '=', 'pilpres.id')
            ->where('pilpres.dapil_id', $this->idDapil)
            ->groupBy('suara_pilpres.capres_id');
    }

    public function headings(): array
    {
        return [
            'No Urut Paslon',
            'Nama Paslon',
            'Jumlah Suara'
        ];
    }

    public function title(): string
    {
        $index = DB::table('dapils')->where('id', $this->idDapil)->first();
        return $index->index;
    }
}
