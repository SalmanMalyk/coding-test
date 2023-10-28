<?php

namespace App\Http\Controllers;

use App\Models\Phase;
use App\Models\Task;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class ReportController extends Controller
{
    public function index(Request $request): mixed
    {
        if ($request->wantsJson() || $request->ajax()) {
            $currentDate = now();

            // I can do this with query but Sqlite is not supporting DATE_FORMAT function :(

            $tasks = Task::all();
            $totalTasks = $tasks->count();

            $monthlyTasks = $tasks->whereNotNull('completed_at')->filter(function ($task) use ($currentDate) {
                return Carbon::parse($task->completed_at)->format('Y-m') == $currentDate->format('Y-m');
            })->count();

            $weeklyTasks = $tasks->whereNotNull('completed_at')->filter(function ($task) use ($currentDate) {
                return Carbon::parse($task->completed_at)->format('oW') == $currentDate->format('oW');
            })->count();

            return response()->json([
                'totalTasks' => $totalTasks,
                'monthlyTasks' => $monthlyTasks,
                'weeklyTasks' => $weeklyTasks,
            ]);
        }
        return view('report.index');
    }

    public function trash(Request $request): mixed
    {
        if ($request->wantsJson() || $request->ajax()) {
            $deletedPhases = Phase::query()
                ->onlyTrashed()
                ->select('id', 'name', 'deleted_at')
                ->withCount([
                    'tasks as deleted_tasks' => function ($query) {
                        $query->onlyTrashed();
                    }
                ])
                ->orderBy('deleted_at', 'desc')
                ->get();

            $deleteTasks = Task::query()
                ->onlyTrashed()
                ->with('phase:id,name')
                ->whereHas('phase', function ($query) {
                    $query->whereNull('deleted_at');
                })
                ->select('id', 'name', 'phase_id', 'deleted_at')
                ->orderBy('deleted_at', 'desc')
                ->get();

            return response()->json([
                'deletedPhases' => $deletedPhases,
                'deletedTasks' => $deleteTasks,
            ]);
        }
    }

    public function restore($id, Request $request): JsonResponse
    {
        return DB::transaction(function () use ($id, $request) {
            if ($request->type == 'phase') {
                $phase = Phase::query()->onlyTrashed()->findOrFail($id);
                $phase->tasks()->onlyTrashed()->restore();
                $phase->restore();
                return response()->json([
                    'message' => 'Phase restored successfully',
                ]);
            } else if ($request->type == 'task') {
                $task = Task::query()->onlyTrashed()->findOrFail($id);
                $task->restore();
                return response()->json([
                    'message' => 'Task restored successfully',
                ]);
            }
        });
    }
}
