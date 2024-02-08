<?php

namespace App\DataTables\Admin;

use App\Models\Caleg;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Html\Editor\Editor;
use Yajra\DataTables\Html\Editor\Fields;
use Yajra\DataTables\Services\DataTable;

class CalegDataTable extends DataTable
{
    public $permission = "Caleg.web";
    /**
     * Build the DataTable class.
     *
     * @param QueryBuilder $query Results from query() method.
     */
    public function dataTable(QueryBuilder $query): EloquentDataTable
    {
        return (new EloquentDataTable($query))
            ->addColumn('partai', function (Caleg $caleg) {
                return $caleg->Partai->nama;
            })
            ->addColumn('dapil', function (Caleg $caleg) {
                return $caleg->Dapil->index;
            })
            ->addColumn('action', function (Caleg $caleg) {
                $button = '';
                if (Auth::user()->can($this->permission . '.edit')) {
                    $button .= '<a href="' . route("admin.master.caleg.edit", $caleg->id) . '" class="btn btn-sm btn-icon me-2"><i class="ti ti-edit"></i></a>';
                }
                if (Auth::user()->can($this->permission . '.destroy')) {
                    $button .= '<form id="deleteCaleg' . $caleg->id . '" action="' . route('admin.master.caleg.destroy', $caleg->id) . '" method="post" class="d-inline" enctype="multipart/form-data">
                                <input type="hidden" name="_token" value="' . csrf_token() . '">
                                <input type="hidden" name="_method" value="DELETE">
                                <button type="button" onclick="deleteCaleg(' . $caleg->id . ')" class="btn btn-sm btn-icon"><i class="ti ti-trash"></i></button>
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
    public function query(Caleg $model): QueryBuilder
    {
        $partaiId = $this->request()->get('partai_id');
        $dapilId = $this->request()->get('dapil_id');
        $query = $model->newQuery()->with(['Partai', 'Dapil']);
        if ($partaiId && $dapilId) {
            $query = $query->where('partai_id', $partaiId)->where('dapil_id', $dapilId);
        }
        return $query;
    }

    /**
     * Optional method if you want to use the html builder.
     */
    public function html(): HtmlBuilder
    {
        return $this->builder()
            ->setTableId('caleg-table')
            ->columns($this->getColumns())
            ->minifiedAjax()
            //->dom('Bfrtip')
            ->orderBy(0, 'asc')
            ->selectStyleSingle()
            ->buttons([]);
    }

    /**
     * Get the dataTable columns definition.
     */
    public function getColumns(): array
    {
        return [
            Column::make('id'),
            Column::make('nama'),
            Column::computed('partai')
                ->exportable(false)
                ->printable(false)
                ->addClass('text-center'),
            Column::computed('dapil')
                ->exportable(false)
                ->printable(false)
                ->addClass('text-center'),
            Column::make('no_urut'),
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
        return 'Caleg_' . date('YmdHis');
    }
}
