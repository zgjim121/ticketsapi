<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Filters\V1\TicketFilter;
use App\Http\Requests\Api\V1\ReplaceTicketRequest;
use App\Http\Requests\Api\V1\StoreTicketRequest;
use App\Http\Requests\Api\V1\UpdateTicketRequest;
use App\Http\Resources\V1\TicketResource;
use App\Models\Ticket;
use App\Models\User;
use App\Policies\V1\TicketPolicy;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Gate;

class AuthorTicketsController extends ApiController
{
    protected $policyClass = TicketPolicy::class;

    public function index($author_id, TicketFilter $filters)
    {
        return TicketResource::collection(
            Ticket::where('user_id', $author_id)
                ->filter($filters)
                ->paginate());
    }

    public function store(StoreTicketRequest $request, $author_id)
    {
        if (Gate::authorize('store', Ticket::class)) {
            return new TicketResource(Ticket::create($request->mappedAttributes([
                'author' => 'user_id'
            ])));
        }
        return $this->error('You are not authorized to create that resource', 401);
    }

    public function replace(ReplaceTicketRequest $request, $author_id, $ticket_id)
    {
        // PATCH
        try {
            $ticket = Ticket::where('id', $ticket_id)
                ->where('user_id', $author_id)
                ->firstOrFail();

            if (Gate::authorize('replace', $ticket)) {
                $ticket->update($request->mappedAttributes());
                return new TicketResource($ticket);
            }
            return $this->error('You are not authorized to create that resource', 401);
        } catch (ModelNotFoundException) {
            return $this->error('Ticket cannot be found.', 404);
        }
    }

    public function update(UpdateTicketRequest $request, $author_id, $ticket_id)
    {
        // PUT
        try {
            $ticket = Ticket::where('id', $ticket_id)
                ->where('user_id', $author_id)
                ->firstOrFail();

            if (Gate::authorize('update', $ticket)) {
                $ticket->update($request->mappedAttributes());
                return new TicketResource($ticket);
            }
            return $this->error('You are not authorized to create that resource', 401);
        } catch (ModelNotFoundException) {
            return $this->error('Ticket cannot be found.', 404);
        }
    }

    public function destroy($author_id, $ticket_id)
    {
        try {
            $ticket = Ticket::where('id', $ticket_id)
                ->where('user_id', $author_id)
                ->firstOrFail();

            if (Gate::authorize('delete', $ticket)) {
                $ticket->delete();
                return $this->ok('Ticket Successfully Deleted');
            }
            return $this->error('You are not authorized to delete that resource', 401);
        } catch (ModelNotFoundException) {
            return $this->error('Ticket Cannot Be Found', 404);
        }
    }
}
