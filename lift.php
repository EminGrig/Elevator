<?php

class elevator
{


    public $_maxFloors = 9;

    public $_Request;
    /*Property for analizing request
        request contains 3 informational fields*/
    public $_Direction;
    /*   1. _Direction, (0, DOWN: 1, UP)*/
    public $_FloorInfo;
    // 2. _FloorInfo, (0  -  _maxfloors)
    public $_Request_status_info;
    /*  3. _Request_status_info (0 - done, 1 - inProgress)
Example Request  (151) Passangere from 5th floor going up (Passanger Status - waiting)
*/

    public $_Status = 2;
    /*Elevator  moving status
        (0 - GoingDown: 1 - GoingUp, 2 - StandBy)
    */

    public $_Floor = 1;
    /*Elevator location  by floor number
        Getting new value when elevator moving
    */
    public $_CallUp = array();
    /* Array where i element contains 1 if
      _Direction = 1 (UP). i =  _FloorInfo.
    */
    public $_CallDown = array();
    /*Array where i element contains 1 if
      _Direction = 0 (Down). i =  _FloorInfo.
    */
    public $_Call_from_inside_elevator = array();
    /* Array where i element contains 1 if
    request was from inside of elevator
    doing i th floor
    */
    public $_waiting_elevator = array();
    /* passangers waiting for elevaitor on i th floor(s)
    */

    public $_doorStatus;

    /* Door status 0 - close, 1 - open
    */

    function __construct($request)
    {
        /* here we using request validators
        must contains 3 digits if we have less than 9 floor building
    */
        for ($i = 0; $i < 10; $i++) {
            $this->_CallUp[$i] = 0;
        }

        for ($i = 0; $i < 10; $i++) {
            $this->_CallDown[$i] = 0;
        }

        if (isset($request)) {
            $this->_Request = $request;
        }

        $this->_Request($this->_Request);
    }

    /* retrive request details from request */
    public function _Request($request_cod)
    {
        echo 'Elevator stoped at ' . $this->_Floor . ' floor' . '</br>';
        $requestarray = str_split($request_cod);

        $_Direction           = $requestarray[0]; //
        $_FloorInfo           = $requestarray[1]; //
        $_Request_status_info = $requestarray[2]; //

        $request = array('Direction' => $_Direction,
            'Floor' => $_FloorInfo,
            'RequestStatus' => $_Request_status_info);
        echo 'Request is: ' . $request_cod . ' that means to go ' . $_FloorInfo . ' floor, direction ' . $_Direction . '</br>';

        if($_FloorInfo > $this->_Floor){

            for ($i = 1; $i < 10; $i++) {
                if ($i == $_FloorInfo) {
                    $this->_CallUp[$i] = 1;
                } else {
                    ($this->_CallUp[$i] != 1 ? $this->_CallUp[$i] = 0 : $this->_CallUp[$i] = 1);
                }
                echo $this->_CallUp[$i];
            }
                echo '  CallUp Array'.'</br>';

        }elseif ($_FloorInfo < $this->_Floor){

            for ($i = 1; $i < 10; $i++) {
                if ($i == $_FloorInfo) {
                    $this->_CallDown[$i] = 1;
                } else {
                    ($this->_CallDown[$i] != 1 ? $this->_CallDown[$i] = 0 : $this->_CallDown[$i] = 1);
                }
                echo $this->_CallDown[$i];
            }
            echo '  CallDown Array'.'</br>';

        }elseif ($_FloorInfo == $this->_Floor){

                echo "Going " . $this->_FloorInfo . 'floor, from ' . $this->_Floor . ' floor ' . '</br>';
                echo "It is in the same floor so we just need open doors" . '</br>';
                $this->_Status=2;
                $this->OpenDoors();
        }

        $this->Waiting_for_request();
        return $request;
    }


    public function Move_Elevator_toStart()
    {
        $this->_Floor = 1;
        $this->_Status = 2;
    }

    public function Waiting_for_request()
    {
        for ($i = 1; $i < 10; $i++) {
            if ($this->_CallUp[$i] == 1) {
                echo "Going UP to " . $i . ' floor, from ' . $this->_Floor . ' floor ' . '</br>';
                echo "It is the different floor so we need make moving decision" . '</br>';
                $this->_Status = 1; //Going UP
                break;
            } else {
                if ($this->_CallDown[$i] == 1) {
                    echo "Going DOWN to " . $i . ' floor, from ' . $this->_Floor . ' floor ' . '</br>';
                    echo "It is the different floor so we need make moving decision" . '</br>';
                    $this->_Status = 0; //Going DOWN
                    break;

                }
            }
        }
        $this->Moving_Decision($i);
        return 0;
    }

    public function OpenDoors()
    {
        $this->_CallUp[$this->_Floor] = 0;
        $this->_CallDown[$this->_Floor] = 0;
        //$this->_Status=2;
        echo "The doors are open on the " . $this->_Floor . '</br>';
        $this->Loading_Unloading();
    }

    public function Loading_Unloading()
    {
        $accptReq = rand(1,9);
        echo "After loading, passenger, pushing floor button inside elevator for example " . $accptReq . 'th floor' . '</br>';
        // waiting to accept request
        // for example
        for ($i = 1; $i < 10; $i++) {
            if ($i == $accptReq) {
                $this->_Call_from_inside_elevator[$i] = 1;
            } else {
                $this->_Call_from_inside_elevator[$i] = 0;
            }
        }
        echo 'Change _Call_from_inside_elevator array - ';
        for ($i = 1; $i < 10; $i++) {
            echo $this->_Call_from_inside_elevator[$i];
        }
        echo '</br>';
        $this->CloseDoors();

    }

    public function CloseDoors()
    {
        echo 'Closing doors' . '</br>';
        for ($i = 1; $i < 10; $i++) {
            if ($this->_Call_from_inside_elevator[$i] == 1 && $i!=$this->_Floor){
                echo 'We have One request from inside of Elevator. Going to '.$i.' floor. Generating new request ';
            };

        }
        return 0;
    }

    public function Moving_Decision($i)
    {
        if($this->_Status == 0){
             $this->GoingDOWN($i);

        }elseif ($this->_Status == 1){
                $this->GoingUP($i);

        }elseif ($this->_Status == 2){

            echo 'Closing doors' . '</br>';
            $this->_Request(191);

        }



    }

    public function GoingUP($toFloor)
    {
        while ($this->_Floor != $toFloor) {
            $this->_Floor++;
            echo 'Going up to ' . $this->_Floor . '</br>';
        }
        echo 'Elevator is in requested ' . $this->_Floor . ' floor' . '</br>';
        $this->OpenDoors();
        return 0;
    }

    public
    function GoingDOWN($toFloor)
    {
        while ($this->_Floor != $toFloor) {
            $this->_Floor--;
            echo 'Going up to ' . $this->_Floor . '</br>';
        }
        echo 'Elevator is in requested ' . $this->_Floor . ' floor' . '</br>';
        $this->OpenDoors();
        return 0;

    }


}