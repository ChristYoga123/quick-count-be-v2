<?php

namespace App\DataTables\Admin;

use App\Models\TPS;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Html\Editor\Editor;
use Yajra\DataTables\Html\Editor\Fields;
use Yajra\DataTables\Services\DataTable;

class TPSDataTable extends DataTable
{
    public $permission = "TPS.web";
    /**
     * Build the DataTable class.
     *
     * @param QueryBuilder $query Results from query() method.
     */
    public function dataTable(QueryBuilder $query): EloquentDataTable
    {
        return (new EloquentDataTable($query))
            ->addColumn('action', function ($tps) {
                $button = '';
                if (Auth::user()->can($this->permission . '.edit')) {
                    $button .= '<a href="' . route("admin.master.tps.edit", $tps->id) . '" class="btn btn-sm btn-icon me-2"><i class="ti ti-edit"></i></a>';
                }
                if (Auth::user()->can($this->permission . '.destroy')) {
                    $button .= '<form id="deleteTPS' . $tps->id . '" action="' . route('admin.master.tps.destroy', $tps->id) . '" method="post" class="d-inline" enctype="multipart/form-data">
                                <input type="hidden" name="_token" value="' . csrf_token() . '">
                                <input type="hidden" name="_method" value="DELETE">
                                <button type="button" onclick="deleteTPS(' . $tps->id . ')" class="btn btn-sm btn-icon"><i class="ti ti-trash"></i></button>
                                </form>
                    ';
                }
                return $button;
            })
            ->setRowId('id');
    }

    /**
     * Get the query source of dataTable.
     */
    public function query(TPS $model): QueryBuilder
    {
        return $model->newQuery();
    }

    /**
     * Optional method if you want to use the html builder.
     */
    public function html(): HtmlBuilder
    {
        return $this->builder()
            ->setTableId('tps-table')
            ->columns($this->getColumns())
            ->minifiedAjax()
            //->dom('Bfrtip')
            ->orderBy(1)
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
            Column::make('index'),
            Column::make('kecamatan'),
            Column::make('kelurahan'),
            Column::computed('action')
                ->exportable(false)
                ->printable(false)
                ->addClass('text-center'),
        ];
    }

    /**
     * Get the filename for export.
     */
    protected function filename(): string
    {
        return 'TPS_' . date('YmdHis');
    }
}
