<?php

namespace App\DataTables\Admin;

use App\Models\Capre;
use App\Models\Capres;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Html\Editor\Editor;
use Yajra\DataTables\Html\Editor\Fields;
use Yajra\DataTables\Services\DataTable;

class CapresDataTable extends DataTable
{
    public $permission = "Capres.web";
    /**
     * Build the DataTable class.
     *
     * @param QueryBuilder $query Results from query() method.
     */
    public function dataTable(QueryBuilder $query): EloquentDataTable
    {
        return (new EloquentDataTable($query))
            ->addColumn('action', function (Capres $capres) {
                $button = '';
                if (Auth::user()->can($this->permission . '.edit')) {
                    $button .= '<a href="' . route("admin.master.capres.edit", $capres->id) . '" class="btn btn-sm btn-icon me-2"><i class="ti ti-edit"></i></a>';
                }
                if (Auth::user()->can($this->permission . '.destroy')) {
                    $button .= '<form id="deleteCapres' . $capres->id . '" action="' . route('admin.master.capres.destroy', $capres->id) . '" method="post" class="d-inline" enctype="multipart/form-data">
                                <input type="hidden" name="_token" value="' . csrf_token() . '">
                                <input type="hidden" name="_method" value="DELETE">
                                <button type="button" onclick="deleteCapres(' . $capres->id . ')" class="btn btn-sm btn-icon"><i class="ti ti-trash"></i></button>
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
    public function query(Capres $model): QueryBuilder
    {
        return $model->newQuery();
    }

    /**
     * Optional method if you want to use the html builder.
     */
    public function html(): HtmlBuilder
    {
        return $this->builder()
            ->setTableId('capres-table')
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
            Column::make('no_urut_paslon'),
            Column::make('nama_paslon'),
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
        return 'Capres_' . date('YmdHis');
    }
}
