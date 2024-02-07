<?php

namespace App\DataTables\Admin;

use App\Models\ConditionalQuestion;
use App\Models\SurveyQuestion;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Html\Editor\Editor;
use Yajra\DataTables\Html\Editor\Fields;
use Yajra\DataTables\Services\DataTable;

class ConditionalQuestionDataTable extends DataTable
{
    public $permission = "Survey.web";

    public function dataTable(QueryBuilder $query): EloquentDataTable
    {
        return (new EloquentDataTable($query))
            ->addColumn('action', function ($radio) {
                $button = '';
                if (Auth::user()->can($this->permission . ".edit")) {
                    $button .= '<a href="' . route("admin.survey.perkondisian.edit", $radio->id) . '" class="btn btn-sm btn-icon me-2"><i class="ti ti-edit"></i></a>';
                }
                return $button;
            })
            ->setRowId('id')
            ->escapeColumns([]);
    }

    /**
     * Get the query source of dataTable.
     */
    public function query(SurveyQuestion $model): QueryBuilder
    {
        return $model->newQuery()->where('type', 'radio');
    }

    /**
     * Optional method if you want to use the html builder.
     */
    public function html(): HtmlBuilder
    {
        return $this->builder()
            ->setTableId('surveyradiocondition-table')
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
            Column::make('index'),
            Column::make('question'),
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
        return 'SurveyRadioCondition_' . date('YmdHis');
    }
}
