<?php
namespace TsukudolAPI\Controller;
use Teto\Routing\Action;
use Baguette\Response\RedirectResponse;
use Baguette\Response\SerializedResponse;
use Baguette\Serializer;
use Tsukudol\Nizicon;
use TsukudolAPI\View\NiziconMapper;

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
                'member' => NiziconMapper::dump($nearest[1]),
            ],
            new Serializer\PhpJsonSerializer
        );
    }

    public function team_member(Action $action)
    {
        $team = $action->param['team'];
        $name = $action->param['member'];
        try {
            $member  = Nizicon\Member::find($name);
            $twitter = '@' . $member->twitter->screen_name;
            $data    = NiziconMapper::dump($member);
        } catch (\OutOfBoundsException $e) {
            $member  = null;
            $twitter = null;
            $data    = [];
        }

        if ($member && $twitter !== $name) {
            return new RedirectResponse("/$team/$twitter", [], 301);
        }

        $serializer = (new Serializer\PhpJsonSerializer)->setEmptyAsObject();

        return new SerializedResponse($data, $serializer, 404);
    }
}
