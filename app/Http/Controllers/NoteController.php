<?php

namespace App\Http\Controllers;

use Exception;
use OpenApi\Annotations as OA;
use Illuminate\Http\JsonResponse;
use App\Service\NoteCreateService;
use App\Repository\NoteRepository;
use App\Http\Requests\AddNoteRequest;
use App\Http\Requests\GetNotesRequest;
use Symfony\Component\HttpFoundation\Response;

class NoteController extends Controller
{
    /**
     * @param GetNotesRequest $request
     * @param NoteRepository $repository
     * @return JsonResponse
     *
     * @OA\Get(
     *     path="/api/notes",
     *     tags={"Notes"},
     *     description="Get all users",
     *     @OA\Parameter(
     *          name="limit",
     *          in="query",
     *          @OA\Schema(
     *              type="integer"
     *          )
     *      ),
     *     @OA\Parameter(
     *          name="user_id",
     *          in="query",
     *          @OA\Schema(
     *              type="integer"
     *          )
     *      ),
     *     @OA\Parameter(
     *          name="restaurant_id",
     *          in="query",
     *          @OA\Schema(
     *              type="integer"
     *          )
     *      ),
     *     @OA\Response(response="200", description="Success"),
     * )
     */
    public function index(
        GetNotesRequest $request,
        NoteRepository  $repository
    ): JsonResponse {
        return new JsonResponse(
            $repository->getNotes(
                $request->validated('limit'),
                $request->validated('user_id'),
                $request->validated('restaurant_id'),
            )
        );
    }

    /**
     * @param AddNoteRequest $request
     * @param NoteCreateService $noteCreateService
     * @return JsonResponse
     *
     * @OA\Post(
     *     path="/api/notes",
     *     tags={"Notes"},
     *     description="Add multiple notes to restaurants or users",
     *     @OA\Response(response="200", description="Success"),
     * )
     */
    public function create(
        AddNoteRequest $request,
        NoteCreateService $noteCreateService
    ): JsonResponse {
        $data = $request->all()['notes'];
        try {
            $this->validateNotes($data);
        } catch (Exception $e) {
            return new JsonResponse($e->getMessage(), Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        $notes = $noteCreateService->create($data);

        return new JsonResponse($notes, Response::HTTP_CREATED);
    }

    /**
     * @throws Exception
     */
    private function validateNotes(array $notes): void
    {
        foreach ($notes as $note) {
            if (!$note) {
                throw new Exception('0 notes provided: ' . json_encode($note), 422);
            }

            if (isset($note['user_id']) && isset($note['restaurant_id'])) {
                throw new Exception('Note can belong only to User or Restaurant: ' . json_encode($note), 422);
            }

            if (!isset($note['user_id']) && !isset($note['restaurant_id'])) {
                throw new Exception('Note is not assigned to User nor Restaurant: ' . json_encode($note), 422);
            }
        }
    }


}
