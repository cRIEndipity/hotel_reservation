Yo! Riri's here to explain the code.

Btw, this system runs through CMD or terminal ha? ge.

1. test_room.php
    -> Wala lang to beh, you guys can just delete this one if you want. for testing lang talaga to kazi tinry ko lang if gumagana pa utak ko sa php.

2. Room.php

        This file contains the room class. I used this class to represent each room in the hotel; each room has its own room num, room type, price per night, and status. So kibale i s-set ng constructor yung values kada may new room object na na-create. (when we say constructor, it's like a method within a class na mag a-auto executes kapag mag c-create ka ng object sa class na yun. gets? ah ndi? basta oi search mo nalang hihi. hirap mag explain pd ba 2 i vm?)

        Also, i added getter methods para ma retrieve yung room information and a displayRoom() method to show the details in CMD. Kunyari, kapag gumawa ako ng Room 101, gagamit aq ng new Room("101",Single,1000). So eto na yung gagawa mismo ng room object kasama na yung mga own information. 

2. Reservation.php

        This one is yung sa Reswervation class, which handles sa mga information for each booking like yung mga reservation id, guest name, contact number, room number, check-in, check-out date, total price, and status.

        The displayReservation() method shows the booking details, while yung canceloReservation() changes the status to cancelled.
        i also used to FileString() para ma convert yung reservation deets to a single line of text para madali lang sha ma gets tas ma basa.

3. Hotel.php

        Here is the main operations of the hotel system; dito na pumapasok yung room management, reservation processing, and file operations.

        Yung nakikita nyong _construct() method sa code, yan na yung mag p-prepare ng data sa mga folders and text files. if there are no saved rooms yet, the system add four sample rooms. yung loadRooms() and loadReservations() naman yung mag r-read ng mga saved records then i convert them into objects. meanwhile yung saveRooms() and saveReservation() yung mag susulat ng mga updated information back sa text files. 




