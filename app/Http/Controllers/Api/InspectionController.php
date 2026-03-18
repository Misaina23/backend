<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Inspection;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Validator;

class InspectionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): JsonResponse
    {
        $query = Inspection::query();

        // Search functionality
        if ($request->has('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('nom_producteur', 'like', "%{$search}%")
                  ->orWhere('code_producteur', 'like', "%{$search}%")
                  ->orWhere('inspecteur', 'like', "%{$search}%");
            });
        }

        // Filter by conformity
        if ($request->has('conformite')) {
            $query->where('conformite', $request->conformite);
        }

        // Filter by date range
        if ($request->has('date_from')) {
            $query->where('date_inspection', '>=', $request->date_from);
        }
        if ($request->has('date_to')) {
            $query->where('date_inspection', '<=', $request->date_to);
        }

        // Filter by inspector
        if ($request->has('inspecteur')) {
            $query->where('inspecteur', 'like', "%{$request->inspecteur}%");
        }

        // Pagination
        $perPage = $request->per_page ?? 20;
        $inspections = $query->with('producer')->orderBy('date_inspection', 'desc')->paginate($perPage);

        return response()->json([
            'success' => true,
            'data' => $inspections,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'code_producteur' => 'required|string|max:50',
            'nom_producteur' => 'required|string|max:255',
            'code_unique_parcelle' => 'nullable|string|max:50',
            'date_inspection' => 'required|date',
            'observations' => 'nullable|string',
            'conformite' => 'nullable|in:Conforme,Non conforme,Partiel',
            'actions_correctives' => 'nullable|string',
            'gps_inspection' => 'nullable|string|max:100',
            'inspecteur' => 'nullable|string|max:255',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors(),
            ], 422);
        }

        $inspection = Inspection::create($request->all());

        return response()->json([
            'success' => true,
            'message' => 'Inspection created successfully',
            'data' => $inspection,
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(int $id): JsonResponse
    {
        $inspection = Inspection::with('producer')->find($id);

        if (!$inspection) {
            return response()->json([
                'success' => false,
                'message' => 'Inspection not found',
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $inspection,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, int $id): JsonResponse
    {
        $inspection = Inspection::find($id);

        if (!$inspection) {
            return response()->json([
                'success' => false,
                'message' => 'Inspection not found',
            ], 404);
        }

        $validator = Validator::make($request->all(), [
            'code_producteur' => 'sometimes|string|max:50',
            'nom_producteur' => 'sometimes|string|max:255',
            'code_unique_parcelle' => 'nullable|string|max:50',
            'date_inspection' => 'sometimes|date',
            'observations' => 'nullable|string',
            'conformite' => 'nullable|in:Conforme,Non conforme,Partiel',
            'actions_correctives' => 'nullable|string',
            'gps_inspection' => 'nullable|string|max:100',
            'inspecteur' => 'nullable|string|max:255',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors(),
            ], 422);
        }

        $inspection->update($request->all());

        return response()->json([
            'success' => true,
            'message' => 'Inspection updated successfully',
            'data' => $inspection,
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(int $id): JsonResponse
    {
        $inspection = Inspection::find($id);

        if (!$inspection) {
            return response()->json([
                'success' => false,
                'message' => 'Inspection not found',
            ], 404);
        }

        $inspection->delete();

        return response()->json([
            'success' => true,
            'message' => 'Inspection deleted successfully',
        ]);
    }

    /**
     * Get statistics.
     */
    public function statistics(): JsonResponse
    {
        $totalInspections = Inspection::count();

        // Count by conformity
        $byConformity = Inspection::select('conformite')
            ->selectRaw('count(*) as total')
            ->whereNotNull('conformite')
            ->groupBy('conformite')
            ->get();

        // Recent inspections
        $recentInspections = Inspection::with('producer')
            ->orderBy('date_inspection', 'desc')
            ->limit(10)
            ->get();

        // Inspections this month
        $thisMonth = Inspection::whereMonth('date_inspection', now()->month)
            ->whereYear('date_inspection', now()->year)
            ->count();

        // Inspections by inspector
        $byInspector = Inspection::select('inspecteur')
            ->selectRaw('count(*) as total')
            ->whereNotNull('inspecteur')
            ->groupBy('inspecteur')
            ->get();

        return response()->json([
            'success' => true,
            'data' => [
                'total_inspections' => $totalInspections,
                'this_month' => $thisMonth,
                'by_conformity' => $byConformity,
                'by_inspector' => $byInspector,
                'recent_inspections' => $recentInspections,
            ],
        ]);
    }

    /**
     * Get inspections by producer code.
     */
    public function byProducer(string $codeProducteur): JsonResponse
    {
        $inspections = Inspection::where('code_producteur', $codeProducteur)
            ->orderBy('date_inspection', 'desc')
            ->get();

        return response()->json([
            'success' => true,
            'data' => $inspections,
        ]);
    }
}
