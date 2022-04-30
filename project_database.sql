#MySQL v8.0.28

CREATE TABLE PASSENGER(
	passenger_id INT NOT NULL,
	first_name   VARCHAR(20),
	last_name    VARCHAR(20),
	address      VARCHAR(50),
	phone        CHAR(11),
	email        VARCHAR(50),
	PRIMARY KEY (passenger_id)
);

CREATE TABLE AIRCRAFT(
	tail_num       INT NOT NULL,
	max_range      FLOAT,
	flight_hours   INT,
	num_passengers INT,
	cargo_capacity FLOAT,
	aircraft_type  ENUM('Airliner', 'CARGO') NOT NULL,
	PRIMARY KEY (tail_num)
);

CREATE TABLE EMPLOYEE(
	ssn CHAR(9) NOT NULL,
	first_name   VARCHAR(20),
	last_name    VARCHAR(20),
	birth_date   DATE,
	address      VARCHAR(50),
	salary       DECIMAL(9,2),
	PRIMARY KEY (ssn)
);

CREATE TABLE FLIGHT(
	flight_num         INT NOT NULL,
	departure_location VARCHAR(30) NOT NULL,
	arrival_location   VARCHAR(30) NOT NULL,
	departure_time     TIME NOT NULL,
	arrival_time       TIME NOT NULL,
	aircraft_id        INT NOT NULL,
	PRIMARY KEY (flight_num),
	FOREIGN KEY (aircraft_id) REFERENCES AIRCRAFT(tail_num)
);

CREATE TABLE BOOKS(
	passenger_id INT NOT NULL,
	flight_num   INT NOT NULL,
	booking_date DATE,
	ticket_type  VARCHAR(15) NOT NULL,
	PRIMARY KEY (passenger_id, flight_num),
	FOREIGN KEY (passenger_id) REFERENCES PASSENGER(passenger_id),
	FOREIGN KEY (flight_num) REFERENCES FLIGHT(flight_num)
);

CREATE TABLE PILOT(
	ssn CHAR(9)  NOT NULL,
	flight_hours INT,
	aircraft_id  INT NOT NULL,
	PRIMARY KEY (ssn),
	FOREIGN KEY (ssn) REFERENCES EMPLOYEE(ssn),
	FOREIGN KEY (aircraft_id) REFERENCES AIRCRAFT(tail_num)
);

CREATE TABLE MECHANIC(
	ssn CHAR(9) NOT NULL,
	PRIMARY KEY (ssn),
	FOREIGN KEY (ssn) REFERENCES EMPLOYEE(ssn)
);

CREATE TABLE PILOT_RATING(
	ssn CHAR(9)      NOT NULL,
	rating VARCHAR(50) NOT NULL,
	PRIMARY KEY (ssn, rating),
	FOREIGN KEY (ssn) REFERENCES PILOT(ssn)
);

CREATE TABLE MECHANIC_CERTIFICATION(
	ssn CHAR(9) NOT NULL,
	certification VARCHAR(50) NOT NULL,
	PRIMARY KEY (ssn, certification),
	FOREIGN KEY (ssn) REFERENCES MECHANIC(ssn)
);

CREATE TABLE WORKS_ON(
	ssn CHAR(9)  NOT NULL,
	aircraft_id  INT NOT NULL,
	hours_worked INT,
	PRIMARY KEY (ssn, aircraft_id),
	FOREIGN KEY (ssn) REFERENCES MECHANIC(ssn),
	FOREIGN KEY (aircraft_id) REFERENCES AIRCRAFT(tail_num)
);

CREATE VIEW AIRLINER AS
	SELECT tail_num, flight_hours, max_range, num_passengers
	FROM AIRCRAFT
	WHERE aircraft_type = 'Airliner';

CREATE TABLE CARGOPLANE(
	tail_num       INT NOT NULL,
	max_range      FLOAT,
	flight_hours   INT,
	cargo_capacity FLOAT NOT NULL,
	PRIMARY KEY (tail_num)
);

DELIMITER //
CREATE TRIGGER INSERT_CARGOPLANE
	BEFORE INSERT ON AIRCRAFT
	FOR EACH ROW
		BEGIN
			IF NEW.aircraft_type = 'CARGO' THEN
				INSERT INTO CARGOPLANE (tail_num, max_range, flight_hours, cargo_capacity)
					VALUES (NEW.tail_num, NEW.max_range, NEW.flight_hours, NEW.cargo_capacity);
			END IF;
		END//
DELIMITER ;

DELIMITER //
CREATE TRIGGER UPDATE_CARGOPLANE
	BEFORE UPDATE ON AIRCRAFT
	FOR EACH ROW
		BEGIN
			IF NEW.aircraft_type = 'Airliner' AND OLD.aircraft_type = 'CARGO' THEN
				DELETE FROM CARGOPLANE WHERE tail_num = NEW.tail_num;
			ELSEIF NEW.aircraft_type = 'CARGO' AND OLD.aircraft_type = 'Airliner' THEN
				INSERT INTO CARGOPLANE (tail_num, max_range, flight_hours, cargo_capacity)
					VALUES (NEW.tail_num, NEW.max_range, NEW.flight_hours, NEW.cargo_capacity);
			ELSEIF NEW.tail_num <> OLD.tail_num THEN
				UPDATE CARGOPLANE
				SET tail_num = NEW.tail_num, 
					max_range = NEW.max_range, 
					flight_hours = NEW.flight_hours, 
					cargo_capacity = NEW.cargo_capacity
				WHERE OLD.tail_num = CARGOPLANE.tail_num;
			ELSE
				UPDATE CARGOPLANE
				SET tail_num = NEW.tail_num, 
					max_range = NEW.max_range, 
					flight_hours = NEW.flight_hours, 
					cargo_capacity = NEW.cargo_capacity
				WHERE NEW.tail_num = CARGOPLANE.tail_num;
			END IF;
		END//
DELIMITER ;

CREATE TRIGGER DELETE_CARGOPLANE
	BEFORE DELETE ON AIRCRAFT
	FOR EACH ROW
		DELETE FROM CARGOPLANE WHERE OLD.tail_num = CARGOPLANE.tail_num;


#Counts the number of certifications a specific mechanic holds.
DELIMITER //
CREATE FUNCTION countCerts(mechanicSSN CHAR(9)) RETURNS INT
DETERMINISTIC
BEGIN
	DECLARE cert_count INT DEFAULT 0;

	SELECT COUNT(*)
	INTO cert_count
	FROM MECHANIC_CERTIFICATION
	WHERE ssn = mechanicSSN;

	RETURN cert_count;
END//
DELIMITER ;

#Counts the number of ratings a specific pilot holds.
DELIMITER //
CREATE FUNCTION countRatings(pilotSSN CHAR(9)) RETURNS INT
DETERMINISTIC
BEGIN
	DECLARE rating_count INT DEFAULT 0;
	
	SELECT COUNT(*)
	INTO rating_count
	FROM PILOT_RATING
	WHERE ssn = pilotSSN;

	RETURN rating_count;
END//
DELIMITER ;