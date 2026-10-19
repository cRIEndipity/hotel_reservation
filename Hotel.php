
<?php

require_once __DIR__ . "/Room.php";
require_once __DIR__ . "/Reservation.php";

class Hotel
{
    private $roomFile;
    private $reservationFile;
    private $rooms = [];
    private $reservations = [];

    public function __construct()
    {
        $dataFolder = __DIR__ . "/data";

        if (!is_dir($dataFolder)) {
            mkdir($dataFolder, 0777, true);
        }

        $this->roomFile = $dataFolder . "/room.txt";
        $this->reservationFile = $dataFolder . "/reservation.txt";

        if (!file_exists($this->roomFile)) {
            file_put_contents($this->roomFile, "");
        }

        if (!file_exists($this->reservationFile)) {
            file_put_contents($this->reservationFile, "");
        }

        $this->loadRooms();
        $this->loadReservations();

        if (count($this->rooms) === 0) {
            $this->addRoom("101", "Single", 1000);
            $this->addRoom("102", "Double", 1800);
            $this->addRoom("103", "Deluxe", 2500);
            $this->addRoom("104", "Suite", 4000);
        }
    }

    private function loadRooms()
    {
        $this->rooms = [];
        $lines = file($this->roomFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);

        if ($lines === false) {
            return;
        }

        foreach ($lines as $line) {
            $data = explode("|", $line);

            if (count($data) !== 4) {
                continue;
            }

            $room = new Room(
                $data[0],
                $data[1],
                (float) $data[2]
            );

            $this->rooms[] = $room;
        }
    }

    private function loadReservations()
    {
        $this->reservations = [];
        $lines = file(
            $this->reservationFile,
            FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES
        );

        if ($lines === false) {
            return;
        }

        foreach ($lines as $line) {
            $data = explode("|", $line);

            if (count($data) !== 8) {
                continue;
            }

            $reservation = new Reservation(
                $data[0],
                $data[1],
                $data[2],
                $data[3],
                $data[4],
                $data[5],
                (float) $data[6],
                $data[7]
            );

            $this->reservations[] = $reservation;
        }
    }

    private function saveRooms()
    {
        $lines = [];

        foreach ($this->rooms as $room) {
            $lines[] = implode("|", [
                $room->getRoomNumber(),
                $room->getRoomType(),
                $room->getPricePerNight(),
                $room->getStatus()
            ]);
        }

        return file_put_contents(
            $this->roomFile,
            implode(PHP_EOL, $lines) .
            (count($lines) > 0 ? PHP_EOL : ""),
            LOCK_EX
        ) !== false;
    }

    private function saveReservations()
    {
        $lines = [];

        foreach ($this->reservations as $reservation) {
            $lines[] = $reservation->toFileString();
        }

        return file_put_contents(
            $this->reservationFile,
            implode(PHP_EOL, $lines) .
            (count($lines) > 0 ? PHP_EOL : ""),
            LOCK_EX
        ) !== false;
    }

    public function addRoom($number, $type, $price)
    {
        foreach ($this->rooms as $room) {
            if ($room->getRoomNumber() === $number) {
                return false;
            }
        }

        $room = new Room($number, $type, $price);
        $this->rooms[] = $room;

        if (!$this->saveRooms()) {
            array_pop($this->rooms);
            return false;
        }

        return true;
    }

    public function viewRooms()
    {
        $this->loadRooms();

        echo "\n--------- HOTEL ROOMS ---------\n";

        if (count($this->rooms) === 0) {
            echo "No rooms found.\n";
            return;
        }

        foreach ($this->rooms as $room) {
            $room->displayRoom();
        }
    }

    private function findRoom($roomNumber)
    {
        foreach ($this->rooms as $room) {
            if ($room->getRoomNumber() === $roomNumber) {
                return $room;
            }
        }

        return null;
    }

    private function validDate($date)
    {
        $parsed = DateTime::createFromFormat("!Y-m-d", $date);

        return $parsed !== false
            && $parsed->format("Y-m-d") === $date;
    }

    private function roomIsAvailable($roomNumber, $checkIn, $checkOut)
    {
        foreach ($this->reservations as $reservation) {
            if (
                $reservation->getRoomNumber() === $roomNumber
                && $reservation->getStatus() === "Booked"
                && $checkIn < $reservation->getCheckOut()
                && $checkOut > $reservation->getCheckIn()
            ) {
                return false;
            }
        }

        return true;
    }

    public function makeReservation()
    {
        $this->loadRooms();
        $this->loadReservations();

        $this->viewRooms();

        echo "\nEnter guest name: ";
        $name = trim(fgets(STDIN));

        echo "Enter contact number: ";
        $contact = trim(fgets(STDIN));

        echo "Enter room number: ";
        $roomNumber = trim(fgets(STDIN));

        $room = $this->findRoom($roomNumber);

        if ($room === null) {
            echo "Room not found.\n";
            return;
        }

        if (
            $name === ""
            || $contact === ""
            || strpos($name, "|") !== false
            || strpos($contact, "|") !== false
        ) {
            echo "Invalid guest information.\n";
            return;
        }

        echo "Enter check-in date (YYYY-MM-DD): ";
        $checkIn = trim(fgets(STDIN));

        echo "Enter check-out date (YYYY-MM-DD): ";
        $checkOut = trim(fgets(STDIN));

        if (
            !$this->validDate($checkIn)
            || !$this->validDate($checkOut)
        ) {
            echo "Invalid date. Use YYYY-MM-DD.\n";
            return;
        }

        if ($checkOut <= $checkIn) {
            echo "Check-out must be after check-in.\n";
            return;
        }

        if (!$this->roomIsAvailable($roomNumber, $checkIn, $checkOut)) {
            echo "Room is already reserved for those dates.\n";
            return;
        }

        $date1 = new DateTime($checkIn);
        $date2 = new DateTime($checkOut);
        $nights = $date1->diff($date2)->days;
        $total = $nights * $room->getPricePerNight();

        $ids = [];

        foreach ($this->reservations as $reservation) {
            $ids[] = (int) $reservation->getReservationId();
        }

        $newId = count($ids) > 0 ? max($ids) + 1 : 1;

        $reservation = new Reservation(
            $newId,
            $name,
            $contact,
            $roomNumber,
            $checkIn,
            $checkOut,
            $total
        );

        $this->reservations[] = $reservation;

        if (!$this->saveReservations()) {
            array_pop($this->reservations);
            echo "Could not save reservation. Check file permissions.\n";
            return;
        }

        echo "\nReservation successful!\n";
        $reservation->displayReservation();
    }

    public function viewReservations()
    {
        $this->loadReservations();

        echo "\n------- RESERVATION RECORDS -------\n";

        if (count($this->reservations) === 0) {
            echo "No reservations found.\n";
            return;
        }

        foreach ($this->reservations as $reservation) {
            $reservation->displayReservation();
        }
    }

    public function cancelReservation()
    {
        $this->loadReservations();

        echo "\nEnter reservation ID to cancel: ";
        $id = trim(fgets(STDIN));

        foreach ($this->reservations as $reservation) {
            if (
                (string) $reservation->getReservationId() === $id
                && $reservation->getStatus() === "Booked"
            ) {
                $reservation->cancelReservation();

                if ($this->saveReservations()) {
                    echo "Reservation cancelled successfully.\n";
                } else {
                    echo "Could not save cancellation.\n";
                }

                return;
            }
        }

        echo "Active reservation not found.\n";
    }
}

?>
