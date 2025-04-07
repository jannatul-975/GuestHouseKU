-- Insert Data into Register table (1 entry initially)
INSERT INTO Register (name, email, phone_no, password)
VALUES
('John Doe', 'john.doe@gmail.com', '0123456789', 'hashedpassword1');

-- Insert Data into Teacher table (Multiple entries)
INSERT INTO Teacher (name, email, phone_no, dept_name, password)
VALUES
('Dr. Michael Johnson', 'michael.johnson@ku.edu', '0198765432', 'Computer Science', 'hashedpassword3'),
('Prof. Sarah Williams', 'sarah.williams@ku.edu', '0198765433', 'Electrical Engineering', 'hashedpassword4'),
('Dr. David Smith', 'david.smith@ku.edu', '0198765434', 'Mechanical Engineering', 'hashedpassword5'),
('Prof. Laura Green', 'laura.green@ku.edu', '0198765435', 'Civil Engineering', 'hashedpassword6');

-- Insert Data into Teacher_Application table (Multiple applications)
INSERT INTO Teacher_Application (TeacherID, RegisterID, status)
VALUES
(1, 1, 'Approved'),
(2, 1, 'Pending'),
(3, 1, 'Approved'),
(4, 1, 'Rejected');

-- Insert Data into Guest table (Multiple entries)
INSERT INTO Guest (name, email, phone_no, address, password)
VALUES
('David Clark', 'david.clark@gmail.com', '0175432123', '123 Guest Lane, Khulna', 'hashedpassword7'),
('Emily Davis', 'emily.davis@gmail.com', '0175432124', '456 Guest Avenue, Khulna', 'hashedpassword8'),
('Samuel Harris', 'samuel.harris@gmail.com', '0175432125', '789 Guest Road, Khulna', 'hashedpassword9'),
('Sophia Turner', 'sophia.turner@gmail.com', '0175432126', '101 Guest Boulevard, Khulna', 'hashedpassword10');

-- Insert Data into Teacher_Guest table (Multiple relationships)
INSERT INTO Teacher_Guest (TeacherID, GuestID)
VALUES
(1, 1),
(2, 2),
(3, 3),
(4, 4);

-- Insert Data into attendant table (1 entry initially)
INSERT INTO attendant (name, email, role, phone_no, password)
VALUES
('Mark Spencer', 'mark.spencer@ku.edu', 'Manager', '0187654321', 'hashedpassword11');

-- Insert Data into Administrative table (Multiple entries)
INSERT INTO Administrative (name, email, role, phone_no, password)
VALUES
('Dr. Alan Green', 'alan.green@ku.edu', 'VC', '0189456789', 'hashedpassword12'),
('Mr. Robert Hall', 'robert.hall@ku.edu', 'Treasurer', '0189456790', 'hashedpassword13');

-- Insert Data into officeStaff table (Multiple entries)
INSERT INTO officeStaff (name, email, role, phone_no, password)
VALUES
('Linda Lee', 'linda.lee@ku.edu', 'Manager', '0176543210', 'hashedpassword14'),
('Tom White', 'tom.white@ku.edu', 'Staff', '0176543211', 'hashedpassword15');

-- Insert Data into Room table (Multiple entries)
INSERT INTO Room (RoomNo, room_type, pricePerNight, status)
VALUES
(101, 'Single', 100.00, 'Available'),
(102, 'Double', 150.00, 'Booked'),
(103, 'Suite', 250.00, 'Under Maintenance'),
(104, 'Single', 120.00, 'Available'),
(105, 'Double', 170.00, 'Available');

-- Insert Data into Booking table (Multiple entries)
INSERT INTO Booking (GuestID, RoomNo, checkInDate, checkOutDate, status, total_amount)
VALUES
(1, 101, '2025-04-01', '2025-04-05', 'Pending', 500.00),
(2, 102, '2025-04-03', '2025-04-07', 'Confirmed', 600.00),
(3, 103, '2025-04-10', '2025-04-12', 'Checked-in', 750.00),
(4, 104, '2025-04-15', '2025-04-17', 'Cancelled', 240.00);

-- Insert Data into Payment table (Multiple entries)
INSERT INTO Payment (BookingID, paid_amount, payment_date, payment_method, transactionID)
VALUES
(1, 500.00, '2025-03-30', 'Credit Card', 'TRX12345'),
(2, 600.00, '2025-03-31', 'Bank Transfer', 'TRX12346'),
(3, 750.00, '2025-04-09', 'Cash', 'TRX12347');

-- Insert Data into Staff table (Multiple entries)
INSERT INTO Staff (name, role, phone_no)
VALUES
('Sophia Taylor', 'Receptionist', '0192345678'),
('Daniel Martin', 'Cleaner', '0192345679'),
('James Parker', 'Maintenance', '0192345680'),
('Olivia Scott', 'Receptionist', '0192345681');
