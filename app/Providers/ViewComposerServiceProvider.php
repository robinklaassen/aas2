<?php

declare(strict_types=1);

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class ViewComposerServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     */
    public function boot()
    {
        // Composer for the 'show participant', includes income table
        view()->composer('participants.show', function ($view) {
            $income = [
                0 => 'Meer dan € 3400',
                1 => 'Tussen € 2200 en € 3400',
                2 => 'Tussen € 1300 en € 2200',
                3 => 'Minder dan € 1300',
            ];
            $view->with('income', $income);
        });

        // Composer for the create/edit event form (includes possible locations and 'streeftal')
        view()->composer('events.form', function ($view) {
            $locations = \App\Models\Location::all();
            $loc_list = [];
            foreach ($locations as $location) {
                $loc_list[$location->id] = $location->plaats . ' (' . $location->naam . ')';
            }
            asort($loc_list);
            $ops = [];
            for ($i = 5; $i <= 8; $i++) {
                $ops[$i] = $i . ' / ' . ($i - 1) * 3;
            }
            $view->with('locations', $loc_list)->with('streeftal_options', $ops);
        });

        // Composer for the create/edit action form (includes possible members)
        view()->composer('actions.form', function ($view) {
            $members = \App\Models\Member::orderBy('voornaam')->get();
            $member_list = [];
            foreach ($members as $member) {
                $member_list[$member->id] = $member->voornaam . ' ' . $member->tussenvoegsel . ' ' . $member->achternaam;
            }
            $view->with('members', $member_list);
        });

        // Composer for the 'send member on event' form (includes all events sorted antichronologically)
        view()->composer('members.onEvent', function ($view) {
            $events = \App\Models\Event::orderBy('datum_start', 'desc')->get();
            $event_options = [];
            foreach ($events as $event) {
                $event_options[$event->id] = $event->naam . ' (' . $event->code . ')';
            }
            $view->with('event_options', $event_options);
        });

        // Composer for the 'send participant on event' form (includes camps sorted antichronologically)
        view()->composer('participants.onEvent', function ($view) {
            $events = \App\Models\Event::where('type', 'kamp')->orderBy('datum_start', 'desc')->get();
            $event_options = [];
            foreach ($events as $event) {
                $event_options[$event->id] = $event->naam . ' (' . $event->code . ')';
            }
            $view->with('event_options', $event_options);
        });

        // Composer for the 'add course' form (includes all courses sorted alphabetically)
        view()->composer('members.addCourse', function ($view) {
            $course_options = \App\Models\Course::orderBy('naam')->pluck('naam', 'id')->toArray();
            $view->with('course_options', $course_options);
        });

        // Composer for the 'participants.onEvent' form (includes all courses sorted alphabetically)
        view()->composer('participants.onEvent', function ($view) {
            $course_options = \App\Models\Course::orderBy('naam')->pluck('naam', 'id')->toArray();
            $course_options = [
                0 => '-geen vak-',
            ] + $course_options;
            $view->with('course_options', $course_options);
        });

        // Composer for the 'participants.editEvent' form (includes all courses sorted alphabetically)
        view()->composer('participants.editEvent', function ($view) {
            $course_options = \App\Models\Course::orderBy('naam')->pluck('naam', 'id')->toArray();
            $course_options = [
                0 => '-geen vak-',
            ] + $course_options;
            $view->with('course_options', $course_options);
        });

        // Composer for the 'events.editParticipant' form (includes all courses sorted alphabetically)
        view()->composer('events.editParticipant', function ($view) {
            $course_options = \App\Models\Course::orderBy('naam')->pluck('naam', 'id')->toArray();
            $course_options = [
                0 => '-geen vak-',
            ] + $course_options;
            $view->with('course_options', $course_options);
        });

        // Composer for the 'member show', includes rank borders
        view()->composer('members.show', function ($view) {
            $ranks = [0, 3, 10, 20, 35, 50, 70, 100];
            $view->with('ranks', $ranks);
        });
    }

    /**
     * Register the application services.
     */
    public function register()
    {
        //
    }
}
