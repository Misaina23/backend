<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Producer;
use App\Models\Inspection;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    /**
     * Display the main dashboard.
     */
    public function index()
    {
        $totalProducers = Producer::count();
        $totalInspections = Inspection::count();
        $totalSuperficie = Producer::sum('superficie');
        $totalChiffreAffaires = Producer::sum('chiffre_affaires');

        // Recent producers
        $recentProducers = Producer::orderBy('created_at', 'desc')->limit(10)->get();

        // Recent inspections
        $recentInspections = Inspection::with('producer')->orderBy('date_inspection', 'desc')->limit(10)->get();

        // Conformity stats
        $conformityStats = Inspection::select('conformite')
            ->selectRaw('count(*) as total')
            ->whereNotNull('conformite')
            ->groupBy('conformite')
            ->get();

        return view('dashboard', compact(
            'totalProducers',
            'totalInspections',
            'totalSuperficie',
            'totalChiffreAffaires',
            'recentProducers',
            'recentInspections',
            'conformityStats'
        ));
    }

    /**
     * Display producers list.
     */
    public function producers(Request $request)
    {
        $query = Producer::query();

        if ($request->has('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('nom_prenom', 'like', "%{$search}%")
                  ->orWhere('code_producteur', 'like', "%{$search}%")
                  ->orWhere('nom_site', 'like', "%{$search}%");
            });
        }

        $producers = $query->orderBy('created_at', 'desc')->paginate(20);

        return view('producers', compact('producers'));
    }

    /**
     * Display producer details.
     */
    public function producerDetail(int $id)
    {
        $producer = Producer::with('inspections')->findOrFail($id);
        return view('producer-detail', compact('producer'));
    }

    /**
     * Display inspections list.
     */
    public function inspections(Request $request)
    {
        $query = Inspection::with('producer');

        if ($request->has('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('nom_producteur', 'like', "%{$search}%")
                  ->orWhere('code_producteur', 'like', "%{$search}%");
            });
        }

        if ($request->has('conformite')) {
            $query->where('conformite', $request->conformite);
        }

        $inspections = $query->orderBy('date_inspection', 'desc')->paginate(20);

        return view('inspections', compact('inspections'));
    }

    /**
     * Display inspection details.
     */
    public function inspectionDetail(int $id)
    {
        $inspection = Inspection::with('producer')->findOrFail($id);
        return view('inspection-detail', compact('inspection'));
    }
}
