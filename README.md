# Conference Management System - README

## Project Overview

This is the CISC 332 Final Project! The website is a Conference Management System designed to handle various aspects of conference organization, including session scheduling, attendee management, sponsorship tracking, and financial intake. It utilizes a MySQL database for data storage and PHP for server-side logic, with HTML and CSS for the front-end interface.

## Setup Instructions

1.  **Database Setup:**
    * Import the `conferenceDB.sql` file to create the database schema and populate the tables.
2.  **PHP Setup:**
    * Ensure that PHP is installed and configured on web server.
3.  **Access the Application:**
    * Open your web browser and navigate to the `conference.php` file.


## Database Schema

The database schema, defined in `conferenceDB.sql`, consists of the following tables:

* **SubCommittee:** Stores information about conference subcommittees.
* **Member:** Stores member details.
* **Attendee:** Stores attendee details.
* **SponsorCompany:** Stores sponsor company information.
* **HotelRoom:** Stores hotel room details.
* **Session:** Stores session details (SessionID, SName, Day, RoomNum, StartTime, EndTime).
* **Student:** Stores student attendee details.
* **Professional:** Stores professional attendee details.
* **SponsorAttendee:** Links attendees to sponsor companies.
* **Speaker:** Stores speaker details.
* **Ad:** Stores job advertisement details.
* **Attending:** Links attendees to sessions (Aid, SessionID).
* **Speaking:** Links speakers to sessions (Aid, SessionID).
* **Made_of:** Links members to subcommittees.

## Project Structure

* **conference.php:** Main landing page with links to other conference management pages.
* **pages/conference_page.php:** Contains the conference session management functionalities.
* **pages/attendees_page.php:** Contains the attendees information and management functionalities.
* **pages/sponsors_page.php:** Contains the conference sponsors management functionalities.
* **pages/subcommittee_page.php:** Contains the subcommittee conference information.
* **database/db_connect.php:** Handles database connection.
* **res/styles/global.css:** Global CSS styles.
* **res/styles/pages.css:** Page-specific CSS styles.
* **conferenceDB.sql:** SQL file to create the database schema and insert initial data.

## Functionalities

### conference.php (Main Page)
* Provides links to the Conference Management pages.
* Serves as the entry point for the admin website.

### subcommittee_page.php (Sub-Committee Members Page)

* **Completes the following assignment requirements:**
    * display all members of a particular organizing sub-committee  (allow the user to choose the sub-committee from a drop down menu).


### attendees_page.php (Attendees Information Page)

* **Completes the following assignment requirements:**
    * show the list of conference attendees as 3 lists: students, professionals, sponsors.
    * for a particular hotel room, list all of the students housed in this room.
    * add a new attendee.  If the attendee is a student, add them to a hotel room. 
    * delete an attendee.


### conference_page.php (Conference Management Page)

* **Completes the following assignment requirements:**
    * display the conference schedule for a particular day.
    * show the total intake of the conference broken down by total registration amounts and total sponsorship amounts.
    * switch a session's day/time and/or location.


### sponsors_page.php (Sponsors Management Page)

* **Completes the following assignment requirements:**
    * list the sponsors (company name) and their level of sponsorship
    * for a particular company, list the jobs that they have available.
    * list all jobs available.
    * add a new sponsoring company
    * delete a sponsoring company and it's associated attendees

### Contributor:
Ethan Mah - 20224551 - April 5th, 2025