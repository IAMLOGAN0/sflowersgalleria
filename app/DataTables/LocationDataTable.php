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
    protected $pinColors = [];

    /**
     * Build the DataTable class.
     */
    public function dataTable(QueryBuilder $query): EloquentDataTable
    {
        // Generate consistent colors for each Pincode
        $pins = $query->pluck('pin')->unique();
        foreach ($pins as $pin) {
            $hash = substr(md5($pin), 0, 6);
            $this->pinColors[$pin] = "#$hash";
        }

        return (new EloquentDataTable($query))
            ->addIndexColumn() // Adds auto-increment ID
            ->editColumn('sectors', function($row) {
                $sectors = explode(',', $row->sectors);
                if (!empty($sectors)) {
                    return collect($sectors)
                        ->map(fn($sector) => "<span class='badge badge-secondary mr-1 px-2 py-1 mt-2'>{$sector}</span>")
                        ->implode(' ');
                }
                return '-';
            })
            ->editColumn('b_times', fn($row) => $row->b_times)
            ->editColumn('t_times', function($row) {
                $allSlots = [];
                foreach (explode(',', $row->t_times) as $slotJson) {
                    $slots = json_decode($slotJson, true);
                    if (is_array($slots)) {
                        $allSlots = array_merge($allSlots, $slots);
                    } elseif (!empty($slotJson)) {
                        $allSlots[] = $slotJson;
                    }
                }

                // Remove empty or whitespace-only slots
                $allSlots = array_map('trim', array_filter($allSlots));

                if (!empty($allSlots)) {
                    return collect($allSlots)
                        ->map(fn($slot) => "<span class='badge badge-info mr-1 px-2 py-1 mt-2'>{$slot}</span>")
                        ->implode(' ');
                }
                return '-';
            })
            ->addColumn('action', function($row){
                $editBtn = "<a href='" . route('admin.locations.edit', $row->pin) . "' class='btn btn-primary btn-sm'><i class='far fa-edit'></i></a>";
                $deleteBtn = "<a href='" . route('admin.locations.destroy', $row->pin) . "' class='btn btn-danger btn-sm ml-1 delete-item'><i class='far fa-trash-alt'></i></a>";
                return $editBtn . $deleteBtn;
            })
            ->addColumn('pin_color', fn($row) => $this->pinColors[$row->pin] ?? '#ffffff')
            ->rawColumns(['sectors','t_times','action']);
    }

    /**
     * Get the query source of dataTable.
     */
    public function query(Location $model): QueryBuilder
    {
        return $model->newQuery()
            ->selectRaw('
                pin,
                GROUP_CONCAT(sector SEPARATOR ",") as sectors,
                GROUP_CONCAT(b_time SEPARATOR ",") as b_times,
                GROUP_CONCAT(t_time SEPARATOR ",") as t_times
            ')
            ->groupBy('pin')
            ->orderBy('pin');
    }

    /**
     * Optional HTML builder.
     */
    public function html(): HtmlBuilder
    {
        return $this->builder()
            ->setTableId('location-table')
            ->columns($this->getColumns())
            ->minifiedAjax()
            ->orderBy(0)
            ->addTableClass('table table-bordered table-striped table-hover') // borders + striped + hover
            ->parameters([
                'dom' => '<"top"fB>rt<"bottom"lip>',
                'pageLength' => 10,
                'responsive' => true,
                'autoWidth' => false,
                'scrollX' => true,
                'scrollCollapse' => true,
                'lengthMenu' => [[10, 25, 50, 100], [10, 25, 50, 100]],
                'buttons' => [
                    ['extend' => 'print', 'className' => 'btn btn-primary btn-sm'],
                    ['extend' => 'copy', 'className' => 'btn btn-primary btn-sm'],
                    ['extend' => 'excel', 'className' => 'btn btn-primary btn-sm'],
                    ['extend' => 'pdf', 'className' => 'btn btn-primary btn-sm'],
                    ['extend' => 'csv', 'className' => 'btn btn-primary btn-sm'],
                ],
            ]);
    }


    /**
     * Get the columns definition.
     */
    public function getColumns(): array
    {
        return [
            Column::computed('DT_RowIndex')->title('ID')->addClass('text-center'),
            Column::make('pin')->title('Pincode'),
            Column::make('sectors')->title('Sectors'),
            Column::make('b_times')->title('Delivery Taken Time'),
            Column::make('t_times')->title('Time Slots'),
            Column::computed('action')
                ->exportable(false)
                ->printable(false)
                ->width(150)
                ->addClass('text-center'),
        ];
    }

    /**
     * Filename for export.
     */
    protected function filename(): string
    {
        return 'Location_' . date('YmdHis');
    }
}

