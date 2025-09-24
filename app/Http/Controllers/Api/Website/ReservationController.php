<?php

namespace App\Http\Controllers\Api\Website;

use App\Http\Controllers\Controller;
use App\Models\Reservation\Reservation;
use App\Http\Resources\Reservation\ReservationShortResource;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class ReservationController extends Controller
{
    /**
     * Display a listing of reservations (paginated).
     */
    public function index(Request $request): JsonResponse
    {
        $query = Reservation::with(['reservable']);

        // Default to latest first
        $query->orderBy('created_at', 'desc');

        $perPage = (int) $request->get('per_page', 15);
        $reservations = $query->paginate($perPage);

        return response()->json([
            'data' => ReservationShortResource::collection($reservations->items()),
            'meta' => [
                'current_page' => $reservations->currentPage(),
                'last_page' => $reservations->lastPage(),
                'per_page' => $reservations->perPage(),
                'total' => $reservations->total(),
                'from' => $reservations->firstItem(),
                'to' => $reservations->lastItem(),
            ],
        ]);
    }

    /**
     * Display the specified reservation by ID.
     */
    public function show(int $id): JsonResponse
    {
        $reservation = Reservation::with(['reservable'])->findOrFail($id);

        return response()->json([
            'data' => new ReservationShortResource($reservation),
        ]);
    }

    /**
     * Get reservations by user ID.
     */
    public function getByUser(Request $request, int $userId): JsonResponse
    {
        $query = Reservation::with(['reservable'])
            ->where('user_id', $userId)
            ->orderBy('created_at', 'desc');

        $perPage = (int) $request->get('per_page', 15);
        $reservations = $query->paginate($perPage);

        return response()->json([
            'data' => ReservationShortResource::collection($reservations->items()),
            'meta' => [
                'current_page' => $reservations->currentPage(),
                'last_page' => $reservations->lastPage(),
                'per_page' => $reservations->perPage(),
                'total' => $reservations->total(),
                'from' => $reservations->firstItem(),
                'to' => $reservations->lastItem(),
            ],
        ]);
    }

    /**
     * Get reservations by email.
     * - If authenticated: return only the authenticated user's reservations (ignore requested email)
     * - If guest: return reservations where reservation.email matches provided email (case-insensitive)
     */
    public function getByEmail(Request $request, string $email): JsonResponse
    {
        $perPage = (int) $request->get('per_page', 15);

        if ($request->user()) {
            // Authenticated users always see their own reservations
            $query = Reservation::with(['reservable'])
                ->where('user_id', $request->user()->id)
                ->orderBy('created_at', 'desc');

            $reservations = $query->paginate($perPage);

            return response()->json([
                'data' => ReservationShortResource::collection($reservations->items()),
                'meta' => [
                    'current_page' => $reservations->currentPage(),
                    'last_page' => $reservations->lastPage(),
                    'per_page' => $reservations->perPage(),
                    'total' => $reservations->total(),
                    'from' => $reservations->firstItem(),
                    'to' => $reservations->lastItem(),
                ],
            ]);
        }

        // Guest lookup by email (exact, case-insensitive)
        $emailDecoded = urldecode($email);
        $query = Reservation::with(['reservable'])
            ->whereRaw('LOWER(email) = ?', [mb_strtolower($emailDecoded)])
            ->orderBy('created_at', 'desc');

        $reservations = $query->paginate($perPage);

        return response()->json([
            'data' => ReservationShortResource::collection($reservations->items()),
            'meta' => [
                'current_page' => $reservations->currentPage(),
                'last_page' => $reservations->lastPage(),
                'per_page' => $reservations->perPage(),
                'total' => $reservations->total(),
                'from' => $reservations->firstItem(),
                'to' => $reservations->lastItem(),
            ],
        ]);
    }
}
