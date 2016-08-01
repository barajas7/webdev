<?php
/**
 * Created by PhpStorm.
 * User: Alex
 * Date: 2/17/16
 * Time: 3:50 PM
 */

namespace Steampunked;


class Player
{
    public function __construct($name) {
        $this->name = $name;
    }

    public function getName() {
        return $this->name;
    }

    public function setName($name) {
        $this->name = $name;
    }

    public function getPipeList()
    {
        return $this->pipe_list;
    }

    public function setPipeList($pipe_list)
    {
        $this->pipe_list = $pipe_list;
    }

    public function getCurrentPipe()
    {
        return $this->pipe_list[$this->current_pipe_index];
    }

    public function setCurrentPipe($index)
    {
        $this->current_pipe_index = intval($index);
    }

    public function getCurrentPipeIndex() {
        return $this->current_pipe_index;
    }

    public function setPipeAtIndex($index, $pipe) {
        $this->pipe_list[$index] = $pipe;
    }

    private $name = null;

    private $pipe_list = null;

    private $current_pipe_index = 0;
}