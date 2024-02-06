<?php

namespace App\DataTables\Admin;

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

class SurveyQuestionDataTable extends DataTable
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
            ->escapeColumns([])
            ->addColumn('action', function ($surveyQuestion) {
                $button = "";
                if (Auth::user()->can($this->permission . '.edit')) {
                    $button .= '<a href="' . route("admin.survey.pertanyaan.edit", $surveyQuestion->id) . '" class="btn btn-sm btn-icon me-2"><i class="ti ti-edit"></i></a>';
                }
                if (Auth::user()->can($this->permission . '.destroy')) {
                    $button .= '<form id="deletePertanyaan' . $surveyQuestion->id . '" action="' . route('admin.survey.pertanyaan.destroy', $surveyQuestion->id) . '" method="post" class="d-inline" enctype="multipart/form-data">
                                <input type="hidden" name="_token" value="' . csrf_token() . '">
                                <input type="hidden" name="_method" value="DELETE">
                                <button type="button" onclick="deletePertanyaan(' . $surveyQuestion->id . ')" class="btn btn-sm btn-icon"><i class="ti ti-trash"></i></button>
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
    public function query(SurveyQuestion $model): QueryBuilder
    {
        $titleId = $this->request()->get('survey_title_id');
        $query = $model->newQuery();
        if ($titleId) {
            $query->whereHas('SurveyCategory', function ($query) use ($titleId) {
                $query->where('survey_title_id', $titleId);
            });
        }
        return $query;
    }

    /**
     * Optional method if you want to use the html builder.
     */
    public function html(): HtmlBuilder
    {
        return $this->builder()
            ->setTableId('surveyquestion-table')
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
            Column::make('type'),
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
        return 'SurveyQuestion_' . date('YmdHis');
    }
}
