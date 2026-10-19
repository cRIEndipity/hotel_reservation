
<?php

require_once __DIR__ . "/Hotel.php";

$hotel = new Hotel();

while (true) {
    echo "\n";
    echo "------------------------------------\n";
    echo "|     HOTEL RESERVATION SYSTEM     |\n";
    echo "------------------------------------\n";
    echo "1. View Rooms\n";
    echo "2. Make a Reservation\n";
    echo "3. View Reservations\n";
    echo "4. Cancel a Reservation\n";
    echo "5. Exit\n";
    echo "------------------------------------\n";
    echo "Enter your choice: ";

    $choice = trim(fgets(STDIN));

    switch ($choice) {
        case "1":
            $hotel->viewRooms();
            break;

        case "2":
            $hotel->makeReservation();
            break;

        case "3":
            $hotel->viewReservations();
            break;

        case "4":
            $hotel->cancelReservation();
            break;

        case "5":
            echo "Thank you for using the Hotel Reservation System!\n";
            exit;

        default:
            echo "Invalid choice. Please enter 1 to 5.\n";
    }
}

?>
