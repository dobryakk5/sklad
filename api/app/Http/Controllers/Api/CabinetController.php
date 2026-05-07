<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\Repositories\Bitrix\BitrixCabinetRepository;
use App\Services\Cabinet\BitrixCabinetAuthClient;
use App\Services\Cabinet\BitrixCabinetException;

final class CabinetController extends ApiController
{
    public function __construct(
        private readonly BitrixCabinetRepository $repository,
        private readonly BitrixCabinetAuthClient $authClient,
    ) {}

    public function login(Request $request): JsonResponse
    {
        $login = trim((string) $request->input('login', ''));
        $password = (string) $request->input('password', '');

        if ($login === '' || $password === '') {
            return $this->errorResponse('INVALID_CREDENTIALS', 401);
        }

        try {
            $user = $this->authClient->login($login, $password);
        } catch (BitrixCabinetException $e) {
            return $this->errorResponse($e->getErrorCode(), $e->getHttpStatus());
        }

        $request->session()->put('cabinet_user_id', $user['id']);
        $request->session()->put('cabinet_user', $user);
        $request->session()->regenerate();

        return response()->json([
            'user' => $user,
        ]);
    }

    public function logout(Request $request): JsonResponse
    {
        $request->session()->forget([
            'cabinet_user_id',
            'cabinet_user',
        ]);
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return response()->json([]);
    }

    public function me(Request $request): JsonResponse
    {
        $userId = (int) $request->session()->get('cabinet_user_id');
        $user = $request->session()->get('cabinet_user')
            ?? $this->repository->findUserById($userId);

        if (! is_array($user)) {
            return response()->json([
                'error' => [
                    'code' => 'UNAUTHENTICATED',
                ],
            ], 401);
        }

        $request->session()->put('cabinet_user', $user);

        return response()->json($user);
    }

    public function contracts(Request $request): JsonResponse
    {
        $userId = (int) $request->session()->get('cabinet_user_id');

        return response()->json([
            'data' => $this->repository->getContracts($userId),
        ]);
    }

    public function invoices(Request $request): JsonResponse
    {
        $userId = (int) $request->session()->get('cabinet_user_id');

        return response()->json([
            'data' => $this->repository->getInvoices($userId),
        ]);
    }

    public function invoice(Request $request, int $id): JsonResponse
    {
        $userId = (int) $request->session()->get('cabinet_user_id');
        $invoice = $this->repository->getInvoiceById($id);

        if ($invoice === null) {
            return $this->errorResponse('INVOICE_NOT_FOUND', 404);
        }

        if (($invoice['owner_user_id'] ?? 0) !== $userId) {
            return $this->errorResponse('FORBIDDEN', 403);
        }

        unset($invoice['owner_user_id']);

        return response()->json($invoice);
    }

    public function balance(Request $request): JsonResponse
    {
        $userId = (int) $request->session()->get('cabinet_user_id');

        return response()->json(
            $this->repository->getBalance($userId)
        );
    }

    public function paymentMethod(Request $request): JsonResponse
    {
        $userId = (int) $request->session()->get('cabinet_user_id');

        return response()->json([
            'data' => $this->repository->getPaymentMethod($userId),
        ]);
    }

    public function createRequest(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'type' => ['required', 'string', 'max:64'],
            'fields' => ['required', 'array'],
            'fields.*' => ['nullable', 'string', 'max:4000'],
        ]);

        $userId = (int) $request->session()->get('cabinet_user_id');
        $sessionUser = $request->session()->get('cabinet_user', []);
        $fields = $validated['fields'];

        if (is_array($sessionUser)) {
            $defaults = [
                'name' => (string) ($sessionUser['name'] ?? ''),
                'email' => (string) ($sessionUser['email'] ?? ''),
                'phone' => (string) ($sessionUser['phone'] ?? ''),
            ];

            foreach ($defaults as $key => $value) {
                if (($fields[$key] ?? '') === '') {
                    $fields[$key] = $value;
                }
            }
        }

        try {
            $result = $this->authClient->submitRequest(
                $userId,
                $validated['type'],
                $fields,
            );
        } catch (BitrixCabinetException $e) {
            return $this->errorResponse($e->getErrorCode(), $e->getHttpStatus());
        }

        return response()->json($result, 201);
    }

    public function notImplemented(Request $request): JsonResponse
    {
        return $this->errorResponse(
            'NOT_IMPLEMENTED',
            501,
            'Cabinet endpoint is not implemented yet.'
        );
    }

    private function errorResponse(
        string $code,
        int $status,
        ?string $message = null,
    ): JsonResponse {
        $payload = ['code' => $code];

        if ($message !== null) {
            $payload['message'] = $message;
        }

        return response()->json([
            'error' => $payload,
        ], $status);
    }
}
