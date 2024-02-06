<?php

namespace App\DataTables\Admin;

use App\Models\User;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Html\Editor\Editor;
use Yajra\DataTables\Html\Editor\Fields;
use Yajra\DataTables\Services\DataTable;

class PetugasDataTable extends DataTable
{
    public $permission = "Petugas.web";
    /**
     * Build the DataTable class.
     *
     * @param QueryBuilder $query Results from query() method.
     */
    public function dataTable(QueryBuilder $query): EloquentDataTable
    {
        return (new EloquentDataTable($query))
            ->addColumn('role', function ($user) {
                return $user->getRoleNames()->implode(', ');
            })
            ->addColumn('action', function ($user) {
                $button = '';
                if (Auth::user()->can($this->permission . '.show')) {
                    $button .= '<a href="' . route('admin.master.petugas.show', $user->id) . '" class=""btn btn-sm btn-icon me-2"><i class="ti ti-eye"></i></a>';
                }
                if (Auth::user()->can($this->permission . '.edit')) {
                    $button .= '<a href="' . route('admin.master.petugas.edit', $user->id) . '" class="btn btn-sm btn-icon ms-2"><i class="ti ti-edit"></i></a>';
                }
                if (Auth::user()->can($this->permission . '.destroy')) {
                    $button .= '<form id="deletePetugas' . $user->id . '" action="' . route('admin.master.petugas.destroy', $user->id) . '" method="post" class="d-inline" enctype="multipart/form-data">
                                <input type="hidden" name="_token" value="' . csrf_token() . '">
                                <input type="hidden" name="_method" value="DELETE">
                                <button type="button" onclick="deletePetugas(' . $user->id . ')" class="btn btn-sm btn-icon"><i class="ti ti-trash"></i></button>
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
    public function query(User $model): QueryBuilder
    {
        return $model->newQuery();
    }

    /**
     * Optional method if you want to use the html builder.
     */
    public function html(): HtmlBuilder
    {
        return $this->builder()
            ->setTableId('user-table')
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
            Column::make('name'),
            Column::make('email'),
            Column::computed('role')
                ->exportable(false)
                ->printable(false)
                ->orderable(true),
            Column::computed('action')
                ->exportable(false)
                ->printable(false),
        ];
    }

    /**
     * Get the filename for export.
     */
    protected function filename(): string
    {
        return 'User_' . date('YmdHis');
    }
}
