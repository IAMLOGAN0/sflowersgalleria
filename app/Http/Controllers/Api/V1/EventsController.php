<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Blog;
use App\Models\BlogCategory;
use Illuminate\Http\Request;

class EventsController extends Controller
{
    /**
     * GET /v1/events
     * Fetch all events
     */
    public function getAllEvents()
    {
        $events = Blog::with(['category', 'user'])
            ->latest()
            ->get();

        return response()->json([
            'status' => true,
            'data' => $events
        ]);
    }

    /**
     * GET /v1/events/{categorySlug}
     * Fetch events by category slug
     */
    public function getEventsByCategorySlug($categorySlug)
    {
        $category = BlogCategory::where('slug', $categorySlug)->first();

        if (!$category) {
            return response()->json([
                'status' => false,
                'message' => 'Category not found'
            ], 404);
        }

        $events = Blog::with(['category', 'user'])
            ->where('category_id', $category->id)
            ->latest()
            ->get();

        return response()->json([
            'status' => true,
            'category' => $category,
            'data' => $events
        ]);
    }

    /**
     * GET /v1/events/{categorySlug}/{eventSlug}
     * Fetch event details
     */
    public function getEventDetails($categorySlug, $eventSlug)
    {
        $event = Blog::with(['category', 'user'])
            ->whereHas('category', function ($q) use ($categorySlug) {
                $q->where('slug', $categorySlug);
            })
            ->where('slug', $eventSlug)
            ->first();

        if (!$event) {
            return response()->json([
                'status' => false,
                'message' => 'Event not found'
            ], 404);
        }

        return response()->json([
            'status' => true,
            'data' => $event
        ]);
    }

    /**
     * GET /v1/events/{categorySlug}/{eventSlug}/comments
     * Fetch comments for an event
     */
    public function getCommentsByEventSlug($categorySlug, $eventSlug)
    {
        $event = Blog::with('comments.user') // Ensure BlogComment has user()
            ->whereHas('category', function ($q) use ($categorySlug) {
                $q->where('slug', $categorySlug);
            })
            ->where('slug', $eventSlug)
            ->first();

        if (!$event) {
            return response()->json([
                'status' => false,
                'message' => 'Event not found'
            ], 404);
        }

        return response()->json([
            'status' => true,
            'event' => $event->title,
            'comments' => $event->comments
        ]);
    }
}
