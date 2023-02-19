<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Inertia\Inertia;
use OwenIt\Auditing\Models\Audit;

class AuditController extends Controller
{
    public function index()
    {
        $audits = Audit::query()
            ->with('auditable')
            ->get()
            ->transform(function ($item) {
                $item->modified = $item->getModified();
                $item->metadata = $item->getMetadata();

                return $item;
            });

        return Inertia::render('Audits/Index', [
            'audits' => $audits,
        ]);
    }

    public function filter()
    {
        $audits = Audit::query()
            ->with('auditable')
            ->where('auditable_type', 'App\\Models\\Site')
            ->whereIn('event', ['updated', 'deleted', 'restored'])
            ->whereDate('created_at', now()->subDay()->format('Y-m-d'))
            ->whereHas('auditable')
            ->get()
            ->filter(function ($item) {

                if($item->event == 'updated') {
                    return Arr::hasAny(
                        $item->getModified(), 
                        ['sale', 'gambling', 'cdb', 'cripto', 'sponsor', 'status']
                    );
                }

                return true;
            })
            ->transform(function ($item) {
                $item->modified = $item->getModified();
                $item->metadata = $item->getMetadata();

                return $item;
            });

        return Inertia::render('Audits/Index', [
            'audits' => $audits,
        ]);
    }

    public function show(Audit $audit)
    {
        $audit->load('auditable');
        
        $audit->modified = $audit->getModified();
        $audit->metadata = $audit->getMetadata();

        return Inertia::render('Audits/Show', [
            'audit' => $audit,
        ]);
    }
}
