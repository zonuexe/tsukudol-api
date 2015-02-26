<?php
namespace TsukudolAPI\Controller;
use Teto\Routing\Action;
use TsukudolAPI\Response\TemplateResponse;
use TsukudolAPI\Response\SerializedResponse;
use TsukudolAPI\Response\Serializer;
use Tsukudol\Nizicon;

final class TeamController implements ControllerInterface
{
    /** @var \TsukudolAPI\Application */
    private $app;

    public function __construct(\TsukudolAPI\Application $app)
    {
        $this->app = $app;
    }

    public function team(Action $action)
    {
        $members = Nizicon\Member::getList();

        return new SerializedResponse(
            array_map('\TsukudolAPI\View\NiziconMapper::dump', $members),
            new Serializer\PhpJsonSerializer
        );
    }

    public function next_birthday(Action $action)
    {
        $now  = $this->app->now;
        $year = $now->format('Y');

        $nearest = null;
        $first   = null;

        foreach (Nizicon::members() as $member) {
            $this_year = \DateTimeImmutable::createFromFormat(
                \DateTime::W3C,
                $member->birth_day->format("$year-m-d\TH:i:sP"));

            if (!$first || $this_year < $first[0]) {
                $first = [$this_year, $member];
            }

            if ((!$nearest || $this_year < $nearest[0]) && $now <= $this_year) {
                $nearest = [$this_year, $member];
            }
        }

        if (!$nearest) {
            $nearest = [
                \DateTimeImmutable::createFromFormat(
                    \DateTime::W3C,
                    $first[0]->format(($year + 1)."-m-d\TH:i:sP")),
                $first[1],
            ];
        }

        if ($now->format('Ymd') === $nearest[0]->format('Ymd')) {
            $until = [
                'days'    => 0,
                'hours'   => 0,
                'minutes' => 0,
                'seconds' => 0,
            ];
        } else {
            $left = $nearest[0]->diff($now);
            $hour = $left->days * 24 + $left->m;
            $minutes = $hour * 60 + $left->i;
            $seconds = $minutes * 60 + $left->s;
            $until = [
                'days'    => $left->days,
                'hours'   => $hour,
                'minutes' => $minutes,
                'seconds' => $seconds,
            ];
        }

        return new SerializedResponse(
            [
                'until'  => $until,
                'member' => \TsukudolAPI\View\NiziconMapper::dump($nearest[1]),
            ],
            new Serializer\PhpJsonSerializer
        );
    }
}