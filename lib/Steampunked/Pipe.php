<?php

namespace Steampunked;


class Pipe
{
    /**
     * Constructor
     * @param $row Row from the user table in the database
     */
    public function __construct(array $row) {
        $this->id = $row['id'];
        if(isset($row['row'])) {
            $this->row = $row['row'];
        }
        if(isset($row['col'])) {
            $this->col = $row['col'];
        }
        $this->type = $row['type'];
        $this->orientation = $row['orientation'];
        $this->gameid = $row['gameid'];
        $this->userid = $row['userid'];
        $this->handposition = $row['handposition'];
    }

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param mixed $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * @return mixed
     */
    public function getRow()
    {
        return $this->row;
    }

    /**
     * @param mixed $row
     */
    public function setRow($row)
    {
        $this->row = $row;
    }

    /**
     * @return mixed
     */
    public function getCol()
    {
        return $this->col;
    }

    /**
     * @param mixed $col
     */
    public function setCol($col)
    {
        $this->col = $col;
    }

    /**
     * @return mixed
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @param mixed $type
     */
    public function setType($type)
    {
        $this->type = $type;
    }

    /**
     * @return mixed
     */
    public function getOrientation()
    {
        return $this->orientation;
    }

    /**
     * @param mixed $orientation
     */
    public function setOrientation($orientation)
    {
        $this->orientation = $orientation;
    }

    /**
     * @return mixed
     */
    public function getGameid()
    {
        return $this->gameid;
    }

    /**
     * @param mixed $gameid
     */
    public function setGameid($gameid)
    {
        $this->gameid = $gameid;
    }

    /**
     * @return mixed
     */
    public function getUserid()
    {
        return $this->userid;
    }

    /**
     * @param mixed $userid
     */
    public function setUserid($userid)
    {
        $this->userid = $userid;
    }

    /**
     * @return mixed
     */
    public function getHandposition()
    {
        return $this->handposition;
    }

    /**
     * @param mixed $handposition
     */
    public function setHandposition($handposition)
    {
        $this->handposition = $handposition;
    }

    public function isFlag() {
        return $this->flag;
    }

    public function setFlag($flag)
    {
        $this->flag = $flag;
    }

    public function rotate() {}

    public function getImageUrl() {}

    public function getDirections() {}

    protected $id;
    protected $row;
    protected $col;
    protected $orientation;
    protected $gameid;
    protected $userid;
    protected $handposition;
    protected $type;

    private $flag = false;          //flag used for depth first search
}