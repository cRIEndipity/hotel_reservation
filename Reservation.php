
<?php

class Reservation
{
    private $reservationId;
    private $guestName;
    private $contactNumber;
    private $roomNumber;
    private $checkIn;
    private $checkOut;
    private $totalPrice;
    private $status;

    public function __construct(
        $reservationId,
        $guestName,
        $contactNumber,
        $roomNumber,
        $checkIn,
        $checkOut,
        $totalPrice,
        $status = "Booked"
    ) {
        $this->reservationId = $reservationId;
        $this->guestName = $guestName;
        $this->contactNumber = $contactNumber;
        $this->roomNumber = $roomNumber;
        $this->checkIn = $checkIn;
        $this->checkOut = $checkOut;
        $this->totalPrice = $totalPrice;
        $this->status = $status;
    }

    public function getReservationId()
    {
        return $this->reservationId;
    }

    public function getRoomNumber()
    {
        return $this->roomNumber;
    }

    public function getCheckIn()
    {
        return $this->checkIn;
    }

    public function getCheckOut()
    {
        return $this->checkOut;
    }

    public function getStatus()
    {
        return $this->status;
    }

    public function cancelReservation()
    {
        $this->status = "Cancelled";
    }

    public function displayReservation()
    {
        echo "Reservation ID: " . $this->reservationId . "\n";
        echo "Guest Name: " . $this->guestName . "\n";
        echo "Contact Number: " . $this->contactNumber . "\n";
        echo "Room Number: " . $this->roomNumber . "\n";
        echo "Check-in: " . $this->checkIn . "\n";
        echo "Check-out: " . $this->checkOut . "\n";
        echo "Total Price: PHP "
            . number_format($this->totalPrice, 2) . "\n";
        echo "Status: " . $this->status . "\n";
        echo "----------------------------------\n";
    }

    public function toFileString()
    {
        $fields = [
            $this->reservationId,
            $this->guestName,
            $this->contactNumber,
            $this->roomNumber,
            $this->checkIn,
            $this->checkOut,
            $this->totalPrice,
            $this->status
        ];

        return implode("|", $fields);
    }
}

?>
