<?php

namespace App\Http\Controllers;

use App\Models\CompanyContact;
use Illuminate\Http\Request;

class CompanyContactController extends Controller
{
    public function index(Request $request)
    {
        $query = CompanyContact::query();

        if ($request->search) {
            $query->where('company_name', 'like', "%{$request->search}%")
                ->orWhere('responsible_name', 'like', "%{$request->search}%");
        }

        $data = $query->orderBy('id', 'desc')->get();

        return view('company-contacts.index', compact('data'));
    }

    public function create()
    {
        return view('company-contacts.create');
    }

    public function store(Request $request)
    {
        CompanyContact::create([
            'company_name' => $request->company_name,
            'lot' => $request->lot,
            'responsible_name' => $request->responsible_name,
            'phone' => $request->phone,
            'is_active' => $request->is_active ?? true,
        ]);

        return redirect()->route('company-contacts.index')
            ->with('success', 'အောင်မြင်စွာဖန်တီးပြီးပါပြီ');
    }

    public function edit(CompanyContact $companyContact)
    {
        return view('company-contacts.edit', compact('companyContact'));
    }

    public function update(Request $request, CompanyContact $companyContact)
    {
        $companyContact->update([
            'company_name' => $request->company_name,
            'lot' => $request->lot,
            'responsible_name' => $request->responsible_name,
            'phone' => $request->phone,
            'is_active' => $request->is_active ?? true,
        ]);

        return redirect()->route('company-contacts.index')
            ->with('success', 'အောင်မြင်စွာပြင်ဆင်ပြီးပါပြီ');
    }

    public function destroy(CompanyContact $companyContact)
    {
        $companyContact->delete();

        return redirect()->route('company-contacts.index')
            ->with('success', 'အောင်မြင်စွာဖျက်ပြီးပါပြီ');
    }
}
