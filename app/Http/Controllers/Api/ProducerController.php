<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Producer;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Validator;

class ProducerController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): JsonResponse
    {
        $query = Producer::query();

        // Search functionality
        if ($request->has('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('nom_prenom', 'like', "%{$search}%")
                  ->orWhere('code_producteur', 'like', "%{$search}%")
                  ->orWhere('nom_site', 'like', "%{$search}%")
                  ->orWhere('culture', 'like', "%{$search}%");
            });
        }

        // Filter by culture
        if ($request->has('culture')) {
            $query->where('culture', $request->culture);
        }

        // Filter by site
        if ($request->has('nom_site')) {
            $query->where('nom_site', $request->nom_site);
        }

        // Pagination
        $perPage = $request->per_page ?? 20;
        $producers = $query->orderBy('created_at', 'desc')->paginate($perPage);

        return response()->json([
            'success' => true,
            'data' => $producers,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'nom_site' => 'required|string|max:255',
            'nom_prenom' => 'required|string|max:255',
            'code_producteur' => 'required|string|unique:producers,code_producteur|max:50',
            'telephone' => 'nullable|string|max:20',
            'date_integration' => 'nullable|date',
            'superficie' => 'nullable|numeric|min:0',
            'chiffre_affaires' => 'nullable|numeric|min:0',
            'code_unique_parcelle' => 'nullable|string|max:50',
            'culture' => 'nullable|string|max:100',
            'interculture' => 'nullable|string|max:100',
            'nombre_arbres' => 'nullable|integer|min:0',
            'gps_parcelle1' => 'nullable|string|max:100',
            'gps_parcelle2' => 'nullable|string|max:100',
            'gps_parcelle3' => 'nullable|string|max:100',
            'gps_menage' => 'nullable|string|max:100',
            'estimation_recolte' => 'nullable|string|max:100',
            'rendement' => 'nullable|string|max:100',
            'quantite_livree' => 'nullable|numeric|min:0',
            'nom_ci' => 'nullable|string|max:255',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors(),
            ], 422);
        }

        $producer = Producer::create($request->all());

        return response()->json([
            'success' => true,
            'message' => 'Producer created successfully',
            'data' => $producer,
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(int $id): JsonResponse
    {
        $producer = Producer::with('inspections')->find($id);

        if (!$producer) {
            return response()->json([
                'success' => false,
                'message' => 'Producer not found',
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $producer,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, int $id): JsonResponse
    {
        $producer = Producer::find($id);

        if (!$producer) {
            return response()->json([
                'success' => false,
                'message' => 'Producer not found',
            ], 404);
        }

        $validator = Validator::make($request->all(), [
            'nom_site' => 'sometimes|string|max:255',
            'nom_prenom' => 'sometimes|string|max:255',
            'code_producteur' => 'sometimes|string|unique:producers,code_producteur,' . $id . '|max:50',
            'telephone' => 'nullable|string|max:20',
            'date_integration' => 'nullable|date',
            'superficie' => 'nullable|numeric|min:0',
            'chiffre_affaires' => 'nullable|numeric|min:0',
            'code_unique_parcelle' => 'nullable|string|max:50',
            'culture' => 'nullable|string|max:100',
            'interculture' => 'nullable|string|max:100',
            'nombre_arbres' => 'nullable|integer|min:0',
            'gps_parcelle1' => 'nullable|string|max:100',
            'gps_parcelle2' => 'nullable|string|max:100',
            'gps_parcelle3' => 'nullable|string|max:100',
            'gps_menage' => 'nullable|string|max:100',
            'estimation_recolte' => 'nullable|string|max:100',
            'rendement' => 'nullable|string|max:100',
            'quantite_livree' => 'nullable|numeric|min:0',
            'nom_ci' => 'nullable|string|max:255',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors(),
            ], 422);
        }

        $producer->update($request->all());

        return response()->json([
            'success' => true,
            'message' => 'Producer updated successfully',
            'data' => $producer,
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(int $id): JsonResponse
    {
        $producer = Producer::find($id);

        if (!$producer) {
            return response()->json([
                'success' => false,
                'message' => 'Producer not found',
            ], 404);
        }

        $producer->delete();

        return response()->json([
            'success' => true,
            'message' => 'Producer deleted successfully',
        ]);
    }

    /**
     * Get statistics.
     */
    public function statistics(): JsonResponse
    {
        $totalProducers = Producer::count();
        $totalSuperficie = Producer::sum('superficie');
        $totalChiffreAffaires = Producer::sum('chiffre_affaires');
        $totalArbres = Producer::sum('nombre_arbres');

        // Count by culture
        $byCulture = Producer::select('culture')
            ->selectRaw('count(*) as total')
            ->whereNotNull('culture')
            ->groupBy('culture')
            ->get();

        // Count by site
        $bySite = Producer::select('nom_site')
            ->selectRaw('count(*) as total')
            ->groupBy('nom_site')
            ->get();

        // Recent producers
        $recentProducers = Producer::orderBy('created_at', 'desc')->limit(10)->get();

        return response()->json([
            'success' => true,
            'data' => [
                'total_producers' => $totalProducers,
                'total_superficie' => $totalSuperficie,
                'total_chiffre_affaires' => $totalChiffreAffaires,
                'total_arbres' => $totalArbres,
                'by_culture' => $byCulture,
                'by_site' => $bySite,
                'recent_producers' => $recentProducers,
            ],
        ]);
    }

    /**
     * Batch sync producers from mobile (offline sync).
     */
    public function batchSync(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'producers' => 'required|array',
            'producers.*.code_producteur' => 'required|string|max:50',
            'producers.*.nom_site' => 'required|string|max:255',
            'producers.*.nom_prenom' => 'required|string|max:255',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors(),
            ], 422);
        }

        $synced = [];
        $failed = [];

        foreach ($request->producers as $producerData) {
            try {
                // Check if producer already exists
                $existing = Producer::where('code_producteur', $producerData['code_producteur'])->first();

                if ($existing) {
                    // Update existing
                    $existing->update($producerData);
                    $synced[] = $existing->id;
                } else {
                    // Create new
                    $producer = Producer::create($producerData);
                    $synced[] = $producer->id;
                }
            } catch (\Exception $e) {
                $failed[] = $producerData['code_producteur'] ?? 'unknown';
            }
        }

        return response()->json([
            'success' => count($failed) === 0,
            'message' => count($failed) === 0 ? 'All producers synced successfully' : 'Some producers failed to sync',
            'data' => [
                'synced_count' => count($synced),
                'failed_count' => count($failed),
                'synced_ids' => $synced,
                'failed_codes' => $failed,
            ],
        ]);
    }

    /**
     * Validate GPS coordinates.
     */
    public function validateGps(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'gps' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors(),
            ], 422);
        }

        $gps = $request->gps;

        // Parse GPS string (format: "latitude, longitude")
        $parts = array_map('trim', explode(',', $gps));

        if (count($parts) !== 2) {
            return response()->json([
                'success' => false,
                'valid' => false,
                'message' => 'Invalid GPS format. Expected: latitude, longitude',
            ]);
        }

        $lat = floatval($parts[0]);
        $lng = floatval($parts[1]);

        // Validate ranges
        if ($lat < -90 || $lat > 90 || $lng < -180 || $lng > 180) {
            return response()->json([
                'success' => true,
                'valid' => false,
                'message' => 'Coordinates out of valid range',
                'parsed' => ['latitude' => $lat, 'longitude' => $lng],
            ]);
        }

        return response()->json([
            'success' => true,
            'valid' => true,
            'message' => 'Valid GPS coordinates',
            'parsed' => ['latitude' => $lat, 'longitude' => $lng],
        ]);
    }
}
