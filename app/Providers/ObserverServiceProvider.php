<?php

namespace App\Providers;

use App\Models\Job;
use App\Models\Tag;
use App\Models\Book;
use App\Models\Game;
use App\Models\User;
use App\Models\Course;
use App\Models\Expert;
use App\Models\Studio;
use App\Models\Ticket;
use App\Models\Episode;
use App\Models\Podcast;
use App\Models\Product;
use App\Models\UserBank;
use App\Models\GameGenre;
use App\Models\Discussion;
use App\Models\GameEngine;
use App\Models\Publication;
use App\Models\GamePlatform;
use App\Observers\JobObserver;
use App\Observers\TagObserver;
use App\Observers\BookObserver;
use App\Observers\GameObserver;
use App\Observers\UserObserver;
use App\Observers\CourseObserver;
use App\Observers\ExpertObserver;
use App\Observers\StudioObserver;
use App\Observers\TicketObserver;
use App\Observers\EpisodeObserver;
use App\Observers\PodcastObserver;
use App\Observers\ProductObserver;
use App\Observers\UserBankObserver;
use App\Observers\GameGenreObserver;
use App\Observers\DiscussionObserver;
use App\Observers\GameEngineObserver;
use App\Observers\PublicationObserver;
use App\Observers\GamePlatformObserver;
use Illuminate\Support\ServiceProvider;

class ObserverServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        User::observe(UserObserver::class);
        Course::observe(CourseObserver::class);
        Book::observe(BookObserver::class);
        Ticket::observe(TicketObserver::class);
        Product::observe(ProductObserver::class);
        Discussion::observe(DiscussionObserver::class);
        Episode::observe(EpisodeObserver::class);
        Expert::observe(ExpertObserver::class);
        Game::observe(GameObserver::class);
        GameGenre::observe(GameGenreObserver::class);
        GamePlatform::observe(GamePlatformObserver::class);
        GameEngine::observe(GameEngineObserver::class);
        Job::observe(JobObserver::class);
        Podcast::observe(PodcastObserver::class);
        Tag::observe(TagObserver::class);
        Publication::observe(PublicationObserver::class);
        UserBank::observe(UserBankObserver::class);
        // Studio::observe(StudioObserver::class);
    }
}
