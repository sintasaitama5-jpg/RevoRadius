<?php

namespace App\Http\Controllers;

use App\Domains\Router\Models\Router;
use App\Domains\Router\Services\RouterService;
use Illuminate\Http\Request;
use Inertia\Inertia;

class RouterController extends Controller
{
    private $routerService;

    public function __construct(RouterService $routerService)
    {
        $this->routerService = $routerService;
    }

    public function index()
    {
        $routers = Router::orderBy('id', 'desc')->get();
        return Inertia::render('Routers/Index', [
            'routers' => $routers
        ]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'ip_address' => ['required', 'ip', 'unique:routers,ip_address'],
            'api_port' => ['required', 'integer', 'min:1', 'max:65535'],
            'os_version' => ['required', 'in:v6,v7'],
            'username' => ['required', 'string'],
            'password' => ['required', 'string'],
        ]);

        $this->routerService->createRouter($data);

        return redirect()->back()->with('success', 'Router berhasil ditambahkan!');
    }

    public function destroy(Router $router)
    {
        $this->routerService->deleteRouter($router);
        return redirect()->back()->with('success', 'Router berhasil dihapus!');
    }

    public function testConnection(Router $router)
    {
        $result = $this->routerService->testConnection($router);

        if ($result['success']) {
            return redirect()->back()->with('success', $result['message']);
        }

        return redirect()->back()->withErrors(['connection' => $result['message']]);
    }
}
