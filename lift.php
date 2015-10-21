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
            $this->_CallDown[$i] = 0;
        }
        for ($i = 0; $i < 10; $i++) {
            $this->_CallUp[$i] = 0;
        }
        if (isset($request)) {
            $this->_Request = $request;
        }

        $this->_Request();
    }

    /* retrive request details from request */
    public function _Request()
    {
        echo 'Elevator stoped at ' . $this->_Floor . ' floor' . '</br>';
        $requestarray = str_split($this->_Request);
        $this->_Direction = $requestarray[0];
        $this->_FloorInfo = $requestarray[1];
        $this->_Request_status_info = $requestarray[2];
        $request = array('Direction' => $this->_Direction,
            'Floor' => $this->_FloorInfo,
            'RequestStatus' => $this->_Request_status_info);
        echo 'Request is: ' . $this->_Request . ' that means to go ' . $this->_Floor . ' floor, direction ' . $this->_Direction . '</br>';
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
        // if we dont have a request for some period of time
        // move elevator to start possition
        if ($this->_Request_status_info = 0) {
            $this->Move_Elevator_toStart();
            $this->_Status = 2;
            return 0;
        } else {
            if ($this->_FloorInfo = $this->_Floor) {
                echo "Going " . $this->_FloorInfo . 'floor, from ' . $this->_Floor . ' floor ' . '</br>';
                echo "It is in the same floor so we just need open doors" . '</br>';
                $this->OpenDoors();

            } else {
                //  $this->Moving_Decision();
            }

        }
        return 0;
    }

    public function OpenDoors()
    {
        echo "The doors are open on the " . $this->_Floor . '</br>';
        $this->_Status = 3; // Open

        $this->Loading_Unloading();
    }

    public function Loading_Unloading()
    {
        for ($i = 1; $i < 10; $i++) {

                if ($this->_CallUp[$i] == 0 || $this->_CallDown[$i] == 0) {
                    //$this->CloseDoors();

            }else{
                    $this->Move_Elevator_toStart();
                }
        }
        $accptReq = 5;
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
        if ($this->_Floor == $i) {
            $this->OpenDoors();
        } else {
            $this->CloseDoors();
        }
    }

    public function CloseDoors()
    {
        echo 'Closing doors' . '</br>';
        //After specific period of time
        $this->_Status = 4; // Close

        $this->Moving_Decision();

    }

    public function Moving_Decision()
    {
        for ($i = 1; $i < 10; $i++) {
            if ($this->_Call_from_inside_elevator[$i] == 1) {
                if ($this->_Floor < $i) {
                    echo 'Moving decision made Going UP to ' . $i . ' floor' . '</br>';
                    $this->GoingUP();
                    $this->_Status = 1; // Going up
                }
                if ($this->_Floor > $i) {
                    echo 'Moving decision made Going DOWN to ' . $i . ' floor' . '</br>';
                    // $this->GoingDOWN();
                    $this->_Status = 2;
                }
            }
        }

    }

    public function GoingUP()
    {

        for ($i = 1; $i < 10; $i++) {
            if ($this->_Call_from_inside_elevator[$i] == 1) {
                $this->_CallUp[$i] = 1;
            } else {
                $this->_CallUp[$i] = 0;
            }
        }
        $i = $this->_Floor;
        while ($this->_CallUp[$i] == 0) {
            $this->_Floor++;
            echo 'Going up to ' . $this->_Floor;
            $i++;
        }
        $this->_CallUp[$this->_Floor] = 0;
        $this->OpenDoors();

    }

    public
    function GoingDOWN()
    {
        for ($i = $this->_Floor; $i == $this->_Request()['Floor']; $i--) {

            // check if there is no other request in same direction
            // if there is, stop at that level and go to function Waiting_for_request()
            $this->_Floor = $this->_Floor - 1;
        }

        return $this->_Floor;


    }


}