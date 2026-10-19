
<?php

class Room
{
    private $roomNumber;
    private $roomType;
    private $roomPrice;
    private $roomStatus;

    public function __construct($roomNumber, $roomType, $pricePerNight)
    {
        $this->roomNumber = $roomNumber;
        $this->roomType = $roomType;
        $this->roomPrice = $pricePerNight;
        $this->roomStatus = "Available";
    }

    public function getRoomNumber()
    {
        return $this->roomNumber;
    }

    public function getRoomType()
    {
        return $this->roomType;
    }

    public function getPricePerNight()
    {
        return $this->roomPrice;
    }

    public function getStatus()
    {
        return $this->roomStatus;
    }

    public function displayRoom()
    {
        echo "Room Number: " . $this->roomNumber . "\n";
        echo "Room Type: " . $this->roomType . "\n";
        echo "Price Per Night: PHP "
            . number_format($this->roomPrice, 2) . "\n";
        echo "Status: " . $this->roomStatus . "\n";
        echo "-----------------------------\n";
    }
}

?>
