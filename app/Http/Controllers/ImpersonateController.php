<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Lab404\Impersonate\Services\ImpersonateManager;

class ImpersonateController extends Controller
{
    /** @var ImpersonateManager */
    protected $manager;

    /**
     * ImpersonateController constructor.
     */
    public function __construct()
    {
        $this->manager = app()->make(ImpersonateManager::class);

        $guard = $this->manager->getDefaultSessionGuard();
        $this->middleware('auth:' . $guard)->only('take');
    }

    /**
     * @param int         $id
     * @param string|null $guardName
     * @return  RedirectResponse
     * @throws  \Exception
     */
    public function take(Request $request, $id, $guardName = null, $redirectTo = null)
    {
        $guardName = $guardName ?? $this->manager->getDefaultSessionGuard();

        // Cannot impersonate yourself
        if ($id == $request->user()->getAuthIdentifier() && ($this->manager->getCurrentAuthGuardName() == $guardName)) {
            abort(403);
        }

        // Cannot impersonate again if you're already impersonate a user
        if ($this->manager->isImpersonating()) {
            abort(403);
        }

        if (!$request->user()->canImpersonate()) {
            abort(403);
        }

        $userToImpersonate = $this->manager->findUserById($id, $guardName);

        if ($userToImpersonate->canBeImpersonated()) {
            if ($this->manager->take($request->user(), $userToImpersonate, $guardName)) {
                if(!blank($redirectTo)) {
                    return redirect()->route($redirectTo);
                }
            }
        }

        return redirect()->back();
    }

    /**
     * @return RedirectResponse
     */
    public function leave($redirectTo)
    {
        if (!$this->manager->isImpersonating()) {
            abort(403);
        }

        $this->manager->leave();

        if(!blank($redirectTo)) {
            return redirect()->route($redirectTo);
        }

        return redirect()->back();
    }
}
