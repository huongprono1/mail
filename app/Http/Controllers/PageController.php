<?php

namespace App\Http\Controllers;

use App\Models\Page;
use Artesaos\SEOTools\Facades\SEOTools;
use Illuminate\Http\Request;

class PageController extends Controller
{
    public function index(Page $page)
    {
        if (! $page->active) {
            abort(404);
        }

        SEOTools::setTitle($page->title);
        //        SEOTools::setDescription($page->getSEODescription());
        //        SEOTools::jsonLd()->addImage($page->getSEOImageUrl());
        //        SEOTools::opengraph()->addImage($page->getSEOImageUrl());

        return view('pages.index', [
            'page' => $page,
        ]);
    }

    public function create() {}

    public function store(Request $request) {}

    public function show(Page $page) {}

    public function edit(Page $page) {}

    public function update(Request $request, Page $page) {}

    public function destroy(Page $page) {}
}
