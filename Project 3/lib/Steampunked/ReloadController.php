<?php
/**
 * Created by PhpStorm.
 * User: Alex
 * Date: 4/21/16
 * Time: 2:20 PM
 */

namespace Steampunked;

class ReloadController {

    public function __construct(Site $site) {

        $game_view = new GameView($site);

        $html = $game_view->present_header();
        $html .= $game_view->present();
        $this->result = json_encode(array('ok' => true, 'table' => $html));
    }

    /**
     * @return string
     */
    public function getResult() {
        return $this->result;
    }



    private $result;

}