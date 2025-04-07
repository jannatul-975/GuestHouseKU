CREATE TABLE Register (
    ID INT PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL,
    phone_no VARCHAR(15) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL
) ENGINE=InnoDB;

CREATE TABLE Teacher (
    TeacherID INT PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL,
    phone_no VARCHAR(15) UNIQUE NOT NULL,
    dept_name VARCHAR(100) NOT NULL,
    password VARCHAR(255) NOT NULL
) ENGINE=InnoDB;

CREATE TABLE Teacher_Application (
    ApplicationID INT PRIMARY KEY AUTO_INCREMENT,
    TeacherID INT NOT NULL,
    RegisterID INT NOT NULL,
    status ENUM('Pending', 'Approved', 'Rejected') DEFAULT 'Pending',
    submission_date DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (TeacherID) REFERENCES Teacher(TeacherID) ON DELETE CASCADE,
    FOREIGN KEY (RegisterID) REFERENCES Register(ID) ON DELETE CASCADE
) ENGINE=InnoDB;

CREATE TABLE Guest (
    GuestID INT PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL,
    phone_no VARCHAR(15) UNIQUE NOT NULL,
    address TEXT NOT NULL,
    registration_date DATETIME DEFAULT CURRENT_TIMESTAMP,
    password VARCHAR(255) NOT NULL
) ENGINE=InnoDB;

CREATE TABLE Teacher_Guest (
    TeacherID INT NOT NULL,
    GuestID INT NOT NULL,
    PRIMARY KEY (TeacherID, GuestID),
    FOREIGN KEY (TeacherID) REFERENCES Teacher(TeacherID) ON DELETE CASCADE,
    FOREIGN KEY (GuestID) REFERENCES Guest(GuestID) ON DELETE CASCADE
) ENGINE=InnoDB;

CREATE TABLE attendant (
    AdminID INT PRIMARY KEY AUTO_INCREMENT,  
    name VARCHAR(100) NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL,
    role ENUM('Attendant', 'Manager', 'Staff') NOT NULL,
    phone_no VARCHAR(15) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL
) ENGINE=InnoDB;

CREATE TABLE Administrative (
    Ad_id INT PRIMARY KEY AUTO_INCREMENT,  
    name VARCHAR(100) NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL,
    role ENUM('VC', 'Treasurer', 'Pro VC') NOT NULL,
    phone_no VARCHAR(15) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL
) ENGINE=InnoDB;

CREATE TABLE officeStaff (
    o_id INT PRIMARY KEY AUTO_INCREMENT,  
    name VARCHAR(100) NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL,
    role ENUM('Super Admin', 'Manager', 'Staff') NOT NULL,
    phone_no VARCHAR(15) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL
) ENGINE=InnoDB;

CREATE TABLE Room (
    RoomNo INT PRIMARY KEY,
    room_type ENUM('Single', 'Double', 'Suite') NOT NULL,
    pricePerNight DECIMAL(10,2) NOT NULL,
    status ENUM('Available', 'Booked', 'Under Maintenance') DEFAULT 'Available'
) ENGINE=InnoDB;

CREATE TABLE Booking (
    BookingID INT PRIMARY KEY AUTO_INCREMENT,
    GuestID INT NOT NULL,
    RoomNo INT NOT NULL,
    checkInDate DATE NOT NULL,
    checkOutDate DATE NOT NULL,
    status ENUM('Pending', 'Confirmed', 'Checked-in', 'Checked-out', 'Cancelled') DEFAULT 'Pending',
    total_amount DECIMAL(10,2) NOT NULL,
    last_update DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (GuestID) REFERENCES Guest(GuestID) ON DELETE CASCADE,
    FOREIGN KEY (RoomNo) REFERENCES Room(RoomNo) ON DELETE CASCADE
) ENGINE=InnoDB;

CREATE TABLE Payment (
    PaymentID INT PRIMARY KEY AUTO_INCREMENT,
    BookingID INT NOT NULL,
    paid_amount DECIMAL(10,2) NOT NULL,
    payment_date DATETIME DEFAULT CURRENT_TIMESTAMP,
    payment_method ENUM('Cash', 'Credit Card', 'Bank Transfer') NOT NULL,
    transactionID VARCHAR(50) UNIQUE NOT NULL,
    FOREIGN KEY (BookingID) REFERENCES Booking(BookingID) ON DELETE CASCADE
) ENGINE=InnoDB;

CREATE TABLE Staff (
    StaffID INT PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(100) NOT NULL,
    role ENUM('Receptionist', 'Cleaner', 'Maintenance') NOT NULL,
    phone_no VARCHAR(15) UNIQUE NOT NULL
) ENGINE=InnoDB;
