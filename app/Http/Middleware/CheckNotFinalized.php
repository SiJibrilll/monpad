<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Models\Concerns\Finalizable;
use Illuminate\Database\Eloquent\Model;

class CheckNotFinalized
{
    public function handle(Request $request, Closure $next)
    {
        // Skip read-only requests entirely
        if (in_array($request->method(), ['GET', 'HEAD', 'OPTIONS'])) {
            return $next($request);
        }
        
        // 1️⃣ Check all route-bound models
        foreach ($request->route()->parameters() as $param) {
            if ($this->isModelFinalized($param)) {
                return $this->denyResponse();
            }
        }

        // 2️⃣ Check for foreign keys in POST/PUT body
        foreach ($request->all() as $key => $value) {
            if (!is_numeric($value) || !str_ends_with($key, '_id')) {
                continue; // skip non-foreign key fields
            }

            $modelName = 'App\\Models\\' . ucfirst(str_replace('_id', '', $key));

            if (!class_exists($modelName)) {
                continue; // skip if model doesn't exist
            }

            $parent = $modelName::find($value);

            if ($parent && $this->isModelFinalized($parent)) {
                return $this->denyResponse();
            }
        }

        return $next($request);
    }

    protected function isModelFinalized($model): bool
    {
        if ($model instanceof Model && in_array(Finalizable::class, class_uses_recursive($model))) {
            return $model->isFinalized();
        }
        return false;
    }

    protected function denyResponse()
    {
        return response()->json(['message' => 'This record is finalized.'], 403);
    }
}
