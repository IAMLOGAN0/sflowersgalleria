<?php

namespace App\DataTables;

use App\Models\Location;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;

class LocationDataTable extends DataTable
{
    /**
     * Build the DataTable class.
     *
     * @param QueryBuilder $query Results from query() method.
     */
    public function dataTable(QueryBuilder $query): EloquentDataTable
    {
        return (new EloquentDataTable($query))
            ->editColumn('t_time', function ($location) {
                $slots = json_decode($location->t_time, true);
                if (!empty($slots)) {
                    return collect($slots)->map(function ($slot) {
                        return "<span class='badge badge-info mr-1'>{$slot}</span>";
                    })->implode(' ');
                }
                return '-';
            })
            ->addColumn('action', function ($location) {
                $editBtn = "<a href='" . route('admin.locations.edit', $location->id) . "'
                               class='btn btn-primary btn-sm'>
                               <i class='far fa-edit'></i>
                            </a>";

                $deleteBtn = "<a href='" . route('admin.locations.destroy', $location->id) . "'
                                 class='btn btn-danger btn-sm ml-1 delete-item'>
                                 <i class='far fa-trash-alt'></i>
                              </a>";

                return $editBtn . $deleteBtn;
            })
            ->rawColumns(['t_time', 'action'])
            ->setRowId('id');
    }

    /**
     * Get the query source of dataTable.
     */
    public function query(Location $model): QueryBuilder
    {
        return $model->newQuery();
    }

    /**
     * Optional method if you want to use the html builder.
     */
    public function html(): HtmlBuilder
    {
        return $this->builder()
            ->setTableId('location-table')
            ->columns($this->getColumns())
            ->minifiedAjax()
            ->orderBy(0)
            ->selectStyleSingle()
            ->buttons([
                Button::make('excel'),
                Button::make('csv'),
                Button::make('pdf'),
                Button::make('print'),
                Button::make('reset'),
                Button::make('reload'),
            ]);
    }

    /**
     * Get the dataTable columns definition.
     */
    public function getColumns(): array
    {
        return [
            Column::make('id'),
            Column::make('sector')->title('Sector'),
            Column::make('pin')->title('PIN'),
            Column::make('b_time')->title('Booking Time'),
            Column::make('t_time')->title('Time Slots'),
            Column::computed('action')
                ->exportable(false)
                ->printable(false)
                ->width(150)
                ->addClass('text-center'),
        ];
    }

    /**
     * Get the filename for export.
     */
    protected function filename(): string
    {
        return 'Location_' . date('YmdHis');
    }
}
