<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Report;
use Storage;
use Auth;

class ReportController extends Controller
{
	public function add_report(Request $request){

	    $imagePath = null; // Default in case no image is uploaded
	    if ($request->hasFile('image')) {
	        $imagePath = $request->file('image')->store('reports', 'public'); // Save image in 'storage/app/public/reports'
	    }

	    $report = new Report();
	    $report->account_id = $request->account_id;
	    $report->user_id = Auth::id();
	    $report->about = $request->about;
	    $report->image = $imagePath;
	    $report->save();

	    // Redirect or return response
	    return redirect()->back()->with('success', 'Reporte enviado correctamente.');
	}
}