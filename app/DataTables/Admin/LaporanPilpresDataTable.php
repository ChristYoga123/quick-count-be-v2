<?php

namespace App\DataTables\Admin;

use App\Models\LaporanPilpre;
use App\Models\LaporanPilpres;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Html\Editor\Editor;
use Yajra\DataTables\Html\Editor\Fields;
use Yajra\DataTables\Services\DataTable;

class LaporanPilpresDataTable extends DataTable
{
    public $permission = 'Laporan.web';
    /**
     * Build the DataTable class.
     *
     * @param QueryBuilder $query Results from query() method.
     */
    public function dataTable(QueryBuilder $query): EloquentDataTable
    {
        return (new EloquentDataTable($query))
            ->addColumn('nama', function (LaporanPilpres $laporanPilpres) {
                return $laporanPilpres->User->name;
            })
            ->addColumn('dapil', function (LaporanPilpres $laporanPilpres) {
                return $laporanPilpres->Pilpres->Dapil->index;
            })
            ->addColumn('action', function (LaporanPilpres $laporanPilpres) {
                if (Auth::user()->can($this->permission . '.show')) {
                    return '<a href="' . route('admin.laporan.pilpres.show', $laporanPilpres->id) . '" class="btn btn-sm"><i class="ti ti-eye"></></a>';
                }
            })
            ->setRowId('id');
    }

    /**
     * Get the query source of dataTable.
     */
    public function query(LaporanPilpres $model): QueryBuilder
    {
        return $model->newQuery()->with(['Pilpres.Dapil', 'User']);
    }

    /**
     * Optional method if you want to use the html builder.
     */
    public function html(): HtmlBuilder
    {
        return $this->builder()
            ->setTableId('laporanpilpres-table')
            ->columns($this->getColumns())
            ->minifiedAjax()
            //->dom('Bfrtip')
            ->orderBy(0, 'asc')
            ->selectStyleSingle()
            ->buttons([
                Button::make('excel'),
                Button::make('csv'),
                Button::make('pdf'),
                Button::make('print'),
                Button::make('reset'),
                Button::make('reload')
            ]);
    }

    /**
     * Get the dataTable columns definition.
     */
    public function getColumns(): array
    {
        return [
            Column::make('id'),
            Column::computed('nama')
                ->exportable(false)
                ->printable(false),
            Column::computed('dapil')
                ->exportable(false)
                ->printable(false),
            Column::computed('action')
                ->exportable(false)
                ->printable(false)
        ];
    }

    /**
     * Get the filename for export.
     */
    protected function filename(): string
    {
        return 'LaporanPilpres_' . date('YmdHis');
    }
}
