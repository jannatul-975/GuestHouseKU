-- Administrative
CREATE TABLE `administrative` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(100) NOT NULL,
  `email` VARCHAR(100) NOT NULL,
  `phone_no` VARCHAR(15) NOT NULL,
  `designation` ENUM('VC', 'Treasurer', 'Pro VC') NOT NULL,
  `password` VARCHAR(255) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE (`email`),
  UNIQUE (`phone_no`)
) ENGINE=InnoDB;

-- Attendant
CREATE TABLE `attendant` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(100) NOT NULL,
  `email` VARCHAR(100) NOT NULL,
  `phone_no` VARCHAR(15) NOT NULL,
  `designation` ENUM('Attendant') NOT NULL DEFAULT 'Attendant',
  `password` VARCHAR(255) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE (`email`),
  UNIQUE (`phone_no`)
) ENGINE=InnoDB;

-- Guest
CREATE TABLE `guest` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(100) NOT NULL,
  `email` VARCHAR(100) NOT NULL,
  `phone_no` VARCHAR(15) NOT NULL,
  `designation` ENUM('Guest') NOT NULL DEFAULT 'Guest',
  `address` TEXT NOT NULL,
  `registration_date` DATETIME DEFAULT CURRENT_TIMESTAMP,
  `password` VARCHAR(255) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE (`email`),
  UNIQUE (`phone_no`)
) ENGINE=InnoDB;

-- Room
CREATE TABLE `room` (
  `RoomID` INT NOT NULL AUTO_INCREMENT,
  `RoomNo` VARCHAR(50) NOT NULL,
  `room_type` ENUM('VIP', 'AC','Non AC &Double', 'Non AC & Single') NOT NULL,
  `pricePerNight` DECIMAL(10,2) NOT NULL,
  `status` ENUM('Available', 'Booked', 'Under Maintenance') DEFAULT 'Available',
  PRIMARY KEY (`RoomID`)
) ENGINE=InnoDB;


-- Booking 
CREATE TABLE `booking` (
  `BookingID` INT NOT NULL AUTO_INCREMENT,
  `GuestID` INT NOT NULL,
  `booked_by_role` ENUM('teacher', 'administrative', 'register', 'officestaff', 'guest') NOT NULL,
  `booked_by_id` INT NOT NULL,
  `RoomNo` INT NOT NULL,
  `checkInDate` DATE NOT NULL,
  `checkOutDate` DATE NOT NULL,
  `status` ENUM('Pending','Confirmed','Checked-in','Checked-out','Cancelled') DEFAULT 'Pending',
  `total_amount` DECIMAL(10,2),
  `last_update` DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`BookingID`),
  FOREIGN KEY (`GuestID`) REFERENCES `guest`(`id`) ON DELETE CASCADE,
  FOREIGN KEY (`RoomNo`) REFERENCES `room`(`RoomNo`) ON DELETE CASCADE
) ENGINE=InnoDB;

-- Payment
CREATE TABLE `payment` (
  `PaymentID` INT NOT NULL AUTO_INCREMENT,
  `BookingID` INT NOT NULL,
  `paid_amount` DECIMAL(10,2) NOT NULL,
  `payment_date` DATETIME DEFAULT CURRENT_TIMESTAMP,
  `payment_method` ENUM('Cash', 'Credit Card', 'Bank Transfer') NOT NULL,
  `transactionID` VARCHAR(50) NOT NULL,
  PRIMARY KEY (`PaymentID`),
  UNIQUE (`transactionID`),
  FOREIGN KEY (`BookingID`) REFERENCES `booking` (`BookingID`) ON DELETE CASCADE
) ENGINE=InnoDB;

-- Register
CREATE TABLE `register` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(100) NOT NULL,
  `email` VARCHAR(100) NOT NULL,
  `phone_no` VARCHAR(15) NOT NULL,
  `designation` ENUM('Register') NOT NULL DEFAULT 'Register',
  `password` VARCHAR(255) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE (`email`),
  UNIQUE (`phone_no`)
) ENGINE=InnoDB;

-- Office Staff
CREATE TABLE `officestaff` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(100) NOT NULL,
  `email` VARCHAR(100) NOT NULL,
  `phone_no` VARCHAR(15) NOT NULL,
  `designation` ENUM('Personal Assistant Cum Computer Operator','Office Assistant Cum Computer Operator', 'Lab Technician','Assistant Lab Technician') NOT NULL,
  `dept_name` VARCHAR(100) NOT NULL,
  `password` VARCHAR(255) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE (`email`),
  UNIQUE (`phone_no`)
) ENGINE=InnoDB;

