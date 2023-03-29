<?php

namespace App\Http\Controllers;

use App\Http\Resources\OrderDetailedResource;
use App\Http\Resources\OrderResource;
use App\Http\Resources\WorkShiftDetailedResource;
use App\Http\Resources\WorkShiftResource;
use App\Models\Order;
use App\Models\OrderPosition;
use App\Models\Position;
use App\Models\User;
use App\Models\WorkShift;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class OrderController extends Controller
{
    public function readByWorkShift(WorkShift $work_shift) {
        $user = Auth::user();
        if ($user->role_id === User::ROLES['waiter']) {
            if ($work_shift->users->where('id', '=', $user->id)->count() < 1) {
                return response()->json([
                    'error' => [
                        'code' => 403,
                        'message' => 'Forbidden. You did not accept this order!'
                    ]
                ], 403);
            }
        }
        return response()->json(['data' => new WorkShiftDetailedResource($work_shift)]);
    }

    public function create(Request $request) {
        $user = Auth::user();
        $validator = Validator::make($request->all(), [
            'work_shift_id' => 'required|exists:work_shifts,id',
            'table_id' => 'required|exists:tables,id',
            'number_of_person' => 'integer'
        ]);
        if ($validator->fails()) return $this->getValidationErrorResponse($validator);

        $work_shift = WorkShift::query()->find($request->work_shift_id);

        if (!$request->has('number_of_person')) {
            $request->merge(['number_of_person' => 1]);
        }

        if (!$work_shift->active) {
            return response()->json([
                'error' => [
                    'code' => 403,
                    'message' => 'Forbidden. The shift must be active!'
                ]
            ], 403);
        }
        if ($work_shift->users->where('id', '=', $user->id)->count() < 1) {
            return response()->json([
                'error' => [
                    'code' => 403,
                    'message' => "Forbidden. You don't work this shift!"
                ]
            ], 403);
        }

        $request->merge(['status' => Order::STATUSES['accepted'], 'shift_worker_id' => $user->id]);
        return response()->json([
            'data' => new OrderResource(
                Order::query()->create($request->all())
            )
        ]);
    }

    public function readOne(Order $order) {
        $user = Auth::user();
        if ($user->id !== $order->shift_worker_id) {
            return response()->json([
                'error' => [
                    'code' => 403,
                    'message' => 'Forbidden. You did not accept this order!'
                ]
            ], 403);
        }
        return response()->json([
            'data' => new OrderDetailedResource($order)
        ]);
    }

    public function changeStatus(Order $order, Request $request) {
        $user = Auth::user();
        $validator = Validator::make($request->all(), [
            'status' => sprintf('required|string|in:%s', implode(',', array_values(Order::STATUSES)))
        ]);
        if ($validator->fails()) return $this->getValidationErrorResponse($validator);
        $unresolved_status = [
            'error' => [
                'code' => 403,
                'message' => "Forbidden! Can't change existing order status"
            ]
        ];
        switch ($user->role_id) {
            case User::ROLES['cook']:
                if (!(
                    ($order->status === Order::STATUSES['accepted'] && $request->status === Order::STATUSES['cooking']) ||
                    ($order->status === Order::STATUSES['cooking'] && $request->status === Order::STATUSES['ready'])
                )) {
                    return response()->json($unresolved_status, 403);
                }
                $order->status = $request->status;
                break;
            case User::ROLES['waiter']:
                if ($order->shift_worker_id !== $user->id) {
                    return response()->json([
                        'error' => [
                            'code' => 403,
                            'message' => 'Forbidden! You did not accept this order!'
                        ]
                    ], 403);
                }
                if (!(
                    ($order->status === Order::STATUSES['accepted'] && $request->status === Order::STATUSES['canceled']) ||
                    ($order->status === Order::STATUSES['ready'] && $request->status === Order::STATUSES['paid-up'])
                )) {
                    return response()->json($unresolved_status, 403);
                }
                $order->status = $request->status;
                break;
        }
        $order->save();
        return response()->json([
            'data' => [
                'id' => $order->id,
                'status' => $order->status
            ]
        ]);
    }

    public function addPosition(Order $order, Request $request) {
        $user = Auth::user();
        $validator = Validator::make($request->all(), [
            'menu_id' => 'required|exists:positions,id',
            'count' => 'required|integer|between:1,10',
        ]);
        if ($validator->fails()) return $this->getValidationErrorResponse($validator);
        $position_id = intval($request->menu_id);

        if ($order->status !== Order::STATUSES['accepted']) {
            return response()->json([
                'error' => [
                    'code' => 403,
                    'message' => 'Forbidden! Cannot be added to an order with this status'
                ]
            ], 403);
        }
        if ($order->shift_worker_id !== $user->id) {
            return response()->json([
                'error' => [
                    'code' => 403,
                    'message' => 'Forbidden! You did not accept this order!'
                ]
            ], 403);
        }
        if (!$order->workShift->active) {
            return response()->json([
                'error' => [
                    'code' => 403,
                    'message' => 'You cannot change the order status of a closed shift!'
                ]
            ], 403);
        }

        if (
            $position = OrderPosition::query()->whereHas('position', function ($query) use ($position_id) {
                return $query->where('id', '=', $position_id);
            })->first()
        ) {
            $position->count = $request->count;
            $position->save();
        } else {
            $position = OrderPosition::query()->create([
                'order_id' => $order->id,
                'position_id' => $position_id,
                'count' => $request->count
            ]);
        }

        $order->refresh();
        return response()->json([
            'data' => new OrderDetailedResource($order)
        ]);
    }

    public function removePosition(Order $order, Position $position) {
        $user = Auth::user();
        if ($order->status !== Order::STATUSES['accepted']) {
            return response()->json([
                'error' => [
                    'code' => 403,
                    'message' => 'Forbidden! Cannot be added to an order with this status'
                ]
            ], 403);
        }
        if ($order->shift_worker_id !== $user->id) {
            return response()->json([
                'error' => [
                    'code' => 403,
                    'message' => 'Forbidden! You did not accept this order!'
                ]
            ], 403);
        }
        if (!$order->workShift->active) {
            return response()->json([
                'error' => [
                    'code' => 403,
                    'message' => 'You cannot change the order status of a closed shift!'
                ]
            ], 403);
        }

        OrderPosition::query()
            ->where('order_id', '=', $order->id)
            ->where('position_id', '=', $position->id)
            ->delete();
        $order->refresh();
        return response()->json([
            'data' => new OrderDetailedResource($order)
        ]);
    }

    public function readTaken() {
        $orders = Order::query()->whereIn('status', [Order::STATUSES['accepted'], Order::STATUSES['cooking']])->get();
        return response()->json(['data' => OrderDetailedResource::collection($orders)]);
    }
}
