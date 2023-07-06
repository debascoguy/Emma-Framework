<?php

namespace Emma\App\View\Form;

use Emma\App\View\Html\Tag;

/**
 * @Author: Ademola Aina
 * Email: debascoguy@gmail.com
 * Date: 8/19/2015
 * Time: 11:18 PM
 */
class Badge extends Tag
{

    const BADGE_PRIMARY = "badge-primary";
    const BADGE_SUCCESS = "badge-success";
    const BADGE_DANGER = "badge-danger";
    const BADGE_WARNING = "badge-warning";
    const BADGE_INFO = "badge-info";
    const BADGE_DEFAULT = "badge-default";
    const BADGE_HASH_TAG = "badge-hash-tag";

    function __construct($html = "")
    {
        parent::__construct("span", ["class" => ["badge"]], $html);
    }

    /**
     * @param string $badgeType
     * @return $this
     */
    public function setType($badgeType = badge::BADGE_DEFAULT)
    {
        $this->addClass($badgeType);
        return $this;
    }

    /**
     * @return mixed
     */
    public static function get_status_badges()
    {
        $status_badges[0] = badge::BADGE_DEFAULT;
        $status_badges[1] = badge::BADGE_PRIMARY;
        $status_badges[2] = badge::BADGE_INFO;
        $status_badges[3] = badge::BADGE_WARNING;
        $status_badges[4] = badge::BADGE_DANGER;
        $status_badges[5] = badge::BADGE_SUCCESS;

        return $status_badges;
    }

    /**
     * @return mixed
     */
    public static function get_decision_badges()
    {
        $decision_badges[0] = badge::BADGE_DEFAULT;
        $decision_badges[1] = badge::BADGE_SUCCESS;
        $decision_badges[2] = badge::BADGE_WARNING;
        return $decision_badges;
    }

    /**
     * @return mixed
     */
    public static function get_yesno_badges()
    {
        $yesno_badges[0] = badge::BADGE_WARNING;
        $yesno_badges[1] = badge::BADGE_SUCCESS;
        return $yesno_badges;
    }


}