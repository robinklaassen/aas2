<?php

declare(strict_types=1);

namespace Updater\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Updater\Services\Updater\UpdaterServiceInterface;

class UpdateController extends Controller
{
    private UpdaterServiceInterface $updaterService;

    public function __construct(UpdaterServiceInterface $updaterService)
    {
        $this->updaterService = $updaterService;
    }

    public function update(Request $request)
    {
        $start = time();
        $secret = $request->get('secret');

        if ($secret !== config('updater.secret')) {
            abort(401);
        }

        $this->updaterService->update();

        return response()->json([
            'took' => time() - $start,
            'output' => $this->updaterService->getUpdateOutput(),
            'current' => $this->updaterService->currentVersion(),
        ]);
    }

    public function version()
    {
        return response()->json([
            'current' => $this->updaterService->currentVersion(),
        ]);
    }
}
