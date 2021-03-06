<?php

namespace App\DataTables;

use App\Models\Pelanggaran;
use App\Models\PelanggaranDetail;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Services\DataTable;
use Yajra\DataTables\EloquentDataTable;

class PelanggaranDetailDataTable extends DataTable
{
    /**
     * Build DataTable class.
     *
     * @param mixed $query Results from query() method.
     * @return \Yajra\DataTables\DataTableAbstract
     */
    public function dataTable($query)
    {
        $dataTable = new EloquentDataTable($query);

        return $dataTable->addColumn('action', 'pelanggaran_details.datatables_actions');
    }

    /**
     * Get query source of dataTable.
     *
     * @param \App\Models\PelanggaranDetail $model
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function query(PelanggaranDetail $model)
    {
        return $model->newQuery()->with(['pelanggaran', 'bio_siswa'])->groupBy('id_pelanggaran');
    }

    /**
     * Optional method if you want to use html builder.
     *
     * @return \Yajra\DataTables\Html\Builder
     */
    public function html()
    {
        return $this->builder()
            ->columns($this->getColumns())
            ->minifiedAjax()
            ->addAction(['width' => '120px', 'printable' => false])
            ->parameters([
                'dom'       => 'Bfrtip',
                'stateSave' => true,
                'order'     => [[0, 'desc']],
                'buttons'   => [
                    ['extend' => 'export', 'className' => 'btn btn-default btn-sm no-corner',],
                    ['extend' => 'print', 'className' => 'btn btn-default btn-sm no-corner',],
                    ['extend' => 'reset', 'className' => 'btn btn-default btn-sm no-corner',],
                    ['extend' => 'reload', 'className' => 'btn btn-default btn-sm no-corner',],
                ],
            ]);
    }

    /**
     * Get columns.
     *
     * @return array
     */
    protected function getColumns()
    {
        return [
            'nama_lengkap' => ['title' => 'Nama Santri', 'name' => 'bio_siswa.nama_lengkap', 'data' => 'bio_siswa.nama_lengkap'],
            'keterangan' => ['name' => 'pelanggaran.keterangan', 'data' => 'pelanggaran.keterangan'],
            'total score' => ['data' => 'pelanggaran.skor'],
        ];
    }

    /**
     * Get filename for export.
     *
     * @return string
     */
    protected function filename()
    {
        return 'pelanggaran_detailsdatatable_' . time();
    }
}
