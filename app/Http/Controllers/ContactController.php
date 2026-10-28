<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Actions\Contact\SubmitContactRequest as SubmitContactAction;
use App\Enums\Locale;
use App\Http\Requests\Contact\SubmitContactRequest as SubmitContactFormRequest;
use App\Queries\ContactPageQuery;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

final class ContactController extends Controller
{
    public function show(ContactPageQuery $query): View
    {
        return view('contact.show', $query->forLocale(app()->getLocale()));
    }

    public function store(SubmitContactFormRequest $request, SubmitContactAction $action): RedirectResponse
    {
        $locale = app()->getLocale();
        $payload = $request->validated();
        $payload['locale'] = $locale;

        $action->handle($payload, $request);

        return redirect()
            ->route($locale === Locale::English->value ? 'en.contact' : 'fr.contact')
            ->with('contact_sent', true);
    }
}
