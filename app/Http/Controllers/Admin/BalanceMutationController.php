<?php

namespace App\Http\Controllers\Admin;

use App\DataTables\Admin\BalanceMutationDataTable;
use App\Models\BalanceMutation;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class BalanceMutationController extends Controller
{
    public function index(BalanceMutationDataTable $balanceMutationDataTable)
    {
        $page = 'Mutasi Saldo';
        $type = ['debit' => 'Debet', 'credit' => 'Kredit'];
        $category = ['deposit','order','refund','upgrade-level','others'];
        return $balanceMutationDataTable->render('admin.balance-mutation.index', compact('page', 'type', 'category'));
    }

    public function create()
    {
        //
    }

    public function store(Request $request)
    {
        //
    }

    public function show(BalanceMutation $balanceMutation)
    {
        //
    }

    public function edit(BalanceMutation $balanceMutation)
    {
        //
    }

    public function update(Request $request, BalanceMutation $balanceMutation)
    {
        //
    }

    public function destroy(BalanceMutation $balanceMutation)
    {
        //
    }
}
