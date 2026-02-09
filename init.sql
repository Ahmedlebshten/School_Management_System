-- Create student_data table
CREATE TABLE IF NOT EXISTS student_data (
    id INT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    class VARCHAR(50) NOT NULL
);

-- Create ahmed marks table
CREATE TABLE IF NOT EXISTS ahmed (
    id INT,
    subject VARCHAR(100),
    marks INT,
    total_marks INT,
    percntage DECIMAL(5, 2),
    FOREIGN KEY (id) REFERENCES student_data(id)
);

-- Create mohamed marks table
CREATE TABLE IF NOT EXISTS mohamed (
    id INT,
    subject VARCHAR(100),
    marks INT,
    total_marks INT,
    percntage DECIMAL(5, 2),
    FOREIGN KEY (id) REFERENCES student_data(id)
);

INSERT INTO `ahmed` (`id`, `subject`, `marks`, `total_marks`, `percntage`) VALUES
(1, 'math', 95, 100, '95%');

INSERT INTO `mohamed` (`id`, `subject`, `marks`, `total_marks`, `percntage`) VALUES
(1, 'math', 97, 100, '97%');

INSERT INTO `student_data` (`id`, `name`, `class`) VALUES
(1, 'Ahmed', 'First'),
(2, 'Mohamed', 'Second');

-- NOTE: Data is NOT inserted here.
-- All student data must be added via phpMyAdmin to your existing MySQL database.
-- The backend will fetch LIVE data directly from the database on every request.
