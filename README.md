# hospital_interface

This is a project of a hospital interface with made-up data.

The main page is only able to access and view data, while the staff and admin pages possess more buttons where they can also modify and add/delete information.

The test.php file is where the connection to the database is made and where data is retrieved and displayed.

Anyone can access the main page, since it requires no login of any sort. All of the buttons are searches only and it looks like this:

![hi_user](https://user-images.githubusercontent.com/36230040/176589032-1200299f-dd49-4466-b6ba-d1ca8a95d05e.png)

Each of the buttons show different data stored in the database.

This is what shows if the "Show employees" button is clicked:

![hi_see_employees](https://user-images.githubusercontent.com/36230040/176589880-568f43cd-9e2c-480e-ab4c-ee2ebc31d179.png)

The specialty area only has a value for the doctors and it is null for the others.

In this table, the names or the titles can be filtered in the search box. Here is the table displayed with "doctor" in the box:

![hi_see_employees_doctor](https://user-images.githubusercontent.com/36230040/176590291-1838d2fb-f508-436c-8356-fa21a7950cfc.png)

Other tables of data also have a search box, which usually allows the user to filter one or two specific columns.

Most of the buttons that just show data, show the table right away, but in some cases, certain inputs are needed first to get specific information.

If the button "Show employees working" is clicked, it gives the user the option to choose a day of the week and an hour:

![hi_see_employees_working_certain_time](https://user-images.githubusercontent.com/36230040/177008140-93215f4a-7e4b-4e93-8726-a9782a3c7249.png)

After selecting a day and an hour, and clicking the "submit" button, the table displayed shows all employees working at that day and at that hour. Here's a table showing all employees working at 12PM on Thursday:

![hi_see_employees_working_thursday_12pm](https://user-images.githubusercontent.com/36230040/177008223-39f248ee-93d4-4e24-867d-5e20f3d9c3a3.png)

To get access to more actions, such as adding, deleting, or altering data stored in the database, the user can login with an account that is labeled as either a hospital staff member or an administrator. If the button in the top right "Register" is clicked, a new account can be created and the type can be chosen as either hospital staff or administrator:

![hi_register_user](https://user-images.githubusercontent.com/36230040/177009684-8537cdae-53d7-464b-808e-15434471eb44.png)

If we go back and go to "Login", a user can login with an account previously registered.

![hi_login_user](https://user-images.githubusercontent.com/36230040/177009764-8efb566e-768c-41e6-946c-2ab605b274f8.png)

If the login is successful and the account type is hospital staff, this is what this type of user will see:

![hi_staff](https://user-images.githubusercontent.com/36230040/177009849-be03a526-155a-46fb-818c-91b506700fb8.png)

If the login is instead done using an administrator account, this is what this user will see:

![hi_admin](https://user-images.githubusercontent.com/36230040/177009943-eff349e6-44c6-4b6c-af52-face6b7cbe1c.png)

The difference between the admin and staff pages, is that the admin one has 3 extra buttons that allows him to change information of the employees, such as adding/removing one, or altering an employee's schedule.

The new buttons, in contrast to the ones the average user has available (no account), are able to add, delete or change the data on the database. Here is what shows in order to add an employee:

![hi_add_employee](https://user-images.githubusercontent.com/36230040/177048878-7146e883-a627-401b-bd00-aa47a22aae31.png)

To add an employee, a name is written, the title is chosen, and if that title is "Doctor", the user can also choose a specialty area. If it isn't "Doctor", the specialty area dropdown menu is greyed out and unusable. After submitting, and if it created an employee successfully, the user can go back and click on "Show employees" and search for the name inserted to make sure that the new employee exists in the database:

![hi_check_new_employee](https://user-images.githubusercontent.com/36230040/177049026-af91874c-13fd-4f9a-9045-6129a45a542a.png)

The new employee can be seen with the new id in the table, which also increased the number of employees by one.
