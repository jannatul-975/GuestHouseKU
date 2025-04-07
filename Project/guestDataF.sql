INSERT INTO `administrative` (`id`, `name`, `email`, `role`, `phone_no`, `password`) VALUES
(1, 'Dr. Alan Green', 'alan.green@ku.edu', 'VC', '0189456789', 'hashedpassword12'),
(2, 'Mr. Robert Hall', 'robert.hall@ku.edu', 'Treasurer', '0189456790', 'hashedpassword13');

INSERT INTO `attendant` (`id`, `name`, `email`, `role`, `phone_no`, `password`) VALUES
(1, 'Mark Spencer', 'mark.spencer@ku.edu', 'Manager', '0187654321', 'hashedpassword11');

INSERT INTO `booking` (`BookingID`, `id`, `RoomNo`, `checkInDate`, `checkOutDate`, `status`, `total_amount`, `last_update`) VALUES
(1, 1, 101, '2025-04-01', '2025-04-05', 'Pending', 500.00, '2025-03-27 00:48:43');


INSERT INTO `guest` (`id`, `name`, `email`, `phone_no`, `address`, `registration_date`, `password`) VALUES
(1, 'Toma Rani', 'tomaranii@gmail.com', '01756896543', 'Rangpur', '2025-03-27 00:26:59', '$2y$10$DTYBzCsUCMAjuD.JjQzwze6UB3ZfrWDnCoZvnnI/Y7MCb1MPgdQ.O'),
(10, 'Toma Rani', 'tomaraniii@gmail.com', '01756896545', 'Rangpur', '2025-03-29 11:30:53', '$2y$10$XGkiYMqS1QO8Slwdi8IXdebALNllLwgw5jJmfTWwys8y6H3P3T0Xe'),
(11, 'Toma Rani', 'tomaraniiii@gmail.com', '01756896549', 'Rangpur', '2025-03-29 11:34:34', '$2y$10$OwHOYUSlTSWdCPR/YHt.6eE.SQoo9tUP/nDu6ku7Ut13Tg0RDs6zK'),
(29, 'Tuly', 'tuly@gmail.com', '+8801739990714', 'Barisal', '2025-03-29 13:16:43', '$2y$10$dqdmHyDGCwTJ9QCLQ8Mu8.NmkXblKiXFy9Sn5cFNxk/LpxnU6j.2u'),
(30, 'Munia', 'munia@gmail.com', '+8801856896545', 'Satkhira', '2025-03-29 13:56:34', '$2y$10$E..scThsXdxLx6B99RwCY.d0nG2z4tJJwwCw.3.uluqMd6Z4pG21a');

INSERT INTO `officestaff` (`id`, `name`, `email`, `role`, `phone_no`, `password`) VALUES
(1, 'Linda Lee', 'linda.lee@ku.edu', 'Manager', '0176543210', 'hashedpassword14'),
(2, 'Tom White', 'tom.white@ku.edu', 'Staff', '0176543211', 'hashedpassword15');



INSERT INTO `payment` (`PaymentID`, `BookingID`, `paid_amount`, `payment_date`, `payment_method`, `transactionID`) VALUES
(1, 1, 500.00, '2025-03-30 00:00:00', 'Credit Card', 'TRX12345');


INSERT INTO `register` (`id`, `name`, `email`, `phone_no`, `password`) VALUES
(1, 'John Doe', 'john.doe@gmail.com', '0123456789', 'hashedpassword1');


INSERT INTO `room` (`RoomNo`, `room_type`, `pricePerNight`, `status`) VALUES
(101, 'Single', 100.00, 'Available'),
(102, 'Double', 150.00, 'Booked'),
(103, 'Suite', 250.00, 'Under Maintenance'),
(104, 'Single', 120.00, 'Available'),
(105, 'Double', 170.00, 'Available');

INSERT INTO `staff` (`id`, `name`, `role`, `phone_no`) VALUES
(1, 'Sophia Taylor', 'Receptionist', '0192345678'),
(2, 'Daniel Martin', 'Cleaner', '0192345679'),
(3, 'James Parker', 'Maintenance', '0192345680'),
(4, 'Olivia Scott', 'Receptionist', '0192345681');

INSERT INTO `teacher` (`id`, `name`, `email`, `phone_no`, `dept_name`, `password`) VALUES
(1, 'Dr. Michael Johnson', 'michael.johnson@ku.edu', '0198765432', 'Computer Science', 'hashedpassword3'),
(2, 'Prof. Sarah Williams', 'sarah.williams@ku.edu', '0198765433', 'Electrical Engineering', 'hashedpassword4'),
(3, 'Dr. David Smith', 'david.smith@ku.edu', '0198765434', 'Mechanical Engineering', 'hashedpassword5'),
(4, 'Prof. Laura Green', 'laura.green@ku.edu', '0198765435', 'Civil Engineering', 'hashedpassword6');


INSERT INTO `teacher_application` (`ApplicationID`, `id`, `id`, `status`, `submission_date`) VALUES
(1, 1, 1, 'Approved', '2025-03-27 00:48:43'),
(2, 2, 1, 'Pending', '2025-03-27 00:48:43'),
(3, 3, 1, 'Approved', '2025-03-27 00:48:43'),
(4, 4, 1, 'Rejected', '2025-03-27 00:48:43');

INSERT INTO `teacher_guest` (`TeacherID`, `GuestID`) VALUES
(1, 1);
