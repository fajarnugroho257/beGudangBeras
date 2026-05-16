<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Models\api\ProcessInput;
use App\Models\api\ProcessInputData;
use App\Models\api\ProcessOutput;
use App\Models\api\ProcessOutputData;
use App\Models\api\Barang;
use App\Models\api\Suplier;
use App\Models\api\StokBarang;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ProcessController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->params;

        $data = ProcessInput::with(['processInputData.barang', 'processInputData.supplier', 'processOutput.processOutputData.barang'])
            ->whereBetween('process_input_tgl', [$request->dateFrom, $request->dateTo])
            ->when($search, function ($query) use ($search) {
                $query->whereHas('processInputData.barang', function ($q) use ($search) {
                    $q->where('nama', 'like', "%{$search}%");
                });
            })
            ->orderBy('id', 'DESC')
            ->get();

        return response()->json([
            'success' => true,
            'data' => $data,
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'processInput.process_input_tgl' => 'required|date',
            'processInputData' => 'required|array|min:1',
        ]);

        $result = DB::transaction(function () use ($request) {

            $processInput = ProcessInput::create([
                'process_input_tgl' => $request->processInput['process_input_tgl'],
                'operasional' => $request->processInput['operasional'] ?? 0,
            ]);

            foreach ($request->processInputData as $item) {

                ProcessInputData::create([
                    'process_input_id' => $processInput->id,
                    'barang_id' => $item['barang_id'],
                    'supplier_id' => $item['supplier_id'],
                    'tonase' => $item['tonase'],
                ]);

                if (!empty($item['barang_id']) && !empty($item['supplier_id'])) {

                    $stokBarang = StokBarang::where('barang_id', $item['barang_id'])
                        ->where('suplier_id', $item['supplier_id'])
                        ->lockForUpdate()
                        ->first();

                    if (!$stokBarang) {
                        throw new \Exception('Stok barang tidak ditemukan');
                    }

                    if ($stokBarang->stok < $item['tonase']) {
                        throw new \Exception('Stok tidak cukup untuk proses produksi');
                    }

                    $stokBarang->decrement('stok', $item['tonase']);
                }
            }

            if (!empty($request->processOutput)) {

                $processOutput = ProcessOutput::create([
                    'process_input_id' => $processInput->id,
                    'process_output_tgl' => $request->processOutput['process_output_tgl'],
                ]);

                if (!empty($request->processOutputData)) {

                    $supplierOlahan = Suplier::firstOrCreate(
                        [
                            'suplier_nama' => 'Produk Olahan',
                        ],
                        [
                            'alamat' => '-',
                            'no_hp' => '-',
                        ]
                    );

                    foreach ($request->processOutputData as $item) {

                        $barangId = $item['barang_id'] ?? null;
                        $supplierId = $supplierOlahan->id;

                        if (
                            !empty($item['barang_tipe']) &&
                            strtolower($item['barang_tipe']) != 'beras'
                        ) {

                            $barangOlahan = Barang::firstOrCreate(
                                [
                                    'nama' => $item['barang_tipe'] . ' Olahan',
                                ],
                                [
                                    'tipe' => $item['barang_tipe'],
                                    'is_process' => 1,
                                ]
                            );

                            $barangId = $barangOlahan->id;
                        } else {

                            if (empty($barangId)) {

                                if (empty($item['barang_nama'])) {
                                    throw new \Exception('Barang nama wajib diisi');
                                }

                                $barangBaru = Barang::create([
                                    'nama' => $item['barang_nama'],
                                    'tipe' => 'beras',
                                    'is_process' => 1,
                                ]);

                                $barangId = $barangBaru->id;
                            }
                        }

                        ProcessOutputData::create([
                            'process_output_id' => $processOutput->id,
                            'barang_id' => $barangId,
                            'tonase' => $item['tonase'],
                        ]);

                        $stokBarang = StokBarang::lockForUpdate()
                            ->firstOrCreate(
                                [
                                    'barang_id' => $barangId,
                                    'suplier_id' => $supplierId,
                                ],
                                [
                                    'stok' => 0,
                                ]
                            );

                        $stokBarang->increment('stok', $item['tonase']);
                    }
                }
            }

            return $processInput->load([
                'processInputData.barang',
                'processInputData.supplier',
                'processOutput.processOutputData.barang',
            ]);
        });

        return response()->json([
            'success' => true,
            'data' => $result,
        ], 200);
    }

    public function show($id)
    {
        $data = ProcessInput::with(['processInputData.barang', 'processInputData.supplier', 'processOutput.processOutputData.barang', 'processOutput.processOutputData.supplier'])->find($id);

        if (!$data) {
            return response()->json(
                [
                    'success' => false,
                    'message' => 'Data tidak ditemukan',
                ],
                404,
            );
        }

        return response()->json([
            'success' => true,
            'data' => $data,
        ]);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'processInput.process_input_tgl' => 'required|date',
            'processInputData' => 'required|array|min:1',
        ]);

        $processInput = ProcessInput::find($id);

        if (!$processInput) {
            return response()->json(
                [
                    'success' => false,
                    'message' => 'Data tidak ditemukan',
                ],
                404,
            );
        }

        DB::transaction(function () use ($request, $processInput) {
            $processInput->update([
                'process_input_tgl' => $request->processInput['process_input_tgl'],
                'operasional' => $request->processInput['operasional'] ?? 0,
            ]);

            ProcessInputData::where('process_input_id', $processInput->id)->delete();

            foreach ($request->processInputData as $item) {
                ProcessInputData::create([
                    'process_input_id' => $processInput->id,
                    'barang_id' => $item['barang_id'],
                    'supplier_id' => $item['supplier_id'],
                    'tonase' => $item['tonase'],
                ]);
            }

            $oldOutputs = ProcessOutput::where('process_input_id', $processInput->id)->get();

            foreach ($oldOutputs as $output) {
                ProcessOutputData::where('process_output_id', $output->id)->delete();

                $output->delete();
            }

            if (!empty($request->processOutput)) {
                $processOutput = ProcessOutput::create([
                    'process_input_id' => $processInput->id,
                    'process_output_tgl' => $request->processOutput['process_output_tgl'],
                ]);

                if (!empty($request->processOutputData)) {
                    foreach ($request->processOutputData as $item) {
                        ProcessOutputData::create([
                            'process_output_id' => $processOutput->id,
                            'barang_id' => $item['barang_id'],
                            'supplier_id' => $item['supplier_id'],
                            'tonase' => $item['tonase'],
                        ]);
                    }
                }
            }
        });

        $result = ProcessInput::with(['processInputData.barang', 'processInputData.supplier', 'processOutput.processOutputData.barang', 'processOutput.processOutputData.supplier'])->find($id);

        return response()->json([
            'success' => true,
            'message' => 'Data berhasil diupdate',
            'data' => $result,
        ]);
    }

    public function destroy($id)
    {
        $processInput = ProcessInput::find($id);

        if (!$processInput) {
            return response()->json(
                [
                    'success' => false,
                    'message' => 'Data tidak ditemukan',
                ],
                404,
            );
        }

        DB::transaction(function () use ($processInput) {

            /**
             * =====================================
             * KEMBALIKAN STOCK PROCESS INPUT
             * =====================================
             */
            $inputDatas = ProcessInputData::where(
                'process_input_id',
                $processInput->id
            )->get();

            foreach ($inputDatas as $input) {

                if (!empty($input->barang_id) && !empty($input->supplier_id)) {

                    $stokBarang = StokBarang::where('barang_id', $input->barang_id)
                        ->where('suplier_id', $input->supplier_id)
                        ->lockForUpdate()
                        ->first();

                    if ($stokBarang) {
                        $stokBarang->increment('stok', $input->tonase);
                    }
                }
            }

            /**
             * =====================================
             * KURANGI STOCK HASIL OUTPUT
             * =====================================
             */
            $outputs = ProcessOutput::where(
                'process_input_id',
                $processInput->id
            )->get();

            foreach ($outputs as $output) {

                $outputDatas = ProcessOutputData::where(
                    'process_output_id',
                    $output->id
                )->get();

                foreach ($outputDatas as $outputData) {

                    if (!empty($outputData->barang_id)) {

                        $stokBarang = StokBarang::where('barang_id', $outputData->barang_id)
                            // ->where('suplier_id', $outputData->supplier_id)
                            ->lockForUpdate()
                            ->first();

                        if ($stokBarang) {

                            if ($stokBarang->stok < $outputData->tonase) {
                                throw new \Exception(
                                    'Stok hasil produksi tidak cukup untuk rollback'
                                );
                            }

                            $stokBarang->decrement('stok', $outputData->tonase);
                        }
                    }
                }

                /**
                 * delete output data
                 */
                ProcessOutputData::where(
                    'process_output_id',
                    $output->id
                )->delete();

                /**
                 * delete output
                 */
                $output->delete();
            }

            /**
             * delete input data
             */
            ProcessInputData::where(
                'process_input_id',
                $processInput->id
            )->delete();

            /**
             * delete process input
             */
            $processInput->delete();
        });

        return response()->json([
            'success' => true,
            'message' => 'Data berhasil dihapus',
        ]);
    }
}
