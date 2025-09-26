<?php

namespace App\DataTables;

use App\Models\Order;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;

class UserOrderDataTable extends DataTable
{
    public function dataTable(QueryBuilder $query): EloquentDataTable
    {
        return (new EloquentDataTable($query))
            ->addColumn('action', function ($query) {
                return "<a href='" . route('user.orders.show', $query->id) . "'
                            class='btn btn-sm btn-primary'>
                            <i class='far fa-eye'></i> View
                        </a>";
            })
            ->addColumn('amount', function ($query) {
                return $query->currency_icon . $query->amount;
            })
            ->addColumn('date', function ($query) {
                return date('d M, Y', strtotime($query->created_at));
            })
            ->addColumn('payment_status', function ($query) {
                if ($query->payment_status === 1) {
                    return "<span class='badge bg-success'>
                                <i class='fas fa-check-circle me-1'></i> Paid
                            </span>";
                } else {
                    return "<span class='badge bg-danger'>
                                <i class='fas fa-times-circle me-1'></i> Unpaid
                            </span>";
                }
            })
            ->addColumn('order_status', function ($query) {
                switch ($query->order_status) {
                    case 'pending':
                        return "<span class='badge bg-warning'>
                                    <i class='fas fa-hourglass-half me-1'></i> Pending
                                </span>";
                    case 'processed_and_ready_to_ship':
                        return "<span class='badge bg-info'>
                                    <i class='fas fa-cogs me-1'></i> Processing
                                </span>";
                    case 'dropped_off':
                        return "<span class='badge bg-secondary'>
                                    <i class='fas fa-store-alt me-1'></i> Dropped Off
                                </span>";
                    case 'shipped':
                        return "<span class='badge bg-info'>
                                    <i class='fas fa-shipping-fast me-1'></i> Shipped
                                </span>";
                    case 'out_for_delivery':
                        return "<span class='badge bg-primary'>
                                    <i class='fas fa-truck me-1'></i> Out for Delivery
                                </span>";
                    case 'delivered':
                        return "<span class='badge bg-success'>
                                    <i class='fas fa-box-open me-1'></i> Delivered
                                </span>";
                    case 'canceled':
                        return "<span class='badge bg-danger'>
                                    <i class='fas fa-ban me-1'></i> Canceled
                                </span>";
                    default:
                        return "<span class='badge bg-dark'>
                                    <i class='fas fa-question-circle me-1'></i> Unknown
                                </span>";
                }
            })
            ->rawColumns(['order_status', 'action', 'payment_status'])
            ->setRowId('id');
    }

    public function query(Order $model): QueryBuilder
    {
        return $model::where('user_id', Auth::user()->id)->newQuery();
    }

    public function html(): HtmlBuilder
    {
        return $this->builder()
            ->setTableId('userorder-table')
            ->columns($this->getColumns())
            ->responsive(true)
            ->minifiedAjax()
            ->orderBy(0)
            ->selectStyleSingle()
            ->buttons([
                Button::make('excel'),
                Button::make('csv'),
                Button::make('pdf'),
                Button::make('print'),
                Button::make('reset'),
                Button::make('reload')
            ])
            ->parameters([
                'responsive' => [
                    'details' => [
                        'renderer' => 'function ( api, rowIdx, columns ) {
                            var data = $.map(columns, function (col, i) {
                                return col.hidden ?
                                    "<div class=\'mb-2\'><strong>" + col.title + ":</strong> " + col.data + "</div>" :
                                    "";
                            }).join("");
                            return data ? $("<div class=\'card p-2 shadow-sm bg-light\'/>").append(data) : false;
                        }'
                    ]
                ]
            ]);
    }

    public function getColumns(): array
    {
        return [
            Column::make('invocie_id')->title('Invoice')->width(80)->responsivePriority(1),
            Column::make('date')->title('Order Date')->width(120)->responsivePriority(2),
            Column::make('product_qty')->title('Items')->width(60)->responsivePriority(5),
            Column::make('amount')->title('Total')->width(100)->responsivePriority(3),
            Column::make('payment_status')->title('Payment')->width(100)->responsivePriority(2),
            Column::make('order_status')->title('Status')->width(120)->responsivePriority(1),
            Column::make('payment_method')->title('Method')->width(120)->responsivePriority(6),
            Column::computed('action')
                ->exportable(false)
                ->printable(false)
                ->width(80)
                ->addClass('text-center')
                ->title('Action')
                ->responsivePriority(1),
        ];
    }

    protected function filename(): string
    {
        return 'UserOrder_' . date('YmdHis');
    }
}
