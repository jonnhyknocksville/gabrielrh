<?php

namespace App\EventSubscriber;

use App\Repository\MissionRepository;
use CalendarBundle\CalendarEvents;
use CalendarBundle\Entity\Event;
use CalendarBundle\Event\CalendarEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;


class CalendarSubscriber implements EventSubscriberInterface
{

    private $user;
    public function __construct(
        private MissionRepository $bookingRepository,
        private UrlGeneratorInterface $router,
        TokenStorageInterface $tokenStorage    )
    {

        $this->user = $tokenStorage->getToken()->getUser();

    }

    public static function getSubscribedEvents()
    {
        return [
            CalendarEvents::SET_DATA => 'onCalendarSetData',
        ];
    }

    public function onCalendarSetData(CalendarEvent $calendar, $user)
    {

        $userId = $this->user->getUserIdentifier();
        $start = $calendar->getStart();
        $end = $calendar->getEnd();
        $filters = $calendar->getFilters();

        // Modify the query to fit to your entity and needs
        // Change booking.beginAt by your start date property
        $missions = $this->bookingRepository
            ->createQueryBuilder('mission')
            ->where('mission.beginAt BETWEEN :start and :end OR mission.endAt BETWEEN :start and :end and mission.user = ' . $userId)
            ->setParameter('start', $start->format('Y-m-d H:i:s'))
            ->setParameter('end', $end->format('Y-m-d H:i:s'))
            ->getQuery()
            ->getResult()
        ;

        foreach ($missions as $mission) {
            // this create the events with your data (here mission data) to fill calendar
            $missionEvent = new Event(
                $mission->getClient()->getName() . " - " . $mission->getCourse()->getTitle(),
                $mission->getBeginAt(),
                $mission->getEndAt() // If the end date is null or not defined, a all day event is created.
            );



            /*
             * Add custom options to events
             *
             * For more information see: https://fullcalendar.io/docs/event-object
             * and: https://github.com/fullcalendar/fullcalendar/blob/master/src/core/options.ts
             */

            $missionEvent->setOptions([
                'backgroundColor' => 'red',
                'borderColor' => 'red',
            ]);
            $missionEvent->addOption(
                'url',
                $this->router->generate('app_teacher_calendar', [
                    'id' => $mission->getId(),
                ])
            );

            // finally, add the event to the CalendarEvent to fill the calendar
            $calendar->addEvent($missionEvent);
        }
    }
}

?>