<?php

namespace App\Exports\Admin\Sheets;

use App\Models\SuaraPilkada;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithTitle;

class RealCountPilkadaSheets implements FromQuery, WithTitle, WithHeadings
{
    public $idDapil;

    public function __construct($id)
    {
        $this->idDapil = $id;
    }

    public function query()
    {
        return SuaraPilkada::query()
            ->select('cakadas.no_urut_paslon', 'cakadas.nama_paslon', DB::raw('SUM(suara_pilkadas.jumlah_suara) as jumlah_suara'))
            ->join('cakadas', 'suara_pilkadas.cakada_id', '=', 'cakadas.id')
            ->join('pilkadas', 'suara_pilkadas.pilkada_id', '=', 'pilkadas.id')
            ->where('pilkadas.dapil_id', $this->idDapil)
            ->groupBy('suara_pilkadas.cakada_id');
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
