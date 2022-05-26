# NfqTask

ProjectsManager is a simple project for managing projects.

The project was developed using the Laravel framework. There are two projects implemented: one contains student data that is passed to the other project via the API.
A MySQL database is used to store the data. Factories have been created for the student database that automatically fill the database for testing.

In this project it is possible to create projects, their groups, and assign students to groups.

Groups are created automatically when you create a project. Creates as many groups as were specified when creating the project. You can later delete, add and rename groups.

Students are assigned to a group. One student can participate in one project per group. Once a student is assigned to a group, he or she is removed from the lists so that he or she is not assigned elsewhere. A student can be removed from the group or deleted completely.
