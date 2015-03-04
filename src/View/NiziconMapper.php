<?php
namespace TsukudolAPI\View;
use Tsukudol\Nizicon;

final class NiziconMapper
{
    private static $LOCALES = ['ja-Jpan', 'ja-Hira', 'en-Latn'];

    /**
     * @param  \Tsukudol\Nizicon\Member $member
     * @return array
     * */
    public static function dump(Nizicon\Member $member)
    {
        return [
            'name' => [
                'ja-Jpan' => $member->names->getNameIn('ja-Jpan'),
                'ja-Hira' => $member->names->getNameIn('ja-Hira'),
                'en-Latn' => $member->names->getNameIn('en-Latn'),
            ],
            'nickName' => [
                'ja-Jpan' => $member->nick_names->getNameIn('ja-Jpan'),
                'en-Latn' => $member->nick_names->getNameIn('en-Latn'),
            ],
            'birthDay' => $member->birth_day->format(\DateTime::W3C),
            'blogUrl'  => $member->blog_url,
            'headShotUrls' =>$member->head_shot_urls,
            'twitter'  => $member->twitter->getUrlAsShort(),
            'pixiv'    => ($member->pixiv) ? $member->pixiv->getUrlAsShort() : null,
        ];
    }
}