-- Internal Staff (non-booking roles)
CREATE TABLE `staff` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(100) NOT NULL,
  `phone_no` VARCHAR(15) NOT NULL,
  `designation` ENUM('Receptionist', 'Cleaner', 'Maintenance') NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE (`phone_no`)
) ENGINE=InnoDB;

-- Teacher
CREATE TABLE `teacher` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(100) NOT NULL,
  `email` VARCHAR(100) NOT NULL,
  `phone_no` VARCHAR(15) NOT NULL,
  `designation` ENUM('Lecturer', 'Professor') NOT NULL,
  `dept_name` VARCHAR(100) NOT NULL,
  `password` VARCHAR(255) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE (`email`),
  UNIQUE (`phone_no`)
) ENGINE=InnoDB;

-- Teacher Application
CREATE TABLE `teacher_application` (
  `ApplicationID` INT NOT NULL AUTO_INCREMENT,
  `TeacherID` INT NOT NULL,
  `GuestID` INT NOT NULL,
  `RegisterID` INT NOT NULL,
  `status` ENUM('Pending', 'Approved', 'Rejected') DEFAULT 'Pending',
  `submission_date` DATETIME DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`ApplicationID`),
  FOREIGN KEY (`TeacherID`) REFERENCES `teacher` (`id`) ON DELETE CASCADE,
  FOREIGN KEY (`GuestID`) REFERENCES `guest` (`id`) ON DELETE CASCADE,
  FOREIGN KEY (`RegisterID`) REFERENCES `register` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB;

-- Teacher-Guest Link
CREATE TABLE `teacher_guest` (
  `TeacherID` INT NOT NULL,
  `GuestID` INT NOT NULL,
  PRIMARY KEY (`TeacherID`, `GuestID`),
  FOREIGN KEY (`TeacherID`) REFERENCES `teacher` (`id`) ON DELETE CASCADE,
  FOREIGN KEY (`GuestID`) REFERENCES `guest` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB;

ALTER TABLE register ADD COLUMN is_active BOOLEAN DEFAULT TRUE;
ALTER TABLE attendant ADD COLUMN is_active BOOLEAN DEFAULT TRUE;
ALTER TABLE administrative ADD COLUMN is_active TINYINT(1) DEFAULT 0;

ALTER TABLE teacher MODIFY COLUMN designation ENUM('Lecturer', 'Assistant Professor', 'Associate Professor', 'Professor');

ALTER TABLE booking ADD COLUMN booked_by_table ENUM('teacher', 'administrative', 'register', 'officestaff', 'guest') NOT NULL;

ALTER TABLE Guest
ADD COLUMN profile_pic VARCHAR(255) DEFAULT 'profile.jpg';

DROP TABLE IF EXISTS `teacher_application`;

CREATE TABLE `teacher_application` (
  `ApplicationID` INT NOT NULL AUTO_INCREMENT,
  `TeacherID` INT NOT NULL,
  `GuestID` INT NULL, 
  `RegisterID` INT NOT NULL,
  `status` ENUM('Pending', 'Approved', 'Rejected') DEFAULT 'Pending',
  `submission_date` DATETIME DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`ApplicationID`),
  FOREIGN KEY (`TeacherID`) REFERENCES `teacher` (`id`) ON DELETE CASCADE,
  FOREIGN KEY (`GuestID`) REFERENCES `guest` (`id`) ON DELETE SET NULL,
  FOREIGN KEY (`RegisterID`) REFERENCES `register` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB;

CREATE TABLE `application_details` (
  `DetailID` INT AUTO_INCREMENT PRIMARY KEY,
  `ApplicationID` INT NOT NULL,
  `room_id` int NOT NULL,
  `checkin_date` DATE NOT NULL,
  `checkout_date` DATE NOT NULL,
  `number_of_guests` INT DEFAULT 1,
  FOREIGN KEY (`ApplicationID`) REFERENCES `teacher_application`(`ApplicationID`) ON DELETE CASCADE,
  FOREIGN KEY (`room_id`) REFERENCES `room`(`RoomNo`) ON DELETE CASCADE,
  UNIQUE (`ApplicationID`, `room_id`)
) ENGINE=InnoDB;

CREATE TABLE `verification_codes` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `application_id` INT NOT NULL,
  `code` VARCHAR(10) NOT NULL,
  `is_used` BOOLEAN DEFAULT 0,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (`application_id`) REFERENCES `teacher_application` (`ApplicationID`) ON DELETE CASCADE
)ENGINE=InnoDB;

