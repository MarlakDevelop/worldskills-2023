<?php

namespace App\Http\Controllers;

use App\Http\Resources\WorkShiftResource;
use App\Models\User;
use App\Models\WorkShift;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class WorkShiftController extends Controller
{
    public function create(Request $request) {
        $validator = Validator::make($request->all(), [
            'start' => 'required|date|date_format:Y-m-d H:i|after:now',
            'end' => 'required|date|date_format:Y-m-d H:i|after:start_date'
        ]);
        $request->merge(['active' => false]);
        $work_shift = WorkShift::query()->create($request->all());
        return response()->json([
            'id' => $work_shift->id,
            'start' => $work_shift->start->format('Y-m-d H:i'),
            'end' => $work_shift->end->format('Y-m-d H:i')
        ]);
    }

    public function open(WorkShift $work_shift) {
        $active_shifts_count = WorkShift::query()->where('active', true)->count();
        if ($active_shifts_count > 0) {
            return response()->json([
                'error' => [
                    'code' => 403,
                    'message' => 'Forbidden. There are open shifts!'
                ]
            ], 403);
        }
        $work_shift->active = true;
        $work_shift->save();
        $work_shift->users()->update(['status' => User::STATUSES['working']]);
        return response()->json(['data' => new WorkShiftResource($work_shift)]);
    }

    public function close(WorkShift $work_shift) {
        if (!$work_shift->active) {
            return response()->json([
                'error' => [
                    'code' => 403,
                    'message' => 'Forbidden. The shift is already closed!'
                ]
            ], 403);
        }
        $work_shift->active = false;
        $work_shift->save();
        $work_shift->users()->update(['status' => User::STATUSES['rest']]);
        return response()->json(['data' => new WorkShiftResource($work_shift)]);
    }

    public function addUser(WorkShift $work_shift, Request $request) {
        $validator = Validator::make($request->all(), [
            'user_id' => [
                'required',
                Rule::exists('users', 'id')->where(function ($query) {
                    return $query->where('status', '!=', User::STATUSES['dismissal']);
                })
            ],
        ]);
        if ($validator->fails()) return $this->getValidationErrorResponse($validator);
        $user = User::query()->findOrFail($request->user_id);
        if ($work_shift->users()->where('id', '=', $user->id)->count() > 0) {
            return response()->json([
                'error' => [
                    'code' => 403,
                    'message' => 'Forbidden. The worker is already on shift!'
                ]
            ], 403);
        }
        $user->status = User::STATUSES['added'];
        $user->save();
        $work_shift->users()->attach([intval($user->id)]);
        return response()->json([
            'data' => [
                'id_user' => $user->id,
                'status' => $user->status
            ]
        ]);
    }
}
