<?php

class elevator{


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

    public $_Status;
    /*Elevator  moving status
        (0 - GoingDown: 1 - GoingUp, 2 - StandBy)
    */

    public $_Floor;
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
        if (isset($request)) {
            $this->_Request = $request;
        }

    }
    /* retrive request details from request */
    public function _Request(){
        $requestarray = str_split($this->_Request);
        $this->_Direction = $requestarray[0];
        $this->_FloorInfo = $requestarray[1];
        $this->_Request_status_info = $requestarray[2];
            $request = array( 'Direction' => $this->_Direction,
                              'Floor' => $this->_FloorInfo,
                              'RequestStatus' => $this->_Request_status_info);
        return $request;
    }


    public function Move_Elevator_toStart(){
        $this->_Floor = 1;
        $this->_Status= 2;
    }

    public function Waiting_for_request(){
        // if we dont have a request for some period of time
        // move elevator to start possition
        if($this->_Request = 0){
            $this->Move_Elevator_toStart();
            return 0;
        }else{
            if($this->_Request()['Floor']=1){
                $this->OpenDoors();

            }else{
                $this->Moving_Decision();
            }

        }

    }

    public function OpenDoors(){
        $this->_doorStatus = 1; // Open
        $this->Loading_Unloading();
    }

    public function Loading_Unloading(){
        // waiting to accept request
        $accptReq = 5; // for example
       $this->_Call_from_inside_elevator[$accptReq] = 1;

    }

    public function CloseDoors(){
        //After specific period of time
        $this->_doorStatus = 0;
        if ($this->_doorStatus = 0 && $this->_Status=2){
            $this->_CallUp[$this->_Floor]=0;
        }
        if ($this->_doorStatus = 0 && $this->_Status=1){
            $this->_CallDown[$this->_Floor]=0;
        }
        $this->_Call_from_inside_elevator[$this->_Floor] = 0;
        $this->Moving_Decision();

    }

    public function Moving_Decision(){
        if($this->_Request()['RequestStatus = 1']){
            if($this->_Request()['Direction = 1']){ // Direction UP
                if($this->_Floor < $this->_Request()['Floor']){
                    $this->_Status = 1; // Going up
                    $this->GoingUP();
                }else{
                    $this->_Status = 2; // Going Down if noRequest from hier floors
                    $this->GoingDOWN();
                }
            }
        }
    }

    public function GoingUP(){
        for($i = $this->_Floor; $i = $this->_Request()['Floor']; $i++){
            // check if there is no other request in same direction
            // if there is, stop at that level and go to function Waiting_for_request()
            $this->_Floor = $this->_Floor + 1;
        }

       return $this->_Floor;
    }

    public function GoingDOWN(){
        for($i = $this->_Floor; $i == $this->_Request()['Floor']; $i--){

            // check if there is no other request in same direction
            // if there is, stop at that level and go to function Waiting_for_request()
            $this->_Floor = $this->_Floor - 1;
        }

        return $this->_Floor;
    }




}