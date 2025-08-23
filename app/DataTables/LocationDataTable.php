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
            ->editColumn('t_time', function ($location) {
                $slots = json_decode($location->t_time, true);
                if (!empty($slots)) {
                    return collect($slots)
                        ->map(fn($slot) => "<span class='badge badge-info mr-1'>{$slot}</span>")
                        ->implode(' ');
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
            ->addColumn('pin_color', function ($location) {
                return $this->pinColors[$location->pin] ?? '#ffffff';
            })
            ->rawColumns(['t_time', 'action'])
            ->setRowId('id');
    }

    /**
     * Get the query source of dataTable.
     */
    public function query(Location $model): QueryBuilder
    {
        return $model->newQuery()->orderBy('pin'); // order by pin for grouping
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
            ->orderBy(2)
            ->parameters([
                'rowGroup' => [
                    'dataSrc' => 'pin',
                    'startRender' => 'function(rows, group){
                        return `<tr class="group-header">
                                    <td colspan="5" style="background-color:#f0f0f0; font-weight:bold;">
                                        Pincode: ${group} (${rows.count()} sectors)
                                    </td>
                                </tr>`;
                    }'
                ],
                'rowCallback' => 'function(row, data){
                    // Apply background color only to Pincode column (3rd column, zero-based index 2)
                    let bgColor = data.pin_color;
                    let textColor = getContrastYIQ(bgColor);
                    $("td:eq(1)", row).css({
                        "background-color": bgColor,
                        "color": textColor,
                        "font-weight": "bold"
                    });

                    function getContrastYIQ(hexcolor){
                        hexcolor = hexcolor.replace("#","");
                        let r = parseInt(hexcolor.substr(0,2),16);
                        let g = parseInt(hexcolor.substr(2,2),16);
                        let b = parseInt(hexcolor.substr(4,2),16);
                        let yiq = ((r*299)+(g*587)+(b*114))/1000;
                        return (yiq >= 128) ? "black" : "white";
                    }
                }',
                'ordering' => false,
                'responsive' => true,
                'autoWidth' => false,
            ])
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
     * Get the columns definition.
     */
    public function getColumns(): array
    {
        return [
            Column::make('id')->title('ID'),
            Column::make('pin')->title('Pincode'),
            Column::make('sector')->title('Sector'),
            Column::make('b_time')->title('Delivery Taken Time'),
            Column::make('t_time')->title('Time Slots'),
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

