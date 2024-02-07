<?php

namespace App\DataTables\Admin;

use App\Models\SurveyTitle;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Html\Editor\Editor;
use Yajra\DataTables\Html\Editor\Fields;
use Yajra\DataTables\Services\DataTable;

class SurveyTitleDataTable extends DataTable
{
    public $permission = "Survey.web";
    /**
     * Build the DataTable class.
     *
     * @param QueryBuilder $query Results from query() method.
     */
    public function dataTable(QueryBuilder $query): EloquentDataTable
    {
        return (new EloquentDataTable($query))
            ->addColumn('action', function (SurveyTitle $surveyTitle) {
                $button = "";
                if (Auth::user()->can($this->permission . '.show')) {
                    $button .= '<a href="' . route("admin.survey.judul.show", $surveyTitle->id) . '" class="btn btn-sm btn-icon me-2"><i class="ti ti-report-analytics"></i></a>';
                }
                if (Auth::user()->can($this->permission . ".edit")) {
                    $button .= '<a href="' . route("admin.survey.judul.edit", $surveyTitle->id) . '" class="btn btn-sm btn-icon me-2"><i class="ti ti-pencil"></i></a>';
                }
                if (Auth::user()->can($this->permission . ".destroy")) {
                    $button .= '<form id="deleteJudul' . $surveyTitle->id . '" action="' . route('admin.survey.judul.destroy', $surveyTitle->id) . '" method="post" class="d-inline" enctype="multipart/form-data">
                                <input type="hidden" name="_token" value="' . csrf_token() . '">
                                <input type="hidden" name="_method" value="DELETE">
                                <button type="button" onclick="deleteJudul(' . $surveyTitle->id . ')" class="btn btn-sm btn-icon"><i class="ti ti-trash"></i></button>
                                </form>
                    ';
                }
                return $button;
            })
            ->escapeColumns([])
            ->setRowId('id');
    }

    /**
     * Get the query source of dataTable.
     */
    public function query(SurveyTitle $model): QueryBuilder
    {
        return $model->newQuery();
    }

    /**
     * Optional method if you want to use the html builder.
     */
    public function html(): HtmlBuilder
    {
        return $this->builder()
            ->setTableId('surveytitle-table')
            ->columns($this->getColumns())
            ->minifiedAjax()
            //->dom('Bfrtip')
            ->orderBy(1)
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
            Column::make('judul'),
            Column::make('deskripsi'),
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
        return 'SurveyTitle_' . date('YmdHis');
    }
}
